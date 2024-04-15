<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'phone' => 'required|string|min:11',
        ];
    }

    public function messages()
    {
        return [
            'phone.min' => 'the phone number must be at least 11 characters.',
        ];
    }
}
