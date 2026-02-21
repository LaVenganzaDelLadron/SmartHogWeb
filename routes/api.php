<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Pig\PigController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// authentication
Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Adding a pen
Route::post('/pens/add', [PigController::class, 'addPen'])->name('pens.add');
Route::post('/batches/add', [PigController::class, 'addBatch'])->name('batches.add');
