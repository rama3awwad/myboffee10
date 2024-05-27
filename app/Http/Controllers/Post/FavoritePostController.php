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
  /*  public function addToFavorites(Request $request, $postId){

       /* $user = Auth::user();
        $user->favorite_posts->attach($postId);

       // $post = Post::find($postId);
        $post = Post::where('id', $postId)->first();
        $favorite = new FavoritePost(['post_id' => $post->id, 'user_id' => auth()->id()]);
        $favorite->favorite_posts->attach($postId);

        return $this->sendResponse(null, 'Post added to favorites');

        $this->increment('likes_count');

}*/
public function addToFavorites($postId)
{

    $user = Auth::user();
    $user->favorites->attach($postId);

    return $this->sendResponse(null, 'Post added to favorites');
}
    public function removeFromFavorites($postId){

        $user = Auth::user();
        $user->favorite_posts->detach($postId);

        return $this->sendResponse(null, 'Post removed from favorites');

        $this->decrement('likes_count');

    }
    public function showMyFavorite(){

        $user = Auth::user();
        $favorite_posts = $user->favorite_posts;

    }
}
