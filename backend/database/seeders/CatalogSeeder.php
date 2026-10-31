<?php
namespace Database\Seeders;
use App\Models\Organization;
use Illuminate\Database\Seeder;
class CatalogSeeder extends Seeder {
 public function run(): void {
  foreach([
   ['slug'=>'dgapr','name'=>'المندوبية العامة لإدارة السجون وإعادة الإدماج','logo'=>'dgapr.png','aliases'=>'السجون DGAPR'],
   ['slug'=>'dgsn','name'=>'الأمن الوطني','logo'=>'dgsn.jpg','aliases'=>'الشرطة DGSN'],
   ['slug'=>'justice','name'=>'وزارة العدل','logo'=>'justice.png','aliases'=>'justice المحاكم'],
  ] as $row) Organization::firstOrCreate(['slug'=>$row['slug']],$row+['is_active'=>true]);
 }
}
