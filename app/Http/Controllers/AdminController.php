<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;

use Illuminate\Support\Facades\Mail;
use App\Mail\BookingStatusUpdated;

class AdminController extends Controller
{
    public function index()
    {
        // Stats
        $totalBookings = Booking::count();
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalRevenue = Booking::sum('total_price');
        $pendingBookings = Booking::where('status', 'pending')->count();

        // Data
        // Data
        $bookings = Booking::with(['user', 'location', 'item'])->latest()->get();

        return view('admin.dashboard', compact(
            'totalBookings', 
            'totalUsers', 
            'totalRevenue', 
            'pendingBookings', 
            'bookings'
        ));
    }

    public function users()
    {
        $users = User::where('role', '!=', 'admin')->latest()->get();
        return view('admin.users', compact('users'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,in_transit,delivered,cancelled'
        ]);

        $originalStatus = $booking->status;

        $booking->update(['status' => $request->status]);

        if ($originalStatus !== $booking->status) {
            Mail::to($booking->user->email)->send(new BookingStatusUpdated($booking, $request->status));
        }

        return redirect()->back()->with('success', 'Booking status updated successfully.');
    }
}
