<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\UserController::class, 'register']);

Route::get('/tests/{test:uuid}', [\App\Http\Controllers\TestController::class, 'show']);
Route::post('/tests/{test:uuid}', [\App\Http\Controllers\TestController::class, 'check']);

Route::middleware('auth:api')->group(function () {
    Route::get('/tests', [\App\Http\Controllers\TestController::class, 'index']);
    Route::post('/tests', [\App\Http\Controllers\TestController::class, 'store']);

    Route::delete('/tests/{test}', [\App\Http\Controllers\TestController::class, 'destroy']);
    Route::get('/tests/{test}/info', [\App\Http\Controllers\TestController::class, 'info']);
});
