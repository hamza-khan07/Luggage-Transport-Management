/**
 * FUTURISTIC LUGGAGE TRANSPORT MANAGEMENT SYSTEM
 * Animation & Interaction Controller
 */

// ============================================
// PARALLAX BACKGROUND EFFECT
// ============================================
class ParallaxController {
    constructor() {
        this.layers = document.querySelectorAll('[data-parallax]');
        this.init();
    }

    init() {
        if (this.layers.length === 0) return;

        window.addEventListener('scroll', () => this.handleScroll());
        window.addEventListener('mousemove', (e) => this.handleMouseMove(e));
    }

    handleScroll() {
        const scrolled = window.pageYOffset;

        this.layers.forEach(layer => {
            const speed = layer.dataset.parallax || 0.5;
            const yPos = -(scrolled * speed);
            layer.style.transform = `translateY(${yPos}px)`;
        });
    }

    handleMouseMove(e) {
        const { clientX, clientY } = e;
        const centerX = window.innerWidth / 2;
        const centerY = window.innerHeight / 2;

        const moveX = (clientX - centerX) / centerX;
        const moveY = (clientY - centerY) / centerY;

        this.layers.forEach(layer => {
            const depth = layer.dataset.depth || 20;
            const x = moveX * depth;
            const y = moveY * depth;
            layer.style.transform = `translate(${x}px, ${y}px)`;
        });
    }
}

// ============================================
// SCROLL ANIMATIONS
// ============================================
class ScrollAnimations {
    constructor() {
        this.elements = document.querySelectorAll('[data-animate]');
        this.init();
    }

    init() {
        if (!('IntersectionObserver' in window)) {
            // Fallback for older browsers
            this.elements.forEach(el => el.classList.add('fade-in'));
            return;
        }

        const options = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateElement(entry.target);
                    this.observer.unobserve(entry.target);
                }
            });
        }, options);

        this.elements.forEach(el => this.observer.observe(el));
    }

    animateElement(element) {
        const animationType = element.dataset.animate || 'fade-in';
        const delay = element.dataset.delay || 0;

        setTimeout(() => {
            element.classList.add(animationType);
            element.style.opacity = '1';
        }, delay);
    }
}

// ============================================
// COUNTER ANIMATIONS
// ============================================
class CounterAnimation {
    constructor(element, target, duration = 2000) {
        this.element = element;
        this.target = parseInt(target);
        this.duration = duration;
        this.start = 0;
        this.increment = this.target / (duration / 16); // 60fps
    }

    animate() {
        const updateCounter = () => {
            this.start += this.increment;

            if (this.start < this.target) {
                this.element.textContent = Math.floor(this.start).toLocaleString();
                requestAnimationFrame(updateCounter);
            } else {
                this.element.textContent = this.target.toLocaleString();
            }
        };

        updateCounter();
    }

    static initAll() {
        const counters = document.querySelectorAll('[data-counter]');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = entry.target.dataset.counter;
                    const duration = entry.target.dataset.duration || 2000;
                    const counter = new CounterAnimation(entry.target, target, duration);
                    counter.animate();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => observer.observe(counter));
    }
}

// ============================================
// NAVBAR SCROLL EFFECT
// ============================================
class NavbarController {
    constructor() {
        this.navbar = document.querySelector('.navbar');
        this.toggle = document.querySelector('.navbar-toggle');
        this.menu = document.querySelector('.navbar-menu');
        this.init();
    }

    init() {
        if (!this.navbar) return;

        // Scroll effect
        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                this.navbar.classList.add('scrolled');
            } else {
                this.navbar.classList.remove('scrolled');
            }
        });

        // Mobile menu toggle
        if (this.toggle && this.menu) {
            this.toggle.addEventListener('click', () => {
                this.menu.classList.toggle('active');
                this.animateToggle();
            });
        }

        // Close menu on link click
        const links = document.querySelectorAll('.navbar-link');
        links.forEach(link => {
            link.addEventListener('click', () => {
                if (this.menu.classList.contains('active')) {
                    this.menu.classList.remove('active');
                    this.animateToggle();
                }
            });
        });
    }

    animateToggle() {
        const spans = this.toggle.querySelectorAll('span');
        spans.forEach((span, index) => {
            span.style.transform = this.menu.classList.contains('active')
                ? `rotate(${index === 1 ? 45 : -45}deg)`
                : 'rotate(0)';
        });
    }
}

// ============================================
// FORM VALIDATION & EFFECTS
// ============================================
class FormController {
    constructor(formSelector) {
        this.form = document.querySelector(formSelector);
        if (this.form) this.init();
    }

