<?php

use App\Http\Controllers\API\V1\AdminDashboardController;
use App\Http\Controllers\API\V1\ApartmentController;
use App\Http\Controllers\API\V1\BookingController;
use App\Http\Controllers\API\V1\DashboardController;
use App\Http\Controllers\API\V1\TenantController;
use App\Models\Apartment;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    
    Route::get('/apartment-data', function () {
    $apartments = Apartment::get();
        return response()->json([
                'data' => $apartments
            ]);
    });

    Route::post('/register',[TenantController::class,'register']);

 
    Route::post('/tenant/login',[TenantController::class,'login']);
    
    Route::post('/admin/login',[AdminDashboardController::class,'adminLogin'])->name('login') ;
 
    Route::middleware('auth:sanctum')->group(function(){

        Route::post('logout',[TenantController::class,'logout']) ;
        Route::post('bookings', [BookingController::class, 'store']);
       Route::get('/bookings',[TenantController::class,'bookings'])->name('bookings') ;
       Route::get('/notifications',[DashboardController::class,'dashboardNotifications'])->name('dashboardNotifications') ;
    });
   
    Route::prefix('admin/dashboard')->middleware('auth:sanctum')->group(function(){        
        
        Route::post('logout',[AdminDashboardController::class,'logout']) ;
        Route::get('/data',[AdminDashboardController::class,'data'])->name('data') ;

         
        Route::apiResource('tenants',TenantController::class);
        
        Route::apiResource('bookings',BookingController::class);
        Route::get('dashboard/summary',[DashboardController::class,'summary']);

       Route::resource('apartments',ApartmentController::class);
    });

    
});



 