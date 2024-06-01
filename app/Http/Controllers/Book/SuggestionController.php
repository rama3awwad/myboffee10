<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\BaseController;
use App\Http\Requests\suggestionRequest;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class suggestionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suggestions=Suggestion::all();
        return $this->sendResponse($suggestions, 'Suggestions retrieved successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(suggestionRequest $request)
    {
        $user_id = Auth::user()->id;
        $suggestion = Suggestion::create([
            'id'=> $request->id,
            'user_id' => $user_id,
            'body' => $request->body,

        ]);

        return $this->sendResponse($suggestion, 'Suggestion created successfully.');
    }

    public function showSuggestion($id)
    {

        $suggestion = Suggestion::find($id);
        if (is_null($suggestion)) {
            return $this->sendError('Suggestion not found');
        }
        else{
            return $this->sendResponse($suggestion, 'Suggestion retrieved successfully.');
        }
    }

    public function update(suggestionRequest $request, $id)
    {
        $suggestion = Suggestion::find($id);
        if (is_null($suggestion)) {
            return $this->sendError('Suggestion not found');}
            else{
        $user_id = Auth::user()->id;
        $suggestion = Suggestion::make([
            'id'=> $request->id,
            'user_id' => $user_id,
            'body' => $request->body,

        ]);

        return $this->sendResponse($suggestion, 'Suggestion updated successfully.');
    }
}

    public function destroy($id)
    {
        $suggestion = Suggestion::find($id);
        if (is_null($suggestion)) {
            return $this->sendError('Suggestion not found');
        }

        $suggestion->each->delete();

        return $this->sendResponse(null, 'Suggestion deleted successfully');
    }
}
