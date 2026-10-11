import { motionDuration, prefersReducedMotion } from './motion';

/**
 * Orchestrate one hero entrance, then restrained one-shot content reveals.
 */
function initScrollReveal() {
    const revealElements = Array.from(document.querySelectorAll('.reveal'));
    const hero = document.querySelector('.hm-hero');
    const heroElements = hero
        ? Array.from(hero.querySelectorAll('.reveal'))
        : [];
    const heroElementSet = new Set(heroElements);
    const contentElements = revealElements.filter((element) => !heroElementSet.has(element));
    const groupedRevealSelectors = [
        '.store-shelf',
        '.store-project-track',
        '.store-principle-grid',
        '.store-catalog-grid',
        '.store-project-grid',
    ];

    heroElements.forEach((element, index) => {
        element.classList.add('motion-item', 'motion-item--hero', 'visible');
        element.style.setProperty('--motion-order', String(index));
    });

    contentElements.forEach((element) => {
        element.classList.add('motion-item');

        const bounds = element.getBoundingClientRect();
        if (bounds.top < window.innerHeight && bounds.bottom > 0) {
            element.classList.add('visible');
        }

        const requestedDelay = Number.parseInt(element.dataset.revealDelay || '0', 10);
        const safeDelay = Number.isFinite(requestedDelay)
            ? Math.min(Math.max(requestedDelay, 0), 180)
            : 0;

        element.style.setProperty('--motion-delay', `${safeDelay}ms`);
    });

    groupedRevealSelectors.forEach((selector) => {
        document.querySelectorAll(selector).forEach((group) => {
            const groupItems = Array.from(group.querySelectorAll('.reveal'))
                .filter((element) => !heroElementSet.has(element));

            groupItems.forEach((element, index) => {
                element.style.setProperty('--motion-delay', `${Math.min(index, 4) * (motionDuration.micro / 2)}ms`);
            });
        });
    });

    const enterHero = () => {
        hero?.classList.add('is-entered');
        heroElements.forEach((element) => element.classList.add('visible'));
    };

    if (prefersReducedMotion() || !('IntersectionObserver' in window)) {
        enterHero();
        contentElements.forEach((element) => element.classList.add('visible'));
        return;
    }

    enterHero();

    if (window.matchMedia('(max-width: 39.999rem)').matches) {
        contentElements.forEach((element) => element.classList.add('visible'));
        return;
    }

    document.documentElement.classList.add('motion-enabled');

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -8% 0px' }
    );

    contentElements.forEach((element) => observer.observe(element));
}

/**
 * Skill bar animation — fills bars when they come into view.
 */
function initSkillBars() {
    const skillBars = document.querySelectorAll('.skill-bar-fill');

    function fillBar(bar) {
        const width = Math.min(100, Math.max(0, Number(bar.dataset.width || 0)));
        bar.style.setProperty('--skill-progress', String(width / 100));
        bar.classList.add('animate');
    }

    if (prefersReducedMotion() || !('IntersectionObserver' in window)) {
        skillBars.forEach(fillBar);
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    fillBar(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.5 }
    );

    skillBars.forEach((el) => observer.observe(el));
}

/**
 * Compositor-only pointer depth for the hero diagram and interactive cards.
 */
