<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = \Illuminate\Support\Facades\Auth::id();

        $activeCount = \App\Models\Booking::where('user_id', $userId)
            ->where('status', '!=', 'delivered')
            ->count();

        $completedCount = \App\Models\Booking::where('user_id', $userId)
            ->where('status', 'delivered')
            ->count();

        $bookings = \App\Models\Booking::where('user_id', $userId)
            ->with(['location', 'item'])
            ->latest()
            ->get();
        
        $recentBookings = $bookings->take(5);

        return view('dashboard', compact('activeCount', 'completedCount', 'recentBookings', 'bookings'));
    }
}
