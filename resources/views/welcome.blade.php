@extends('layouts.app')

@section('title', 'Welcome')

@push('styles')
<style>
    /* Hero Section Styles */
    .hero {
        min-height: 100vh;
        display: flex;
        align-items: center;
        position: relative;
        padding-top: 80px;
        overflow: hidden;
    }

    .hero-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }

    .hero-gradient {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 30% 50%, rgba(0, 212, 255, 0.15) 0%, transparent 50%),
                    radial-gradient(circle at 70% 80%, rgba(176, 38, 255, 0.15) 0%, transparent 50%);
    }

    .hero .container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }

    .hero-title {
        font-size: clamp(2.5rem, 6vw, 5rem);
        margin-bottom: 1.5rem;
        line-height: 1.1;
    }

    .gradient-text {
        background: linear-gradient(135deg, var(--neon-cyan), var(--neon-blue), var(--neon-purple));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: glow 3s ease-in-out infinite;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        color: var(--text-secondary);
        margin-bottom: 2rem;
        max-width: 500px;
    }

    .hero-buttons {
        display: flex;
        gap: 1rem;
        margin-bottom: 3rem;
        flex-wrap: wrap;
    }

    .btn svg {
        margin-left: 0.5rem;
        stroke: currentColor;
        stroke-width: 2;
        fill: none;
    }

    .hero-stats {
        display: flex;
        gap: 3rem;
        flex-wrap: wrap;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-family: 'Orbitron', sans-serif;
        font-size: 3rem;
        font-weight: 700;
        color: var(--neon-cyan);
        text-shadow: var(--glow-cyan);
    }

    .stat-text {
        font-size: 0.875rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* 3D Luggage Visual */
    .hero-visual {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .luggage-3d {
        position: relative;
        width: 400px;
        height: 500px;
    }

    .luggage-glow {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(0, 212, 255, 0.3) 0%, transparent 70%);
        filter: blur(40px);
        animation: pulse 3s ease-in-out infinite;
    }

    .luggage-svg {
        position: relative;
        z-index: 1;
        filter: drop-shadow(0 0 30px rgba(0, 212, 255, 0.5));
    }

    /* Features Section */
    .features {
        background: linear-gradient(180deg, var(--dark-bg) 0%, var(--dark-bg-secondary) 100%);
    }

    .section-title {
        font-size: clamp(2rem, 4vw, 3rem);
        margin-bottom: 1rem;
    }

    .section-subtitle {
        font-size: 1.125rem;
        color: var(--text-muted);
        margin-bottom: 3rem;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .feature-card {
        text-align: center;
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: rgba(0, 212, 255, 0.1);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--neon-cyan);
    }

    .feature-card:hover .feature-icon {
        background: rgba(0, 212, 255, 0.2);
        box-shadow: var(--glow-cyan);
    }

    .feature-card h3 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    /* How It Works */
    .how-it-works {
        background: var(--dark-bg-secondary);
    }

    .steps-container {
        max-width: 800px;
        margin: 3rem auto 0;
    }

    .step {
        display: flex;
        align-items: center;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .step-number {
        font-family: 'Orbitron', sans-serif;
        font-size: 4rem;
        font-weight: 700;
        color: transparent;
        -webkit-text-stroke: 2px var(--neon-cyan);
        min-width: 100px;
    }

    .step-content h3 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .step-connector {
        width: 2px;
        height: 60px;
        background: linear-gradient(180deg, var(--neon-cyan), transparent);
        margin-left: 50px;
    }
    
    .cta-section {
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(176, 38, 255, 0.1));
    }

    .cta-card {
        text-align: center;
        padding: 4rem 2rem;
    }

    .cta-card h2 {
        font-size: clamp(2rem, 4vw, 3rem);
        margin-bottom: 1rem;
    }

    .cta-card p {
        font-size: 1.25rem;
        margin-bottom: 2rem;
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-lg {
        padding: 1.125rem 2.5rem;
        font-size: 1.125rem;
    }

    @media (max-width: 768px) {
        .hero .container {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .hero-visual {
            order: -1;
        }

        .luggage-3d {
            width: 300px;
            height: 400px;
        }

        .hero-buttons {
            justify-content: center;
        }

        .hero-stats {
            justify-content: center;
        }

        .step {
            flex-direction: column;
            text-align: center;
        }

        .step-number {
            min-width: auto;
        }

        .step-connector {
            margin-left: 0;
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, var(--neon-cyan), transparent);
        }
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-background" data-parallax="0.3">
            <div class="hero-gradient"></div>
            <div class="hero-particles"></div>
        </div>
        
        <div class="container">
            <div class="hero-content" data-animate="slide-up">
                <h1 class="hero-title">
                    The Future of
                    <span class="gradient-text">Luggage Transport</span>
                </h1>
                <p class="hero-subtitle">
                    Experience next-generation logistics with real-time tracking, 
                    AI-powered routing, and seamless delivery management.
                </p>
                <div class="hero-buttons">
                    @auth
                        <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                            <span>Book Transport</span>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 3l7 7-7 7M3 10h14"/>
                            </svg>
                        </a>
                        <a href="{{ route('tracking') }}" class="btn btn-outline">
                            Track Shipment
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            <span>Get Started</span>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 3l7 7-7 7M3 10h14"/>
                            </svg>
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline">
                            Sign In
                        </a>
                    @endauth
                </div>
                
                <!-- Stats -->
                <div class="hero-stats" data-animate="fade-in" data-delay="300">
                    <div class="stat-item">
                        <div class="stat-number" data-counter="50000">0</div>
                        <div class="stat-text">Deliveries</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" data-counter="98">0</div>
                        <div class="stat-text">% Success Rate</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" data-counter="24">0</div>
                        <div class="stat-text">Hour Tracking</div>
                    </div>
                </div>
            </div>
            
            <!-- 3D Luggage Visual -->
            <div class="hero-visual" data-animate="float">
                <div class="luggage-3d">
                    <div class="luggage-glow"></div>
                    <svg viewBox="0 0 400 500" class="luggage-svg">
                        <defs>
                            <linearGradient id="luggageGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#00d4ff;stop-opacity:0.8" />
                                <stop offset="100%" style="stop-color:#b026ff;stop-opacity:0.8" />
                            </linearGradient>
                            <filter id="glow">
                                <feGaussianBlur stdDeviation="10" result="coloredBlur"/>
                                <feMerge>
                                    <feMergeNode in="coloredBlur"/>
                                    <feMergeNode in="SourceGraphic"/>
                                </feMerge>
                            </filter>
                        </defs>
                        <rect x="100" y="150" width="200" height="280" rx="20" fill="url(#luggageGrad)" filter="url(#glow)" opacity="0.9"/>
                        <path d="M 150 150 Q 150 100 200 100 Q 250 100 250 150" stroke="var(--neon-cyan)" stroke-width="8" fill="none" stroke-linecap="round" filter="url(#glow)"/>
                        <circle cx="140" cy="420" r="15" fill="var(--neon-teal)" filter="url(#glow)"/>
                        <circle cx="260" cy="420" r="15" fill="var(--neon-teal)" filter="url(#glow)"/>
                        <line x1="200" y1="180" x2="200" y2="400" stroke="var(--neon-cyan)" stroke-width="3" opacity="0.5"/>
                        <rect x="180" y="280" width="40" height="30" rx="5" fill="var(--neon-blue)" opacity="0.7"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section features">
        <div class="container">
            <h2 class="section-title text-center" data-animate="slide-up">
                Why Choose <span class="gradient-text">LuggageGo</span>
            </h2>
            <div class="features-grid">
                <div class="feature-card glass-card" data-animate="slide-up" data-delay="100">
                    <div class="feature-icon">
                        <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                    </div>
                    <h3>Real-Time Tracking</h3>
                    <p>Monitor your luggage every step of the way with GPS-enabled tracking and live updates.</p>
                </div>
                <!-- More features omitted for brevity but keeping 3 for visual -->
                <div class="feature-card glass-card" data-animate="slide-up" data-delay="200">
                    <div class="feature-icon">
                         <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Our dedicated team is always available to assist you with any questions or concerns.</p>
                </div>
                <div class="feature-card glass-card" data-animate="slide-up" data-delay="300">
                     <div class="feature-icon">
                        <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                    </div>
                    <h3>Lightning Fast</h3>
                    <p>Optimized routes and efficient logistics ensure the fastest delivery times.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section cta-section">
        <div class="container">
            <div class="cta-card glass" data-animate="slide-up">
                <h2>Ready to Experience the Future?</h2>
                <p>Join thousands of satisfied customers who trust LuggageGo.</p>
                <div class="cta-buttons">
                    @auth
                        <a href="{{ route('bookings.create') }}" class="btn btn-primary btn-lg">Book Now</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get Started Now</a>
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-lg">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
@endsection
