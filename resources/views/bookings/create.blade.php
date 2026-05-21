@extends('layouts.app')

@section('title', 'Book Transport')

@push('styles')
<style>
    .booking-section {
        padding: 100px 0 40px;
        min-height: 100vh;
    }

    .booking-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .booking-header h1 {
        font-size: clamp(2rem, 4vw, 3rem);
        margin-bottom: 0.5rem;
    }

    .booking-header p {
        color: var(--text-muted);
    }

    .progress-steps {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 3rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .step-number {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Orbitron', sans-serif;
        font-weight: 700;
        color: var(--text-muted);
        transition: var(--transition-normal);
    }

    .step.active .step-number {
        background: linear-gradient(135deg, var(--neon-cyan), var(--neon-blue));
        border-color: var(--neon-cyan);
        color: var(--dark-bg);
        box-shadow: var(--glow-cyan);
    }

    .step.completed .step-number {
        background: rgba(0, 255, 170, 0.2);
        border-color: var(--neon-teal);
        color: var(--neon-teal);
    }

    .step-label {
        font-size: 0.875rem;
        color: var(--text-muted);
        text-align: center;
    }

    .step.active .step-label {
        color: var(--neon-cyan);
        font-weight: 600;
    }

    .step-line {
        width: 80px;
        height: 2px;
        background: rgba(255, 255, 255, 0.1);
    }

    .booking-form-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 3rem;
    }

    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
        animation: fadeIn 0.3s ease-out;
    }

    .step-title {
        font-size: 1.75rem;
        margin-bottom: 2rem;
        color: var(--neon-cyan);
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        justify-content: flex-end;
    }

    .summary-card {
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .summary-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .summary-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .summary-section h4 {
        color: var(--neon-cyan);
        margin-bottom: 1rem;
        font-size: 1.125rem;
    }

    .summary-content p {
        margin-bottom: 0.5rem;
        color: var(--text-secondary);
    }

    .price-breakdown {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .price-item {
        display: flex;
        justify-content: space-between;
        color: var(--text-secondary);
    }

    .price-item.total {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--neon-cyan);
        padding-top: 1rem;
        border-top: 2px solid var(--neon-cyan);
    }

    @media (max-width: 768px) {
        .booking-form-container {
            padding: 2rem 1.5rem;
        }

        .progress-steps {
            gap: 0.5rem;
        }
        
        .step-line { width: 40px; }
        .step-label { font-size: 0.75rem; }
        
        .form-actions {
            flex-direction: column;
        }
    }

    /* Validation Styles */
    .required-asterisk {
        color: #ef4444;
        margin-left: 4px;
    }
    
    .input-error {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1) !important;
    }
    
    .error-message {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 0.25rem;
        display: block;
        animation: slideDown 0.2s ease-out;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<section class="booking-section">
    <div class="container">
        <div class="booking-header" data-animate="slide-down">
            <h1>Book <span class="gradient-text">Luggage Transport</span></h1>
            <p>Complete the form below to schedule your shipment</p>
        </div>

        <!-- Progress Steps -->
        <div class="progress-steps" data-animate="fade-in">
            <div class="step active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-label">Pickup Details</div>
            </div>
            <div class="step-line"></div>
            <div class="step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-label">Delivery Details</div>
            </div>
            <div class="step-line"></div>
            <div class="step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-label">Luggage Info</div>
            </div>
            <div class="step-line"></div>
            <div class="step" data-step="4">
                <div class="step-number">4</div>
                <div class="step-label">Confirmation</div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="booking-form-container glass-card" data-animate="slide-up">
            <form id="bookingForm" method="POST" action="{{ route('bookings.store') }}" enctype="multipart/form-data">
                @csrf
                <!-- Step 1: Pickup Details -->
                <div class="form-step active" data-step="1">
                    <h3 class="step-title">Pickup Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="pickupName" class="form-label">Full Name <span class="required-asterisk">*</span></label>
                            <input type="text" name="pickupName" id="pickupName" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="pickupPhone" class="form-label">Phone Number <span class="required-asterisk">*</span></label>
                            <input type="tel" name="pickupPhone" id="pickupPhone" class="form-input" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pickupAddress" class="form-label">Pickup Address <span class="required-asterisk">*</span></label>
                        <input type="text" name="pickupAddress" id="pickupAddress" class="form-input" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="pickupCity" class="form-label">City <span class="required-asterisk">*</span></label>
                            <input type="text" name="pickupCity" id="pickupCity" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="pickupProvince" class="form-label">Province <span class="required-asterisk">*</span></label>
                            <input type="text" name="pickupProvince" id="pickupProvince" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="pickupZip" class="form-label">ZIP Code <span class="required-asterisk">*</span></label>
                            <input type="text" name="pickupZip" id="pickupZip" class="form-input" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="pickupDate" class="form-label">Pickup Date <span class="required-asterisk">*</span></label>
                            <input type="date" name="pickupDate" id="pickupDate" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="pickupTime" class="form-label">Pickup Time <span class="required-asterisk">*</span></label>
                            <input type="time" name="pickupTime" id="pickupTime" class="form-input" required>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-primary" onclick="nextStep()">Next Step →</button>
                    </div>
                </div>

                <!-- Step 2: Delivery Details -->
                <div class="form-step" data-step="2">
                    <h3 class="step-title">Delivery Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="deliveryName" class="form-label">Recipient Name <span class="required-asterisk">*</span></label>
                            <input type="text" name="deliveryName" id="deliveryName" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="deliveryPhone" class="form-label">Phone Number <span class="required-asterisk">*</span></label>
                            <input type="tel" name="deliveryPhone" id="deliveryPhone" class="form-input" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="deliveryAddress" class="form-label">Delivery Address <span class="required-asterisk">*</span></label>
                        <input type="text" name="deliveryAddress" id="deliveryAddress" class="form-input" required>
                    </div>
                    <div class="form-row">
                         <div class="form-group">
                            <label for="deliveryCity" class="form-label">City <span class="required-asterisk">*</span></label>
                            <input type="text" name="deliveryCity" id="deliveryCity" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="deliveryProvince" class="form-label">Province <span class="required-asterisk">*</span></label>
                            <input type="text" name="deliveryProvince" id="deliveryProvince" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="deliveryZip" class="form-label">ZIP Code <span class="required-asterisk">*</span></label>
                            <input type="text" name="deliveryZip" id="deliveryZip" class="form-input" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="distance" class="form-label">Distance (km) <span class="required-asterisk">*</span></label>
                        <input type="number" name="distance" id="distance" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="deliveryDate" class="form-label">Expected Delivery Date <span class="required-asterisk">*</span></label>
                        <input type="date" name="deliveryDate" id="deliveryDate" class="form-input" required>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-outline" onclick="prevStep()">← Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep()">Next Step →</button>
                    </div>
                </div>

                <!-- Step 3: Luggage Info -->
                <div class="form-step" data-step="3">
                    <h3 class="step-title">Luggage Details</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="luggageType" class="form-label">Luggage Type <span class="required-asterisk">*</span></label>
                            <select name="luggageType" id="luggageType" class="form-select" required>
                                <option value="">Select type</option>
                                <option value="Box">Box</option>
                                <option value="Luggage/Personal Effects">Luggage/Personal Effects</option>
                                <option value="Electronics">Electronics</option>
                                <option value="Furniture">Furniture</option>
                                <option value="Fragile">Fragile</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quantity" class="form-label">Quantity <span class="required-asterisk">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-input" min="1" value="1" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="weight" class="form-label">Total Weight (kg) <span class="required-asterisk">*</span></label>
                            <input type="number" name="weight" id="weight" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="dimensions" class="form-label">Dimensions (L×W×H inches)</label>
                            <input type="text" name="dimensions" id="dimensions" class="form-input">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="luggageImage" class="form-label">Luggage Image <span class="required-asterisk">*</span></label>
                        <input type="file" name="luggageImage" id="luggageImage" class="form-input" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label for="specialInstructions" class="form-label">Special Instructions</label>
                        <textarea name="specialInstructions" id="specialInstructions" class="form-textarea" rows="4"></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-outline" onclick="prevStep()">← Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep()">Review Order →</button>
                    </div>
                </div>

                <!-- Step 4: Confirmation -->
                <div class="form-step" data-step="4">
                    <h3 class="step-title">Order Summary</h3>
                    <div class="summary-card glass">
                        <div class="summary-section">
                            <h4>Pickup Details</h4>
                            <div id="pickupSummary" class="summary-content"></div>
                        </div>
                        <div class="summary-section">
                             <h4>Delivery Details</h4>
                            <div id="deliverySummary" class="summary-content"></div>
                        </div>
                        <div class="summary-section">
                            <h4>Luggage Information</h4>
                            <div id="luggageSummary" class="summary-content"></div>
                        </div>
                        <div class="summary-section price-section">
                            <h4>Estimated Cost</h4>
                            <div class="price-breakdown">
                                <div class="price-item"><span>Base Rate:</span><span>$50.00</span></div>
                                <div class="price-item"><span>Distance Fee:</span><span>$75.00</span></div>
                                <div class="price-item total"><span>Total:</span><span id="totalPrice">$125.00</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-outline" onclick="prevStep()">← Previous</button>
                        <button type="submit" class="btn btn-primary">Confirm Booking</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 4;

    function updateStepIndicators() {
        document.querySelectorAll('.step').forEach(step => {
            const stepNum = parseInt(step.dataset.step);
            if (stepNum < currentStep) {
                step.classList.add('completed', 'active'); // Keep active for style
                // Actually style says active OR completed.
                step.classList.remove('active'); // Remove active, add completed
                step.classList.add('completed');
            } else if (stepNum === currentStep) {
                step.classList.add('active');
                step.classList.remove('completed');
            } else {
                step.classList.remove('active', 'completed');
            }
        });
    }

    function showStep(step) {
        document.querySelectorAll('.form-step').forEach(formStep => {
            formStep.classList.remove('active');
        });
        document.querySelector(`.form-step[data-step="${step}"]`).classList.add('active');
        
        // Update indicators logic simplified
        document.querySelectorAll('.step').forEach(s => {
            let n = parseInt(s.dataset.step);
            s.classList.remove('active', 'completed');
            if(n < step) s.classList.add('completed');
            if(n === step) s.classList.add('active');
        });

        if (step === 4) {
            updateSummary();
        }
    }

    function nextStep() {
        if (currentStep < totalSteps) {
            if (validateStep(currentStep)) {
                currentStep++;
                showStep(currentStep);
            }
        }
    }

    function validateStep(step) {
        const currentStepEl = document.querySelector(`.form-step[data-step="${step}"]`);
        // Select all required inputs within the current step
        const requiredInputs = currentStepEl.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;

        requiredInputs.forEach(input => {
            const formGroup = input.closest('.form-group');
            // Reset previous error state (optional, but good practice to clear before checking again if button clicked multiple times)
            
            // Check if input is empty
            if (!input.value.trim()) {
                isValid = false;
                
                // Add error styling if not already present
                if (!input.classList.contains('input-error')) {
                     input.classList.add('input-error');
                     const errorMessage = document.createElement('span');
                     errorMessage.className = 'error-message';
                     errorMessage.textContent = 'This field is required';
                     formGroup.appendChild(errorMessage);
                }
            } else {
                // If valid now (e.g. user fixed it but clicked next again without triggering input?), clear it
                input.classList.remove('input-error');
                 const existingError = formGroup.querySelector('.error-message');
                if (existingError) existingError.remove();
            }
        });

        return isValid;
    }

    // Real-time error clearing
    document.addEventListener('DOMContentLoaded', function() {
        const allRequiredInputs = document.querySelectorAll('input[required], select[required], textarea[required]');
        
        allRequiredInputs.forEach(input => {
            ['input', 'change'].forEach(eventType => {
                input.addEventListener(eventType, function() {
                    if (this.classList.contains('input-error')) {
                        this.classList.remove('input-error');
                        const formGroup = this.closest('.form-group');
                        const errorMessage = formGroup.querySelector('.error-message');
                        if (errorMessage) {
                            errorMessage.remove();
                        }
                    }
                });
            });
        });
    });

    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    }

    function updateSummary() {
        // Pickup summary
        const pDate = document.getElementById('pickupDate').value;
        const pTime = document.getElementById('pickupTime').value;
        const fullDate = (pDate && pTime) ? new Date(pDate + 'T' + pTime) : null;

        const pickupSummary = `
            <p><strong>${document.getElementById('pickupName').value}</strong></p>
            <p>${document.getElementById('pickupAddress').value}</p>
            <p>${document.getElementById('pickupCity').value}, ${document.getElementById('pickupProvince').value} ${document.getElementById('pickupZip').value}</p>
            <p>Phone: ${document.getElementById('pickupPhone').value}</p>
            <p>Date: ${fullDate ? fullDate.toLocaleString() : 'Not set'}</p>
        `;
        document.getElementById('pickupSummary').innerHTML = pickupSummary;

        // Delivery summary
        const deliverySummary = `
            <p><strong>${document.getElementById('deliveryName').value}</strong></p>
            <p>${document.getElementById('deliveryAddress').value}</p>
            <p>${document.getElementById('deliveryCity').value}, ${document.getElementById('deliveryProvince').value} ${document.getElementById('deliveryZip').value}</p>
            <p>Phone: ${document.getElementById('deliveryPhone').value}</p>
            <p>Expected: ${new Date(document.getElementById('deliveryDate').value).toLocaleDateString()}</p>
        `;
        document.getElementById('deliverySummary').innerHTML = deliverySummary;

        // Luggage summary
        const fileInput = document.getElementById('luggageImage');
        const fileName = fileInput.files[0] ? fileInput.files[0].name : 'No image uploaded';

        const luggageSummary = `
            <p>Type: <strong>${document.getElementById('luggageType').value}</strong></p>
            <p>Quantity: ${document.getElementById('quantity').value} piece(s)</p>
            <p>Weight: ${document.getElementById('weight').value} kg</p>
            <p>Dimensions: ${document.getElementById('dimensions').value || 'Not specified'}</p>
            <p>Image: ${fileName}</p>
            ${document.getElementById('specialInstructions').value ? `<p>Notes: ${document.getElementById('specialInstructions').value}</p>` : ''}
        `;
        document.getElementById('luggageSummary').innerHTML = luggageSummary;

        // Calculate price
        const weight = parseFloat(document.getElementById('weight').value) || 0;
        const distance = parseFloat(document.getElementById('distance').value) || 0;
        
        const baseRate = 500;
        const ratePerKg = 50; // PKR
        const ratePerKm = 2; // PKR

        const weightCost = weight * ratePerKg;
        const distanceCost = distance * ratePerKm;
        const total = baseRate + weightCost + distanceCost;

        const formulaHtml = `
            <div class="price-item"><span>Base Fee:</span><span>PKR ${baseRate.toLocaleString()}</span></div>
            <div class="price-item"><span>Weight Fee (${weight}kg × PKR ${ratePerKg}):</span><span>PKR ${weightCost.toLocaleString()}</span></div>
            <div class="price-item"><span>Distance Fee (${distance}km × PKR ${ratePerKm}):</span><span>PKR ${distanceCost.toLocaleString()}</span></div>
            <div class="price-item total" style="display:block; border-top: 2px solid var(--neon-cyan); padding-top: 1rem; margin-top: 0.5rem;">
                <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem; font-family: monospace;">
                    Total Cost = ${baseRate} + (${weight} × ${ratePerKg}) + (${distance} × ${ratePerKm})
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span>Total:</span>
                    <span>PKR ${total.toLocaleString()}</span>
                </div>
            </div>
        `;
        
        document.querySelector('.price-breakdown').innerHTML = formulaHtml;
    }
</script>
@endpush
