<?php

namespace App\Http\Controllers\Shelf;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaterStatusRequest;
use App\Models\Book;
use App\Models\Level;
use App\Models\Shelf;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShelfController extends BaseController
{

//create shelf status = later
    public function storeLaterStatus(StoreLaterStatusRequest $request)
    {
        $userId = auth()->id();
        $bookId = $request->input('book_id');

        $shelf = Shelf::where('user_id', $userId)->where('book_id', $bookId)->first();

        if ($shelf) {
            $shelf->update([
                'status' => 'read_later',
                'progress' => 0,
            ]);
            return $this->sendResponse($shelf, 'Shelf updated successfully.');

        } else {
            $shelf = Shelf::create([
                'user_id' => $userId,
                'book_id' => $bookId,
                'status' => 'read_later',
                'progress' => 0,
            ]);

            return $this->sendResponse($shelf, 'Shelf created successfully.');
        }
    }


// Count shelves with status 'reading'  for a given book
    public function count($bookId): \Illuminate\Http\JsonResponse
    {
        $count = Shelf::where('book_id', $bookId)
            ->where('status', 'reading')
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
            return response()->json(['error' => 'No shelves found '], 404);
        }

        $bookCount = Shelf::where('user_id', $userId)->where('status', $status)->count();

        $newShelves = [];
        foreach ($shelves as $shelf) {
            $newShelves[] = [
                'shelf' => $shelf->only(['id', 'book_id', 'user_id', 'status', 'progress']),
                'book' => [
                    'title' => $shelf->book->title,
                    'cover' => $shelf->book->cover,
                    'file' => $shelf->book->file,
                ],
               'total_books_count' => $bookCount,
            ];
        }
        return response()->json([
            'shelves' => $newShelves,
        ], 200);
    }

    //update progress
    public function updateProgress(Request $request, $shelfId)
    {
        $shelf = Shelf::findOrFail($shelfId);

        $validated = $request->validate([
            'progress' => ['required', 'integer'],
        ]);

        $shelf->update(['progress' => (int) $validated['progress'], 'status' => 'reading']);

        $book = Book::where('id', $shelf->book_id)->first();

        if ( (int) ($validated['progress']) >= (int) ($book->total_pages-1)) {
            $shelf->update(['status' => 'finished']);

            $userId = Auth::user()->id;
            User::find($userId)->increment('my_points', 5);

            $level = Level::find($userId);
            Level::find($userId)->increment('books', 1);
            $count = Level::find($userId)->books;

            if ($count > 0 && $count < 10) {
                $level->update([
                    'level' => 'first',
                ]);
            }
            if ($count >= 10 && $count < 20) {
                $level->update([
                    'level' => 'second',
                ]);
            } elseif ($count >= 20) {
                $level->update([
                    'level' => 'third',
                ]);
            }
        }
        return $this->sendResponse([
            'updated Shelf' => $shelf,
        ], 'Progress updated.');
    }
}
