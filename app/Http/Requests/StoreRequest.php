<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('get')) {
            return [];
        }
        return [
            'Address' => 'required|string|max:50',
            'Phone' => 'required|string|max:10|min:10',
            'Manager_name' => 'required|string|max:50',
            'Manager_phone' => 'required|string|max:10|min:10'
        ];
    }
}
