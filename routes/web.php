<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');
Route::post('/login', [AuthController::class, 'Login'])->name('login');

Route::get('/signup', [AuthController::class, 'showSignup'])->name('show.signup');
Route::post('/signup', [AuthController::class, 'Signup'])->name('signup');

Route::post('/logout', [AuthController::class, 'Logout'])->name('logout');

// Protected Routes
Route::middleware('auth.custom')->group(function () {
    Route::get('/home', [DashboardController::class, 'showDashboard'])->name('show.dashobard');
    Route::get('/pig', [DashboardController::class, 'showPigManagement'])->name('show.pig');
    Route::get('/feeding', [DashboardController::class, 'showFeedingManagement'])->name('show.feeding');
    Route::get('/monitor', [DashboardController::class, 'showMonitorManagement'])->name('show.monitor');
    Route::get('/notifications', [DashboardController::class, 'showNotifications'])->name('show.notifications');
});
