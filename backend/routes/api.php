<?php
use App\Models\Organization;
use Illuminate\Support\Facades\Route;
Route::get('/organizations', function () {
    return Organization::query()->where('is_active', true)
        ->with(['specialties' => fn ($query) => $query->where('is_active', true)->orderBy('name')])
        ->orderBy('name')->get()->map(fn ($organization) => [
            'id' => $organization->slug, 'name' => $organization->name,
            'logo' => $organization->logo, 'aliases' => $organization->aliases ?? '',
            'specialties' => $organization->specialties->map(fn ($specialty) => [
                'id' => $specialty->slug, 'name' => $specialty->name,
            ]),
        ]);
})->middleware('throttle:60,1');
