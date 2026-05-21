@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    /* DataTables Overrides for Admin */
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
    
    /* Status Colors */
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .status-pending { background: rgba(250, 204, 21, 0.15) !important; color: #FACC15 !important; border: 1px solid rgba(250, 204, 21, 0.3) !important; }
    .status-approved { background: rgba(59, 130, 246, 0.15) !important; color: #3B82F6 !important; border: 1px solid rgba(59, 130, 246, 0.3) !important; }
    .status-in_transit { background: rgba(139, 92, 246, 0.15) !important; color: #8B5CF6 !important; border: 1px solid rgba(139, 92, 246, 0.3) !important; }
    .status-delivered { background: rgba(34, 197, 94, 0.15) !important; color: #22C55E !important; border: 1px solid rgba(34, 197, 94, 0.3) !important; }
    .status-cancelled { background: rgba(239, 68, 68, 0.15) !important; color: #EF4444 !important; border: 1px solid rgba(239, 68, 68, 0.3) !important; }

    /* Text Utilities */
    .text-neon {
        color: var(--neon-teal) !important;
        text-shadow: 0 0 10px rgba(0, 255, 170, 0.3);
    }
    
    /* Table Header Styling */
    #adminBookingsTable thead tr {
        background: rgba(0, 212, 255, 0.1);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    #adminBookingsTable th {
        font-weight: 600;
        color: var(--neon-cyan) !important;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.05em;
        padding: 1rem;
        border-bottom: none !important;
    }
    #adminBookingsTable td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    /* Filter Bar Styling */
    .filter-bar {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: var(--radius-sm);
        padding: 0.5rem;
        margin-bottom: 1.5rem;
        width: 100%;
    }
    .filter-select {
        width: 100%;
        background: transparent;
        border: none;
        color: #ffffff;
        padding: 0.5rem 1rem;
        font-size: 0.95rem;
        cursor: pointer;
    }
    .filter-select:focus {
        outline: none;
    }
    .filter-select option {
        background: #0f172a;
        color: #ffffff;
    }
    
    /* Fix for "Block" look - ensure spacing */
    .dataTables_wrapper .dataTables_length, 
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container section">
    <div class="flex flex-between mb-3 fade-in">
        <div>
            <h1>Admin Dashboard</h1>
            <p>Overview of system performance and booking management</p>
        </div>
        <div class="text-right">
            <span class="badge badge-info">{{ now()->format('l, F j, Y') }}</span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid mb-5">
        <div class="stat-card slide-up" style="animation-delay: 0.1s;">
            <div class="stat-icon">📦</div>
            <div class="stat-value">{{ number_format($totalBookings) }}</div>
            <div class="stat-label">Total Bookings</div>
        </div>
        
        <div class="stat-card slide-up" style="animation-delay: 0.2s;">
            <div class="stat-icon">💰</div>
            <div class="stat-value">PKR {{ number_format($totalRevenue) }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>
        
        <div class="stat-card slide-up" style="animation-delay: 0.3s;">
            <div class="stat-icon">👥</div>
            <div class="stat-value">{{ number_format($totalUsers) }}</div>
            <div class="stat-label">Registered Users</div>
        </div>
        
        <div class="stat-card slide-up" style="animation-delay: 0.4s;">
            <div class="stat-icon">⏳</div>
            <div class="stat-value">{{ number_format($pendingBookings) }}</div>
            <div class="stat-label">Pending Requests</div>
        </div>
    </div>

    <!-- Bookings Management -->
    <div class="card fade-in slide-up" style="animation-delay: 0.5s;">
        <div class="card-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="card-title mb-4">All Bookings</h3>
        </div>
        
        <!-- Filter Bar -->
        <div class="filter-bar">
            <select id="statusFilter" class="filter-select">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="in_transit">In Transit</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        
        <div class="table-container">
            <table class="table" id="adminBookingsTable">
                <thead>
                    <tr>
                        <th>Tracking ID</th>
                        <th>User</th>
                        <th>Route</th>
                        <th>Cost</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>
                                <span style="font-family: 'Space Grotesk'; color: var(--neon-cyan);">
                                    {{ $booking->tracking_number }}
                                </span>
                            </td>
                            <td>
                                {{ $booking->user->name }}<br>
                                <small class="text-muted">{{ $booking->user->email }}</small>
                            </td>
                            <td>
                                <div><small>From:</small> {{ $booking->location->pickup_city }}</div>
                                <div><small>To:</small> {{ $booking->location->delivery_city }}</div>
                            </td>
                            <td class="text-neon" style="font-weight: 600;">PKR {{ number_format($booking->total_price) }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->created_at)->format('M d, Y') }}</td>
                            <td>
                                <!-- Hidden span for filtering -->
                                <span style="display:none;">{{ $booking->status }}</span>
                                <span class="status-badge status-{{ strtolower($booking->status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </td>
                            <td>
                                <div class="flex gap-2 align-items-center">
                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; min-width: auto; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); color: #fff;">Details</a>
                                    
                                    <form action="{{ route('admin.bookings.status', $booking) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" onchange="this.form.submit()" class="form-select status-select" style="padding: 0.25rem 0.5rem; font-size: 0.875rem; width: auto;">
                                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ $booking->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="in_transit" {{ $booking->status == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                            <option value="delivered" {{ $booking->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-5">
                                <p>No bookings found in the system.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#adminBookingsTable').DataTable({
            responsive: true,
            order: [[0, 'desc']], // Sort by ID
            pageLength: 10,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search bookings...",
                lengthMenu: "Show _MENU_"
            }
        });

        $('#statusFilter').on('change', function() {
            var val = $(this).val();
            // Filter on column 5 (Status). 
            // We use a simpler regex to match the exact hidden value or part of the visible text if needed.
            // Since we added a hidden span with raw status (e.g., 'pending', 'in_transit'), we can search for that.
            table.column(5).search(val ? val : '', true, false).draw();
        });
    });
</script>
@endpush
