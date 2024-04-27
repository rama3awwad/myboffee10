<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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

            'user_name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|confirmed|min:6',
            'age' => 'required',
            'user_name.unique' => 'user_name is not unique',
            'Email.unique' => 'email is not unique',
            'password.confirmed' => 'The password confirmation does not match.',
            'gendre_id' => 'required'

        ];
    }
}
