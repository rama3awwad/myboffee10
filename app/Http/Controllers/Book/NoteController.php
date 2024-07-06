<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\NoteRequest;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NoteController extends BaseController
{

    // Add a note
    public function store(NoteRequest $request,$bookId): JsonResponse
    {
        $request->validated();
        $user = Auth::user();
        $note = Note::create ([
            'user_id'=>$request->user()->id,
            'book_id'=>$bookId,
            'page_num' => $request->page_num,
            'body'=>$request->body,
            'color'=>$request->color,
        ]);

        return $this->sendResponse($note,  'Note created successfully.');
    }


    //show all of my notes
    public function showMyNotes(): JsonResponse
    {
        $userId = Auth::user()->id;

        $notes = DB::table('notes')
            ->join('books', 'notes.book_id', '=', 'books.id')
            ->select('notes.*', 'books.title', 'books.cover', 'books.file')
            ->where('notes.user_id', $userId)
            ->get();

        return $this->sendResponse($notes, 'Your notes retrieved successfully');
    }

    public function update(NoteRequest $request, $noteId): JsonResponse
    {
        $request->validated();
        $user_id = Auth::user()->id;

        $note = Note::find($noteId);

        if (is_null($note)) {
            return $this->sendError('Note not found');
        }

        $note->update($request->all());

        return $this->sendResponse($note,  'Note updated successfully.');
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

    // Delete my notes
    public function deleteAll(): JsonResponse
    {
        $user = Auth::user();
        $user->notes()->detach();

        return $this->sendResponse(null, 'All notes removed successfully');
    }
}
