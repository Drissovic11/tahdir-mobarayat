<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstallationController;

Route::get('/installation',[InstallationController::class,'show'])->middleware('throttle:10,1');
Route::post('/installation',[InstallationController::class,'store'])->middleware('throttle:5,1');

Route::get('/', function () {
    return file_exists(public_path('frontend.html'))
        ? response()->file(public_path('frontend.html'))
        : view('welcome');
});