function initPointerMotion() {
    const precisePointerMedia = window.matchMedia('(hover: hover) and (pointer: fine)');
    if (prefersReducedMotion() || !precisePointerMedia.matches) return;

    const hero = document.querySelector('.hm-hero');
    const circuit = hero?.querySelector('[data-circuit-scene]');
    const cardSelector = '.spotlight-card:not(.hm-node), .store-index-card';

    let heroBounds = null;
    let heroFrame = null;
    let heroPoint = null;
    let activeCard = null;
    let activeCardBounds = null;
    let cardFrame = null;
    let cardPoint = null;

    const resetCircuit = () => {
        if (!circuit) return;

        if (heroFrame) {
            cancelAnimationFrame(heroFrame);
            heroFrame = null;
        }

        circuit.classList.remove('is-pointer-active');
        circuit.style.setProperty('--motion-pointer-x', '0px');
        circuit.style.setProperty('--motion-pointer-y', '0px');
        circuit.style.setProperty('--motion-rotate-x', '0deg');
        circuit.style.setProperty('--motion-rotate-y', '0deg');
    };

    if (hero && circuit) {
        circuit.classList.add('motion-reactive');

        hero.addEventListener('pointerenter', () => {
            heroBounds = hero.getBoundingClientRect();
            circuit.classList.add('is-pointer-active');
        });

        hero.addEventListener('pointermove', (event) => {
            if (prefersReducedMotion() || !heroBounds) return;

            heroPoint = { x: event.clientX, y: event.clientY };
            if (heroFrame) return;

            heroFrame = requestAnimationFrame(() => {
                const normalizedX = Math.min(1, Math.max(-1, ((heroPoint.x - heroBounds.left) / heroBounds.width) * 2 - 1));
                const normalizedY = Math.min(1, Math.max(-1, ((heroPoint.y - heroBounds.top) / heroBounds.height) * 2 - 1));

                circuit.style.setProperty('--motion-pointer-x', `${(normalizedX * 7).toFixed(2)}px`);
                circuit.style.setProperty('--motion-pointer-y', `${(normalizedY * 5).toFixed(2)}px`);
                circuit.style.setProperty('--motion-rotate-x', `${(-normalizedY * 0.8).toFixed(2)}deg`);
                circuit.style.setProperty('--motion-rotate-y', `${(normalizedX * 0.9).toFixed(2)}deg`);
                heroFrame = null;
            });
        });

        hero.addEventListener('pointerleave', resetCircuit);
        window.addEventListener('resize', () => {
            heroBounds = null;
            resetCircuit();
        }, { passive: true });
    }

    document.addEventListener('pointerover', (event) => {
        const card = event.target.closest?.(cardSelector);
        if (!card || card === activeCard) return;

        activeCard?.classList.remove('is-pointer-active');
        activeCard = card;
        activeCardBounds = card.getBoundingClientRect();
        activeCard.classList.add('motion-pointer-card', 'is-pointer-active');
    });

    document.addEventListener('pointermove', (event) => {
        if (!activeCard || !activeCardBounds || prefersReducedMotion()) return;

        cardPoint = { x: event.clientX, y: event.clientY };
        if (cardFrame) return;

        cardFrame = requestAnimationFrame(() => {
            const normalizedX = Math.min(1, Math.max(-1, ((cardPoint.x - activeCardBounds.left) / activeCardBounds.width) * 2 - 1));
            const normalizedY = Math.min(1, Math.max(-1, ((cardPoint.y - activeCardBounds.top) / activeCardBounds.height) * 2 - 1));

            activeCard.style.setProperty('--motion-card-rotate-x', `${(-normalizedY * 1.1).toFixed(2)}deg`);
            activeCard.style.setProperty('--motion-card-rotate-y', `${(normalizedX * 1.4).toFixed(2)}deg`);
            activeCard.style.setProperty('--motion-card-origin-x', `${((normalizedX + 1) * 50).toFixed(1)}%`);
            activeCard.style.setProperty('--motion-card-origin-y', `${((normalizedY + 1) * 50).toFixed(1)}%`);
            cardFrame = null;
        });
    });

    document.addEventListener('pointerout', (event) => {
        if (!activeCard || activeCard.contains(event.relatedTarget)) return;

        if (cardFrame) {
            cancelAnimationFrame(cardFrame);
            cardFrame = null;
        }

        activeCard.classList.remove('is-pointer-active');
        activeCard.style.setProperty('--motion-card-rotate-x', '0deg');
        activeCard.style.setProperty('--motion-card-rotate-y', '0deg');
        activeCard = null;
        activeCardBounds = null;
    });
}

/**
 * A visible global circuit reticle with a short, low-cost signal trail.
 */
