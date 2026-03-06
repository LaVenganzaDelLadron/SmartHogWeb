<?php

use App\Http\Controllers\Batch\BatchController;
use App\Http\Controllers\Growth\GetGrowthController;
use App\Http\Controllers\Pen\PenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('pens')->group(function () {
    // Pen Routes
    Route::post('/add', [PenController::class, 'addPen'])->name('api.pens.add');
    Route::get('/all', [PenController::class, 'getAllPen'])->name('api.pens.all');
    Route::put('/update/{pen_code}', [PenController::class, 'updatePen'])->name('api.pens.update');
    Route::delete('/delete/{pen_code}', [PenController::class, 'deletePen'])->name('api.pens.delete');
});

Route::prefix('batch')->group(function () {
    // Batch Routes
    Route::post('/add', [BatchController::class, 'addBatch'])->name('api.batch.add');
    Route::get('/all', [BatchController::class, 'getAllBatch'])->name('api.batch.all');
    Route::get('/total-pigs', [BatchController::class, 'getTotalPigs'])->name('api.batch.total-pigs');
    Route::put('/update/{batch_code}', [BatchController::class, 'updateBatch'])->name('api.batch.update');
    Route::delete('/delete/{batch_code}', [BatchController::class, 'deleteBatch'])->name('api.batch.delete');
});




// Growth Stage
Route::get('/growth/all', [GetGrowthController::class, 'getAllGrowthStage'])->name('api.growth.all');

