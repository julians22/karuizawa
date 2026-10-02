<?php

namespace App\Http\Requests\Api\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'label' => 'nullable|string|max:255',
            'recipient_name' => 'nullable|string|max:255',
            'recipient_phone' => 'nullable|string|min:10|max:20',
            'recipient_email' => 'nullable|email|max:255',
            'full_address' => 'required|string',
            'note' => 'nullable|string',
            'area_id' => 'nullable|string',
            'area_name' => 'nullable|string',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_primary' => 'sometimes|boolean',
        ];
    }
}
