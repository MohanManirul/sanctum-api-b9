<?php

namespace App\Http\Requests\Booking;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
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
            'apartment_id'=>'required|exists:apartments,id',
            //'tenant_id'=>'required|exists:tenants,id', //frontend থেকে tenant_id পাঠানো হচ্ছে না → validation fail → reject।
            'start_date'=>'required|date',
            'end_date'=>'required|date|after:start_date'
        ];
    }

     public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $exists = Booking::where('apartment_id',$this->apartment_id)
                ->where(function ($q){
                    $q->whereBetween('start_date',[$this->start_date,$this->end_date])
                      ->orWhereBetween('end_date',[$this->start_date,$this->end_date]);
                })->exists();

            if ($exists) {
                $validator->errors()
                    ->add('apartment_id','Apartment already booked for this period');
            }
        });
    }
}