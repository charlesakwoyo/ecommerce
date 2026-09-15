<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'address_id' => ['required_without:address', 'nullable', 'integer', 'exists:addresses,id'],
            'address' => ['required_without:address_id', 'nullable', 'array'],
            'address.name' => ['required_with:address', 'string', 'max:255'],
            'address.line1' => ['required_with:address', 'string', 'max:255'],
            'address.line2' => ['nullable', 'string', 'max:255'],
            'address.city' => ['required_with:address', 'string', 'max:255'],
            'address.state' => ['nullable', 'string', 'max:255'],
            'address.postal_code' => ['required_with:address', 'string', 'max:20'],
            'address.country' => ['required_with:address', 'string', 'size:2'],
            'address.phone' => ['nullable', 'string', 'max:30'],
            'success_url' => ['required', 'url'],
            'cancel_url' => ['required', 'url'],
        ];
    }
}
