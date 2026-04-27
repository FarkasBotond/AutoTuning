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
            'full_name' => [
                'required',
                'string',
                'min:7',
                'max:100',
                function ($attribute, $value, $fail) {
                    $parts = preg_split('/\s+/', trim((string) $value));

                    if (count($parts) < 2) {
                        $fail('A teljes névnek vezetéknévből és keresztnévből kell állnia.');
                        return;
                    }

                    foreach ($parts as $part) {
                        if (mb_strlen($part) < 3) {
                            $fail('A vezetéknév és a keresztnév is legalább 3 karakter legyen.');
                            return;
                        }
                    }
                },
            ],
            'email' => ['required', 'email:rfc,dns', 'max:150'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^\+36[\s-]?\d{1,2}[\s-]?\d{3}[\s-]?\d{3,4}$/'],

            'delivery_method' => ['required', Rule::in(['capital_store', 'home_delivery'])],
            'country' => ['required', 'string', 'min:3', 'max:80'],

            'city' => ['required_if:delivery_method,home_delivery', 'nullable', 'string', 'min:2', 'max:80'],
            'postal_code' => ['required_if:delivery_method,home_delivery', 'nullable', 'string', 'min:3', 'max:20'],
            'street_name' => ['required_if:delivery_method,home_delivery', 'nullable', 'string', 'min:3', 'max:120'],
            'house_number' => ['required_if:delivery_method,home_delivery', 'nullable', 'string', 'min:1', 'max:30'],

            'note' => ['nullable', 'string', 'max:150'],

            'payment_method' => ['required', Rule::in(['mobile_pay', 'cash_on_delivery'])],

            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:tuning_products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'Valós, működő email címet adj meg.',
            'phone.regex' => 'A telefonszám +36-tal kezdődjön, például: +36 70 123 4567.',
            'note.max' => 'A megjegyzés legfeljebb 150 karakter lehet.',
        ];
    }
}
