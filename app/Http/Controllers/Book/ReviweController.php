<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviweRequest;
use App\Models\Reviwe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviweController extends Controller
{
    public function index()
    {
        $review = Reviwe::all();
        return $this->sendResponse($review, 'Reviews retrieved successfully');
    }

    
    public function create(ReviweRequest $request)
    {
        $review = Reviwe::create([

            'user_id' => Auth::id(),
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
