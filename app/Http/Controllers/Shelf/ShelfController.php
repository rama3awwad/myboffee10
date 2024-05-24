<?php

namespace App\Http\Controllers\Shelf;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaterStatusRequest;
use App\Models\Shelf;
use App\Models\User;
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
                'status' => 'watch_later',
                'progress' => 0,
            ]);
            return $this->sendResponse($shelf, 'Shelf created successfully.');

        } else {
            $shelf = Shelf::create([
                'user_id' => $userId,
                'book_id' => $bookId,
                'status' => 'watch_later',
                'progress' => 0,
            ]);

        return $this->sendResponse($shelf, 'Shelf updated successfully.');
    }}


//update progress
    public function updateProgress(Request $request, $shelfId)
    {
        $shelf = Shelf::findOrFail($shelfId);

        $validated = $request->validate([
            'progress' => 'required|integer|min:0|max:'. $shelf->total_pages,
        ]);

        $updatedProgress = $shelf->update(['progress' => $validated['progress']]);

        if ($updatedProgress['progress'] >= $shelf->total_pages) {
            $shelf->update(['status' => 'finished']);

        }
        $userId = Auth::user()->id;

        User::find($userId)->increment('my_points', 5);

        return $this->sendResponse([
            'updated Shelf' => $shelf,
        ], 'Progress updated.');
    }

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

}
