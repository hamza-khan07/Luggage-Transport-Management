@extends('layouts.app')

@section('title', 'Contact Us')

@push('styles')
<style>
    .contact-section {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 100px 20px 40px;
        position: relative;
        overflow: hidden;
    }

    .contact-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background:
            radial-gradient(circle at 20% 30%, rgba(0, 212, 255, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(176, 38, 255, 0.15) 0%, transparent 50%);
        z-index: -1;
    }

    .contact-container {
        max-width: 600px;
        width: 100%;
        position: relative;
    }

    .contact-card {
        padding: 3rem;
        margin-bottom: 2rem;
    }

    .contact-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .contact-header h2 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, var(--neon-cyan), var(--neon-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .contact-header p {
        color: var(--text-muted);
        font-size: 1rem;
    }

    .contact-form {
        margin-top: 2rem;
    }

    .form-group textarea.form-input {
        resize: vertical;
        min-height: 120px;
        font-family: inherit;
    }

    .invalid-feedback {
        color: #ff3b5c;
        font-size: 0.85rem;
        margin-top: 0.25rem;
        display: block;
    }
</style>
@endpush

@section('content')
<section class="contact-section">
    <div class="contact-background"></div>

    <div class="contact-container">
        <div class="contact-card glass-card" data-animate="slide-up">
            <div class="contact-header">
                <h2>Contact Us</h2>
                <p>Have a question or need assistance? Reach out to us!</p>
            </div>

            <form method="POST" action="{{ route('contact.submit') }}" class="contact-form">
                @csrf
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" id="name" class="form-input" placeholder="Your Name" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-input" placeholder="you@example.com" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="subject" class="form-label">Subject (Optional)</label>
                    <input type="text" name="subject" id="subject" class="form-input" placeholder="How can we help you?" value="{{ old('subject') }}">
                    @error('subject')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="message" class="form-label">Message</label>
                    <textarea name="message" id="message" class="form-input" placeholder="Write your message here..." required>{{ old('message') }}</textarea>
                    @error('message')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</section>
@endsection