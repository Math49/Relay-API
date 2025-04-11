<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'Label' => 'string|max:50',
            'Box_quantity' => 'integer',
            'Image' => 'string|nullable',
            'Packing' => 'boolean',
            'Barcode' => 'string|max:13|min:13',
            'ID_category' => 'integer|exists:categories,ID_category',
        ];
    }

    
}
