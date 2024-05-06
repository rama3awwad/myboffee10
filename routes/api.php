<?php

use App\Http\Controllers\Book\BookController;
use App\Http\Controllers\Gendre\GendreController;
use App\Http\Controllers\Types\TypeController;
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

    Route::post('/gendres', 'store');
    Route::get('/gendres/{id}', 'show');
    Route::delete('/gendres/{id}', 'destroy');

    });

//user routes
    Route::controller(UserController::class)->group(function () {

    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');

    });

//type routes
    Route::controller(TypeController::class)->group(function () {
        Route::get('/types', 'index');
        Route::post('/types', 'store');
        Route::get('/types/{type}', 'show');
        Route::put('/types/{type}', 'update');
        Route::delete('/types/{type}', 'destroy');
    });

// Book routes
    Route::controller(BookController::class)->group(function () {
        Route::get('/books', 'index');
        Route::post('/books', 'store');
        Route::get('/books/{id}', 'show')->middleware('auth:sanctum');
        Route::get('/Abooks/{id}', 'Ashow');
        Route::post('/Bbooks/search', 'findByName');
        Route::put('/updateBook/{id}', 'update');
        Route::get('/books/type/{typeId}', 'showBooksByType');
    });











Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
