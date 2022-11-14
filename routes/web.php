<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/getEmployee',[\App\Http\Controllers\RotaController::class, 'getEmployee']);
Route::get('/getCalculatedData',[\App\Http\Controllers\RotaController::class, 'getCalculatedData']);
Route::post('/postData',[\App\Http\Controllers\RotaController::class, 'postData']);
Route::get('/',[\App\Http\Controllers\RotaController::class, 'index']);
Route::post('storeInfo',[\App\Http\Controllers\RotaController::class, 'storeShifts'])->name('storeInfo');

