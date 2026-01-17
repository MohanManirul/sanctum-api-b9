<?php

namespace App\Http\Resources\Apartment;

use App\Http\Resources\Booking\BookingResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
        {
            return [
                'id' => $this->id . 'B9',
                'name' => $this->name,
                'slug' => $this->slug,
                'rent' => $this->rent,

                'image' => $this->image? asset('storage/' . $this->image)
                    : null,

                'status' => $this->currentBooking ? 'Booked' : 'Vacant', // কোথায় আসে $this->currentBooking? আসে Apartment model এর relation থেকে

                'created_at' => $this->created_at?->toDateTimeString(),

                // ✅ single booking → Resource (NOT collection)
                'current_booking' => $this->when(
                    $this->relationLoaded('currentBooking') && $this->currentBooking,
                    fn () => new BookingResource($this->currentBooking)
                ),
            ];
        }

}

/*

    $this->when() কী? -> when() হলো Laravel Resource helper। এটি condition অনুযায়ী array field include বা exclude করে।
    $this->when(condition, value)
    -- যদি condition = true → value API response এ যোগ হবে
    -- যদি condition = false → field API response এ থাকবে না
    -- condition: $this->relationLoaded('currentBooking') && $this->currentBooking
    -- $this->relationLoaded('currentBooking') → চেক করে currentBooking relation preloaded হয়েছে কিনা (যেমন with('currentBooking'))
    -- $this->currentBooking → চেক করে এই Apartment এ আসলে currentBooking আছে কিনা
    -- দুইটি চেক মিললে → true , না হলে → false
    -- অর্থাৎ, আমরা শুধু তখনই current_booking দেখাবো যখন relation লোড করা আছে এবং Booking আছে।
    -- 💡 সংক্ষেপে:

        -- $this->when() + relationLoaded চেক → conditional inclusion of related resource।
        -- এটা Laravel এর lazy loading এবং API response optimization এর জন্য best practice।

*/