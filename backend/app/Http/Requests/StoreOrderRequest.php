<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:3', 'max:50'],
            'last_name' => ['required', 'string', 'min:3', 'max:50'],

            'email' => [
                'required',
                'string',
                'max:150',
                function ($attribute, $value, $fail) {
                    if ($value === 'admin@example.com') {
                        return;
                    }

                    $validator = validator(
                        ['email' => $value],
                        ['email' => ['email:rfc,dns']]
                    );

                    if ($validator->fails()) {
                        $fail('Valós, működő email címet adj meg.');
                    }
                },
            ],

            'phone' => ['required', 'string', 'regex:/^\+36[0-9]{8,9}$/'],

            'delivery_method' => ['required', 'string', 'in:capital_store,home_delivery'],
            'payment_method' => ['required', 'string', 'in:mobile_pay,cash_on_delivery'],

            /*
             * Szállítási cím:
             * csak házhozszállításnál kötelező.
             * fővárosi üzleti átvételnél nem kell.
             */
            'shipping_country' => ['nullable', 'required_if:delivery_method,home_delivery', 'string', 'min:2', 'max:80'],
            'shipping_city' => ['nullable', 'required_if:delivery_method,home_delivery', 'string', 'min:2', 'max:80'],
            'shipping_postal_code' => ['nullable', 'required_if:delivery_method,home_delivery', 'string', 'min:4', 'max:10'],
            'shipping_street_name' => ['nullable', 'required_if:delivery_method,home_delivery', 'string', 'min:3', 'max:120'],
            'shipping_house_number' => ['nullable', 'required_if:delivery_method,home_delivery', 'string', 'min:1', 'max:30'],

            /*
             * Számlázási cím:
             * mindig kötelező.
             */
            'billing_country' => ['required', 'string', 'min:2', 'max:80'],
            'billing_city' => ['required', 'string', 'min:2', 'max:80'],
            'billing_postal_code' => ['required', 'string', 'min:4', 'max:10'],
            'billing_street_name' => ['required', 'string', 'min:3', 'max:120'],
            'billing_house_number' => ['required', 'string', 'min:1', 'max:30'],

            'note' => ['nullable', 'string', 'max:150'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:tuning_products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:5'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'A keresztnév megadása kötelező.',
            'first_name.min' => 'A keresztnév legalább 3 karakter legyen.',
            'first_name.max' => 'A keresztnév legfeljebb 50 karakter lehet.',

            'last_name.required' => 'A vezetéknév megadása kötelező.',
            'last_name.min' => 'A vezetéknév legalább 3 karakter legyen.',
            'last_name.max' => 'A vezetéknév legfeljebb 50 karakter lehet.',

            'email.required' => 'Az email cím megadása kötelező.',
            'email.max' => 'Az email cím legfeljebb 150 karakter lehet.',

            'phone.required' => 'A telefonszám megadása kötelező.',
            'phone.regex' => 'A telefonszámnak +36-tal kell kezdődnie, utána 8 vagy 9 számjeggyel.',

            'delivery_method.required' => 'Az átvételi mód kiválasztása kötelező.',
            'delivery_method.in' => 'Érvénytelen átvételi mód.',

            'payment_method.required' => 'A fizetési mód kiválasztása kötelező.',
            'payment_method.in' => 'Érvénytelen fizetési mód.',

            'shipping_country.required_if' => 'Házhozszállításnál a szállítási ország megadása kötelező.',
            'shipping_city.required_if' => 'Házhozszállításnál a szállítási város megadása kötelező.',
            'shipping_postal_code.required_if' => 'Házhozszállításnál a szállítási irányítószám megadása kötelező.',
            'shipping_street_name.required_if' => 'Házhozszállításnál a szállítási utca név megadása kötelező.',
            'shipping_house_number.required_if' => 'Házhozszállításnál a szállítási házszám megadása kötelező.',

            'shipping_country.min' => 'A szállítási ország legalább 2 karakter legyen.',
            'shipping_city.min' => 'A szállítási város legalább 2 karakter legyen.',
            'shipping_postal_code.min' => 'A szállítási irányítószám legalább 4 karakter legyen.',
            'shipping_street_name.min' => 'A szállítási utca név legalább 3 karakter legyen.',
            'shipping_house_number.min' => 'A szállítási házszám legalább 1 karakter legyen.',

            'billing_country.required' => 'A számlázási ország megadása kötelező.',
            'billing_country.min' => 'A számlázási ország legalább 2 karakter legyen.',
            'billing_country.max' => 'A számlázási ország legfeljebb 80 karakter lehet.',

            'billing_city.required' => 'A számlázási város megadása kötelező.',
            'billing_city.min' => 'A számlázási város legalább 2 karakter legyen.',
            'billing_city.max' => 'A számlázási város legfeljebb 80 karakter lehet.',

            'billing_postal_code.required' => 'A számlázási irányítószám megadása kötelező.',
            'billing_postal_code.min' => 'A számlázási irányítószám legalább 4 karakter legyen.',
            'billing_postal_code.max' => 'A számlázási irányítószám legfeljebb 10 karakter lehet.',

            'billing_street_name.required' => 'A számlázási utca név megadása kötelező.',
            'billing_street_name.min' => 'A számlázási utca név legalább 3 karakter legyen.',
            'billing_street_name.max' => 'A számlázási utca név legfeljebb 120 karakter lehet.',

            'billing_house_number.required' => 'A számlázási házszám megadása kötelező.',
            'billing_house_number.min' => 'A számlázási házszám legalább 1 karakter legyen.',
            'billing_house_number.max' => 'A számlázási házszám legfeljebb 30 karakter lehet.',

            'note.max' => 'A megjegyzés legfeljebb 150 karakter lehet.',

            'items.required' => 'A rendeléshez legalább egy termék szükséges.',
            'items.array' => 'A rendelési tételek formátuma hibás.',
            'items.min' => 'A rendeléshez legalább egy termék szükséges.',

            'items.*.id.required' => 'Hiányzó termékazonosító.',
            'items.*.id.integer' => 'A termékazonosító formátuma hibás.',
            'items.*.id.exists' => 'A kiválasztott termék nem található.',

            'items.*.quantity.required' => 'A mennyiség megadása kötelező.',
            'items.*.quantity.integer' => 'A mennyiségnek egész számnak kell lennie.',
            'items.*.quantity.min' => 'A mennyiség legalább 1 legyen.',
            'items.*.quantity.max' => 'Egy termékből legfeljebb 5 darab rendelhető.',
        ];
    }
}