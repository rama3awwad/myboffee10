<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteBookController extends Controller
{
    public function addToFavorites($bookId){

        $user = Auth::user();
        $user->favorite_books->attach($bookId);

        return $this->sendResponse(null, 'Book added to favorites');
}
    public function removeFromFavorites($bookId){

        $user = Auth::user();
        $user->favorite_books->detach($bookId);

        return $this->sendResponse(null, 'Book removed from favorites');
    }
    public function showMyFavorite(){

        $user = Auth::user();
        $favorite_books = $user->favorite_books;

    }
}
