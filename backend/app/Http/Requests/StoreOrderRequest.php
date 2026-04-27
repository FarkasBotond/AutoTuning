<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],

            'delivery_method' => ['required', Rule::in(['capital_store', 'home_delivery'])],
            'country' => ['required', 'string', 'max:80'],

            'city' => ['required_if:delivery_method,home_delivery', 'nullable', 'string', 'max:80'],
            'postal_code' => ['required_if:delivery_method,home_delivery', 'nullable', 'string', 'max:20'],
            'street_name' => ['required_if:delivery_method,home_delivery', 'nullable', 'string', 'max:120'],
            'house_number' => ['required_if:delivery_method,home_delivery', 'nullable', 'string', 'max:30'],

            'note' => ['nullable', 'string', 'max:1000'],

            'payment_method' => ['required', Rule::in(['mobile_pay', 'cash_on_delivery'])],

            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'exists:tuning_products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ];
    }
}