<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::controller(ProjectController::class)->group(function(){

    Route::get('/project', 'index');
    Route::post('/project', 'store');
    Route::put('/project/edit/{id}', 'edit');
    Route::post('/project/pinned', 'pinnedProject');
});

