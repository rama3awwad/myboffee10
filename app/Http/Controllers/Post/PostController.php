<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $post = Post::all();
        return $this->sendResponse($post, 'Posts retrieved successfully');
    }
    public function showMyPosts(){

        $user = Auth::user();
        $posts = $user->posts;
    }
    public function create(PostRequest $request){


        $post = Post::create([

         'user_id' => Auth::id(),
         'body' => $request->body,
         'book_name' => $request->book_name,

        ]);


    return $this->sendResponse($post[], 'Post created successfully.');
}
public function update(PostRequest $request, Post $id ){

    $post = Post::find($id);
    if (is_null($post)) {
     return $this->sendError('Post not found');

        $post = Post::make([
            'body' => $request->body,
            'book_name'=>$request->book_name
            ]);
        $success = [
            'id' => $post->id,
            'user_id'=>$post -> user_id,
            'body' => $post->body,
            'book_name' => $post->book_name,
            'likes_num' => $post->likes_num,
            ];

    return $this->sendResponse($success, 'Post updated successfully');

    }
}
    public function delete(Post $id)
    {
        $post = Post::find($id);
        if (is_null($post)) {
            return $this->sendError('Post not found');
        }
        $post->delete();
        return $this->sendResponse(null, 'Post deleted successfully');
    }
}

