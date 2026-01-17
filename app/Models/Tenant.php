<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Tenant extends Authenticatable
{
   use HasApiTokens ;
    
    protected $fillable = ['name','phone','image','password'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // public function apartments()
    // {
    //     return $this->belongsToMany(Apartment::class, 'bookings', 'tenant_id', 'apartment_id');
    // }
}
