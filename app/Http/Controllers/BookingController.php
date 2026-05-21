<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['location', 'item'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        return view('bookings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pickupName' => 'required|string|max:255',
            'pickupPhone' => 'required|string|max:20',
            'pickupAddress' => 'required|string|max:255',
            'pickupCity' => 'required|string|max:100',
            'pickupProvince' => 'required|string|max:100',
            'pickupZip' => 'required|string|max:20',
            'pickupDate' => 'required|date',
            'pickupTime' => 'required',
            
            'deliveryName' => 'required|string|max:255',
            'deliveryPhone' => 'required|string|max:20',
            'deliveryAddress' => 'required|string|max:255',
            'deliveryCity' => 'required|string|max:100',
            'deliveryProvince' => 'required|string|max:100',
            'deliveryZip' => 'required|string|max:20',
            'deliveryDate' => 'required|date',
            
            'luggageType' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'weight' => 'required|numeric|min:0.1',
            'distance' => 'required|numeric|min:0.1',
            'dimensions' => 'nullable|string',
            'luggageImage' => 'nullable|image|max:5120',
            'specialInstructions' => 'nullable|string',
        ]);

        $baseRate = 500;
        $ratePerKg = 50;
        $ratePerKm = 2;
        $weightCost = $request->weight * $ratePerKg;
        $distanceCost = $request->distance * $ratePerKm;
        $totalPrice = $baseRate + $weightCost + $distanceCost;

        $imagePath = null;
        if ($request->hasFile('luggageImage')) {
            if (!$request->file('luggageImage')->isValid()) {
                return back()->withInput()->with('error', 'Image upload failed. Please try a smaller file.');
            }
            $imagePath = $request->file('luggageImage')->store('luggage_images', 'public');
        }

        $trackingNumber = 'TRK-' . strtoupper(Str::random(10));

        DB::transaction(function () use ($request, $totalPrice, $trackingNumber, $imagePath) {
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'tracking_number' => $trackingNumber,
                'pickup_date' => $request->pickupDate,
                'pickup_time' => $request->pickupTime,
                'delivery_date' => $request->deliveryDate,
                'total_price' => $totalPrice,
                'status' => 'pending'
            ]);

            $booking->location()->create([
                'pickup_name' => $request->pickupName,
                'pickup_phone' => $request->pickupPhone,
                'pickup_address' => $request->pickupAddress,
                'pickup_city' => $request->pickupCity,
                'pickup_province' => $request->pickupProvince,
                'pickup_zip' => $request->pickupZip,
                'delivery_name' => $request->deliveryName,
                'delivery_phone' => $request->deliveryPhone,
                'delivery_address' => $request->deliveryAddress,
                'delivery_city' => $request->deliveryCity,
                'delivery_province' => $request->deliveryProvince,
                'delivery_zip' => $request->deliveryZip,
            ]);

            $booking->item()->create([
                'luggage_type' => $request->luggageType,
                'quantity' => $request->quantity,
                'weight' => $request->weight,
                'distance' => $request->distance,
                'dimensions' => $request->dimensions,
                'description' => $request->specialInstructions,
                'image_path' => $imagePath,
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Booking created successfully! Tracking Number: ' . $trackingNumber);
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        $booking->load(['location', 'item']);
        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        $booking->load(['location', 'item']);
        return view('bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'pickupName' => 'required|string|max:255',
            'pickupPhone' => 'required|string|max:20',
            'pickupAddress' => 'required|string|max:255',
            'pickupCity' => 'required|string|max:100',
            'pickupProvince' => 'required|string|max:100',
            'pickupZip' => 'required|string|max:20',
            'pickupDate' => 'required|date',
            'pickupTime' => 'required',
            
            'deliveryName' => 'required|string|max:255',
            'deliveryPhone' => 'required|string|max:20',
            'deliveryAddress' => 'required|string|max:255',
            'deliveryCity' => 'required|string|max:100',
            'deliveryProvince' => 'required|string|max:100',
            'deliveryZip' => 'required|string|max:20',
            'deliveryDate' => 'required|date',
            
            'luggageType' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'weight' => 'required|numeric|min:0.1',
            'distance' => 'required|numeric|min:0.1',
            'dimensions' => 'nullable|string',
            'luggageImage' => 'nullable|image|max:5120',
            'specialInstructions' => 'nullable|string',
            'status' => 'nullable|string|in:pending,approved,in_transit,delivered,cancelled'
        ]);

        $baseRate = 500;
        $ratePerKg = 50;
        $ratePerKm = 2;
        $weightCost = $request->weight * $ratePerKg;
        $distanceCost = $request->distance * $ratePerKm;
        $totalPrice = $baseRate + $weightCost + $distanceCost;

        $imagePath = $booking->item->image_path;
        
        if ($request->input('delete_image') == '1') {
            $imagePath = null;
        }
        if ($request->hasFile('luggageImage')) {
            if (!$request->file('luggageImage')->isValid()) {
                return back()->withInput()->with('error', 'Image upload failed. Please try a smaller file.');
            }
            $imagePath = $request->file('luggageImage')->store('luggage_images', 'public');
        }

        DB::transaction(function () use ($request, $booking, $totalPrice, $imagePath) {
            $bookingData = [
                'pickup_date' => $request->pickupDate,
                'pickup_time' => $request->pickupTime,
                'delivery_date' => $request->deliveryDate,
                'total_price' => $totalPrice,
            ];

            // Only admin can update status
            if (Auth::user()->isAdmin() && $request->has('status')) {
                $bookingData['status'] = $request->status;
            }

            $booking->update($bookingData);

            $booking->location()->update([
                'pickup_name' => $request->pickupName,
                'pickup_phone' => $request->pickupPhone,
                'pickup_address' => $request->pickupAddress,
                'pickup_city' => $request->pickupCity,
                'pickup_province' => $request->pickupProvince,
                'pickup_zip' => $request->pickupZip,
                'delivery_name' => $request->deliveryName,
                'delivery_phone' => $request->deliveryPhone,
                'delivery_address' => $request->deliveryAddress,
                'delivery_city' => $request->deliveryCity,
                'delivery_province' => $request->deliveryProvince,
                'delivery_zip' => $request->deliveryZip,
            ]);

            $booking->item()->update([
                'luggage_type' => $request->luggageType,
                'quantity' => $request->quantity,
                'weight' => $request->weight,
                'distance' => $request->distance,
                'dimensions' => $request->dimensions,
                'description' => $request->specialInstructions,
                'image_path' => $imagePath,
            ]);
        });

        // Redirect based on role
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin')->with('success', 'Booking updated successfully!');
        }
        
        return redirect()->route('dashboard')->with('success', 'Booking updated successfully!');
    }

    public function destroy(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        $booking->delete();

        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin')->with('deleted', 'Booking deleted successfully!');
        }

        return redirect()->route('bookings.index')->with('deleted', 'Booking deleted successfully!');
    }
}
