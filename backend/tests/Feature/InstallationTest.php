<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
class InstallationTest extends TestCase {
 use RefreshDatabase;
 public function test_installer_is_disabled_by_default(): void { $this->get('/installation')->assertNotFound(); }
 public function test_token_is_required_and_installation_cannot_be_repeated(): void {
  Storage::fake('local'); config(['installation.enabled'=>true,'installation.token'=>str_repeat('x',64)]);
  $this->post('/installation',['token'=>'incorrect','name'=>'Admin','email'=>'admin@example.test','password'=>'long-test-password','password_confirmation'=>'long-test-password'])->assertForbidden();
  $this->post('/installation',['token'=>str_repeat('x',64),'name'=>'Admin','email'=>'admin@example.test','password'=>'long-test-password','password_confirmation'=>'long-test-password'])->assertRedirect('/admin/login');
  $this->assertTrue(User::where('email','admin@example.test')->firstOrFail()->is_admin);
  $this->get('/installation')->assertNotFound();
  $this->assertSame(1,User::count());
 }
}