function initPointerSignal() {
    const precisePointerMedia = window.matchMedia('(hover: hover) and (pointer: fine)');
    if (prefersReducedMotion() || !precisePointerMedia.matches) return;

    const signal = document.createElement('div');
    const position = document.createElement('span');
    const reticle = document.createElement('span');
    const core = document.createElement('span');
    const trailElements = Array.from({ length: 4 }, (_, index) => {
        const trail = document.createElement('span');
        trail.className = 'motion-pointer-signal__trail';
        trail.style.setProperty('--trail-index', String(index));

        return trail;
    });
    const interactiveSelector = 'a, button, input, select, textarea, [role="button"], [role="tab"], [data-gallery-open]';
    const textEntrySelector = 'input, select, textarea, [contenteditable="true"]';
    const trailPoints = trailElements.map(() => ({ x: 0, y: 0 }));
    const target = { x: 0, y: 0 };
    const current = { x: 0, y: 0 };
    let frame = null;
    let hasPosition = false;
    let angle = 0;

    signal.className = 'motion-pointer-signal';
    signal.setAttribute('aria-hidden', 'true');
    position.className = 'motion-pointer-signal__position';
    reticle.className = 'motion-pointer-signal__reticle';
    core.className = 'motion-pointer-signal__core';
    reticle.appendChild(core);
    position.appendChild(reticle);
    signal.append(...trailElements, position);
    document.body.appendChild(signal);

    const render = () => {
        const previousX = current.x;
        const previousY = current.y;
        current.x += (target.x - current.x) * 0.32;
        current.y += (target.y - current.y) * 0.32;

        const velocityX = current.x - previousX;
        const velocityY = current.y - previousY;
        if (Math.abs(velocityX) + Math.abs(velocityY) > 0.08) {
            angle = Math.atan2(velocityY, velocityX) * (180 / Math.PI);
        }

        position.style.transform = `translate3d(${current.x.toFixed(2)}px, ${current.y.toFixed(2)}px, 0)`;

        let leader = current;
        trailPoints.forEach((point, index) => {
            const followStrength = 0.27 - (index * 0.025);
            point.x += (leader.x - point.x) * followStrength;
            point.y += (leader.y - point.y) * followStrength;
            trailElements[index].style.transform = `translate3d(${point.x.toFixed(2)}px, ${point.y.toFixed(2)}px, 0) rotate(${angle.toFixed(1)}deg)`;
            leader = point;
        });

        const cursorDistance = Math.hypot(target.x - current.x, target.y - current.y);
        const trailEnd = trailPoints[trailPoints.length - 1];
        const trailDistance = Math.hypot(current.x - trailEnd.x, current.y - trailEnd.y);

        if (cursorDistance > 0.08 || trailDistance > 0.12) {
            frame = requestAnimationFrame(render);
        } else {
            frame = null;
        }
    };

    const scheduleRender = () => {
        if (!frame) {
            frame = requestAnimationFrame(render);
        }
    };

    document.addEventListener('pointermove', (event) => {
        target.x = event.clientX;
        target.y = event.clientY;

        if (!hasPosition) {
            current.x = target.x;
            current.y = target.y;
            trailPoints.forEach((point) => {
                point.x = target.x;
                point.y = target.y;
            });
            hasPosition = true;
        }

        signal.classList.add('is-visible');
        signal.classList.toggle('is-interactive', Boolean(event.target.closest?.(interactiveSelector)));
        signal.classList.toggle('is-text-entry', Boolean(event.target.closest?.(textEntrySelector)));
        scheduleRender();
    }, { passive: true });

    document.addEventListener('pointerdown', () => signal.classList.add('is-pressed'));
    document.addEventListener('pointerup', () => signal.classList.remove('is-pressed'));
    document.documentElement.addEventListener('pointerleave', () => signal.classList.remove('is-visible'));
    document.documentElement.addEventListener('pointerenter', () => {
        if (hasPosition) signal.classList.add('is-visible');
    });
    window.addEventListener('blur', () => signal.classList.remove('is-visible'));
}

/**
 * Lightweight scroll progress and depth shift for the hero visual.
 */
function initScrollMotion() {
    const desktopMotionMedia = window.matchMedia('(min-width: 48rem)');
    if (prefersReducedMotion() || !desktopMotionMedia.matches) return;

    const circuit = document.querySelector('[data-circuit-scene]');
    const progressRail = document.createElement('div');
    const progressFill = document.createElement('span');
    let maximumScroll = 1;
    let heroMotionLimit = 0;
    let scrollFrame = null;
    let settleTimer = null;

    progressRail.className = 'motion-scroll-rail';
    progressRail.setAttribute('aria-hidden', 'true');
    progressFill.className = 'motion-scroll-rail__fill';
    progressRail.appendChild(progressFill);
    document.body.appendChild(progressRail);
    circuit?.classList.add('motion-reactive');

    const measure = () => {
        maximumScroll = Math.max(1, document.documentElement.scrollHeight - window.innerHeight);
        heroMotionLimit = document.querySelector('.hm-hero')?.offsetHeight || 0;
    };

    const render = () => {
        const scrollPosition = window.scrollY;
        const pageProgress = Math.min(1, Math.max(0, scrollPosition / maximumScroll));

        progressFill.style.transform = `scaleY(${pageProgress.toFixed(4)})`;

        if (circuit) {
            const heroProgress = Math.min(1, Math.max(0, scrollPosition / Math.max(1, heroMotionLimit)));
            circuit.style.setProperty('--motion-scroll-y', `${(heroProgress * 24).toFixed(2)}px`);
            circuit.classList.add('is-scroll-moving');

            window.clearTimeout(settleTimer);
            settleTimer = window.setTimeout(() => {
                circuit.classList.remove('is-scroll-moving');
            }, motionDuration.micro);
        }

        scrollFrame = null;
    };

    const scheduleRender = () => {
        if (prefersReducedMotion() || scrollFrame) return;
        scrollFrame = requestAnimationFrame(render);
    };

    measure();
    render();
    window.addEventListener('scroll', scheduleRender, { passive: true });
    window.addEventListener('resize', () => {
        measure();
        scheduleRender();
    }, { passive: true });
}

