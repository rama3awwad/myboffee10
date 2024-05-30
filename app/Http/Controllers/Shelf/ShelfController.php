<?php

namespace App\Http\Controllers\Shelf;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaterStatusRequest;
use App\Models\Book;
use App\Models\Shelf;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\shelf as shelfResource;

class ShelfController extends BaseController
{

//create shelf status = later
    public function storeLaterStatus(StoreLaterStatusRequest $request)
    {
        $userId = Auth::user()->id;
        $bookId = $request->input('book_id');

        $shelf = Shelf::where('user_id', $userId)->where('book_id', $bookId)->first();

        if ($shelf) {
            $shelf->update([
                'status' => 'watch_later',
                'progress' => 1,
            ]);
            return $this->sendResponse($shelf, 'Shelf updated successfully.');

        } else {
            $shelf = Shelf::create([
                'user_id' => $userId,
                'book_id' => $bookId,
                'status' => 'watch_later',
                'progress' => 1,
            ]);

        return $this->sendResponse($shelf, 'Shelf created successfully.');
    }}



// Count shelves with status 'reading' or 'finished' for a given book
    public function count($bookId): \Illuminate\Http\JsonResponse
    {
        $count = Shelf::where('book_id', $bookId)
            ->whereIn('status', ['reading', 'finished'])
            ->count();

        return $this->sendResponse([
            'count' => $count,
        ], 'Count of reading or finished shelves retrieved successfully.');
    }

//show books of user's shelf
 /*   public function myShelf(Request $request): JsonResponse
    {
        $userId = Auth::user()->id;
        $status = $request->input('status');

        $shelves = Shelf::where('user_id', $userId)->where('status', $status)->get();

        $bookIds = $shelves->pluck(' book_id')->toArray();

        $books = Book::whereIn('id', $bookIds)->get();

        if ($books->isEmpty()) {
            return $this->sendError('No books found with the specified status.');
        }

        return $this->sendResponse($books, 'Books retrieved successfully.');
    }*/

//show books in my shelf
    public function myShelf(Request $request): JsonResponse
    {

        $userId = Auth::user()->id;

        $status = $request->input('status');
        $shelves = Shelf::with(['book'])
        ->where('user_id', $userId)
            ->where('status', $status)
            ->get();

        if ($shelves->isEmpty()) {
            return response()->json(['error' => 'No shelves found with the specified status.'], 404);
        }

        $newShelve = $shelves->map(function ($shelf) {
            return [
                'shelf' => $shelf->only(['id', 'book_id', 'user_id', 'status', 'progress', 'created_at', 'updated_at']),
                'book' => [
                    'title' => $shelf->book->title,
                    'cover' => $shelf->book->cover,
                ],
            ];
        });

        return response()->json($newShelve, 200);
    }



//count books on user's shelf
    public function countMine(Request $request): JsonResponse
    {
        $userId = Auth::user()->id;
        $status = $request->input('status');

        $count = Shelf::where('user_id', $userId)->where('status', $status)->count();

        return $this->sendResponse([
            'count' => $count,
        ], 'Count of books in this shelf retrieved successfully.');
    }


    //update progress
    public function updateProgress(Request $request, $shelfId)
    {
        $shelf = Shelf::findOrFail($shelfId);

        $validated = $request->validate([
            'progress' => ['required', 'integer'],
        ]);
        $shelf->update(['progress' => $validated['progress'],'status' => 'reading']);

        $book = Book::where('id', $shelf->book_id)->first();

           if (($validated['progress']) >=( $book->total_pages)) {
                $shelf->update(['status' => 'finished']);
                $userId = Auth::user()->id;
                User::find($userId)->increment('my_points', 5);
            }
            return $this->sendResponse([
                'updated Shelf' => $shelf,
            ], 'Progress updated.');
        }


    }
