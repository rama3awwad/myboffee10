<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends BaseController
{

    //add to favorite
    public function add(Request $request, $bookId)
    {

        $user = Auth::user();
        $isFavorited = $user->favoriteBooks()->where('book_id', $bookId)->exists();

        if ($isFavorited) {
            return $this->sendError('Already added to favorites.');
        }
        $user->favoriteBooks()->attach($bookId);

        return $this->sendResponse([], 'Book added to favorites');
    }

    //show me my favorites
    public function showMine()
    {
        $user = Auth::user();
        $favorites = $user->favoriteBooks()->get();

        if ($favorites->isEmpty()) {
            return $this->sendResponse(['error' => 'There is no books in favorites.'], 'Error');
        }

        return $this->sendResponse($favorites, 'User\'s favorites');
    }

    //show favorite by user id
    public function showUserFav($userId)
    {

        $user = User::findOrFail($userId);
        $favorites = $user->favoriteBooks;

        return $this->sendResponse($favorites, 'Favorites fetched successfully');
    }

    //remove from favorite
    public function remove($bookId)
    {
        $user = Auth::user();
        $user->favoriteBooks()->detach($bookId);

        return $this->sendResponse([], 'File removed successfully');
    }
}


