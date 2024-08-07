<?php

namespace App\Http\Controllers\Post;

use App\Models\Post;
use App\Models\User;
use App\Models\FavoritePost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;

class FavoritePostController extends BaseController
{

    /* public function addToFavorites($postId){
    $user = Auth::user();

    if (!$user) {
        return $this->sendError('User not found!');
    }

    $post = Post::findOrFail($postId);
    if(!$post){
        return $this->sendError('post not found!');
    }
    $is_favorited = $user->favoritePosts()->where('post_id' , $postId)->exists();
        if($is_favorited){
            return $this->sendError('Post is favorited');
        }
       $user->favoritePosts()->attach($postId);
        return $this->sendResponse(null, 'Post added to favorites');
}*/

    public function addToFavorites($post)
    {

        $user = auth()->user()->favoritePosts()->syncWithoutDetaching((array)$post);

        $post = Post::findOrFail($post);
        if (!$post) {
            return $this->sendError('post not found!');
        }
        return $this->sendResponse(null, 'Post added to favorites');
    }

    public function countLikes($postId)
    {

        $count = FavoritePost::where('post_id', $postId)->count();
        $post = Post::findOrFail($postId);
        $post->likes_count = $count;
        $post->save();

        return $this->sendResponse($count, 'update likes number');
    }

    public function removeFromFavorites($postId)
    {

        $user = Auth::user();
        $user->favoritePosts()->where('post_id', $postId)->exists();

        $user->favoritePosts()->detach($postId);

        return $this->sendResponse(null, 'Post removed from favorites');
    }

    public function showUserFav($userId)
    {

        $user = User::find($userId);
        $favorites = $user->favoritePosts()->get();

        return $this->sendResponse($favorites, 'Favorites fetched successfully');
    }

    public function showMyFavorite()
    {

        $user = Auth::user();
        $favorite_posts = $user->favoritePosts()->get();

        return $this->sendResponse($favorite_posts, 'Favorites fetched successfully');
    }
}
