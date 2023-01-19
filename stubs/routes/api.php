<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use KFoobar\Restful\Http\Controllers\Restful\CoreController;
use KFoobar\Restful\Http\Controllers\Restful\UserController;

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

Route::prefix('v1')->group(function () {
    Route::get('ping', [CoreController::class, 'ping']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [UserController::class, 'profile']);
    });
});

