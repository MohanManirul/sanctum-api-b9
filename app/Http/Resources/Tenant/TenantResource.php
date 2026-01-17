<?php

namespace App\Http\Resources\Tenant;

use App\Http\Resources\Apartment\ApartmentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'phone'=>$this->phone,
            'image'=>$this->image ? asset('storage/'.$this->image) : null,
            // 'apartments' => ApartmentResource::collection($this->apartments) 
        ];
    }
}
