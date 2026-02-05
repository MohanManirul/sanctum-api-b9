<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Resources\Booking\BookingCollection;
use App\Http\Resources\Booking\BookingResource;
use App\Models\Booking;
use Exception;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        try{
            $booking = Booking::with(['apartment','tenant'])->get();

        return response()->json([
            'message' => 'Booking retrieved successfully',
            'data' => new BookingCollection($booking),
        ]);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
        

    }
   
 

    public function store(StoreBookingRequest $request)
    {
        try {
            // Start transaction
            DB::beginTransaction();

            // Get authenticated tenant
            $tenantId = auth('sanctum')->id();
            if (!$tenantId) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // Create booking
            $booking = Booking::create([
                'tenant_id'    => $tenantId,
                'apartment_id' => $request->apartment_id,
                'start_date'   => $request->start_date,
                'end_date'     => $request->end_date,
                'status'       => true,
            ]);

            // Update apartment status
            $booking->apartment()->update([
                'status' => 1
            ]);

            
            // Trigger event
            event(new \App\Events\ApartmentBooked($booking));
            // Commit transaction
            DB::commit();

            

            return new BookingResource($booking);

        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();

            // Optional: log error
            \Log::error('Booking failed: '.$e->getMessage());

            return response()->json([
                'message' => 'Booking failed. Please try again.'
            ], 500);
        }
    }
}
