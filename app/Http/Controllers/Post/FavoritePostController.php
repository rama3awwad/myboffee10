<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritePostController extends Controller
{
    public function addToFavorites($postId){

        $user = Auth::user();
        $user->favorite_posts->attach($postId);

        return $this->sendResponse(null, 'Post added to favorites');

        $this->increment('likes_num');

}
    public function removeFromFavorites($postId){

        $user = Auth::user();
        $user->favorite_posts->detach($postId);
        
        return $this->sendResponse(null, 'Post removed from favorites');

        $this->decrement('likes_num');

    }
    public function showMyFavorite(){

        $user = Auth::user();
        $favorite_posts = $user->favorite_posts;

    }
}
