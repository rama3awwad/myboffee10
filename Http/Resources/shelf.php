<?php

namespace App\Http\Resources;

use App\Models\Book;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class shelf extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'book_id'=>$this->book_id,
            'status'=>$this->status,
            'progress'=>$this->progress,
             'cover' =>count(Book::where('user_id',Auth::user()->id))->Book::with(['cover' => function ($query) {
                $query->select('id', 'url');}])->get()
          //  'cover'=>count(Book::where('user_id',Auth::user()->id)->)
        ];




    }
}
