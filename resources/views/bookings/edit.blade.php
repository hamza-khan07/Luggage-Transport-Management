@extends('layouts.app')

@section('title', 'Edit Booking')

@push('styles')
<style>
    .booking-section { padding: 100px 0 40px; min-height: 100vh; }
    .booking-header { text-align: center; margin-bottom: 3rem; }
    .booking-header h1 { font-size: clamp(2rem, 4vw, 3rem); margin-bottom: 0.5rem; }
    .booking-header p { color: var(--text-muted); }
    .booking-form-container { max-width: 800px; margin: 0 auto; padding: 3rem; }
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; margin-bottom: 0.5rem; color: var(--text-secondary); }
    .form-input, .form-select, .form-textarea {
        width: 100%; padding: 0.75rem 1rem;
        background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: var(--radius-sm); color: var(--text-primary);
    }
    .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
    .form-actions { display: flex; gap: 1rem; margin-top: 2rem; justify-content: flex-end; }

    /* Custom File Input Styles */
    .custom-file-wrapper {
        position: relative;
        width: 100%;
    }
    .img-preview-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: var(--radius-sm);
        padding: 1rem;
        margin-bottom: 1rem;
        display: inline-block;
    }
    .hidden-input {
        position: absolute;
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        z-index: -1;
    }
    .file-input-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px dashed rgba(255, 255, 255, 0.2);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.3s ease;
        color: var(--text-muted);
    }
    .file-input-label:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: var(--primary-color);
        color: var(--text-primary);
    }
    .file-status-text {
        font-size: 0.95rem;
    }
    .btn-browse {
        background: rgba(255, 255, 255, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .file-input-label:hover .btn-browse {
        background: var(--primary-color);
        color: white;
    }
    .btn-close-red {
        position: absolute;
        top: -10px;
        right: -10px;
        background: #ff4d4d;
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        line-height: 24px;
        text-align: center;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        font-size: 18px;
        transition: transform 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-close-red:hover {
        background: #ff3333;
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')
<section class="booking-section">
    <div class="container">
        <div class="booking-header">
            <h1>Edit Booking <span class="gradient-text">{{ $booking->tracking_number }}</span></h1>
        </div>

        <div class="booking-form-container glass-card">
            <form method="POST" action="{{ route('bookings.update', $booking->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Pickup Information -->
                <h3 class="text-neon mb-4">Pickup Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="pickupName" class="form-input" value="{{ old('pickupName', $booking->location->pickup_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="pickupPhone" class="form-input" value="{{ old('pickupPhone', $booking->location->pickup_phone) }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" name="pickupAddress" class="form-input" value="{{ old('pickupAddress', $booking->location->pickup_address) }}" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="pickupCity" class="form-input" value="{{ old('pickupCity', $booking->location->pickup_city) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Province</label>
                        <input type="text" name="pickupProvince" class="form-input" value="{{ old('pickupProvince', $booking->location->pickup_province) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ZIP</label>
                        <input type="text" name="pickupZip" class="form-input" value="{{ old('pickupZip', $booking->location->pickup_zip) }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Date</label>
                        <input type="date" name="pickupDate" class="form-input" value="{{ old('pickupDate', $booking->pickup_date) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time</label>
                        <input type="time" name="pickupTime" class="form-input" value="{{ old('pickupTime', \Carbon\Carbon::parse($booking->pickup_time)->format('H:i')) }}" required>
                    </div>
                </div>

                <hr class="border-gray-700 my-8">

                <!-- Delivery Information -->
                <h3 class="text-neon mb-4">Delivery Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Recipient Name</label>
                        <input type="text" name="deliveryName" class="form-input" value="{{ old('deliveryName', $booking->location->delivery_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="deliveryPhone" class="form-input" value="{{ old('deliveryPhone', $booking->location->delivery_phone) }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" name="deliveryAddress" class="form-input" value="{{ old('deliveryAddress', $booking->location->delivery_address) }}" required>
                </div>
                <div class="form-row">
                     <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="deliveryCity" class="form-input" value="{{ old('deliveryCity', $booking->location->delivery_city) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Province</label>
                        <input type="text" name="deliveryProvince" class="form-input" value="{{ old('deliveryProvince', $booking->location->delivery_province) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ZIP</label>
                        <input type="text" name="deliveryZip" class="form-input" value="{{ old('deliveryZip', $booking->location->delivery_zip) }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Distance (km)</label>
                        <input type="number" name="distance" class="form-input" value="{{ old('distance', $booking->item->distance) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Delivery Date</label>
                        <input type="date" name="deliveryDate" class="form-input" value="{{ old('deliveryDate', $booking->delivery_date) }}" required>
                    </div>
                </div>

                <hr class="border-gray-700 my-8">

                <!-- Luggage Information -->
                <h3 class="text-neon mb-4">Luggage Details</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Type</label>
                        <select name="luggageType" class="form-select" required>
                            <option value="suitcase" {{ $booking->item->luggage_type == 'suitcase' ? 'selected' : '' }}>Suitcase</option>
                            <option value="backpack" {{ $booking->item->luggage_type == 'backpack' ? 'selected' : '' }}>Backpack</option>
                            <option value="box" {{ $booking->item->luggage_type == 'box' ? 'selected' : '' }}>Box</option>
                            <option value="other" {{ $booking->item->luggage_type == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-input" value="{{ old('quantity', $booking->item->quantity) }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Weight (kg)</label>
                        <input type="number" name="weight" class="form-input" value="{{ old('weight', $booking->item->weight) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Dimensions</label>
                        <input type="text" name="dimensions" class="form-input" value="{{ old('dimensions', $booking->item->dimensions) }}">
                    </div>
                </div>
                <div class="form-group">
                <div class="form-group">
                    <label class="form-label">Luggage Image</label>
                    <div class="custom-file-wrapper">
                        <input type="hidden" name="delete_image" id="deleteImageInput" value="0">
                        
                        @if($booking->item->image_path)
                            <div class="img-preview-card" id="currentImagePreview" style="position: relative; padding: 0.5rem; display: inline-block;">
                                <button type="button" class="btn-close-red" onclick="deleteImage()" title="Remove Image">
                                    &times;
                                </button>
                                <img src="{{ asset('storage/' . $booking->item->image_path) }}" alt="Current Luggage Image" style="max-width: 200px; max-height: 200px; object-fit: cover; border-radius: var(--radius-sm); display: block;">
                            </div>
                        @endif

                        <input type="file" name="luggageImage" id="luggageImage" class="hidden-input" accept="image/*">
                        <label for="luggageImage" class="file-input-label">
                            <span class="file-status-text" id="fileStatusText">
                                @if($booking->item->image_path)
                                    Update Image
                                @else
                                    Choose an image...
                                @endif
                            </span>
                            <span class="btn-browse">Browse</span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Special Instructions</label>
                    <textarea name="specialInstructions" class="form-textarea" rows="4">{{ old('specialInstructions', $booking->item->description) }}</textarea>
                </div>

                @if(Auth::user()->isAdmin())
                <hr class="border-gray-700 my-8">
                <!-- Admin Status Update -->
                <h3 class="text-neon mb-4">Update Status (Admin Only)</h3>
                <div class="form-group">
                    <label class="form-label">Booking Status</label>
                    <select name="status" class="form-select">
                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $booking->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="in_transit" {{ $booking->status == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                        <option value="delivered" {{ $booking->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                @endif

                <div class="form-actions">
                    <a href="{{ Auth::user()->isAdmin() ? route('admin') : route('dashboard') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Booking</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function deleteImage() {
        if(confirm('Are you sure you want to remove this image?')) {
            document.getElementById('currentImagePreview').style.display = 'none';
            document.getElementById('deleteImageInput').value = '1';
            
            // Allow selecting a new one immediately or leaving it empty
            const statusText = document.getElementById('fileStatusText');
            statusText.innerHTML = 'Choose an image...';
            statusText.style.color = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('luggageImage');
        const statusText = document.getElementById('fileStatusText');
        const hasCurrentImage = {{ $booking->item->image_path ? 'true' : 'false' }};

        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files.length > 0) {
                const fileName = this.files[0].name;
                statusText.innerHTML = `📄 Selected: <strong>${fileName}</strong>`;
                statusText.style.color = 'var(--neon-teal)'; // Optional: highlight change
                
                // If they select a new file, we shouldn't necessarily delete the old one via the delete flag,
                // but the backend logic prioritizes the new file upload over the old path anyway.
                // However, safe practice: ensure delete flag is 0 if they're engaging with upload, 
                // though technically 'store' logic overwrites.
                // But if they clicked delete first, then uploaded? We probably want the new upload.
                document.getElementById('deleteImageInput').value = '0'; 

            } else {
                // If user cancels selection
                // Check if the delete flag is set. If so, don't show "preserved".
                const isDeleted = document.getElementById('deleteImageInput').value === '1';
                
                if (hasCurrentImage && !isDeleted) {
                    statusText.innerHTML = 'Update Image';
                    statusText.style.color = '';
                } else {
                    statusText.innerHTML = 'Choose an image...';
                    statusText.style.color = '';
                }
            }
        });
    });
</script>
@endpush
