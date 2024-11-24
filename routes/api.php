<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MemberController;

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
    Route::get('/project/{slug}', 'getProject');
});

Route::controller(MemberController::class)->group(function(){

    Route::get('/member', 'index');
    Route::post('/member', 'store');
    Route::put('/member/edit/{id}', 'edit');
});

Route::controller(TaskController::class)->group(function(){

    Route::post('/task', 'createTask');
    // Route::post('/member', 'store');
    // Route::put('/member/edit/{id}', 'edit');
});

