<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Feeding\FeedingController;
use App\Http\Controllers\Growth\AddGrowthController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Pen\AddPenController;
use App\Http\Controllers\Pen\DeletePenController;
use App\Http\Controllers\Pen\GetPenController;
use App\Http\Controllers\Pen\UpdatePenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// authentication
Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

// Adding a pen
Route::get('/pens', [GetPenController::class, 'getAll'])->name('pens.index');
Route::post('/pens/add', [AddPenController::class, 'addPen'])->name('pens.add');
Route::put('/pens/{penCode}', [UpdatePenController::class, 'updatePen'])->name('pens.update');
Route::delete('/pens/{penCode}', [DeletePenController::class, 'deletePen'])->name('pens.delete');

// Growth Stage Routes
Route::post('/feeding/add-growth-stage', [AddGrowthController::class, 'addGrowthStage'])->name('feeding.addGrowthStage');
