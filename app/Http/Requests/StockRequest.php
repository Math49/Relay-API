<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
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
            'ID_product' => 'integer|exists:products,ID_product',
            'ID_store' => 'integer|exists:stores,ID_store',
            'Quantity' => 'integer',
            'Nmb_boxes' => 'integer',
            'Nmb_on_shelves' => 'integer',
        ];
    }
}
