<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends BaseController
{
    public function add(Request $book_id){

        $user_id = Auth::user()->id;
        $exist = Rating::where('book_id',$book_id)->first();
        if(!exist)


    }
}
