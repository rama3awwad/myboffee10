<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends BaseController
{
    public function index()
    {
        $post = Post::all();
        return $this->sendResponse($post, 'Posts retrieved successfully');
    }
    public function showMyPosts(){

        $user = Auth::user();
        $posts = $user->posts;
    }
    public function create(Request $request){

        $request->validate([
            //'id' => ['required', 'integer' , 'exists:users,id'],
            'body'=> 'required',
        ]);
        $input=$request->all();

        //$user = Auth::user();
        $user_id = Auth::user()->id;
        $post = Post::create([
            'user_id' => $user_id,
            'body' => $request['body'],
        ]);

        return $this->sendResponse($post, 'Post created successfully.');
    }
//    public function create(PostRequest $request){
//        $input = $request->all();
//
//        // Correctly pass additional attributes as separate parameters
//        $post = Post::create([
//            'body' => $input['body'], // Assuming 'title' is one of the fields in your input
//            'book_name' => $input['book_name'],
//            // Add other fields here...
//        ], [
//            'user_id' => Auth::id(),
//            'body' => $request->body,
//            'book_name' => $request->book_name,
//        ]);
//
//        return $this->sendResponse($post, 'Post created successfully.');
//    }

    public function update(PostRequest $request, Post $id ){

        $post = Post::find($id);
        if (is_null($post)) {
            return $this->sendError('Post not found');

            $post = Post::make($request->all(),[
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
