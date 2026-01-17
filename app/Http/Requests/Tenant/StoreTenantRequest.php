<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTenantRequest extends FormRequest
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
            'name'=>'required|string',
            'phone'=>'required|unique:tenants,phone',
            'password'  => 'required|string|min:8|confirmed',
            'image'=>'nullable|image'
        ];
    }

    public function messages():array
    {
        return [
            'name.required' => 'Name field দেওয়া আবশ্যক।',
            'name.max'      => 'Name সর্বোচ্চ 100 অক্ষর হতে পারে।',
            'phone.numeric'  => 'phone অবশ্যই একটি সংখ্যা হতে হবে।',
            'phone.min'      => 'phone সর্বনিম্ন 1 হতে হবে।',
            'phone.max'      => 'phone সর্বাধিক 11 হতে পারে।',
            'password.min'      => 'password সর্বাধিক 8 হতে পারে।',
            'image.mimes'   => 'Image অবশ্যই jpg, jpeg, png, gif বা webp ফাইল হতে হবে।',
            'image.max'     => 'Image সর্বাধিক 2MB হতে পারে।',
        ];
    }

    /**
     * Handle a failed validation attempt.
     * 3️⃣ কখন ব্যবহার হয়?
        *যখন আপনি API validation করেন
        *Redirect বা HTML response চাই না, শুধু JSON response দরকার
        *যদি validation fail হয় → এই method call হয়
        *আমরা HttpResponseException throw করি, যার মধ্যে JSON response থাকে
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422)
        );
    }

}
