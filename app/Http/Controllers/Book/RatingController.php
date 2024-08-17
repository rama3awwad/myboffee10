<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\RatingRequest;
use App\Http\Resources\RatingResource;
use App\Models\Book;
use App\Models\Rating;
use App\Models\Shelf;
use App\Models\User;
use App\Models\Level;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends BaseController
{

    /**
     * Add or update a rating for a book by a user.
     *
     * @param RatingRequest $request
     * @return JsonResponse
     */
   /* public function add(RatingRequest $request)
    {
        $userId = Auth::id();
        $bookId = $request->input('book_id');
        $ratingValue = $request->input('rate');

        $exist = Rating::where('user_id', $userId)->where('book_id', $bookId)->first();

        if ($exist) {
            $exist->update(['rate' => $ratingValue]);
            return $this->sendResponse($exist, 'Rating updated successfully.');
        } else {
            $rating = Rating::create([
                'user_id' => $userId,
                'book_id' => $bookId,
                'rate' => $ratingValue,
            ]);
            return $this->sendResponse($rating, 'Rating created successfully.');
        }
    }
*/

        public function avgRate($bookId): JsonResponse
    {
        $avgRate = Rating::where('book_id', $bookId)->avg('rate');

        if ($avgRate == null) {
            return response()->json(['message' => "There are no ratings for this book."], 404);
        }

        return response()->json(['average_rating' => $avgRate], 200);
    }





   /* public function store(Request $request,Book $book)
    {
        $ratings = $book->ratings()->select('rating','user_id')
            ->where('user_id',$request->user()->id)->get();
        if ($ratings->isNotEmpty()){
            return response()->json([
                'message'=>'is exists',
                'data'=>$ratings,
            ]);
        }
        $request->validate([
            'rating'=>['required','integer','min:1','max:5']
        ]);
        Rating::create([
            'book_id'=>$book->id,
            'user_id'=>$request->user()->id,
            'rating'=>$request->rating
        ]);
        $bookRating = $book->ratings()->where('book_id',$book->id)
            ->where('user_id',$request->user()->id)->get();
        return response()->json([
            'data'=>$bookRating
        ]);
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'rating'=>['required','integer','min:1','max:5']
        ]);
        $book->ratings()->where('user_id',$request->user()->id)->update([
            'rating'=>$request->rating
        ]);
        $bookRating = $book->ratings()->where('user_id',$request->user()->id)
            ->get();
        return response()->json([
            'data'=>$bookRating
        ]);
    }*/


    public function add(RatingRequest $request)
    {
        $userId = Auth::user()->id;
        $bookId =  (int) $request->input('book_id');
        $rate =  (int) $request->input('rate');

        $exist = Rating::where('user_id', $userId)->where('book_id', $bookId)->first();

        if ($exist) {
            $exist->update(['rate' => $rate]);
            return $this->sendResponse($exist, 'Rating updated successfully.');
        } else {
            $rating = Rating::create([
                'user_id' => $userId,
                'book_id' => $bookId,
                'rate' => $rate,
            ]);
            return $this->sendResponse($rating, 'Rating created successfully.');
        }
    }

   /* public function avgRating($bookId): JsonResponse
    {
        $book = Book::find($bookId);

        if (!$book) {
            return response()->json(['message' => "Book not found."], 404);
        }

        $avgRate = $book->ratings()->avg('rate');

        if ($avgRate === null) {
            return response()->json(['message' => "There are no ratings for this book."], 404);
        }

        return response()->json(['average_rating' => $avgRate], 200);
    }
*/

    public function avgRating($id)
    {

        $sumOfRatings = Rating::where('book_id', $id)->sum('rate');
        $countOfRatings = Rating::where('book_id', $id)->count();

        if ($countOfRatings >= 0) {
            $averageRating =round( $sumOfRatings / $countOfRatings,1);
            //$scaledRating = round($averageRating , 1);
            return $averageRating;
        }

        return null;

    }

    public function showRatersDetails($bookId): JsonResponse
    {
        $ratings = Rating::where('book_id', $bookId)->with(['user.level'])->get();

        $raterDetails = Rating::select('books.title','books.id as book_id', 'users.user_name', 'users.id as user_id', 'levels.level', 'levels.books')
            ->join('books', 'ratings.book_id', '=', 'books.id')
            ->join('users', 'ratings.user_id', '=', 'users.id')
            ->join('levels', 'users.id', '=', 'levels.user_id')
            ->where('ratings.book_id', $bookId)
            ->get();

        return $this->sendResponse($raterDetails, 'Users Details retrieved successfully.');
    }

}
