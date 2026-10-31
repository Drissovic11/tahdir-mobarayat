<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Artisan,DB,Schema,Storage};

class InstallationController extends Controller {
 private function guardInstallation(): void {
  abort_unless(config('installation.enabled') && strlen(config('installation.token'))>=64,404);
  abort_if(Storage::disk('local')->exists('installation-complete') || (Schema::hasTable('users') && User::where('is_admin',true)->exists()),404);
 }
 public function show() { $this->guardInstallation(); return view('installation'); }
 public function store(Request $request) {
  $this->guardInstallation();
  $v=$request->validate(['token'=>['required','string'],'name'=>['required','string','max:255'],
   'email'=>['required','email','max:255'],'password'=>['required','string','min:12','confirmed']]);
  abort_unless(hash_equals(config('installation.token'),$v['token']),403);
  // Les paramètres DB doivent être renseignés dans .env avant l’installation.
  Artisan::call('migrate',['--force'=>true]);
  DB::transaction(function() use($v) {
   abort_if(User::where('is_admin',true)->lockForUpdate()->exists(),409);
   $user=new User(['name'=>$v['name'],'email'=>$v['email'],'password'=>$v['password']]);
   $user->is_admin=true; $user->email_verified_at=now(); $user->save();
  });
  Artisan::call('db:seed',['--class'=>'Database\\Seeders\\CatalogSeeder','--force'=>true]);
  Storage::disk('local')->put('installation-complete',now()->toIso8601String());
  return redirect('/admin/login')->with('status','Installation terminée. Connectez-vous avec le compte créé.');
 }
}
