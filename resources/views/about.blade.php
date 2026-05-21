@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<section class="section about-page" style="padding: 100px 0;">
    <div class="container">
        <div class="page-heading" data-animate="slide-up">
            <h1>About <span class="gradient-text">LuggageGo</span></h1>
            <p style="max-width: 720px; margin-top: 1rem; color: var(--text-secondary);">
                LuggageGo makes luggage transport smarter, safer, and simpler for travelers, businesses, and logistics teams. Our platform blends intuitive booking with secure tracking so every delivery arrives with confidence.
            </p>
        </div>

        <div class="content-grid" style="margin-top: 3rem; gap: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
            <div class="feature-card glass-card" data-animate="slide-up" data-delay="100">
                <h2>Our Mission</h2>
                <p>To deliver seamless luggage logistics with real-time visibility, trusted handling, and exceptional customer service.</p>
            </div>

            <div class="feature-card glass-card" data-animate="slide-up" data-delay="200">
                <h2>Our Vision</h2>
                <p>To be the most reliable luggage transport solution, helping people move with confidence wherever their journey takes them.</p>
            </div>

            <div class="feature-card glass-card" data-animate="slide-up" data-delay="300">
                <h2>Our Values</h2>
                <ul style="list-style: disc inside; color: var(--text-secondary);">
                    <li>Dependability in every delivery</li>
                    <li>Transparent tracking and communication</li>
                    <li>Customer-first responsiveness</li>
                    <li>Secure and responsible handling</li>
                </ul>
            </div>
        </div>

        <div class="about-story" data-animate="fade-in" data-delay="300" style="margin-top: 3rem;">
            <h2>Why Choose LuggageGo?</h2>
            <p>We combine modern logistics technology with a human-centered service approach. From online booking to live status updates, we help you keep every shipment on track and under control.</p>
        </div>
    </div>
</section>
@endsection
