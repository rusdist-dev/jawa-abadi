<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q'          => ['nullable', 'string', 'max:100'],
            'category'   => ['nullable', 'array', 'max:20'],
            'category.*' => ['string', 'max:100'],
            'region'     => ['nullable', 'array', 'max:20'],
            'region.*'   => ['string', 'max:100'],
            'min_price'  => ['nullable', 'integer', 'min:0'],
            'max_price'  => ['nullable', 'integer', 'min:0', 'gte:min_price'],
            'in_stock'   => ['nullable', 'boolean'],
            'min_rating' => ['nullable', 'numeric', 'between:0,5'],
            'sort'       => ['nullable', Rule::in(array_keys(Product::SORTS))],
            'per_page'   => ['nullable', 'integer', 'between:1,48'],
            'page'       => ['nullable', 'integer', 'min:1'],
        ];
    }
}
