<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Feeding\FeedingController;
use App\Http\Controllers\Notification\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');
Route::get('/signup', [AuthController::class, 'showSignup'])->name('show.signup');

Route::get('/home', [DashboardController::class, 'showDashboard'])->name('show.dashboard');
Route::get('/pig', [DashboardController::class, 'showPigManagement'])->name('show.pig');
Route::get('/feeding', [FeedingController::class, 'showFeedingManagement'])->name('show.feeding');
Route::get('/monitor', [DashboardController::class, 'showMonitorManagement'])->name('show.monitor');
Route::get('/notifications', [NotificationController::class, 'index'])->name('show.notifications');
Route::get('/reports', [DashboardController::class, 'showReports'])->name('reports.index');