    init() {
        const inputs = this.form.querySelectorAll('.form-input, .form-select, .form-textarea');

        inputs.forEach(input => {
            // Floating label effect
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', () => {
                if (!input.value) {
                    input.parentElement.classList.remove('focused');
                }
            });

            // Real-time validation
            input.addEventListener('input', () => {
                this.validateField(input);
            });
        });

        // Form submission
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            if (this.validateForm()) {
                this.handleSubmit();
            }
        });
    }

    validateField(field) {
        const value = field.value.trim();
        const type = field.type;
        let isValid = true;

        if (field.hasAttribute('required') && !value) {
            isValid = false;
        }

        if (type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            isValid = emailRegex.test(value);
        }

        if (type === 'tel' && value) {
            const phoneRegex = /^[0-9]{10,}$/;
            isValid = phoneRegex.test(value.replace(/\D/g, ''));
        }

        this.updateFieldStatus(field, isValid);
        return isValid;
    }

    updateFieldStatus(field, isValid) {
        if (isValid) {
            field.style.borderColor = 'var(--neon-teal)';
            field.style.boxShadow = '0 0 15px rgba(0, 255, 170, 0.2)';
        } else {
            field.style.borderColor = '#ff3b5c';
            field.style.boxShadow = '0 0 15px rgba(255, 59, 92, 0.2)';
        }
    }

    validateForm() {
        const inputs = this.form.querySelectorAll('.form-input, .form-select, .form-textarea');
        let isValid = true;

        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    handleSubmit() {
        // Show success animation
        this.showToast('Form submitted successfully!', 'success');
    }

    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        toast.style.cssText = `
      position: fixed;
      top: 100px;
      right: 20px;
      padding: 1rem 1.5rem;
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      border: 1px solid var(--neon-cyan);
      border-radius: var(--radius-md);
      color: var(--text-primary);
      box-shadow: var(--glow-cyan);
      z-index: 10000;
      animation: slideLeft 0.3s ease-out;
    `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideRight 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
}

// ============================================
// MODAL CONTROLLER
// ============================================
class ModalController {
    constructor() {
        this.modals = document.querySelectorAll('[data-modal]');
        this.init();
    }

    init() {
        // Open modal triggers
        document.querySelectorAll('[data-modal-open]').forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                const modalId = trigger.dataset.modalOpen;
                this.openModal(modalId);
            });
        });

        // Close modal triggers
        document.querySelectorAll('[data-modal-close]').forEach(trigger => {
            trigger.addEventListener('click', () => {
                this.closeModal(trigger.closest('[data-modal]'));
            });
        });

        // Close on backdrop click
        this.modals.forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.closeModal(modal);
                }
            });
        });

        // Close on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
            }
        });
    }

    openModal(modalId) {
        const modal = document.querySelector(`[data-modal="${modalId}"]`);
        if (modal) {
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('active'), 10);
            document.body.style.overflow = 'hidden';
        }
    }

    closeModal(modal) {
        if (modal) {
            modal.classList.remove('active');
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }, 300);
        }
    }

    closeAllModals() {
        this.modals.forEach(modal => this.closeModal(modal));
    }
}

// ============================================
// LOADING SPINNER
// ============================================
class LoadingSpinner {
    static show() {
        const spinner = document.createElement('div');
        spinner.id = 'loading-spinner';
        spinner.innerHTML = `
      <div class="spinner-container">
        <div class="spinner"></div>
        <p>Loading...</p>
      </div>
    `;
        spinner.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(10, 14, 39, 0.9);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 100000;
    `;

        const style = document.createElement('style');
        style.textContent = `
      .spinner {
        width: 60px;
        height: 60px;
        border: 4px solid rgba(0, 212, 255, 0.2);
        border-top-color: var(--neon-cyan);
        border-radius: 50%;
        animation: spin 1s linear infinite;
      }
      @keyframes spin {
        to { transform: rotate(360deg); }
      }
      .spinner-container {
        text-align: center;
      }
      .spinner-container p {
        margin-top: 1rem;
        color: var(--neon-cyan);
        font-family: 'Orbitron', sans-serif;
      }
    `;

        document.head.appendChild(style);
        document.body.appendChild(spinner);
    }

    static hide() {
        const spinner = document.getElementById('loading-spinner');
        if (spinner) {
            spinner.style.opacity = '0';
            setTimeout(() => spinner.remove(), 300);
        }
    }
}

// ============================================
// SMOOTH SCROLL
// ============================================
class SmoothScroll {
    static init() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href === '#') return;

                e.preventDefault();
                const target = document.querySelector(href);

                if (target) {
                    const offsetTop = target.offsetTop - 80; // Account for fixed navbar
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }
}

// ============================================
// INITIALIZE ALL
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    // Initialize all controllers
    new ParallaxController();
    new ScrollAnimations();
    new NavbarController();
    new ModalController();

    // Initialize counters
    CounterAnimation.initAll();

    // Initialize smooth scroll
    SmoothScroll.init();

    // Initialize forms
    document.querySelectorAll('form').forEach(form => {
        new FormController(`#${form.id}`);
    });

    console.log('🚀 Futuristic UI System Initialized');
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        ParallaxController,
        ScrollAnimations,
        CounterAnimation,
        NavbarController,
        FormController,
        ModalController,
        LoadingSpinner,
        SmoothScroll
    };
}
