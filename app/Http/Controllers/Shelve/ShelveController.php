<?php

namespace App\Http\Controllers\Shelve;

use App\Http\Controllers\Controller;
use App\Models\Shelve;
use Illuminate\Http\Request;

class ShelveController extends Controller
{
    public function storeL(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ]);

        //create new shelve with status: read_later
        $shelve = Shelve::create([
            'user_id' => $validatedData['user_id'],
            'book_id' => $validatedData['book_id'],
            'status' => 'read_later',
            'progress' => 0,
        ]);

        // Return a success response
        return response()->json([
            'message' => 'Shelve entry created successfully.',
            'data' => $shelve
        ], 201);
    }

}
