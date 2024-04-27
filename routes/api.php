<?php

use App\Http\Controllers\Gendre\GendreController;
use App\Http\Controllers\User\UserController;
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

//gendre routes
Route::controller(GendreController::class)->group(function () {

    Route::get('/gendres', 'index');
    Route::post('/gendres', 'store');
    Route::get('/gendres/{gendre}', 'show');
    Route::put('/gendres/{gendre}', 'update');
    Route::delete('/gendres/{gendre}', 'destroy');

    });

//user routes
    Route::controller(UserController::class)->group(function () {

    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');

    });












Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
