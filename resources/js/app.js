import './bootstrap';

function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

/**
 * Scroll Reveal — animate elements as they enter the viewport.
 */
function initScrollReveal() {
    const revealElements = document.querySelectorAll('.reveal');

    revealElements.forEach((el) => {
        if (el.dataset.revealDelay) {
            el.style.setProperty('--reveal-delay', `${el.dataset.revealDelay}ms`);
        }
    });

    if (prefersReducedMotion() || !('IntersectionObserver' in window)) {
        revealElements.forEach((el) => el.classList.add('visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.06, rootMargin: '0px 0px 140px 0px' }
    );

    revealElements.forEach((el) => observer.observe(el));
}

/**
 * Skill bar animation — fills bars when they come into view.
 */
function initSkillBars() {
    const skillBars = document.querySelectorAll('.skill-bar-fill');

    function fillBar(bar) {
        const width = Math.min(100, Math.max(0, Number(bar.dataset.width || 0)));
        bar.style.width = `${width}%`;
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
 * Mobile navigation toggle.
 */
function initMobileNav() {
    const toggle = document.getElementById('mobile-nav-toggle');
    const menu = document.getElementById('mobile-nav-menu');

    if (toggle && menu) {
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-controls', 'mobile-nav-menu');

        toggle.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            toggle.setAttribute('aria-expanded', String(!menu.classList.contains('hidden')));
        });

        // Close on link click
        menu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                menu.classList.add('hidden');
                toggle.setAttribute('aria-expanded', 'false');
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

    let isScrolled = null;

    function updateNav() {
        const shouldBeScrolled = window.scrollY > 50;

        if (shouldBeScrolled === isScrolled) return;

        isScrolled = shouldBeScrolled;

        if (shouldBeScrolled) {
            nav.classList.add('nav-scrolled');
            nav.classList.add('bg-surface-dark/95', 'backdrop-blur-xl', 'shadow-lg', 'shadow-black/20');
            nav.classList.remove('bg-transparent');
        } else {
            nav.classList.remove('nav-scrolled');
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

    if (prefersReducedMotion()) {
        el.textContent = texts[0];
        return;
    }

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

/**
 * Spotlight hover and subtle tilt for premium cards.
 */
function initHomeSpotlight() {
    if (prefersReducedMotion()) return;

    const supportsHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    if (!supportsHover) return;

    document.querySelectorAll('.spotlight-card, .home-button, .store-search').forEach((card) => {
        let frame = null;
        let pointerEvent = null;

        const syncSpotlight = () => {
            if (!pointerEvent) {
                frame = null;
                return;
            }

            const rect = card.getBoundingClientRect();
            const x = pointerEvent.clientX - rect.left;
            const y = pointerEvent.clientY - rect.top;
            const rotateY = ((x / rect.width) - 0.5) * 5;
            const rotateX = -((y / rect.height) - 0.5) * 4;

            card.style.setProperty('--spotlight-x', `${x}px`);
            card.style.setProperty('--spotlight-y', `${y}px`);
            card.style.setProperty('--tilt-x', `${rotateX.toFixed(2)}deg`);
            card.style.setProperty('--tilt-y', `${rotateY.toFixed(2)}deg`);

            frame = null;
        };

        card.addEventListener('pointermove', (event) => {
            pointerEvent = event;

            if (!frame) {
                frame = requestAnimationFrame(syncSpotlight);
            }
        });

        card.addEventListener('pointerleave', () => {
            if (frame) {
                cancelAnimationFrame(frame);
            }

            frame = null;
            pointerEvent = null;
            card.style.setProperty('--spotlight-x', '50%');
            card.style.setProperty('--spotlight-y', '50%');
            card.style.setProperty('--tilt-x', '0deg');
            card.style.setProperty('--tilt-y', '0deg');
        });
    });
}

/**
 * Lightweight parallax for selected home visuals.
 *
 * Disabled intentionally for the home storefront. Scroll-linked CSS variable
 * updates were repainting large gradients and made the page feel janky on
 * mid-range devices.
 */
function initHomeParallax() {
    document.querySelectorAll('.home-parallax').forEach((item) => {
        item.style.setProperty('--parallax-y', '0px');
    });
}

/**
 * Scroll-linked scene progress for the home editorial background.
 *
 * Kept as a static initializer so older CSS hooks remain harmless without
 * attaching a continuous scroll handler.
 */
function initHomeScrollScene() {
    const homeScene = document.querySelector('[data-home-scene]');
    if (!homeScene) return;

    homeScene.style.setProperty('--home-scroll-progress', '0');
}

/**
 * Split display text into animated words while keeping the accessible label.
 * Word-level motion is smoother for large Vietnamese hero typography than
 * per-character 3D animation.
 */
function initSplitText() {
    document.querySelectorAll('.text-split').forEach((element) => {
        if (element.dataset.splitReady === 'true') return;

        const text = element.textContent.trim();
        if (!text) return;

        element.dataset.splitReady = 'true';
        element.setAttribute('aria-label', text);
        element.textContent = '';

        let wordIndex = 0;

        text.split(/(\s+)/).forEach((segment) => {
            const isSpace = /^\s+$/.test(segment);

            if (isSpace) {
                const space = document.createElement('span');
                space.className = 'split-space';
                space.setAttribute('aria-hidden', 'true');
                space.textContent = segment;
                element.appendChild(space);
                return;
            }

            const word = document.createElement('span');
            word.className = 'split-word';
            word.setAttribute('aria-hidden', 'true');
            word.style.setProperty('--word-index', wordIndex);
            word.textContent = segment;

            element.appendChild(word);
            wordIndex++;
        });
    });
}

/**
 * Code-store hero scene: cursor light and premium motion variables.
 */
function initCodeStoreScene() {
    const hero = document.querySelector('.store-hero');
    const labScene = document.querySelector('[data-lab-scene]');

    if (!hero && !labScene) return;

    if (prefersReducedMotion()) {
        hero?.style.setProperty('--hero-progress', '0');
        labScene?.style.setProperty('--scene-x', '50%');
        labScene?.style.setProperty('--scene-y', '50%');
        return;
    }

    hero?.style.setProperty('--hero-progress', '0');
    hero?.style.setProperty('--cursor-x', '50%');
    hero?.style.setProperty('--cursor-y', '38%');
    labScene?.style.setProperty('--hero-progress', '0');

    const supportsPointerScene = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    if (!supportsPointerScene || !hero) return;

    let frame = null;
    let pointerEvent = null;

    const syncScene = () => {
        if (!pointerEvent) {
            frame = null;
            return;
        }

        const heroRect = hero.getBoundingClientRect();
        const heroX = ((pointerEvent.clientX - heroRect.left) / heroRect.width) * 100;
        const heroY = ((pointerEvent.clientY - heroRect.top) / heroRect.height) * 100;
        const clampedHeroX = Math.min(100, Math.max(0, heroX));
        const clampedHeroY = Math.min(100, Math.max(0, heroY));

        hero.style.setProperty('--cursor-x', `${clampedHeroX.toFixed(2)}%`);
        hero.style.setProperty('--cursor-y', `${clampedHeroY.toFixed(2)}%`);

        if (labScene) {
            const labRect = labScene.getBoundingClientRect();
            const labX = ((pointerEvent.clientX - labRect.left) / labRect.width) * 100;
            const labY = ((pointerEvent.clientY - labRect.top) / labRect.height) * 100;
            const clampedLabX = Math.min(100, Math.max(0, labX));
            const clampedLabY = Math.min(100, Math.max(0, labY));

            labScene.style.setProperty('--scene-x', `${clampedLabX.toFixed(2)}%`);
            labScene.style.setProperty('--scene-y', `${clampedLabY.toFixed(2)}%`);
            labScene.style.setProperty('--scene-tilt-x', `${((50 - clampedLabY) * 0.035).toFixed(2)}deg`);
            labScene.style.setProperty('--scene-tilt-y', `${((clampedLabX - 50) * 0.045).toFixed(2)}deg`);
        }

        frame = null;
    };

    hero.addEventListener('pointermove', (event) => {
        pointerEvent = event;

        if (!frame) {
            frame = requestAnimationFrame(syncScene);
        }
    });

    hero.addEventListener('pointerleave', () => {
        if (frame) {
            cancelAnimationFrame(frame);
        }

        frame = null;
        pointerEvent = null;
        hero.style.setProperty('--cursor-x', '50%');
        hero.style.setProperty('--cursor-y', '38%');
        labScene?.style.setProperty('--scene-x', '50%');
        labScene?.style.setProperty('--scene-y', '50%');
        labScene?.style.setProperty('--scene-tilt-x', '0deg');
        labScene?.style.setProperty('--scene-tilt-y', '0deg');
    });
}

function readJsonScript(scope, selector) {
    const script = scope.querySelector(selector);
    if (!script?.textContent) return [];

    try {
        const data = JSON.parse(script.textContent);
        return Array.isArray(data) ? data.filter((item) => item?.url) : [];
    } catch {
        return [];
    }
}

/**
 * Product detail gallery and fullscreen lightbox.
 */
function initProductGallery() {
    document.querySelectorAll('[data-product-gallery]').forEach((gallery) => {
        if (gallery.dataset.galleryReady === 'true') return;

        const images = readJsonScript(gallery, '[data-product-gallery-images]');
        if (images.length === 0) return;

        gallery.dataset.galleryReady = 'true';

        const stageButton = gallery.querySelector('[data-gallery-open]');
        const mainImage = gallery.querySelector('[data-gallery-image]');
        const counter = gallery.querySelector('[data-gallery-counter]');
        const prevButton = gallery.querySelector('[data-gallery-prev]');
        const nextButton = gallery.querySelector('[data-gallery-next]');
        const thumbButtons = Array.from(gallery.querySelectorAll('[data-gallery-thumb]'));
        const lightboxEl = document.getElementById('product-lightbox');
        const lightboxImg = document.getElementById('product-lightbox-img');
        const lightboxCounter = document.getElementById('product-lightbox-counter');
        const lightboxCaption = document.getElementById('product-lightbox-caption');
        const lightboxCloseButton = lightboxEl?.querySelector('.product-lightbox__close');

        let currentIndex = 0;
        let lightboxCurrentIndex = 0;
        let lastFocusedElement = null;

        const normalizeIndex = (index) => (index + images.length) % images.length;

        const updateControls = () => {
            const hasMultipleImages = images.length > 1;
            prevButton?.toggleAttribute('disabled', !hasMultipleImages);
            nextButton?.toggleAttribute('disabled', !hasMultipleImages);
        };

        const setActiveIndex = (index, options = {}) => {
            currentIndex = normalizeIndex(index);
            const image = images[currentIndex];

            if (mainImage && image) {
                if (mainImage.getAttribute('src') !== image.url) {
                    gallery.classList.add('is-changing');
                    mainImage.src = image.url;
                    window.setTimeout(() => gallery.classList.remove('is-changing'), 260);
                } else {
                    gallery.classList.remove('is-changing');
                }
                mainImage.alt = image.alt || '';
            }

            stageButton?.setAttribute('data-lightbox-index', String(currentIndex));

            if (counter) {
                counter.textContent = `${currentIndex + 1} / ${images.length}`;
            }

            thumbButtons.forEach((button) => {
                const isActive = Number(button.dataset.galleryIndex) === currentIndex;
                button.classList.toggle('is-active', isActive);
                button.classList.toggle('swiper-slide-thumb-active', isActive);
                button.setAttribute('aria-selected', String(isActive));

                if (isActive && options.scrollThumb !== false) {
                    button.scrollIntoView({
                        behavior: prefersReducedMotion() ? 'auto' : 'smooth',
                        block: 'nearest',
                        inline: 'nearest',
                    });
                }
            });
        };

        const renderLightbox = () => {
            if (!lightboxImg) return;

            const image = images[lightboxCurrentIndex];
            lightboxImg.src = image.url;
            lightboxImg.alt = image.alt || '';

            if (lightboxCounter) {
                lightboxCounter.textContent = `${lightboxCurrentIndex + 1} / ${images.length}`;
            }

            if (lightboxCaption) {
                lightboxCaption.textContent = image.alt || '';
            }
        };

        const showLightboxImage = (index) => {
            lightboxCurrentIndex = normalizeIndex(index);
            renderLightbox();
            setActiveIndex(lightboxCurrentIndex, { scrollThumb: true });
        };

        const openLightbox = (index) => {
            if (!lightboxEl || !lightboxImg) return;

            lastFocusedElement = document.activeElement;
            showLightboxImage(index);
            lightboxEl.classList.remove('hidden');
            lightboxEl.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
            lightboxCloseButton?.focus({ preventScroll: true });
        };

        const closeLightbox = () => {
            if (!lightboxEl || !lightboxImg) return;

            lightboxEl.classList.add('hidden');
            lightboxEl.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('overflow-hidden');
            lightboxImg.removeAttribute('src');
            lastFocusedElement?.focus?.({ preventScroll: true });
        };

        mainImage?.addEventListener('load', () => {
            requestAnimationFrame(() => gallery.classList.remove('is-changing'));
        });

        stageButton?.addEventListener('click', () => openLightbox(currentIndex));

        prevButton?.addEventListener('click', () => {
            setActiveIndex(currentIndex - 1);
        });

        nextButton?.addEventListener('click', () => {
            setActiveIndex(currentIndex + 1);
        });

        thumbButtons.forEach((button) => {
            button.addEventListener('click', () => {
                setActiveIndex(Number(button.dataset.galleryIndex || 0));
            });
        });

        gallery.addEventListener('keydown', (event) => {
            if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') return;

            event.preventDefault();
            setActiveIndex(currentIndex + (event.key === 'ArrowRight' ? 1 : -1));
        });

        if (lightboxEl && lightboxImg) {
            lightboxEl.querySelectorAll('[data-lightbox-close]').forEach((button) => {
                button.addEventListener('click', closeLightbox);
            });

            lightboxEl.querySelector('[data-lightbox-prev]')?.addEventListener('click', (event) => {
                event.stopPropagation();
                showLightboxImage(lightboxCurrentIndex - 1);
            });

            lightboxEl.querySelector('[data-lightbox-next]')?.addEventListener('click', (event) => {
                event.stopPropagation();
                showLightboxImage(lightboxCurrentIndex + 1);
            });

            document.addEventListener('keydown', (event) => {
                if (lightboxEl.classList.contains('hidden')) return;

                if (event.key === 'Escape') {
                    closeLightbox();
                    return;
                }

                if (event.key === 'ArrowLeft' && images.length > 1) {
                    showLightboxImage(lightboxCurrentIndex - 1);
                    return;
                }

                if (event.key === 'ArrowRight' && images.length > 1) {
                    showLightboxImage(lightboxCurrentIndex + 1);
                    return;
                }

                if (event.key === 'Tab') {
                    const controls = Array.from(lightboxEl.querySelectorAll('button:not([disabled])'));
                    if (controls.length === 0) return;

                    const first = controls[0];
                    const last = controls[controls.length - 1];

                    if (event.shiftKey && document.activeElement === first) {
                        event.preventDefault();
                        last.focus();
                    } else if (!event.shiftKey && document.activeElement === last) {
                        event.preventDefault();
                        first.focus();
                    }
                }
            });
        }

        setActiveIndex(0, { scrollThumb: false });
        updateControls();
    });
}

/**
 * Product detail tabs.
 */
function initProductTabs() {
    const tabButtons = Array.from(document.querySelectorAll('.tab-btn'));
    const tabContents = Array.from(document.querySelectorAll('.tab-content'));

    if (tabButtons.length === 0 || tabContents.length === 0) return;

    const activateTab = (button) => {
        const target = button.dataset.target;
        if (!target) return;

        tabButtons.forEach((tabButton) => {
            const isActive = tabButton === button;
            tabButton.classList.toggle('active', isActive);
            tabButton.classList.toggle('text-white', isActive);
            tabButton.classList.toggle('border-primary', isActive);
            tabButton.classList.toggle('text-gray-400', !isActive);
            tabButton.classList.toggle('border-transparent', !isActive);
            tabButton.setAttribute('aria-selected', String(isActive));
        });

        tabContents.forEach((content) => {
            content.classList.toggle('hidden', content.id !== target);
        });
    };

    tabButtons.forEach((button, index) => {
        button.addEventListener('click', () => activateTab(button));

        button.addEventListener('keydown', (event) => {
            if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') return;

            event.preventDefault();
            const offset = event.key === 'ArrowRight' ? 1 : -1;
            const nextButton = tabButtons[(index + offset + tabButtons.length) % tabButtons.length];
            nextButton.focus();
            activateTab(nextButton);
        });
    });
}

/**
 * Product variant selector.
 */
function initProductVariants() {
    const variantOptions = document.querySelectorAll('.variant-option');
    if (variantOptions.length === 0) return;

    const displayPrice = document.getElementById('display-price');
    const displayOriginal = document.getElementById('display-original-price');
    const displayDiscount = document.getElementById('display-discount');
    const hiddenInput = document.getElementById('selected-variant-id');
    const demoBtn = document.getElementById('product-demo-btn');
    const demoBtnText = document.getElementById('demo-btn-text');
    const toPriceNumber = (value) => {
        const parsedValue = Number.parseFloat(value || '0');

        return Number.isFinite(parsedValue) ? parsedValue : 0;
    };

    variantOptions.forEach((option) => {
        option.addEventListener('click', () => {
            const radio = option.querySelector('input[type="radio"]');
            if (!radio) return;

            radio.checked = true;

            if (hiddenInput) {
                hiddenInput.value = radio.value;
            }

            variantOptions.forEach((variantOption) => {
                const container = variantOption.querySelector('div');
                const dot = variantOption.querySelector('.w-5');
                const innerDot = variantOption.querySelector('.w-2\\.5');

                container?.classList.remove('border-primary', 'bg-primary/10');
                container?.classList.add('border-white/10', 'bg-white/5');
                dot?.classList.remove('border-primary');
                dot?.classList.add('border-gray-500');
                innerDot?.classList.remove('bg-primary');
                innerDot?.classList.add('bg-transparent');
            });

            const activeContainer = option.querySelector('div');
            const activeDot = option.querySelector('.w-5');
            const activeInnerDot = option.querySelector('.w-2\\.5');

            activeContainer?.classList.add('border-primary', 'bg-primary/10');
            activeContainer?.classList.remove('border-white/10', 'bg-white/5');
            activeDot?.classList.add('border-primary');
            activeDot?.classList.remove('border-gray-500');
            activeInnerDot?.classList.add('bg-primary');
            activeInnerDot?.classList.remove('bg-transparent');

            const isOnSale = radio.dataset.isOnSale === '1';
            const price = toPriceNumber(radio.dataset.price);
            const salePrice = toPriceNumber(radio.dataset.salePrice);
            const discount = toPriceNumber(radio.dataset.discount);
            const hasValidSale = isOnSale && salePrice > 0 && salePrice < price && discount > 0;

            if (displayPrice) {
                displayPrice.textContent = `$${(hasValidSale ? salePrice : price).toFixed(2)}`;
            }

            if (displayOriginal && displayDiscount) {
                displayOriginal.classList.toggle('hidden', !hasValidSale);
                displayDiscount.classList.toggle('hidden', !hasValidSale);

                if (hasValidSale) {
                    displayOriginal.textContent = `$${price.toFixed(2)}`;
                    displayDiscount.textContent = `-${Math.round(discount)}%`;
                } else {
                    displayOriginal.textContent = '';
                    displayDiscount.textContent = '';
                }
            }

            if (demoBtn) {
                const demoUrl = radio.dataset.demoUrl;

                if (demoUrl) {
                    demoBtn.href = demoUrl;
                    demoBtn.classList.remove('hidden');

                    if (demoBtnText) {
                        demoBtnText.textContent = `Xem Trước (${radio.dataset.name})`;
                    }
                } else {
                    demoBtn.classList.add('hidden');
                }
            }
        });
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

                window.setTimeout(() => {
                    button.textContent = originalLabel;
                }, 1800);
            } catch {
                target.focus();
                target.select?.();
            }
        });
    });
}

// Initialize everything on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    initSplitText();
    initScrollReveal();
    initSkillBars();
    initMobileNav();
    initSmoothScroll();
    initNavbarScroll();
    initCart();
    initTypingAnimation();
    initHomeSpotlight();
    initHomeParallax();
    initHomeScrollScene();
    initCodeStoreScene();
    initProductGallery();
    initProductTabs();
    initProductVariants();
    initCopyButtons();
});
