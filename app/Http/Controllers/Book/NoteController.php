<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteRequest;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{

    // Add a note
    public function store(NoteRequest $request, $bookId): JsonResponse
    {
        $request->validated();
        $user = Auth::user();
        $note = Note::create ([
            'user_id'=>$request->user()->id,
            'book_id'=>$request->$bookId,
            'page_num' => $request->page_num,
            'body'=>$request->body,
        ]);

        return $this->sendResponse($note,  'Note created successfully.');
    }

    //show note by its id for admin
    public function show($id): JsonResponse
    {
        $note = Note::find($id);

        if (is_null($note)) {
            return $this->sendError('Note not found');
        }

        return $this->sendResponse($note, 'Note retrieved successfully');
    }

    //show all of my notes
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $notes = $user->notes();

        return $this->sendResponse($notes, 'User\'s notes');
    }

    public function update(Request $request, $bookId): JsonResponse
    {
        $request->validated();
        $user = Auth::user();

        $note = Note::find($bookId);

        if (is_null($note)) {
            return $this->sendError('Note not found');
        }

        $note->update($request->all());

        return $this->sendResponse($note,  'Note created successfully.');
    }

    // Show my notes on specific book
    public function showMine($bookId): JsonResponse
    {
        $user_id =Auth::user()->id;
        $notes = Note::where('book_id', $bookId)->where('user_id',$user_id)->get();

        return $this->sendResponse($notes, 'Notes of this book retrieved successfully');
    }

    // Remove a note
    public function delete($noteID): JsonResponse
    {
        $note = Note::findOrFail($noteID);
        $note->delete();

        return $this->sendResponse(null, 'Note removed successfully');
    }

    // Delete my reports
    public function deleteAllUserReports(): JsonResponse
    {
        $user = Auth::user();
        $user->reports()->detach();

        return $this->sendResponse(null, 'All reports removed successfully');
    }
}
