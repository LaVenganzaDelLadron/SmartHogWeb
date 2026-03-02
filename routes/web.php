<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');
Route::get('/signup', [AuthController::class, 'showSignup'])->name('show.signup');

Route::post('/login', [AuthController::class, 'login'])->name('web.login');
Route::post('/signup', [AuthController::class, 'signup'])->name('web.signup');
Route::post('/logout', [AuthController::class, 'logout'])->name('web.logout');

Route::middleware(['auth.custom'])->group(function () {
    Route::get('/home', [DashboardController::class, 'showDashboard'])->name('show.dashboard');
    Route::get('/pig', [DashboardController::class, 'showPigManagement'])->name('show.pig');
    Route::get('/feeding', [DashboardController::class, 'showFeedingManagement'])->name('show.feeding');
    Route::get('/monitor', [DashboardController::class, 'showMonitorManagement'])->name('show.monitor');
    Route::get('/reports', [DashboardController::class, 'showReports'])->name('reports.index');
});
