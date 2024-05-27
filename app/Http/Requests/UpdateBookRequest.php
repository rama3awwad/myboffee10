<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
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
