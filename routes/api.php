<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Feeding\FeedingController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Pig\PenController;
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
Route::post('/pens/add', [PenController::class, 'addPen'])->name('pens.add');
Route::post('/feeding/schedules/add', [FeedingController::class, 'addSchedule'])->name('feeding.schedules.add');
Route::get('/feeding/schedules', [FeedingController::class, 'listSchedules'])->name('feeding.schedules.list');
Route::get('/notifications', [NotificationController::class, 'list'])->name('notifications.list');
Route::post('/notifications/receive', [NotificationController::class, 'store'])->name('notifications.store');
Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
