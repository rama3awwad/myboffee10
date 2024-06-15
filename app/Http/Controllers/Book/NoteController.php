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
        $notes = DB::table('notes')
            ->join('users', 'users.id', '=', 'notes.user_id')
            ->join('books', 'books.id', '=', 'notes.book_id')
            ->select('notes.*', 'books.title as book_title')
            ->select('notes.*', 'books.cover as book_cover')
            ->where('users.id', $user->id)
            ->get();

        return $this->sendResponse($notes, 'User\'s notes');
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

    // Delete my reports
    public function deleteAll(): JsonResponse
    {
        $user = Auth::user();
        $user->notes()->detach();

        return $this->sendResponse(null, 'All notes removed successfully');
    }
}
