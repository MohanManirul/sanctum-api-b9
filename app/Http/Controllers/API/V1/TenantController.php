<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreTenantRequest;
use App\Http\Requests\Tenant\TenantLoginRequest;
use App\Http\Resources\Tenant\TenantCollection;
use App\Http\Resources\Tenant\TenantResource;

use Illuminate\Support\Facades\Hash;
use App\Models\Tenant;
use Exception;
use Illuminate\Http\Request;

class TenantController extends Controller
{

public function register(StoreTenantRequest $request)
    {
        $validatedData = $request->validated();
        try{
            $imagePath = null;
            if ($request->hasFile('image')){
                $imagePath = $request->file('image')->store('tenant','public');
            }
            $tenant = Tenant::create([
                'name' => $validatedData['name'],
                'phone' => $validatedData['phone'],
                'password' => bcrypt($request->password),
                'image' => $imagePath
            ]);

            return response()->json([
                'message' => 'tenant created successfully',
                'data' => $tenant
            ], 201);

        }catch(Exception $e){
            return response()->json([
                'message' => 'tenant creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(TenantLoginRequest $request)
    {
        $tenant = Tenant::where('phone', $request->phone)->first();
        if(!$tenant || !Hash::check($request->password, $tenant->password)) {                    
            return response()->json([
                'message' => 'Invalid credentials'
            ],401);
        }
       
        $token = $tenant->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'   => 'Login successful',
            'tenant'    => new TenantResource($tenant),
            'access_token'     => $token 
        ], 200); 
    }

     public function index()
    {
        try{
            $tenants = Tenant::all() ;
            return response()->json([
                    'message' => 'Tenant retrieved successfully',
                    'data'    => new TenantCollection($tenants) 
            ]);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
   
       
    }

    public function store(StoreTenantRequest $request)
    {
        $validatedData = $request->validated();
        try{
            $imagePath = null;
            if ($request->hasFile('image')){
                $imagePath = $request->file('image')->store('tenant','public');
            }
            $tenant = Tenant::create([
                'name' => $validatedData['name'],
                'phone' => $validatedData['phone'],
                'image' => $imagePath
            ]);

            return response()->json([
                'message' => 'tenant created successfully',
                'data' => $tenant
            ], 201);

        }catch(Exception $e){
            return response()->json([
                'message' => 'tenant creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
        {
           $request->user()->currentAccessToken()->delete();
           
           return response()->json([
            'message' => 'Logout Success'
           ]);

        }

}
