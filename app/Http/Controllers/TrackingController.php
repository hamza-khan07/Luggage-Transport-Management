<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        return view('tracking');
    }

    public function track(Request $request)
    {
        $request->validate([
            'tracking_number' => 'nullable|string'
        ]);

        $trackingNumber = $request->input('tracking_number');
        $booking = null;

        if ($trackingNumber) {
            $booking = \App\Models\Booking::with(['location', 'item'])->where('tracking_number', $trackingNumber)->first();
        }

        return view('tracking', compact('booking', 'trackingNumber'));
    }
}
