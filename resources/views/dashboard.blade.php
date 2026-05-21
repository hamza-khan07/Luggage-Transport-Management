@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    .dashboard {
        padding: 100px 0 40px;
        min-height: 100vh;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .dashboard-header h1 {
        font-size: clamp(1.75rem, 3vw, 2.5rem);
        margin-bottom: 0.5rem;
    }

    .dashboard-header p {
        color: var(--text-muted);
        margin: 0;
    }

    .stat-change {
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .stat-change.positive {
        color: var(--neon-teal);
    }

    .stat-change.neutral {
        color: var(--text-muted);
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
    }

    .dashboard-card {
        height: fit-content;
    }

    .view-all {
        color: var(--neon-cyan);
        font-size: 0.95rem;
        font-weight: 500;
    }

    .shipments-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .shipment-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.02);
        border-radius: var(--radius-md);
        transition: var(--transition-normal);
    }

    .shipment-item:hover {
        background: rgba(0, 212, 255, 0.05);
        transform: translateX(5px);
    }

    .shipment-icon {
        font-size: 2rem;
    }

    .shipment-info {
        flex: 1;
    }

    .shipment-id {
        font-family: 'Orbitron', sans-serif;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .shipment-route {
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    .timeline {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        position: relative;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(180deg, var(--neon-cyan), transparent);
    }

    .timeline-item {
        display: flex;
        gap: 1rem;
        position: relative;
    }

    .timeline-dot {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        flex-shrink: 0;
        box-shadow: 0 0 15px currentColor;
        z-index: 1;
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-title {
        color: var(--text-primary);
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .timeline-time {
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    .chart-section {
        margin-bottom: var(--spacing-xl);
    }

    .chart-filters {
        display: flex;
        gap: 0.5rem;
    }

    .filter-btn {
        padding: 0.5rem 1rem;
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: var(--radius-sm);
        color: var(--text-secondary);
        cursor: pointer;
        transition: var(--transition-fast);
        font-family: 'Rajdhani', sans-serif;
        font-size: 0.95rem;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: rgba(0, 212, 255, 0.1);
        border-color: var(--neon-cyan);
        color: var(--neon-cyan);
    }

    .chart-placeholder {
        padding: 1rem 0;
    }

    .chart-svg {
        width: 100%;
        height: auto;
    }

    .chart-point {
        cursor: pointer;
        transition: var(--transition-fast);
    }

    .chart-point:hover {
        r: 8;
        filter: drop-shadow(0 0 10px var(--neon-cyan));
    }

    .quick-actions h3 {
        margin-bottom: 1.5rem;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .action-card {
        text-align: center;
        padding: 2rem 1rem;
        text-decoration: none;
        transition: var(--transition-normal);
    }

    .action-card:hover {
        transform: translateY(-8px) scale(1.05);
    }

    .action-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .action-title {
        font-weight: 600;
        color: var(--text-primary);
    }

    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .actions-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .action-buttons .btn {
            width: 100%;
            justify-content: center;
        }
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
    
    /* Minimal Actions */
    .action-link {
        font-size: 0.85rem;
        text-decoration: none;
        padding: 0.25rem 0.5rem;
        transition: all 0.2s;
        background: none;
        border: none;
        cursor: pointer;
        opacity: 0.8;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .action-link:hover { opacity: 1; text-decoration: none; }
    .action-link.edit { color: var(--neon-cyan); }
    .action-link.delete { color: #ff4d4d; }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@section('content')
<main class="dashboard">
    <div class="container">
        <!-- Dashboard Header -->
        <div class="dashboard-header" data-animate="slide-down">
            <div>
                <h1>Welcome Back, <span class="gradient-text">{{ Auth::user()->name }}</span></h1>
                <p>Here's what's happening with your shipments today</p>
            </div>
            <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                + New Shipment
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid" data-animate="slide-up" data-delay="100">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(0, 212, 255, 0.1); color: var(--neon-cyan);">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                </div>
                <div class="stat-value" data-counter="{{ $activeCount }}">{{ $activeCount }}</div>
                <div class="stat-label">Active Shipments</div>
                <div class="stat-change positive">+12% from last month</div>
            </div>
            <!-- Additional stats omitted for brevity, keeping 1 for structure -->
             <div class="stat-card">
                <div class="stat-icon" style="background: rgba(0, 255, 170, 0.1); color: var(--neon-teal);">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <div class="stat-value" data-counter="{{ $completedCount }}">{{ $completedCount }}</div>
                <div class="stat-label">Completed</div>
                <div class="stat-change positive">+8% from last month</div>
            </div>
        </div>

        <!-- Comprehensive Bookings Table -->
        <div class="dashboard-card glass-card" style="margin-bottom: 2rem;" data-animate="slide-up">
             <div class="card-header" style="justify-content: space-between; align-items: center;">
                <h3 class="card-title">All Bookings</h3>
                <div class="table-filters" style="display: flex; gap: 1rem; align-items: center;">
                    <select id="statusFilter" class="form-select" style="background-color: #0f172a; color: #ffffff; border: 1px solid #334155; padding: 0.5rem 1rem; border-radius: 0.375rem; cursor: pointer; min-width: 150px;">
                        <option value="" style="background-color: #0f172a; color: #ffffff;">All Statuses</option>
                        <option value="Pending" style="background-color: #0f172a; color: #ffffff;">Pending</option>
                        <option value="Approved" style="background-color: #0f172a; color: #ffffff;">Approved</option>
                        <option value="In Transit" style="background-color: #0f172a; color: #ffffff;">In Transit</option>
                        <option value="Delivered" style="background-color: #0f172a; color: #ffffff;">Delivered</option>
                        <option value="Cancelled" style="background-color: #0f172a; color: #ffffff;">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="dashboardTable" style="width:100%; color: var(--text-primary);">
                        <thead>
                            <tr>
                                <th style="padding:1rem; text-align:left; color:var(--neon-cyan);">Tracking ID</th>
                                <th style="padding:1rem; text-align:left; color:var(--neon-cyan);">Route</th>
                                <th style="padding:1rem; text-align:left; color:var(--neon-cyan);">Date</th>
                                <th style="padding:1rem; text-align:left; color:var(--neon-cyan);">Cost</th>
                                <th style="padding:1rem; text-align:left; color:var(--neon-cyan);">Status</th>
                                <th style="padding:1rem; text-align:left; color:var(--neon-cyan);">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                                <td style="padding:1rem; font-family: 'Orbitron', sans-serif;">{{ $booking->tracking_number ?? 'N/A' }}</td>
                                <td style="padding:1rem;">
                                    <div style="font-weight: 500;">{{ $booking->location->pickup_city }}</div>
                                    <div style="font-size: 0.85em; color: var(--text-muted);">to</div>
                                    <div style="font-weight: 500;">{{ $booking->location->delivery_city }}</div>
                                </td>
                                <td style="padding:1rem;">{{ \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y') }}</td>
                                <td style="padding:1rem; font-weight:600; color:var(--neon-teal);">PKR {{ number_format($booking->total_price) }}</td>
                                <td style="padding:1rem;">
                                    @php
                                        $statusClass = 'status-' . strtolower($booking->status);
                                        // Fallback if status doesn't match exactly
                                        if(!in_array($statusClass, ['status-pending', 'status-approved', 'status-in_transit', 'status-delivered', 'status-cancelled'])) {
                                            $statusClass = 'status-pending'; 
                                        }
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ ucwords(str_replace('_', ' ', $booking->status)) }}</span>
                                </td>
                                <td style="padding:1rem;">
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-sm btn-outline-primary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Edit</a>
                                        <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking?');" style="margin:0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; border-color: #ff4d4d; color: #ff4d4d;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="dashboard-grid">
            <!-- Recent Shipments Table -->
            <div class="dashboard-card glass-card" data-animate="slide-up" data-delay="200">
                <div class="card-header">
                    <h3 class="card-title">Recent Shipments</h3>
                    <a href="{{ route('bookings.index') }}" class="view-all">View All →</a>
                </div>
                <div class="card-body">
                    <div class="shipments-list">
                        @forelse($recentBookings as $booking)
                        <div class="shipment-item">
                            <div class="shipment-icon">📦</div>
                            <div class="shipment-info">
                                <div class="shipment-id">{{ $booking->tracking_number }}</div>
                                <div class="shipment-route">{{ $booking->location->pickup_city }} → {{ $booking->location->delivery_city }}</div>
                            </div>
                            <div class="shipment-status">
                                @php
                                    $statusClass = 'status-' . strtolower($booking->status);
                                    if(!in_array($statusClass, ['status-pending', 'status-approved', 'status-in_transit', 'status-delivered', 'status-cancelled'])) {
                                        $statusClass = 'status-pending'; 
                                    }
                                @endphp
                                <span class="status-badge {{ $statusClass }}">{{ ucfirst($booking->status) }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted">
                            No recent shipments found.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Activity Timeline -->
            <div class="dashboard-card glass-card" data-animate="slide-up" data-delay="300">
                <div class="card-header">
                     <h3 class="card-title">Recent Activity</h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                         <!-- ... timeline items ... -->
                         <div class="timeline-item">
                            <div class="timeline-dot" style="background: var(--neon-cyan);"></div>
                            <div class="timeline-content">
                                <div class="timeline-title">Shipment #LG-2024-001 picked up</div>
                                <div class="timeline-time">2 hours ago</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions" data-animate="fade-in" data-delay="500">
            <h3>Quick Actions</h3>
            <div class="actions-grid">
                <a href="{{ route('bookings.create') }}" class="action-card glass-card">
                    <div class="action-icon">📦</div>
                    <div class="action-title">Book Transport</div>
                </a>
                <a href="{{ route('tracking') }}" class="action-card glass-card">
                    <div class="action-icon">📍</div>
                    <div class="action-title">Track Shipment</div>
                </a>
                <a href="#" class="action-card glass-card">
                    <div class="action-icon">⚙️</div>
                    <div class="action-title">Settings</div>
                </a>
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
        var table = $('#dashboardTable').DataTable({
            responsive: true,
            order: [[2, 'desc']], // Sort by Date
            pageLength: 5,
            lengthMenu: [5, 10, 25],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search shipments...",
                lengthMenu: "Show _MENU_"
            }
        });

        $('#statusFilter').on('change', function() {
            var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
            );

            // Filter on column 4 (Status), looking for exact match
            table.column(4).search(val ? '^' + val + '$' : '', true, false).draw();
        });
    });
</script>
@endpush
