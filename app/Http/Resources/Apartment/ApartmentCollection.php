<?php

namespace App\Http\Resources\Apartment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApartmentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => ApartmentResource::collection($this->collection), // $this->collection হলো মূল Eloquent Collection যা Resource-এ পাস করা হয়েছে।
        ];
    }
}
