@extends('layouts.app')

@section('title', 'Login')

@push('styles')
<style>
    /* Auth Page Styles (from login.html) */
    .auth-section {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 100px 20px 40px;
        position: relative;
        overflow: hidden;
    }

    .auth-background {
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

    .auth-container {
        max-width: 500px;
        width: 100%;
        position: relative;
    }

    .auth-card {
        padding: 3rem;
        margin-bottom: 2rem;
    }

    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .auth-header h2 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, var(--neon-cyan), var(--neon-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .auth-header p {
        color: var(--text-muted);
        font-size: 1rem;
    }

    .auth-form {
        margin-top: 2rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
        cursor: pointer;
        font-size: 0.95rem;
    }

    .checkbox-label input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--neon-cyan);
    }

    .forgot-link {
        color: var(--neon-cyan);
        font-size: 0.95rem;
    }

    .auth-divider {
        text-align: center;
        margin: 1.5rem 0;
        position: relative;
    }

    .auth-divider::before,
    .auth-divider::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 40%;
        height: 1px;
        background: rgba(255, 255, 255, 0.1);
    }

    .auth-divider::before { left: 0; }
    .auth-divider::after { right: 0; }

    .auth-divider span {
        color: var(--text-muted);
        background: var(--dark-bg);
        padding: 0 1rem;
        position: relative;
    }

    .auth-footer {
        text-align: center;
        margin-top: 2rem;
        color: var(--text-muted);
    }

    .auth-footer a {
        color: var(--neon-cyan);
        font-weight: 600;
        cursor: pointer;
    }

    .password-strength {
        margin-top: 0.5rem;
        height: 4px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        overflow: hidden;
    }

    .strength-bar {
        height: 100%;
        width: 0;
        transition: all 0.3s ease;
        border-radius: 2px;
    }

    .auth-decoration {
        position: absolute;
        top: 50%;
        right: -100px;
        transform: translateY(-50%);
        width: 200px;
        height: 200px;
        opacity: 0.3;
        pointer-events: none;
    }
    
    .decoration-svg {
        width: 100%;
        height: 100%;
        filter: blur(2px);
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
<section class="auth-section">
    <div class="auth-background">
        <div class="auth-particles"></div>
    </div>

    <div class="auth-container">
        <!-- Login Form -->
        <div class="auth-card glass-card" id="loginCard" data-animate="slide-up" style="{{ $mode == 'login' ? 'display:block' : 'display:none' }}">
            <div class="auth-header">
                <h2>Welcome Back</h2>
                <p>Login to access your dashboard</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="auth-form">
                @csrf
                <div class="form-group">
                    <label for="loginEmail" class="form-label">Email Address</label>
                    <input type="email" name="email" id="loginEmail" class="form-input" placeholder="you@example.com" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="loginPassword" class="form-label">Password</label>
                    <input type="password" name="password" id="loginPassword" class="form-input" placeholder="••••••••" required>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Login
                </button>

                <div class="auth-divider">
                    <span>or</span>
                </div>

                <button type="button" class="btn btn-outline w-100" onclick="showRegister()">
                    Create New Account
                </button>
            </form>

            <div class="auth-footer">
                <p>Don't have an account? <a onclick="showRegister()">Sign up</a></p>
            </div>
        </div>

        <!-- Register Form -->
        <div class="auth-card glass-card" id="registerCard" data-animate="slide-up" style="{{ $mode == 'register' ? 'display:block' : 'display:none' }}">
            <div class="auth-header">
                <h2>Create Account</h2>
                <p>Join LuggageGo today</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="auth-form">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" name="first_name" id="firstName" class="form-input" placeholder="John" value="{{ old('first_name') }}" required>
                        @error('first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" name="last_name" id="lastName" class="form-input" placeholder="Doe" value="{{ old('last_name') }}" required>
                        @error('last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="registerEmail" class="form-label">Email Address</label>
                    <input type="email" name="register_email" id="registerEmail" class="form-input" placeholder="you@example.com" value="{{ old('register_email') }}" required>
                    @error('register_email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" name="phone" id="phone" class="form-input" placeholder="+1 (555) 000-0000" value="{{ old('phone') }}" required>
                    @error('phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="registerPassword" class="form-label">Password</label>
                    <input type="password" name="register_password" id="registerPassword" class="form-input" placeholder="••••••••" required>
                    <div class="password-strength">
                        <div class="strength-bar"></div>
                    </div>
                    @error('register_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" name="register_password_confirmation" id="confirmPassword" class="form-input" placeholder="••••••••" required>
                </div>

                <label class="checkbox-label">
                    <input type="checkbox" required>
                    <span>I agree to the <a href="#">Terms of Service</a></span>
                </label>

                <button type="submit" class="btn btn-primary w-100 mt-3">
                    Create Account
                </button>

                <div class="auth-divider">
                    <span>or</span>
                </div>

                <button type="button" class="btn btn-outline w-100" onclick="showLogin()">
                    Back to Login
                </button>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a onclick="showLogin()">Sign in</a></p>
            </div>
        </div>

        <!-- Decorative Elements -->
        <div class="auth-decoration">
            <svg viewBox="0 0 200 200" class="decoration-svg">
                <defs>
                    <linearGradient id="decorGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:var(--neon-cyan);stop-opacity:0.6" />
                        <stop offset="100%" style="stop-color:var(--neon-purple);stop-opacity:0.6" />
                    </linearGradient>
                </defs>
                <circle cx="100" cy="100" r="80" fill="none" stroke="url(#decorGrad)" stroke-width="2" opacity="0.5">
                    <animate attributeName="r" from="70" to="90" dur="3s" repeatCount="indefinite" />
                    <animate attributeName="opacity" from="0.3" to="0.7" dur="3s" repeatCount="indefinite" />
                </circle>
            </svg>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function showRegister() {
        document.getElementById('loginCard').style.display = 'none';
        document.getElementById('registerCard').style.display = 'block';
    }

    function showLogin() {
        document.getElementById('registerCard').style.display = 'none';
        document.getElementById('loginCard').style.display = 'block';
    }

    // Password strength indicator
    const registerPassword = document.getElementById('registerPassword');
    if (registerPassword) {
        registerPassword.addEventListener('input', function () {
            const strength = calculatePasswordStrength(this.value);
            const strengthBar = document.querySelector('.strength-bar');

            strengthBar.style.width = strength + '%';

            if (strength < 40) {
                strengthBar.style.background = '#ff3b5c';
            } else if (strength < 70) {
                strengthBar.style.background = '#ffc107';
            } else {
                strengthBar.style.background = 'var(--neon-teal)';
            }
        });
    }

    function calculatePasswordStrength(password) 
    {
        let strength = 0;
        if (password.length >= 8) strength += 25;
        if (password.length >= 12) strength += 25;
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
        if (/[0-9]/.test(password)) strength += 15;
        if (/[^a-zA-Z0-9]/.test(password)) strength += 10;
        return Math.min(strength, 100);
    }
</script>
@endpush
