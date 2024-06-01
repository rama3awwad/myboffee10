<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\FavoritePost;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritePostController extends BaseController
{

    public function addToFavorites($postId)
{
        $user = Auth::user();
        $is_favorited = $user->favoritePosts()->where('post_id' , $postId)->exists();

        if($is_favorited){
            return $this->sendError('Post is favorited');
        }

       $user->favoritePosts()->attach($postId);

        return $this->sendResponse(null, 'Post added to favorites');

     //   $this->increment('likes_count');
}


    public function removeFromFavorites($postId){

        $user = Auth::user();
        $user->favoritePosts()->where('post_id' , $postId)->exists();

        $user->favoritePosts()->detach($postId);

        return $this->sendResponse(null, 'Post removed from favorites');

      //  $this->decrement('likes_count');

    }

    public function showUserFav($userId)
    {

        $user = User::find($userId);
        $favorites = $user->favoritePosts;

        return $this->sendResponse($favorites, 'Favorites fetched successfully');
    }

    public function showMyFavorite(){

        $user = Auth::user();
        $favorite_posts = $user->favoritePosts;

        return $this->sendResponse($favorite_posts, 'Favorites fetched successfully');
    }

}
