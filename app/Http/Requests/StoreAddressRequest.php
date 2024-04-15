<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|unique:addresses,title,NULL,id,addressable_id,' . auth()->id(),
            'address' => 'required|string|unique:addresses,address,NULL,id,addressable_id,' . auth()->id() .
                ',latitude,' . $this->input('latitude') . ',longitude,' . $this->input('longitude'),
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ];
    }
}
