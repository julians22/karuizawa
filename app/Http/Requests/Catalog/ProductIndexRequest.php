<?php

namespace App\Http\Requests\Catalog;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ProductIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Public catalog endpoint, no authentication required.
        return true;
    }

    /**
     * This is a pure JSON API route, so always respond with JSON 422 errors
     * instead of redirecting back, regardless of the client's Accept header.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Invalid request parameters.',
            'errors' => $validator->errors(),
        ], 422));
    }

    public function rules(): array
    {
        return [
            'category' => ['nullable', 'string', Rule::in(config('catalog.online_categories', ['perfume']))],
            'search' => ['nullable', 'string', 'max:100'],
            'brand' => ['nullable', 'integer', 'exists:brands,id'],
            'store_id' => ['nullable', 'integer', 'exists:stores,id'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'gte:min_price'],
            'sort' => ['nullable', Rule::in(['newest', 'oldest', 'price_asc', 'price_desc'])],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:' . config('catalog.max_per_page', 50)],
        ];
    }
}
