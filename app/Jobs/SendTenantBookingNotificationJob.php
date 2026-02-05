<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Notifications\BookingCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendTenantBookingNotificationJob implements ShouldQueue
{
    use Queueable;
    public $booking;
    public $tenant;
    /**
     * Create a new job instance.
     */
    public function __construct(Booking $booking, $tenant)
    {
        $this->booking = $booking;
        $this->tenant  = $tenant;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         $this->tenant->notify(new BookingCreatedNotification($this->booking));
    }
}
