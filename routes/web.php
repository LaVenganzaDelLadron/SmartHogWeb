<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Feeding\FeedingController;
use App\Http\Controllers\Growth\AddGrowthController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Pen\AddPenController;
use App\Http\Controllers\Pen\DeletePenController;
use App\Http\Controllers\Pen\UpdatePenController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');
Route::get('/signup', [AuthController::class, 'showSignup'])->name('show.signup');
Route::post('/logout', [AuthController::class, 'logout'])->name('web.logout');

Route::get('/home', [DashboardController::class, 'showDashboard'])->name('show.dashboard');
Route::get('/pig', [DashboardController::class, 'showPigManagement'])->name('show.pig');
Route::get('/feeding', [FeedingController::class, 'showFeedingManagement'])->name('show.feeding');
Route::get('/monitor', [DashboardController::class, 'showMonitorManagement'])->name('show.monitor');
Route::get('/notifications', [NotificationController::class, 'index'])->name('show.notifications');
Route::get('/reports', [DashboardController::class, 'showReports'])->name('reports.index');

// Pig Pen Routes
Route::post('/pig/pens/add', [AddPenController::class, 'addPenFromWeb'])->name('web.pens.add');
Route::match(['put', 'patch'], '/pig/pens/{penCode}/update', [UpdatePenController::class, 'updatePenFromWeb'])->name('web.pens.update');
Route::delete('/pig/pens/{penCode}/delete', [DeletePenController::class, 'deletePenFromWeb'])->name('web.pens.delete');
Route::post('/pig/batches/add', [AddPenController::class, 'addBatchFromWeb'])->name('web.batches.add');

// Feeding Routes
Route::post('/feeding/add-growth-stage', [AddGrowthController::class, 'addGrowthStageFromWeb'])
    ->name('web.feeding.addGrowth');
Route::post('/feeding/schedules/add', [FeedingController::class, 'addScheduleFromWeb'])->name('web.feeding.schedules.add');
