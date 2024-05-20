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
    public function showMyPosts($userId){

       // $user = Auth::user();
        $user = User::where('id', $userId)->first();
        $posts = $user->posts;
        return $this->sendResponse($posts, 'My Posts retrieved successfully');
    }
    public function create(Request $request){

        $request->validate([
            'body'=> 'required',
        ]);
        $input=$request->all();

        $user_id = Auth::user()->id;
        $post = Post::create([
            'user_id' => $user_id,
            'body' => $request->body,
        ]);

        return $this->sendResponse($post, 'Post created successfully.');
    }


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
            $post = Post::make([

                'user_id' => $user_id,
                'body' => $request->body,
            ]);

            return $this->sendResponse($post, 'Post updated successfully');

        }
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
