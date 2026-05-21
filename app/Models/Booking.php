<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'tracking_number',
        'pickup_date',
        'pickup_time',
        'delivery_date',
        'total_price',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->hasOne(BookingLocation::class);
    }

    public function item()
    {
        return $this->hasOne(BookingItem::class);
    }
}
