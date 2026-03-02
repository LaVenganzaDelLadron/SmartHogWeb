<?php

use App\Http\Controllers\Growth\GetGrowthController;
use App\Http\Controllers\Pen\PenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Adding a pen
Route::post('/pens/add', [PenController::class, 'addPen'])->name('api.pens.add');
Route::get('/pen/all', [PenController::class, 'getAllPen'])->name('api.pens.all');
Route::delete('/pen/delete/{pen_code}', [PenController::class, 'deletePen'])->name('api.pens.delete');
Route::put('/pen/update/{pen_code}', [PenController::class, 'updatePen'])->name('api.pens.update');

// Growth Stage
Route::get('/growth/all', [GetGrowthController::class, 'getAllGrowthStage'])->name('api.growth.all');
