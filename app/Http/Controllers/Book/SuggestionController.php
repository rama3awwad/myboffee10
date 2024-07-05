<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\BaseController;
use App\Http\Requests\SuggestionRequest;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuggestionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $suggestions=Suggestion::all();
        return $this->sendResponse($suggestions, 'Suggestions retrieved successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(suggestionRequest $request)
    {
        $user_id = Auth::user()->id;
        $user = auth()->user()->user_name;
        $suggestion = Suggestion::create([
            'id'=> $request->id,
            'user_id' => $user_id,
            'user_name' => $user,
            'body' => $request->body,
            'author_name'=> $request->author_name,

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

    public function update(Request $request, $id)
    {
        $suggestion = Suggestion::find($id);
        if (is_null($suggestion)) {
            return $this->sendError('Suggestion not found');
        }
            else{
          $request->validate([
          'body'=> 'required',
        ]);
            $input=$request->all();

            $user_id = Auth::user()->id;
            $suggestion -> update([
            'id'=> $request->id,
            'user_id' => $user_id,
            'body' => $request->body,
            'author_name'=> $request->author_name,

        ]);

        return $this->sendResponse($suggestion, 'Suggestion updated successfully.');
    }
}

public function delete($id)
{
    $suggestion = Suggestion::find($id);
    if (is_null($suggestion)) {
        return $this->sendError('Suggestion not found');
    }
    $suggestion->delete();
    return $this->sendResponse(null, 'Suggestion deleted successfully');
}
}
