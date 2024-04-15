<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|unique:users|email',
            'phone' => 'required|string|min:11',
            'password' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'this email address is already in use.',
            'phone.min' => 'the phone number must be at least 11 characters.',
        ];
    }
}
