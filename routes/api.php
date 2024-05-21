<?php

use App\Http\Controllers\Book\BookController;
use App\Http\Controllers\Book\FavoriteBookController;
use App\Http\Controllers\Book\ReviweController;
use App\Http\Controllers\Gendre\GendreController;
use App\Http\Controllers\Post\FavoritePostController;
use App\Http\Controllers\Post\PostController;
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
        Route::get('/types/{id}', 'show');
        Route::put('/types/{id}', 'update');
        Route::delete('/types/{id}', 'destroy');
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

//Shelf controller


//post routes
Route::controller(PostController::class)->group(function () {
    Route::get('/showAllPosts', 'index');
    Route::get('/showMyPosts/{id}', 'showMyPosts');
    Route::post('/createPost', 'create')->middleware('auth:sanctum');
    Route::post('/updatePost/{id}', 'update')->middleware('auth:sanctum');
    Route::delete('/deletePost/{id}', 'delete')->middleware('auth:sanctum');

  });

//favorite post routes
Route::controller(FavoritePostController::class)->group(function () {
     Route::get('/showAllFavoritePosts', 'showFavorites')->middleware('auth:sanctum');
     Route::post('/addToFavoritePosts/{postId}', 'addToFavorites')->middleware('auth:sanctum');
     Route::delete('/removeFromFavoritesPosts/{postId}', 'removeFromFavorites')->middleware('auth:sanctum');

  });

//review routes
Route::controller(ReviweController::class)->group(function () {
    Route::get('/showAllReviwes', 'index')->middleware('auth:sanctum');
    Route::post('/addReviwe', 'create')->middleware('auth:sanctum');
    Route::delete('/deleteReviwe/{id}', 'delete')->middleware('auth:sanctum');

  });

  Route::controller(FavoriteBookController::class)->group(function () {
    Route::get('/showAllFavoriteBooks', 'showFavorites')->middleware('auth:sanctum');
    Route::post('/addToFavoriteBooks/{bookId}', 'addToFavorites')->middleware('auth:sanctum');
    Route::delete('/removeFromFavoritesBooks/{bookId}', 'removeFromFavorites')->middleware('auth:sanctum');

 });






Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
