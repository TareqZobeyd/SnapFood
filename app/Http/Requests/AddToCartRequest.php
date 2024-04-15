<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            'food_id' => 'required|exists:food,id',
            'count' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'food_id.exists' => 'the selected food does not exist.',
            'count.integer' => 'the count must be an integer.',
            'count.min' => 'the count must be at least 1.',
        ];
    }
}
