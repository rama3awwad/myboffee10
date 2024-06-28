<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\BaseController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends BaseController
{
    public function index()
    {
        $post = Post::all();
        return $this->sendResponse($post, 'Posts retrieved successfully');
    }

    //show user's posts
    public function show($userId){

       // $user = Auth::user();
        $user = User::where('id', $userId)->first();
        $posts = $user->posts;
        return $this->sendResponse($posts, 'user\'s posts retrieved successfully');
    }

    //show my posts
    public function showMyPosts(){

        $user_id = Auth::user()->id;
        $user = User::where('id', $user_id)->first();
        $posts = $user->posts;
        return $this->sendResponse($posts, 'Your posts retrieved successfully');
    }

    public function create(Request $request){

        $request->validate([
            'body'=> 'required',

        ]);
        $input=$request->all();

        $user = auth()->user()->user_name;
        $user_id = Auth::user()->id;
        $post = Post::create([

            'user_id' => $user_id,
            'user_name' => $user,
            'body' => $request->body,
            'likes_count'=>$request->likes_count ?? 0
        ]);

        return $this->sendResponse($post, 'Post created successfully.');
    }
  /* public static function updateLikesCount($postId)
    {
       // $posts = Post::all();
       $user = Auth::user();
       $posts = $user->favoritePosts()->where('post_id' , $postId)->exists();
        foreach ($posts as $post) {
            $post = Post::find($postId);
            if($post){
            $totalLikes = $post->favoritesPosts()->count();
            $post->update(['likes_count' => $totalLikes]);
        }}
    }
*/
    public function update(Request $request, Post $id ){

        $post = Post::find($id);
        if (is_null($post)) {
            return $this->sendError('Post not found');}
        else{

            $request->validate([
                'body'=> 'required',
            ]);
            $input=$request->all();

            $user_id = Auth::user()->id;
            $post -> update([
                'id' => $request->id,
                'user_id' => $user_id,
                'body' => $request->body,
            ]);

            return $this->sendResponse($post, 'Post updated successfully');

        }
    }

    //show post
    public function showP($post_id){

        $post = Post::find($post_id);

        if (is_null($post)) {
            return $this->sendError('Post not found');
        }

        return $this->sendResponse($post, 'Post retrieved successfully');

    }
    public function delete(Post $id)
    {
        $post = Post::find($id);
        if (is_null($post)) {
            return $this->sendError('Post not found');
        }

        $post->each->delete();

        return $this->sendResponse(null, 'Post deleted successfully');
    }

}
