<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    protected $fillable = [
        'booking_id',
        'luggage_type', 'quantity', 'weight', 'distance', 'dimensions', 'description', 'image_path'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