/**
 * Mobile navigation toggle.
 */
function initMobileNav() {
    const toggle = document.getElementById('mobile-nav-toggle');
    const menu = document.getElementById('mobile-nav-menu');

    if (!toggle || !menu) return;

    let closeTimer = null;

    const setOpen = (isOpen, returnFocus = false) => {
        window.clearTimeout(closeTimer);
        toggle.setAttribute('aria-expanded', String(isOpen));
        toggle.setAttribute('aria-label', isOpen ? 'Đóng menu điều hướng' : 'Mở menu điều hướng');
        menu.setAttribute('aria-hidden', String(!isOpen));

        if (isOpen) {
            menu.classList.remove('hidden');
            requestAnimationFrame(() => menu.classList.add('is-open'));
            return;
        }

        menu.classList.remove('is-open');

        const finishClose = () => {
            menu.classList.add('hidden');

            if (returnFocus) {
                toggle.focus({ preventScroll: true });
            }
        };

        if (prefersReducedMotion()) {
            finishClose();
        } else {
            closeTimer = window.setTimeout(finishClose, motionDuration.micro);
        }
    };

    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-controls', 'mobile-nav-menu');
    menu.setAttribute('aria-hidden', 'true');

    toggle.addEventListener('click', () => {
        setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    menu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setOpen(false));
    });

    document.addEventListener('pointerdown', (event) => {
        if (toggle.getAttribute('aria-expanded') !== 'true') return;
        if (menu.contains(event.target) || toggle.contains(event.target)) return;

        setOpen(false);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
            setOpen(false, true);
        }
    });

    window.matchMedia('(min-width: 48rem)').addEventListener('change', (event) => {
        if (event.matches) {
            setOpen(false);
        }
    });
}

/**
 * Account dropdown disclosure with keyboard and light-dismiss support.
 */
