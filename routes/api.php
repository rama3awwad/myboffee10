<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\langController;
use App\Http\Controllers\Book\BookController;
use App\Http\Controllers\Book\NoteController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\User\UserController;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Http\Controllers\Types\TypeController;
use App\Http\Controllers\Book\RatingController;
use App\Http\Controllers\Book\ReportController;
use App\Http\Controllers\Book\ReviweController;
use App\Http\Controllers\Level\LevelController;
use App\Http\Controllers\Shelf\ShelfController;
use App\Http\Controllers\Book\FavoriteController;
use App\Http\Controllers\Gendre\GendreController;
use App\Http\Controllers\Book\SuggestionController;
use App\Http\Controllers\Post\FavoritePostController;

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
//translation route
Route::get('/language', langController::class);

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
    Route::get('/user/show', 'show')->middleware('auth:sanctum');
    Route::post('/changeLang', 'updateUserLang')->middleware('auth:sanctum');

    Route::post('password/email', 'userForgotPassword');
    Route::post('password/code/check', 'userCheckCode');
    Route::post('password/reset', 'userResetPassword');
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
    Route::get('/books', 'index')->middleware('auth:sanctum');
    Route::post('/books', 'store');
    Route::get('/Abooks/{id}', 'Ashow');
    Route::get('/file/{id}', 'getFile');
    Route::get('/book/{id}', 'show')->middleware('auth:sanctum');
    Route::post('/search', 'findByName');
    Route::post('/books/{id}', 'update');
    Route::post('upBooks/{id}', 'updateImage');
    Route::delete('/books/{id}', 'delete');
    Route::get('/books/type/{typeId}', 'showBooksByType')->middleware('auth:sanctum');
    Route::get('/details/{id}', 'showDetails')->middleware('auth:sanctum');
    Route::post('/author', 'author')->middleware('auth:sanctum');
    Route::get('/mostReading', 'mostReading');
    Route::get('/mostRating', 'mostRating');
});


//shelf routes
Route::controller(ShelfController::class)->group(function () {
    Route::post('/shelf/later', 'storeLaterStatus')->middleware('auth:sanctum');
    Route::post('/shelf/{shelfId}', 'updateProgress')->middleware('auth:sanctum');
    Route::get('/count/{bookId}', 'count');
    Route::post('/myShelf', 'myShelf')->middleware('auth:sanctum');
    Route::post('/countMine', 'countMine')->middleware('auth:sanctum');
});

//favorite routes
Route::controller(FavoriteController::class)->group(function () {
    Route::post('/add/{bookId}', 'addToFavorite')->middleware('auth:sanctum');
    Route::get('/favorites', 'showMine')->middleware('auth:sanctum');
    //    Route::get('/showUserFav','showUserFav');
    Route::delete('/remove/{bookId}', 'remove')->middleware('auth:sanctum');
});

//report routes
Route::controller(ReportController::class)->group(function () {
    Route::get('/reports',  'index');
    Route::post('/report/{bookId}', 'store')->middleware('auth:sanctum');
    Route::get('/report/{id}', 'show');
    Route::get('/user/reports',  'showMyReports')->middleware('auth:sanctum');
    Route::get('/book/reports/{bookId}', 'showBookReports');
    Route::delete('/delete/{id}', 'removeReport');
    Route::delete('/delete', 'deleteAllUserReports');
});

//note routes
Route::controller(NoteController::class)->group(function () {
    Route::post('/note/{bookId}', 'store')->middleware('auth:sanctum');
    Route::get('/note/{noteId}', 'show');
    Route::get('/notes', 'index')->middleware('auth:sanctum');
    Route::post('/update/{noteId}', 'update')->middleware('auth:sanctum');
    Route::get('/notes/{bookId}', 'showMine')->middleware('auth:sanctum');
    Route::delete('note/{noteId}', 'delete')->middleware('auth:sanctum');
    Route::delete('/notes', 'deleteAll')->middleware('auth:sanctum');
});

//rate routes
Route::controller(RatingController::class)->group(function () {
    Route::post('/rate', 'add')->middleware('auth:sanctum');
    Route::get('/avg/{bookId}', 'avgRate');
    Route::get('/rater/details/{bookId}', 'showRatersDetails');
});


//level routes
Route::controller(LevelController::class)->group(function () {
    Route::get('/level', 'show')->middleware('auth:sanctum');
});


//review routes
Route::controller(ReviweController::class)->group(function () {
    Route::get('/showAllReviwes', 'index')->middleware('auth:sanctum');
    Route::post('/addReviwe', 'create')->middleware('auth:sanctum');
    Route::delete('/deleteReviwe/{id}', 'delete')->middleware('auth:sanctum');
});



//post routes
Route::controller(PostController::class)->group(function () {
    Route::get('/showAllPosts', 'index');
    Route::get('/showMyPosts', 'showMyPosts')->middleware('auth:sanctum');
    Route::get('/showUserPosts/{user_id}', 'showUserPosts');
    Route::get('/showPost/{post_id}', 'ShowPost');
    Route::post('/createPost', 'create')->middleware('auth:sanctum');
    Route::post('/updatePost/{id}', 'update')->middleware('auth:sanctum');
    Route::delete('/deletePost/{id}', 'delete')->middleware('auth:sanctum');
});

//favorite post routes
Route::controller(FavoritePostController::class)->group(function () {
    Route::get('/showMyFavorite', 'showMyFavorite')->middleware('auth:sanctum');
    Route::get('/showUserFavorite/{userId}', 'showUserFav');
    Route::post('/addToFavorite/{postId}', 'addToFavorites')->middleware('auth:sanctum');
    Route::delete('/removeFromFavorites/{postId}', 'removeFromFavorites')->middleware('auth:sanctum');
    Route::get('/likesCount/{postId}', 'countLikes');
});


//review routes
Route::controller(ReviweController::class)->group(function () {
    Route::get('/showAllReviwes', 'index');
    Route::post('/addReviwe', 'create')->middleware('auth:sanctum');
    Route::post('/updateReview/{review_id}', 'update')->middleware('auth:sanctum');
    Route::delete('/deleteReviwe/{review_id}', 'delete');
});

//suggestion routes
Route::controller(SuggestionController::class)->group(function () {
    Route::get('/showAllSuggestions', 'index');
    Route::get('/showsuggestion/{suggestion_id}', 'showSuggestion');
    Route::post('/createsuggestion', 'create')->middleware('auth:sanctum');
    Route::post('/updatesuggestion/{suggestion_id}', 'update')->middleware('auth:sanctum');
    Route::delete('/deletesuggestion/{suggestion_id}', 'delete');
});

//filter
Route::get('/level/count', [LevelController::class, 'countlevelusers']);
Route::get('/ages', [UserController::class, 'showAges']);
Route::get('/users/show', [UserController::class, 'showUsers']);
Route::get('/type/count/{periodVariable}', [BookController::class, 'typeReading']);




Route::middleware('setapplang')->prefix('{locale}')->group(function () {
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
