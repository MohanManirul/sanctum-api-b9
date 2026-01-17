<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apartment\StoreApartmentRequest;
use App\Http\Resources\Apartment\ApartmentCollection;
use App\Models\Apartment;
use Exception;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{

    public function index(Request $request)
    { 
        try{
            $apartments = Apartment::with('currentBooking.tenant')->get();

            return response()->json([
                'message' => 'Apartments retrieved successfully',
                'data' => new ApartmentCollection($apartments),
            ]);
        }catch(Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
        
    }

    public function create()
        {
            return view('backend.create') ;
        }
 
    public function store(StoreApartmentRequest $request)
    {

        $validatedData = $request->validated();
        try{
            $imagePath = null;
            if ($request->hasFile('image')){
                $imagePath = $request->file('image')->store('apartment','public');
            }
            $apartment = Apartment::create([
                'name' => $validatedData['name'],
                'rent' => $validatedData['rent'],
                'status' => $validatedData['status'],
                'descriptions' => $validatedData['descriptions'],
                'image' => $imagePath
            ]);

            return response()->json([
                'message' => 'Apartment created successfully',
                'data' => $apartment
            ], 201);

        }catch(Exception $e){
            return response()->json([
                'message' => 'Apartment creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
  
    public function edit($id)
    {
        $apartment = Apartment::findOrFail($id);

        // API response
        return response()->json([
            'success' => true,
            'data' => $apartment
        ]);
    }

    public function update(StoreApartmentRequest $request, $id)
    {
        $validatedData = $request->validated();

        try {
            $apartment = Apartment::findOrFail($id);

            // Update basic fields
            $apartment->name = $validatedData['name'];
            $apartment->rent = $validatedData['rent'];
            $apartment->status = $validatedData['status'];
            $apartment->descriptions = $validatedData['descriptions'];

            // Image handling
            if ($request->hasFile('image')) {
                // Old image delete
                if ($apartment->image) {
                    \Storage::disk('public')->delete($apartment->image);
                }

                // Save new image
                $apartment->image = $request->file('image')->store('apartment', 'public');
            }

            $apartment->save();

            return response()->json([
                'message' => 'Apartment updated successfully',
                'data' => $apartment
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Apartment update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $apartment = Apartment::findOrFail($id);

        // Delete image if exists
        if($apartment->image && \Storage::disk('public')->exists($apartment->image)){
            \Storage::disk('public')->delete($apartment->image);
        }

        $apartment->delete();

        return response()->json([
            'message' => 'Apartment deleted successfully'
        ], 200);
    }


}
