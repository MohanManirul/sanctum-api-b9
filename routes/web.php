<?php

use App\Http\Controllers\API\V1\AdminApartmentController;
use App\Http\Controllers\API\V1\AdminDashboardController;
use App\Http\Controllers\API\V1\ApartmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {   
    return view('frontend.index');
});


Route::get('/tenant/registration', function () {   
    return view('frontend.register');
});
 

Route::get('/tenant/login', function () {   
    return view('frontend.login');
});
 

Route::get('/tenant/dashboard', function () {   
    return view('frontend.dashboard');
});
 

Route::get('/admin/login', function(){
    return view('backend.admin-login') ;
});

 
Route::get('/admin/dashboard', function(){
    return view('backend.dashboard') ;
});

Route::get('/admin/dashboard/apartment/create', function(){
    return view('backend.create') ;
});

Route::get('/admin/dashboard/apartment/{id}/edit', function(){
    return view('backend.edit') ;
});




