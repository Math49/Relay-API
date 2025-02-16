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
        return [
            'Label' => 'required|string|max:50',
            'Box_quantity' => 'required|integer',
            'Image' => 'required|string|max:255',
            'Packing' => 'required|boolean',
            'Barcode' => 'required|string|max:13|min:13',
            'Category_id' => 'required|integer|exists:categories,ID_category',
        ];
    }
}
