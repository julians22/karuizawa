<?php

namespace App\Http\Requests\OnlineSales;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address.customer_address_id' => 'nullable|exists:customer_addresses,id',
            'address.label' => 'nullable|string|max:100',
            'address.recipient_name' => 'required|string|max:255',
            'address.recipient_phone' => 'required|string|min:10|max:20',
            'address.recipient_email' => 'nullable|email|max:255',
            'address.full_address' => 'required|string',
            'address.note' => 'nullable|string',
            'address.area_id' => 'required|string',
            'address.area_name' => 'nullable|string',
            'address.postal_code' => 'nullable|string|max:10',
            'address.latitude' => 'nullable|numeric',
            'address.longitude' => 'nullable|numeric',
            'address.save_address' => 'sometimes|boolean',

            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|string|exists:products,sku',
            'items.*.qty' => 'required|integer|min:1',

            'courier.company' => 'required|string',
            'courier.type' => 'required|string',
            'courier.name' => 'nullable|string',
            'courier.service_name' => 'nullable|string',
            'courier.shipping_cost' => 'required|numeric|min:0',
            'courier.insurance_cost' => 'sometimes|numeric|min:0',

            'midtrans_order_id' => 'nullable|string|max:100',

            'store_id' => 'nullable|exists:stores,id',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], 422));
    }
}
