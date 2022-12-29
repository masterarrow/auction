<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\LotController;
use \App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//<editor-fold desc="LotController routes">
Route::get('/lot/all', [LotController::class, 'index'])->name('showLots');

Route::get('/lot/{id}', [LotController::class, 'show'])->name('showLot');

Route::post('/lot', [LotController::class, 'store'])->name('createLot');

Route::put('/lot/{id}', [LotController::class, 'update'])->name('updateLot');

Route::delete('/lot/{id}', [LotController::class, 'destroy'])->name('showLot');
//</editor-fold>

//<editor-fold desc="LotController routes">
Route::get('/category/all', [CategoryController::class, 'index'])->name('showCategories');

Route::get('/category/{id}', [CategoryController::class, 'show'])->name('showCategory');

Route::post('/category', [CategoryController::class, 'store'])->name('createCategory');

Route::put('/category/{id}', [CategoryController::class, 'update'])->name('updateCategory');

Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('showCategory');
//</editor-fold>
