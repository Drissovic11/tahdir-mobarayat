<?php
namespace Tests\Feature;
use App\Models\{Attempt,Exam,Organization,Question,Specialty};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamTest extends TestCase
{
 use RefreshDatabase;
 private function exam(): array {
  $o=Organization::create(['name'=>'جهة','slug'=>'test','is_active'=>true]);
  $s=Specialty::create(['organization_id'=>$o->id,'name'=>'تخصص','slug'=>'test','is_active'=>true]);
  $q=Question::create(['specialty_id'=>$s->id,'prompt'=>'2+2?','options'=>['1','2','3','4'],'correct_option'=>3,'explanation'=>'4','is_active'=>true]);
  $e=Exam::create(['specialty_id'=>$s->id,'title'=>'تجريبي','duration_minutes'=>5,'is_published'=>true]);
  $e->questions()->attach($q); return [$e,$q];
 }
 public function test_answers_are_hidden_until_submission_and_score_is_immutable(): void {
  [$exam,$question]=$this->exam();
  $r=$this->postJson('/api/exams/'.$exam->id.'/attempts')->assertCreated();
  $r->assertJsonMissingPath('questions.0.correct_option')->assertJsonMissingPath('questions.0.explanation');
  $d=$r->json(); $url='/api/attempts/'.$d['id'].'/submit';
  $this->postJson($url,['answers'=>[$question->id=>3]])->assertForbidden();
  $this->withHeader('X-Attempt-Token',$d['token'])->postJson($url,['answers'=>[$question->id=>3]])->assertOk()->assertJsonPath('score',1);
  $this->withHeader('X-Attempt-Token',$d['token'])->postJson($url,['answers'=>[$question->id=>0]])->assertOk()->assertJsonPath('score',1);
  $this->assertSame(1,Attempt::find($d['id'])->score);
 }
 public function test_expired_attempt_cannot_receive_a_late_correct_answer(): void {
  [$e,$q]=$this->exam(); $d=$this->postJson('/api/exams/'.$e->id.'/attempts')->json();
  $this->travel(6)->minutes();
  $this->withHeader('X-Attempt-Token',$d['token'])->postJson('/api/attempts/'.$d['id'].'/submit',['answers'=>[$q->id=>3]])->assertOk()->assertJsonPath('score',0);
 }
 public function test_saved_answers_are_preserved_when_the_timer_expires(): void {
  [$e,$q]=$this->exam(); $d=$this->postJson('/api/exams/'.$e->id.'/attempts')->json();
  $this->withHeader('X-Attempt-Token',$d['token'])->postJson('/api/attempts/'.$d['id'].'/answers',['question_id'=>$q->id,'option'=>3])->assertOk();
  $this->travel(6)->minutes();
  $this->withHeader('X-Attempt-Token',$d['token'])->postJson('/api/attempts/'.$d['id'].'/answers',['question_id'=>$q->id,'option'=>0])->assertStatus(409);
  $this->withHeader('X-Attempt-Token',$d['token'])->postJson('/api/attempts/'.$d['id'].'/submit',['answers'=>[]])->assertOk()->assertJsonPath('score',1);
 }
 public function test_unpublished_and_cross_specialty_exams_cannot_be_started(): void {
  [$e,$q]=$this->exam(); $e->update(['is_published'=>false]);
  $this->postJson('/api/exams/'.$e->id.'/attempts')->assertNotFound();
  $e->update(['is_published'=>true]); $s=Specialty::create(['organization_id'=>$e->specialty->organization_id,'name'=>'آخر','slug'=>'other','is_active'=>true]);
  $e->update(['specialty_id'=>$s->id]);
  $this->postJson('/api/exams/'.$e->id.'/attempts')->assertStatus(409);
 }
}
