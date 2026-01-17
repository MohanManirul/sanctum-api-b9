<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    protected $fillable = ['name','rent','image','status','descriptions'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function currentBooking()
    {
        return $this->hasOne(Booking::class)
            ->whereDate('start_date','<=',now())
            ->whereDate('end_date','>=',now());
    }

    /* 
    আজকের তারিখে যেই Booking চলছে সেটি"
    currentBooking() relationটা বলে দিচ্ছে: এই Apartment বা Resource এ বর্তমানে কোন Booking চলছে কি
    hasOne(Booking::class) → একটি Apartment-এর সাথে একটার বেশি Booking থাকতে পারে, কিন্তু এখানে শুধু বর্তমান Booking চাই। 
    */

    protected $casts = [
            'rent' => 'decimal:2',
        ];

        /*🔍 এর মানে কী?

        👉 rent কলামটা যখনই model থেকে পড়া হবে,
        Laravel এটাকে —

        decimal number হিসেবে treat করবে

        সবসময় ২ ঘর decimal সহ দেখাবে */
}
