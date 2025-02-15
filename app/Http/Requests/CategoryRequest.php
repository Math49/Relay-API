<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [];

        if ($this->has('category')) {
            $rules = array_merge($rules, [
                'label' => 'required|string|max:255',
            ]);
        }

        if ($this->has('category_enable')) {
            $rules = array_merge($rules, [
                'ID_category' => 'required|integer',
                'ID_store' => 'required|integer',
                'Category_position' => 'required|integer',
            ]);
        }

        return $rules;
    }
}
