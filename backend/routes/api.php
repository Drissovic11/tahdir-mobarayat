<?php
use App\Models\Organization;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamController;
Route::get('/organizations', function () {
    return Organization::query()->where('is_active', true)
        ->with(['specialties' => fn ($query) => $query->where('is_active', true)->orderBy('name')])
        ->orderBy('name')->get()->map(fn ($organization) => [
            'id' => $organization->slug, 'name' => $organization->name,
            'logo' => $organization->logo, 'aliases' => $organization->aliases ?? '',
            'specialties' => $organization->specialties->map(fn ($specialty) => [
                'id' => $specialty->slug, 'database_id' => $specialty->id, 'name' => $specialty->name,
            ]),
        ]);
})->middleware('throttle:60,1');
Route::get('/exams',[ExamController::class,'index'])->middleware('throttle:60,1');
Route::post('/exams/{exam}/attempts',[ExamController::class,'start'])->middleware('throttle:10,1');
Route::post('/attempts/{attempt}/submit',[ExamController::class,'submit'])->middleware('throttle:30,1');
