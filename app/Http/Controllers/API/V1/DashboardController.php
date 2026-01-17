<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Apartment\ApartmentResource;
use App\Models\Apartment;
use App\Models\Tenant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
     public function summary()
    {
        return response()->json([
            'total_apartments'=>Apartment::count(),
            'total_tenants'=>Tenant::count(),
            'booked_apartments'=>ApartmentResource::collection(
                Apartment::whereHas('currentBooking')->get()
            ),
            'vacant_apartments'=>ApartmentResource::collection(
                Apartment::whereDoesntHave('currentBooking')->get()
            ),
        ]);
    }
}

/*
অর্থাৎ, whereHas('currentBooking') → শুধু সেই Apartmentগুলো নির্বাচন করবে যাদের বর্তমানে Booking আছে।

Lazy load বা eager load নয়, এটি filtering query।
-------------
 Apartment::whereDoesntHave('currentBooking')->get()
Apartment table থেকে

যাদের currentBooking relation নেই (active booking নেই)
*/