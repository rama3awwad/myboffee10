<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritePostController extends Controller
{
    public function addToFavorites(){

        $this->favorite_posts()->where('user_id', auth()->id())->exists() ;
        $this->favorite_posts()->attach(auth()->id());

        return $this->sendResponse(null, 'Post added to favorites');

        $this->increment('likes_num');

}
    public function removeFromFavorites(){

        $this->favorite_posts()->where('user_id', auth()->id())->exists() ;
        $this->favorite_posts()->detach(auth()->id());

        return $this->sendResponse(null, 'Post removed from favorites');

        $this->decrement('likes_num');

    }
    public function showMyFavorite(){

        $user = Auth::user();
        $favorite_posts = $user->favorite_posts;

    }
}
