<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            'order_id' => 'required|integer',
            'score' => 'required|integer|min:1|max:5',
            'message' => 'required|string|min:5',
        ];
    }

    public function messages()
    {
        return [
            'order_id.required' => 'the order ID is required.',
            'order_id.integer' => 'the order ID must be an integer.',
            'score.required' => 'the score is required.',
            'score.integer' => 'the score must be an integer.',
            'score.min' => 'the score must be at least 1.',
            'score.max' => 'the score must not exceed 5.',
            'message.required' => 'the comment message is required.',
            'message.string' => 'the comment message must be a string.',
            'message.min' => 'the comment message must be at least 5 characters.',
        ];
    }
}
