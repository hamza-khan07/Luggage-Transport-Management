@extends('layouts.app')

@section('title', 'Booking Details')

@push('styles')
<style>
    .details-section { padding: 100px 0 40px; min-height: 100vh; }
    .details-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
    .details-header h1 { font-size: clamp(1.5rem, 3vw, 2.5rem); margin: 0; }
    
    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .info-card {
        height: 100%;
    }

    .info-group { margin-bottom: 1.5rem; }
    .info-group:last-child { margin-bottom: 0; }
    .info-label { color: var(--text-muted); font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem; }
    .info-value { font-size: 1.125rem; color: var(--text-primary); }

    .luggage-image-container {
        width: 100%;
        max-height: 400px;
        overflow: hidden;
        border-radius: var(--radius-md);
        margin-top: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        position: relative;
    }
    
    .luggage-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .status-badge {
        font-size: 1rem;
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
    }

    .action-bar {
        margin-top: 2rem;
        padding: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }
</style>
@endpush

@section('content')
<div class="details-section">
    <div class="container">
        <!-- Header -->
        <div class="details-header" data-animate="slide-down">
            <div>
                 <a href="{{ Auth::user()->isAdmin() ? route('admin') : route('dashboard') }}" class="btn btn-outline btn-sm mb-3">← Back to Dashboard</a>
                <h1>Booking <span class="gradient-text">{{ $booking->tracking_number }}</span></h1>
            </div>
            <div class="badge badge-info status-badge" style="text-transform: capitalize;">{{ $booking->status }}</div>
        </div>

        <div class="details-grid">
            <!-- Location Details -->
            <div class="glass-card info-card" data-animate="slide-up">
                <h3 class="text-neon mb-4">Route Information</h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-group">
                            <div class="info-label">Pickup From</div>
                            <div class="info-value">{{ $booking->location->pickup_name }}</div>
                            <div class="text-muted">{{ $booking->location->pickup_address }}</div>
                            <div class="text-muted">{{ $booking->location->pickup_city }}, {{ $booking->location->pickup_province }}</div>
                            <div class="text-muted mt-1">📞 {{ $booking->location->pickup_phone }}</div>
                            <div class="text-muted mt-1">📅 {{ \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y') }} at {{ \Carbon\Carbon::parse($booking->pickup_time)->format('h:i A') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-4 mt-md-0">
                         <div class="info-group">
                            <div class="info-label">Deliver To</div>
                            <div class="info-value">{{ $booking->location->delivery_name }}</div>
                            <div class="text-muted">{{ $booking->location->delivery_address }}</div>
                            <div class="text-muted">{{ $booking->location->delivery_city }}, {{ $booking->location->delivery_province }}</div>
                            <div class="text-muted mt-1">📞 {{ $booking->location->delivery_phone }}</div>
                            <div class="text-muted mt-1">📅 Expected: {{ \Carbon\Carbon::parse($booking->delivery_date)->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-700 my-4">

                <div class="info-group">
                    <div class="info-label">Total Distance</div>
                    <div class="info-value">{{ $booking->item->distance }} km</div>
                </div>
            </div>

            <!-- Item Details -->
            <div class="glass-card info-card" data-animate="slide-up" data-delay="100">
                <h3 class="text-neon mb-4">Luggage Details</h3>
                
                <div class="row">
                    <div class="col-6">
                        <div class="info-group">
                            <div class="info-label">Type</div>
                            <div class="info-value" style="text-transform: capitalize;">{{ $booking->item->luggage_type }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-group">
                             <div class="info-label">Quantity</div>
                            <div class="info-value">{{ $booking->item->quantity }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-group">
                            <div class="info-label">Weight</div>
                            <div class="info-value">{{ $booking->item->weight }} kg</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-group">
                            <div class="info-label">Dimensions</div>
                            <div class="info-value">{{ $booking->item->dimensions ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                @if($booking->item->description)
                <div class="info-group mt-3">
                    <div class="info-label">Special Instructions</div>
                    <div class="info-value">{{ $booking->item->description }}</div>
                </div>
                @endif
                
                <div class="info-group mt-3">
                     <div class="info-label">Total Cost</div>
                     <div class="info-value text-neon" style="font-size: 1.5rem;">PKR {{ number_format($booking->total_price) }}</div>
                </div>

                @if($booking->item->image_path)
                <div class="info-group mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="info-label">Luggage Image</div>
                         <a href="{{ asset('storage/' . $booking->item->image_path) }}" download class="btn btn-sm btn-outline-primary">
                            ⬇ Download Image
                        </a>
                    </div>
                    <div class="luggage-image-container">
                        <img src="{{ asset('storage/' . $booking->item->image_path) }}" alt="Luggage Image" class="luggage-image">
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if(Auth::user()->isAdmin())
        <div class="glass-card action-bar" data-animate="fade-in">
             <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-primary">Edit Booking</a>
             
             <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Booking</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
