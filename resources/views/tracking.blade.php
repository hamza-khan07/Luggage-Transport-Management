@extends('layouts.app')

@section('title', 'Track Luggage')

@push('styles')
<style>
    .tracking-section { padding: 100px 0 40px; min-height: 100vh; }
    .tracking-header { text-align: center; margin-bottom: 3rem; }
    .tracking-header h1 { font-size: clamp(2rem, 4vw, 3rem); margin-bottom: 0.5rem; }
    .tracking-search { max-width: 600px; margin: 0 auto 3rem; padding: 2rem; display: flex; gap: 1rem; }
    .tracking-input { flex: 1; padding: 1rem 1.5rem; background: rgba(15, 20, 41, 0.6); border: 2px solid rgba(255, 255, 255, 0.1); border-radius: var(--radius-md); color: var(--text-primary); font-family: 'Orbitron', sans-serif; font-size: 1.125rem; transition: var(--transition-normal); }
    .tracking-input:focus { outline: none; border-color: var(--neon-cyan); box-shadow: var(--glow-cyan); }
    .tracking-results { max-width: 1000px; margin: 0 auto; }
    .shipment-info-card { margin-bottom: 2rem; padding: 2rem; }
    .shipment-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
    .shipment-header h2 { font-family: 'Orbitron', sans-serif; font-size: 1.75rem; margin-bottom: 0.5rem; }
    .shipment-route { color: var(--text-muted); font-size: 1.125rem; }
    .badge-lg { padding: 0.75rem 1.5rem; font-size: 1rem; }
    .shipment-details-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; }
    .detail-item { text-align: center; }
    .detail-label { font-size: 0.875rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; }
    .detail-value { font-size: 1.25rem; font-weight: 600; color: var(--neon-cyan); }
    .tracking-timeline { margin-bottom: 2rem; padding: 2rem; }
    .tracking-timeline h3 { margin-bottom: 2rem; color: var(--neon-cyan); }
    .timeline-track { position: relative; }
    .timeline-track::before { content: ''; position: absolute; left: 15px; top: 0; bottom: 0; width: 3px; background: linear-gradient(180deg, var(--neon-teal), var(--neon-cyan), rgba(255, 255, 255, 0.1)); }
    .track-item { position: relative; padding-left: 60px; margin-bottom: 2rem; }
    .track-dot { position: absolute; left: 0; top: 0; width: 30px; height: 30px; border-radius: 50%; background: rgba(255, 255, 255, 0.1); border: 3px solid rgba(255, 255, 255, 0.2); z-index: 1; }
    .track-item.completed .track-dot { background: var(--neon-teal); border-color: var(--neon-teal); box-shadow: 0 0 15px var(--neon-teal); }
    .track-item.active .track-dot { background: var(--neon-cyan); border-color: var(--neon-cyan); box-shadow: 0 0 20px var(--neon-cyan); }
    .track-item.active .track-dot.pulsing { animation: pulse 2s ease-in-out infinite; }
    .track-status { font-weight: 600; font-size: 1.125rem; margin-bottom: 0.25rem; }
    .track-item.completed .track-status { color: var(--neon-teal); }
    .track-item.active .track-status { color: var(--neon-cyan); }
    .track-location { color: var(--text-primary); font-weight: 500; }
    .track-time { color: var(--text-muted); font-size: 0.875rem; margin-top: 0.25rem; }
</style>
@endpush

