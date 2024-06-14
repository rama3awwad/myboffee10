<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\RatingRequest;
use App\Models\Book;
use App\Models\Rating;
use App\Models\Shelf;
use App\Models\User;
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
    public function add(RatingRequest $request)
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
        }}


        public function avgRate($bookId): JsonResponse
    {
        $avgRate = Rating::where('book_id', $bookId)->avg('rate');

        if ($avgRate == null) {
            return response()->json(['message' => "There are no ratings for this book."], 404);
        }

        return response()->json(['average_rating' => $avgRate], 200);
    }


    public function delete(int $userId, int $bookId): JsonResponse
    {
        $rating = Rating::where('user_id', $userId)->where('book_id', $bookId)->first();

        if (!$rating) {
            return response()->json(['message' => "Rating not found."], 404);
        }

        $rating->delete();

        return $this->sendResponse(null, 'Rating deleted successfully');
    }
}
