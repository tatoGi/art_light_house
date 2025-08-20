<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Assuming anyone can make a product request; you may adjust this as needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            // Keep only the necessary fields; others can be null/not provided
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'on_sale' => 'nullable|boolean',
            // sale_price will be set conditionally below
            'sort_order' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'file|mimes:jpg,jpeg,png,webp|max:4096',
        ];

        // Translatable attributes: only title (required) and description (optional)
        foreach (config('app.locales') as $locale) {
            $rules["{$locale}.title"] = 'required|string|max:255';
            $rules["{$locale}.description"] = 'nullable|string|max:5000';
        }
        
        // Conditional rule for sale_price
        if ($this->boolean('on_sale')) {
            $rules['sale_price'] = 'required|numeric|min:0';
        } else {
            $rules['sale_price'] = 'nullable|numeric|min:0';
        }
    
        return $rules;
    }
}
