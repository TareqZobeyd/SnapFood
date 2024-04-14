<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFoodDiscountRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'restaurant_id' => 'nullable|exists:restaurants,id',
            'discount_percentage' => 'required|numeric|between:5,95',
            'food_party' => 'nullable|string',
        ];
    }
}
