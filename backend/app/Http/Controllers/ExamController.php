<?php
namespace App\Http\Controllers;
use App\Models\Attempt;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ExamController extends Controller
{
 public function index(Request $request) {
  $v=$request->validate(['specialty_id'=>['required','integer','exists:specialties,id']]);
  return Exam::where('specialty_id',$v['specialty_id'])->where('is_published',true)
   ->whereHas('specialty',fn($q)=>$q->where('is_active',true)->whereHas('organization',fn($o)=>$o->where('is_active',true)))
   ->withCount('questions')->get(['id','title','duration_minutes','specialty_id']);
 }
 public function start(Exam $exam) {
  abort_unless($exam->is_published && $exam->specialty->is_active && $exam->specialty->organization->is_active,404);
  $questions=$exam->questions()->get();
  abort_if($questions->isEmpty() || $questions->count()>200 || $questions->contains(fn($q)=>!$q->is_active || $q->specialty_id!==$exam->specialty_id),409,'Examen incomplet ou indisponible.');
  $snapshot=$questions->map(fn($q)=>['id'=>$q->id,'prompt'=>$q->prompt,'options'=>$q->options,'correct_option'=>$q->correct_option,'explanation'=>$q->explanation])->all();
  $token=Str::random(64);
  $attempt=new Attempt(); $attempt->exam_id=$exam->id; $attempt->token_hash=hash('sha256',$token);
  $attempt->snapshot=$snapshot; $attempt->expires_at=now()->addMinutes($exam->duration_minutes); $attempt->save();
  return response()->json(['id'=>$attempt->id,'token'=>$token,'expires_at'=>$attempt->expires_at->toIso8601String(),
   'questions'=>array_map(fn($q)=>['id'=>$q['id'],'prompt'=>$q['prompt'],'options'=>$q['options']],$snapshot)],201);
 }
 public function submit(Request $request,string $attempt) {
  $v=$request->validate(['answers'=>['present','array','max:200'],'answers.*'=>['nullable','integer','between:0,3']]);
  return DB::transaction(function() use($request,$attempt,$v) {
   $record=Attempt::whereKey($attempt)->lockForUpdate()->firstOrFail();
   abort_unless(hash_equals($record->token_hash,hash('sha256',(string)$request->header('X-Attempt-Token'))),403);
   if (!$record->submitted_at) {
    $answers=$v['answers']; $ids=array_column($record->snapshot,'id');
    foreach(array_keys($answers) as $id) if(!in_array((int)$id,$ids,true)) throw ValidationException::withMessages(['answers'=>'Question étrangère à cet examen.']);
    // Aucune réponse tardive acceptée par le serveur.
    if(now()->greaterThan($record->expires_at)) $answers=[];
    $record->answers=$answers;
    $record->score=collect($record->snapshot)->filter(fn($q)=>isset($answers[$q['id']]) && (int)$answers[$q['id']]===$q['correct_option'])->count();
    $record->submitted_at=now(); $record->save();
   }
   return ['id'=>$record->id,'score'=>$record->score,'total'=>count($record->snapshot),'submitted_at'=>$record->submitted_at->toIso8601String(),
    'correction'=>array_map(fn($q)=>$q+['answer'=>$record->answers[$q['id']]??null],$record->snapshot)];
  });
 }
}
