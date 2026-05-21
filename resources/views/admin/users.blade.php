@extends('layouts.app')

@section('title', 'Manage Users')

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
    
    table.dataTable tbody td {
        border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="container section">
    <div class="flex flex-between mb-3 fade-in">
        <div>
            <h1>Manage Users</h1>
            <p>List of all registered users in the system</p>
        </div>
        <div>
            <a href="{{ route('admin') }}" class="btn btn-outline">Back to Dashboard</a>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card fade-in slide-up" style="animation-delay: 0.2s;">
        <div class="card-header">
            <h3 class="card-title">Registered Users</h3>
        </div>
        
        <div class="table-container">
            <table class="table" id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Joined Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>#{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? 'N/A' }}</td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-5">
                                <p>No users found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#usersTable').DataTable({
            responsive: true,
            order: [[0, 'asc']], 
            pageLength: 10,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search users...",
                lengthMenu: "Show _MENU_"
            }
        });
    });
</script>
@endpush
