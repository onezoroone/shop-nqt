import './bootstrap';

/**
 * Scroll Reveal — animate elements as they enter the viewport.
 */
function initScrollReveal() {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.1, rootMargin: '0px 0px -50px 0px' }
    );

    document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));
}

/**
 * Skill bar animation — fills bars when they come into view.
 */
function initSkillBars() {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const bar = entry.target;
                    const width = bar.dataset.width;
                    bar.style.width = width + '%';
                    bar.classList.add('animate');
                    observer.unobserve(bar);
                }
            });
        },
        { threshold: 0.5 }
    );

    document.querySelectorAll('.skill-bar-fill').forEach((el) => observer.observe(el));
}

/**
 * Mobile navigation toggle.
 */
function initMobileNav() {
    const toggle = document.getElementById('mobile-nav-toggle');
    const menu = document.getElementById('mobile-nav-menu');

    if (toggle && menu) {
        toggle.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });

        // Close on link click
        menu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                menu.classList.add('hidden');
            });
        });
    }
}

/**
 * Smooth scroll for anchor links.
 */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

/**
 * Navbar background on scroll.
 */
function initNavbarScroll() {
    const nav = document.getElementById('main-nav');
    if (!nav) return;

    function updateNav() {
        if (window.scrollY > 50) {
            nav.classList.add('bg-surface-dark/95', 'backdrop-blur-xl', 'shadow-lg', 'shadow-black/20');
            nav.classList.remove('bg-transparent');
        } else {
            nav.classList.remove('bg-surface-dark/95', 'backdrop-blur-xl', 'shadow-lg', 'shadow-black/20');
            nav.classList.add('bg-transparent');
        }
    }

    window.addEventListener('scroll', updateNav, { passive: true });
    updateNav();
}

/**
 * Cart AJAX operations.
 */
function initCart() {
    // Add to cart buttons
    document.querySelectorAll('[data-cart-add]').forEach((btn) => {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            const url = this.dataset.cartAdd;
            const token = document.querySelector('meta[name="csrf-token"]').content;

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        Accept: 'application/json',
                    },
                });

                const data = await response.json();
                updateCartBadge(data.cartCount);
                showToast(data.message || 'Đã thêm vào giỏ hàng!');
            } catch {
                // Fallback to regular form submission
                window.location.href = url;
            }
        });
    });
}

/**
 * Update the cart badge count in the navbar.
 */
function updateCartBadge(count) {
    const badge = document.getElementById('cart-badge');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    }
}

/**
 * Show a toast notification.
 */
function showToast(message, type = 'success') {
    const existing = document.querySelector('.toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.add('show');
    });

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}

/**
 * Typing animation for hero text.
 */
function initTypingAnimation() {
    const el = document.getElementById('typing-text');
    if (!el) return;

    const texts = JSON.parse(el.dataset.texts || '[]');
    if (texts.length === 0) return;

    let textIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    let typingSpeed = 100;

    function type() {
        const currentText = texts[textIndex];

        if (isDeleting) {
            el.textContent = currentText.substring(0, charIndex - 1);
            charIndex--;
            typingSpeed = 50;
        } else {
            el.textContent = currentText.substring(0, charIndex + 1);
            charIndex++;
            typingSpeed = 100;
        }

        if (!isDeleting && charIndex === currentText.length) {
            typingSpeed = 2000;
            isDeleting = true;
        } else if (isDeleting && charIndex === 0) {
            isDeleting = false;
            textIndex = (textIndex + 1) % texts.length;
            typingSpeed = 500;
        }

        setTimeout(type, typingSpeed);
    }

    type();
}

// Initialize everything on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    initScrollReveal();
    initSkillBars();
    initMobileNav();
    initSmoothScroll();
    initNavbarScroll();
    initCart();
    initTypingAnimation();
});
