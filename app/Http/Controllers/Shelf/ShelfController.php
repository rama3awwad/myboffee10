<?php

namespace App\Http\Controllers\Shelf;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaterStatusRequest;
use App\Models\Shelf;
use Illuminate\Http\Request;

class ShelfController extends BaseController
{
    //create shelf its status == watch_later
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
        } else {
            $shelf = Shelf::create([
                'user_id' => $userId,
                'book_id' => $bookId,
                'status' => 'watch_later',
                'progress' => 0,
            ]);
        }

        return $this->sendResponse($shelf, 'Shelf updated or created successfully.');
    }



}
