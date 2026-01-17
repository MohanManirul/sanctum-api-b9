<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Models\Apartment;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
     
    public function adminLoginPage()
    {
        return view('backend.admin-login');
    }

    public function adminLogin(AdminLoginRequest $request)
        {
            try{
                $admin = User::where('email', $request->email)->first();
                if(!$admin || !Hash::check($request->password, $admin->password)) {                    
                    return response()->json([
                        'message' => 'Invalid credentials'
                    ],401);
                }
                 
                $token = $admin->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'message'           => 'Login successful',
                    'access_token'      => $token 
                ], 200);
            }catch(Exception $e){
                return response()->json([
                    'message' => 'Something went wrong',
                    'error' => $e->getMessage()
                ], 500);
            }
            
        }
 
    public function data(){
         try{
            $apartments = Apartment::get();

            return response()->json([
                'message' => 'Apartments retrieved successfully',
                'data' => $apartments,
            ]);
        }catch(Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }


    public function logout(Request $request)
        {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message' => 'User logged out successfully'
            ]);
        }
}
