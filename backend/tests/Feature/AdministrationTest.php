<?php
namespace Tests\Feature;
use App\Models\User;
use App\Models\Organization;
use App\Models\Specialty;
use App\Models\Question;
use App\Filament\Resources\Questions\Pages\CreateQuestion;
use App\Filament\Resources\Questions\Pages\EditQuestion;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdministrationTest extends TestCase
{
 use RefreshDatabase;
 public function test_admin_routes_require_authentication_and_admin_role(): void {
  $this->get('/admin')->assertRedirect('/admin/login');
  $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
  $admin=User::factory()->create(['is_admin'=>true,'email_verified_at'=>now()]);
  $this->actingAs($admin)->get('/admin')->assertOk();
  $this->get('/admin/organizations')->assertOk();
  $this->get('/admin/specialties')->assertOk();
  $this->get('/admin/questions')->assertOk();
  $this->get('/admin/exams')->assertOk();
 }
 public function test_admin_can_create_and_edit_question_with_its_correction(): void {
  $org=Organization::create(['name'=>'جهة','slug'=>'test','is_active'=>true]);
  $specialty=Specialty::create(['name'=>'تخصص','slug'=>'test','organization_id'=>$org->id,'is_active'=>true]);
  $this->actingAs(User::factory()->create(['is_admin'=>true,'email_verified_at'=>now()]));
  Filament::setCurrentPanel(Filament::getPanel('admin'));
  Livewire::test(CreateQuestion::class)->fillForm(['specialty_id'=>$specialty->id,'prompt'=>'2 + 2 = ؟',
   'options'=>['1','2','3','4'],'correct_option'=>3,'explanation'=>'2 + 2 = 4','is_active'=>true])
   ->call('create')->assertHasNoFormErrors();
  $question=Question::firstOrFail();
  $this->assertSame(3,$question->correct_option);
  Livewire::test(EditQuestion::class,['record'=>$question->getRouteKey()])
   ->assertFormSet(['correct_option'=>3,'explanation'=>'2 + 2 = 4'])
   ->fillForm(['explanation'=>'شرح جديد'])->call('save')->assertHasNoFormErrors();
  $this->assertSame('شرح جديد',$question->fresh()->explanation);
 }
 public function test_catalog_excludes_inactive_content(): void {
  $a=Organization::create(['name'=>'مرئية','slug'=>'visible','is_active'=>true]);
  Organization::create(['name'=>'مخفية','slug'=>'hidden','is_active'=>false]);
  Specialty::create(['organization_id'=>$a->id,'name'=>'مخفي','slug'=>'hidden','is_active'=>false]);
  $this->getJson('/api/organizations')->assertOk()->assertJsonCount(1)->assertJsonCount(0,'0.specialties');
 }
}
