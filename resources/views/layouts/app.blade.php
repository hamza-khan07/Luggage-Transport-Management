<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Futuristic Luggage Transport Management System">
    <title>LuggageGo - @yield('title', 'Next-Gen Transport')</title>
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🧳</text></svg>">
    @stack('styles')
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('home') }}" class="navbar-logo" style="text-decoration:none; color:inherit;">🧳 LuggageGo</a>
            
            <ul class="navbar-menu">
                @if(!Auth::check() || !Auth::user()->isAdmin())
                <li><a href="{{ route('home') }}" class="navbar-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                @endif
                <li><a href="{{ route('about') }}" class="navbar-link {{ request()->routeIs('about') ? 'active' : '' }}">About Us</a></li>
                @auth
                    @if(Auth::user()->isAdmin())
                        <li><a href="{{ route('admin') }}" class="navbar-link {{ request()->routeIs('admin') ? 'active' : '' }}">Admin Dashboard</a></li>
                        <li><a href="{{ route('admin.users') }}" class="navbar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">Manage Users</a></li>
                    @else
                        <li><a href="{{ route('dashboard') }}" class="navbar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
                        <li><a href="{{ route('bookings.create') }}" class="navbar-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}">Book Now</a></li>
                        <li><a href="{{ route('tracking') }}" class="navbar-link {{ request()->routeIs('tracking') ? 'active' : '' }}">Track</a></li>
                    @endif
                    <li>
                        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="navbar-link" style="background:none; border:none; cursor:pointer; font-family:inherit; font-size:inherit; padding:0.5rem 1rem;">Logout</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" class="navbar-link {{ request()->routeIs('login') ? 'active' : '' }}">Login</a></li>
                    <li><a href="{{ route('register') }}" class="navbar-link {{ request()->routeIs('register') ? 'active' : '' }}">Register</a></li>
                @endauth
            </ul>
            
            <div class="navbar-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Toast Notifications -->
    <!-- Toast Notifications -->
    <div id="toast-container" style="position: fixed; top: 100px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; pointer-events: none;">
        @if(session('success'))
            <div class="toast-alert" style="pointer-events: auto; background: rgba(0, 20, 15, 0.95); border: 1px solid var(--neon-teal); color: var(--neon-teal); padding: 1rem 1.5rem; border-radius: var(--radius-md); box-shadow: 0 4px 12px rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: space-between; min-width: 300px; backdrop-filter: blur(10px); animation: slideInRight 0.3s ease-out;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span>✅</span>
                    <span style="font-weight: 500;">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" style="background:none; border:none; color:inherit; cursor:pointer; font-size:1.2rem; margin-left:1rem; opacity:0.7;">&times;</button>
            </div>
        @endif

        @if(session('deleted'))
            <div class="toast-alert" style="pointer-events: auto; background: rgba(30, 0, 0, 0.95); border: 1px solid #ff4d4d; color: #ff4d4d; padding: 1rem 1.5rem; border-radius: var(--radius-md); box-shadow: 0 4px 12px rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: space-between; min-width: 300px; backdrop-filter: blur(10px); animation: slideInRight 0.3s ease-out;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span>🗑️</span>
                    <span style="font-weight: 500;">{{ session('deleted') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" style="background:none; border:none; color:inherit; cursor:pointer; font-size:1.2rem; margin-left:1rem; opacity:0.7;">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="toast-alert" style="pointer-events: auto; background: rgba(30, 0, 0, 0.95); border: 1px solid #ff4d4d; color: #ff4d4d; padding: 1rem 1.5rem; border-radius: var(--radius-md); box-shadow: 0 4px 12px rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: space-between; min-width: 300px; backdrop-filter: blur(10px); animation: slideInRight 0.3s ease-out;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span>⚠️</span>
                    <span style="font-weight: 500;">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" style="background:none; border:none; color:inherit; cursor:pointer; font-size:1.2rem; margin-left:1rem; opacity:0.7;">&times;</button>
            </div>
        @endif
    </div>

    <!-- Add keyframes for animation -->
    <style>
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
    
    <script>
        // Auto-dismiss toasts after 5 seconds
        setTimeout(function() {
            const toasts = document.querySelectorAll('.toast-alert');
            toasts.forEach(t => {
                t.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                t.style.opacity = '0';
                t.style.transform = 'translateX(100%)';
                setTimeout(() => t.remove(), 500);
            });
        }, 5000);
    </script>

    @yield('content')

    <!-- Footer - Only on Home Page -->
    @if(request()->routeIs('home'))
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4 class="footer-logo">🧳 LuggageGo</h4>
                    <p>Next-generation luggage transport management system.</p>
                </div>
                
                <div class="footer-section">
                    <h5>Quick Links</h5>
                    <ul>
                        @auth
                            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ route('bookings.create') }}">Book Transport</a></li>
                            <li><a href="{{ route('tracking') }}">Track Luggage</a></li>
                        @else
                            <li><a href="{{ route('login') }}">How it Works</a></li>
                            <li><a href="{{ route('register') }}">Join Us</a></li>
                        @endauth
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h5>Support</h5>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h5>Connect</h5>
                    <div class="social-links">
                        <a href="#" class="social-link">Twitter</a>
                        <a href="#" class="social-link">LinkedIn</a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} LuggageGo. All rights reserved.</p>
            </div>
        </div>
    </footer>
    @endif

    <!-- Scripts -->
    <script src="{{ asset('js/animations.js') }}"></script>
    @stack('scripts')
</body>
</html>
