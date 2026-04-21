<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCarModelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()?->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'brand_id' => 'required|exists:car_brands,id',
            'name' => 'required|string|max:50',
            'gen' => 'required|string|max:50',
            'mod' => 'required|string|max:50',
            'startyear' => 'required|integer|min:1900',
            'endyear' => 'nullable|integer|min:1900'
        ];
    }
}