function initUserMenu() {
    const menu = document.querySelector('[data-user-menu]');
    const toggle = menu?.querySelector('[data-user-menu-toggle]');
    const dropdown = menu?.querySelector('[data-user-menu-dropdown]');

    if (!menu || !toggle || !dropdown) return;

    let closeTimer = null;

    const setOpen = (isOpen, returnFocus = false) => {
        window.clearTimeout(closeTimer);
        toggle.setAttribute('aria-expanded', String(isOpen));
        dropdown.setAttribute('aria-hidden', String(!isOpen));

        if (isOpen) {
            dropdown.classList.remove('hidden');
            requestAnimationFrame(() => dropdown.classList.add('is-open'));
            return;
        }

        dropdown.classList.remove('is-open');

        const finishClose = () => {
            dropdown.classList.add('hidden');

            if (returnFocus) {
                toggle.focus({ preventScroll: true });
            }
        };

        if (prefersReducedMotion()) {
            finishClose();
        } else {
            closeTimer = window.setTimeout(finishClose, motionDuration.micro);
        }
    };

    toggle.addEventListener('click', () => {
        setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    document.addEventListener('pointerdown', (event) => {
        if (toggle.getAttribute('aria-expanded') !== 'true' || menu.contains(event.target)) return;

        setOpen(false);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
            setOpen(false, true);
        }
    });
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
                target.scrollIntoView({
                    behavior: prefersReducedMotion() ? 'auto' : 'smooth',
                    block: 'start',
                });
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

    if (!('IntersectionObserver' in window)) {
        nav.classList.toggle('nav-scrolled', window.scrollY > 50);
        return;
    }

    const sentinel = document.createElement('span');
    sentinel.className = 'site-nav-sentinel';
    sentinel.setAttribute('aria-hidden', 'true');
    document.body.prepend(sentinel);

    const observer = new IntersectionObserver(([entry]) => {
        nav.classList.toggle('nav-scrolled', !entry.isIntersecting);
    }, { rootMargin: '-52px 0px 0px 0px' });

    observer.observe(sentinel);
}

/**
 * Cart AJAX operations.
 */
function initCart() {
    // Add to cart buttons
    document.querySelectorAll('[data-cart-add]').forEach((btn) => {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            if (this.dataset.state === 'loading') return;

            const url = this.dataset.cartAdd;
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const canDisable = this instanceof HTMLButtonElement;

            this.dataset.state = 'loading';
            this.setAttribute('aria-busy', 'true');

            if (canDisable) {
                this.disabled = true;
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        Accept: 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error('Cart request failed');
                }

                const data = await response.json();
                updateCartBadge(data.cartCount);
                this.dataset.state = 'success';
                showToast(data.message || 'Đã thêm vào giỏ hàng!');

                window.setTimeout(() => {
                    delete this.dataset.state;
                    this.removeAttribute('aria-busy');

                    if (canDisable) {
                        this.disabled = false;
                    }
                }, 900);
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
        badge.classList.remove('is-updated');
        requestAnimationFrame(() => badge.classList.add('is-updated'));
        window.setTimeout(() => badge.classList.remove('is-updated'), motionDuration.long);
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
    toast.setAttribute('role', type === 'error' ? 'alert' : 'status');
    toast.setAttribute('aria-live', type === 'error' ? 'assertive' : 'polite');
    toast.textContent = message;
    document.body.appendChild(toast);

    requestAnimationFrame(() => toast.classList.add('show'));

    const dismiss = () => {
        toast.classList.remove('show');
        toast.classList.add('is-leaving');
        window.setTimeout(() => toast.remove(), prefersReducedMotion() ? 0 : motionDuration.micro);
    };

    window.setTimeout(dismiss, 3600);
}

/**
 * Focus the product search from Ctrl/Command K without adding a heavy command palette.
 */
function initNavSearchShortcut() {
    const desktopSearch = document.getElementById('site-nav-search');
    const mobileSearch = document.getElementById('site-mobile-search');

    document.addEventListener('keydown', (event) => {
        const isSearchShortcut = (event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k';
        if (!isSearchShortcut) return;

        const target = window.matchMedia('(min-width: 64rem)').matches
            ? desktopSearch
            : (mobileSearch || desktopSearch);
        if (!target) return;

        event.preventDefault();
        target.focus({ preventScroll: true });
    });
}

/**
 * Copy small readonly values such as payment wallet addresses.
 */
function initCopyButtons() {
    document.querySelectorAll('[data-copy-target]').forEach((button) => {
        button.addEventListener('click', async () => {
            const target = document.getElementById(button.dataset.copyTarget);
            if (!target) return;

            const originalLabel = button.textContent;

            try {
                await navigator.clipboard.writeText(target.value || target.textContent || '');
                button.textContent = button.dataset.copyLabel || 'Đã copy!';
                button.dataset.state = 'success';

                window.setTimeout(() => {
                    button.textContent = originalLabel;
                    delete button.dataset.state;
                }, 2500);
            } catch {
                button.dataset.state = 'error';
                target.focus();
                target.select?.();

                window.setTimeout(() => delete button.dataset.state, 1200);
            }
        });
    });
}

/**
 * Accessible password visibility controls for authentication forms.
 */
function initPasswordToggles() {
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        const input = document.getElementById(button.dataset.passwordToggle);
        if (!(input instanceof HTMLInputElement)) return;

        button.addEventListener('click', () => {
            const isVisible = input.type === 'text';
            input.type = isVisible ? 'password' : 'text';
            button.setAttribute('aria-pressed', String(!isVisible));
            button.setAttribute('aria-label', isVisible ? 'Hiện mật khẩu' : 'Ẩn mật khẩu');
            input.focus({ preventScroll: true });
        });
    });
}

// Initialize everything on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    initScrollReveal();
    initSkillBars();
    if (document.querySelector('[data-home-signal-canvas]')) {
        import('./home').then(({ initHomeSignalField }) => initHomeSignalField());
    }
    initPointerSignal();
    initPointerMotion();
    initScrollMotion();
    initMobileNav();
    initUserMenu();
    initSmoothScroll();
    initNavbarScroll();
    initCart();
    initNavSearchShortcut();
    if (document.querySelector('.store-product-detail-view')) {
        import('./product').then(({ initProductDetail }) => initProductDetail());
    }
    initCopyButtons();
    initPasswordToggles();
});
