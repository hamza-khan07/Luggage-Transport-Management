@extends('layouts.app')

@section('title', 'My Bookings')

@push('styles')
<style>
    .bookings-page {
        padding: 100px 0 40px;
        min-height: 100vh;
    }
                    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 2rem;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .bookings-table {
        width: 100%;
        border-collapse: collapse;
        color: var(--text-primary);
    }

    .bookings-table th,
    .bookings-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .bookings-table th {
        font-weight: 600;
        color: var(--neon-cyan);
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.05em;
    }

    .bookings-table tbody tr:hover {
        background: rgba(0, 212, 255, 0.05);
    }

    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-pending { background: rgba(250, 204, 21, 0.15) !important; color: #FACC15 !important; border: 1px solid rgba(250, 204, 21, 0.3) !important; }
    .status-approved { background: rgba(59, 130, 246, 0.15) !important; color: #3B82F6 !important; border: 1px solid rgba(59, 130, 246, 0.3) !important; }
    .status-in_transit { background: rgba(139, 92, 246, 0.15) !important; color: #8B5CF6 !important; border: 1px solid rgba(139, 92, 246, 0.3) !important; }
    .status-delivered { background: rgba(34, 197, 94, 0.15) !important; color: #22C55E !important; border: 1px solid rgba(34, 197, 94, 0.3) !important; }
    .status-cancelled { background: rgba(239, 68, 68, 0.15) !important; color: #EF4444 !important; border: 1px solid rgba(239, 68, 68, 0.3) !important; }

    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    
    /* DataTables Overrides */
    .dataTables_wrapper {
        padding: 1rem;
        color: #ffffff;
    }
    .dataTables_wrapper label {
        color: #94a3b8 !important;
        font-size: 0.85rem;
    }
    .dataTables_length select,
    .dataTables_filter input {
        background: rgba(15, 23, 42, 0.8) !important;
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
        color: #cbd5e1 !important;
        padding: 0.3rem 0.5rem;
        border-radius: var(--radius-sm);
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }
    .dataTables_length select:focus,
    .dataTables_filter input:focus,
    .dataTables_length select:hover,
    .dataTables_filter input:hover {
        border-color: var(--neon-cyan) !important;
        color: #fff !important;
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.1);
    }
    .dataTables_filter input::placeholder {
        color: #475569;
        opacity: 1;
    }
    .dataTables_info,
    .dataTables_paginate {
        margin-top: 1rem;
        font-size: 0.85rem;
        color: #94a3b8 !important;
    }
    .dataTables_paginate .paginate_button {
        color: #94a3b8 !important;
        padding: 0.3rem 0.8rem !important;
        border-radius: var(--radius-sm) !important;
        background: transparent !important;
        border: 1px solid transparent !important;
    }
    .dataTables_paginate .paginate_button:hover {
        background: rgba(255, 255, 255, 0.1) !important;
        color: #fff !important;
        border-color: rgba(255, 255, 255, 0.2) !important;
    }
    .dataTables_paginate .paginate_button.current {
        background: rgba(0, 212, 255, 0.1) !important;
        color: var(--neon-cyan) !important;
        font-weight: 600;
        border: 1px solid var(--neon-cyan) !important;
    }
    .text-neon {
        color: var(--neon-teal) !important;
        text-shadow: 0 0 10px rgba(0, 255, 170, 0.3);
    }

    .tracking-id-text {
        font-family: 'Space Grotesk', sans-serif !important;
        color: var(--neon-cyan) !important;
        font-weight: 700 !important;
        letter-spacing: 1px;
        text-shadow: 0 0 8px rgba(0, 212, 255, 0.3);
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@section('content')
<main class="bookings-page">
    <div class="container">
        <div class="page-header" data-animate="slide-down">
            <h1>My <span class="gradient-text">Bookings</span></h1>
            <a href="{{ route('bookings.create') }}" class="btn btn-primary">+ New Booking</a>
        </div>

        <div class="glass-card" data-animate="slide-up">
            <div class="table-responsive">
                <table class="table" id="bookingsTable">
                    <thead>
                        <tr>
                            <th>Tracking ID</th>
                            <th>Pickup</th>
                            <th>Delivery</th>
                            <th>Date</th>
                            <th>Weight</th>
                            <th>Cost</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td>
                                <span class="tracking-id-text">
                                    {{ $booking->tracking_number ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $booking->location->pickup_city }}</div>
                            </td>
                            <td>
                                <div>{{ $booking->location->delivery_city }}</div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y') }}</td>
                            <td>{{ $booking->item->weight }} kg</td>
                            <td class="text-neon" style="font-weight: 600;">PKR {{ number_format($booking->total_price) }}</td>
                            <td>
                                <span class="status-badge status-{{ strtolower($booking->status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; align-items: center;">
                                    <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking?');" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                No bookings found. <a href="{{ route('bookings.create') }}" class="text-neon">Create one now!</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#bookingsTable').DataTable({
            responsive: true,
            order: [[3, 'desc']], // Sort by Date (index 3) by default
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search bookings...",
                lengthMenu: "Show _MENU_ entries"
            }
        });
    });
</script>
@endpush
