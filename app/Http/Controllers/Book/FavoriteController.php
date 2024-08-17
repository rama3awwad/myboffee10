<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteController extends BaseController
{

    //add to favorite
    public function addToFavorite(Request $request, $bookId)
    {

        $user = Auth::user();
        $user->favoriteBooks()->attach($bookId);

        return $this->sendResponse([], 'Book added to favorites');
    }

    //show me my favorites

        public function showMine()
    {
        $userId = Auth::id();

        $favorites = DB::table('favorite_books')
            ->join('books', 'favorite_books.book_id', '=', 'books.id')
            ->join('types', 'books.type_id', '=', 'types.id')
            ->where('favorite_books.user_id', $userId)
            ->select(
                'favorite_books.id as favorite_id',
                'favorite_books.user_id',
                'favorite_books.book_id',
                'books.title',
                'books.cover',
                'books.file',
                'books.author_name',
                'books.total_pages',
                'books.points',
                'types.name as type_name'
            )
            ->get();

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

        return $this->sendResponse([], 'PDF removed successfully');
    }
}


