<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
                'email'    => 'required|email',
                'password' => 'required|string|min:8',
        ];
    }

    public function messages():array
    {
        return [
            'email.required'    => 'ইমেইল ফিল্ড দেওয়া আবশ্যক।',
            'email.email'       => 'সঠিক ইমেইল ঠিকানা দিন।',
            'password.required' => 'পাসওয়ার্ড দেওয়া আবশ্যক।',
            'password.min'      => 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষরের হতে হবে।',
        ];
    }

}
