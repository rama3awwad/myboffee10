<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviweRequest;
use App\Models\Book;
use App\Models\Reviwe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviweController extends BaseController
{
    public function index()
    {
        $review = Reviwe::all();
        return $this->sendResponse($review, 'Reviews retrieved successfully');
    }


    public function create(ReviweRequest $request)
    {

         $request->validated();
        $userId = Auth::user()->id;

        $review = Reviwe::create([
            'id'=>$request->id,
            'user_id' => $userId,
            'book_id'=> $request->book_id,
            'body' => $request->body,

           ]);

       return $this->sendResponse($review, 'Review created successfully.');
    }


    public function delete($id)
    {
        $review = Reviwe::find($id);
        if (is_null($review)) {
            return $this->sendError('Review not found');
        }
        $review->delete();
        return $this->sendResponse(null, 'Review deleted successfully');
    }
}
