<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritePostController extends Controller
{
    public function addMyFavourite(){

        $this->favorite_posts()->where('user_id', auth()->id())->exists() ;
        $this->favorite_posts()->attach(auth()->id());
        $this->increment('likes_num');

}
    public function removeMyFavourite(){

        $this->favorite_posts()->where('user_id', auth()->id())->exists() ;
        $this->favorite_posts()->detach(auth()->id());
        $this->decrement('likes_num');

    }
    public function showMyFavorite(){

        $user = Auth::user();
        $favorite_posts = $user->favorite_posts;

    }
}
