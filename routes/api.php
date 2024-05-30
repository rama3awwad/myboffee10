<?php

use App\Http\Controllers\Book\BookController;
use App\Http\Controllers\Book\FavoriteController;
use App\Http\Controllers\Book\ReportController;
use App\Http\Controllers\Book\ReviweController;
use App\Http\Controllers\Gendre\GendreController;
use App\Http\Controllers\Post\FavoritePostController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Shelf\ShelfController;
use App\Http\Controllers\Suggestion\suggestionController;
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
        Route::get('/Abooks/{id}', 'Ashow');
        Route::get('/file/{id}','getFile');
        Route::get('/book/{id}', 'show')->middleware('auth:sanctum');
        Route::post('/search', 'findByName');
        Route::post('/books/{id}', 'update');
        Route::post('upBooks/{id}', 'updateImage');
        Route::delete('/books/{id}','delete');
        Route::get('/books/type/{typeId}', 'showBooksByType');
        Route::get('/details/{id}','showDetails');
        Route::post('/author', 'author');
    });


//shelf routes
    Route::controller(ShelfController::class)->group(function (){
        Route::post('/shelf/later','storeLaterStatus')->middleware('auth:sanctum');
        Route::post('/shelf/{shelfId}','updateProgress')->middleware('auth:sanctum');
        Route::get('/count/{bookId}','count');
        Route::post('/myShelf', 'myShelf')->middleware('auth:sanctum');
        Route::post('/countMine','countMine')->middleware('auth:sanctum');
    });


//review routes
    Route::controller(ReviweController::class)->group(function () {
        Route::get('/showAllReviwes', 'index')->middleware('auth:sanctum');
        Route::post('/addReviwe', 'create')->middleware('auth:sanctum');
        Route::delete('/deleteReviwe/{id}', 'delete')->middleware('auth:sanctum');

    });

//favorite routes
    Route::controller(FavoriteController::class)->group(function () {
        Route::post('/add/{bookId}', 'addToFavorites')->middleware('auth:sanctum');
        Route::get('/showMine', 'showMine')->middleware('auth:sanctum');
        Route::get('/showUserFav','showUserFav')->middleware('auth:sanctum');
        Route::delete('/remove','remove')->middleware('auth:sanctum');
    });

//post routes
    Route::controller(PostController::class)->group(function () {
        Route::get('/showAllPosts', 'index');
        Route::get('/showMyPosts', 'showMyPosts')->middleware('auth:sanctum');
        Route::get('/show/{user_id}','show');
        Route::get('/showP/{post_id}','ShowP');
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

//report routes
    Route::controller(ReportController::class)->group(function () {
        Route::get('/reports',  'index');
        Route::post('/report', 'store')->middleware('auth:sanctum');
        Route::get('/report{id}', 'show');
        Route::get('/user-reports',  'showUserReports')->middleware('auth:sanctum');
        Route::get('/bookReports', 'showReportsByBookId');
        Route::delete('/delete/{id}','removeReport');
        Route::delete('/delete', 'deleteAllUserReports');
    });
//review routes
Route::controller(ReviweController::class)->group(function () {
    Route::get('/showAllReviwes', 'index');
    Route::post('/addReviwe', 'create')->middleware('auth:sanctum');
    Route::delete('/deleteReviwe/{id}', 'delete');

  });

  //suggestion routes
  Route::controller(suggestionController::class)->group(function () {
    Route::get('/showAllSuggestions', 'index');
    Route::get('/showsuggestion/{id}', 'showSuggestion');
    Route::post('/createsuggestion', 'create')->middleware('auth:sanctum');
    Route::post('/updatesuggestion/{id}', 'update')->middleware('auth:sanctum');
    Route::delete('/deletesuggestion/{id}', 'delete');

  });

//note routes
    Route::controller(ReportController::class)->group(function(){
    });


//suggestion routes
    Route::controller(SuggestionController::class)->group(function(){

    });






Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

