<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{

    //add to favorite
    public function addToFavorite(Request $request, $bookId)
    {

        $user = Auth::user();
        $book = Book::findOrFail($bookId);
        $book->favoriteBooks()->attach($bookId);

        return $this->sendResponse([], 'Book added to favorites');
    }

    //show me my favorites
    public function showMine()
    {
        $user = Auth::user();
        $favorites = $user->favoriteBooks();

        return $this->sendResponse($favorites, 'User\'s favorites');
    }

    //show favorite by user id
    public function showUserFav($userId)
    {

        $user = User::findOrFail($userId);
        $favorites = $user->favoriteBooks();

        return $this->sendResponse($favorites, 'Favorites fetched successfully');
    }

    //remove from favorite
    public function remove($bookId)
    {
        $user = Auth::user();
        $book = Book::findOrFail($bookId);
        $book->favoriteBooks()->detach($bookId);

        return $this->sendResponse([], 'PDF removed successfully');
    }
}


