<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteBookResource extends JsonResource
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
           /* 'pivot' => [
                'user_id' => $this->('pivot.user_id'),
                'book_id' => $this->('pivot.book_id'),
                'created_at' => $this->('pivot.created_at'),
                'updated_at' => $this->('pivot.updated_at')
            ],*/
            'bookDetails' => [
                'id' => $this->id,
                'title' => $this->title,
                'file' => $this->file,
                'cover' => $this->cover,
                'author_name' => $this->author_name,
                'points' => $this->points,
                'description' => $this->description,
                'total_pages' => $this->total_pages,
                'type_id' => $this->type_id,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ]
        ];
    }
}
