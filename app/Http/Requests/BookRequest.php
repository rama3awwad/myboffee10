<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'file'=>['required','file'],
            'cover'=>['required','image'],
            'author_name' => 'required|string|max:255',
            'points' => 'required|integer',
            'description' => 'required|string',
            'total_pages' => 'required|integer',
            'type_id' => 'required|integer|exists:types,id',
        ];
    }
}
