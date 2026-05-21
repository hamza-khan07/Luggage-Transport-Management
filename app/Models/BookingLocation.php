<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingLocation extends Model
{
    protected $fillable = [
        'booking_id',
        'pickup_name', 'pickup_phone', 'pickup_address', 'pickup_city', 'pickup_province', 'pickup_zip',
        'delivery_name', 'delivery_phone', 'delivery_address', 'delivery_city', 'delivery_province', 'delivery_zip'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
