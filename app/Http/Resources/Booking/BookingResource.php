<?php

namespace App\Http\Resources\Booking;

use App\Http\Resources\Apartment\ApartmentResource;
use App\Http\Resources\Tenant\TenantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            // Apartment কে শুধুমাত্র ID এবং Name দিয়ে দেখানো হলো
            'apartment' => [
                'id' => $this->apartment->id,
                'name' => $this->apartment->name,
            ],
            // Tenant কে শুধুমাত্র ID এবং Name দিয়ে দেখানো হলো
            'tenant' => [
                'id' => $this->tenant->id,
                'name' => $this->tenant->name,
            ],
        ];
    }
}