@section('content')
<section class="tracking-section">
    <div class="container">
        <div class="tracking-header" data-animate="slide-down">
            <h1>Track Your <span class="gradient-text">Shipment</span></h1>
            <p>Enter your tracking number to see real-time updates</p>
        </div>

        <!-- Search Box -->
        <div class="tracking-search glass-card" data-animate="slide-up">
            <form action="{{ route('tracking.search') }}" method="POST" style="display: flex; gap: 1rem; width: 100%;">
                @csrf
                <input type="text" name="tracking_number" class="tracking-input" placeholder="Enter tracking number (e.g., TRK-12345)" value="{{ $trackingNumber ?? '' }}" required>
                <button type="submit" class="btn btn-primary">Track Shipment</button>
            </form>
        </div>

        <!-- Tracking Results -->
        @if(isset($booking))
            <div id="trackingResults" class="tracking-results">
                <!-- Shipment Info Card -->
                <div class="shipment-info-card glass-card" data-animate="slide-up">
                    <div class="shipment-header">
                        <div>
                            <h2>Shipment {{ $booking->tracking_number }}</h2>
                            <p class="shipment-route">{{ $booking->location->pickup_city }} → {{ $booking->location->delivery_city }}</p>
                        </div>
                        <span class="badge badge-info badge-lg" style="text-transform: capitalize;">{{ $booking->status }}</span>
                    </div>

                    <div class="shipment-details-grid">
                        <div class="detail-item">
                            <div class="detail-label">Expected Delivery</div>
                            <div class="detail-value">{{ \Carbon\Carbon::parse($booking->delivery_date)->format('M d, Y') }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Pickup Location</div>
                            <div class="detail-value">{{ $booking->location->pickup_city }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Weight</div>
                            <div class="detail-value">{{ $booking->item->weight }} kg</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Status</div>
                            <div class="detail-value" style="text-transform: capitalize;">{{ $booking->status }}</div>
                        </div>
                    </div>
                </div>

                <!-- Timeline (Simplified based on status) -->
                <div class="tracking-timeline glass-card" data-animate="slide-up" data-delay="100">
                    <h3>Shipment History</h3>
                    <div class="timeline-track">
                        <!-- Order Placed (Always completed) -->
                        <div class="track-item completed">
                            <div class="track-dot"></div>
                            <div class="track-content">
                                <div class="track-status">✓ Order Placed</div>
                                <div class="track-location">{{ $booking->location->pickup_city }}</div>
                                <div class="track-time">{{ $booking->created_at->format('M d, Y - h:i A') }}</div>
                            </div>
                        </div>

                        @if($booking->status == 'pending')
                            <div class="track-item active">
                                <div class="track-dot pulsing"></div>
                                <div class="track-content">
                                    <div class="track-status">● Processing</div>
                                    <div class="track-location">Warehouse</div>
                                    <div class="track-time">Currently being processed</div>
                                </div>
                            </div>
                        @elseif($booking->status == 'in_transit')
                             <div class="track-item completed">
                                <div class="track-dot"></div>
                                <div class="track-content">
                                    <div class="track-status">✓ Processed</div>
                                    <div class="track-location">Warehouse</div>
                                </div>
                            </div>
                            <div class="track-item active">
                                <div class="track-dot pulsing"></div>
                                <div class="track-content">
                                    <div class="track-status">● In Transit</div>
                                    <div class="track-location">En Route to {{ $booking->location->delivery_city }}</div>
                                </div>
                            </div>
                        @elseif($booking->status == 'delivered')
                            <div class="track-item completed">
                                <div class="track-dot"></div>
                                <div class="track-content">
                                    <div class="track-status">✓ In Transit</div>
                                </div>
                            </div>
                            <div class="track-item completed">
                                <div class="track-dot"></div>
                                <div class="track-content">
                                    <div class="track-status">✓ Delivered</div>
                                    <div class="track-location">{{ $booking->location->delivery_city }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @elseif(isset($trackingNumber))
            <div class="text-center" style="padding: 3rem;">
                <h3 style="color: #ff4d4d; margin-bottom: 1rem;">Tracking Number Not Found</h3>
                <p style="color: var(--text-muted);">We couldn't find any shipment with tracking number: <strong style="color: var(--text-primary);">{{ $trackingNumber }}</strong></p>
                <p style="margin-top: 1rem;">Please check the number and try again.</p>
            </div>
        @endif
    </div>
</section>
@endsection
