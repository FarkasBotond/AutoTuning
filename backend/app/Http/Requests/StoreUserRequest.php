<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:4', 'max:255'],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:users,email',
                'not_regex:/@(example\.com|example\.hu|test\.com)$/i',
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->letters()->numbers()->symbols(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'A név megadása kötelező.',
            'name.min' => 'A név legalább 4 karakter hosszú legyen.',
            'email.required' => 'Az email cím megadása kötelező.',
            'email.email' => 'Valós, működő email címet adj meg.',
            'email.unique' => 'Ezzel az email címmel már létezik fiók.',
            'email.not_regex' => 'Példa vagy teszt email címmel nem lehet regisztrálni.',
            'password.required' => 'A jelszó megadása kötelező.',
            'password.confirmed' => 'A két jelszó nem egyezik.',
        ];
    }
}
