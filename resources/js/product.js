import { motionDuration, prefersReducedMotion } from './motion';

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

function applyResponsiveImage(element, image, sizes) {
    element.src = image.src || image.url;
    element.alt = image.alt || '';
    element.sizes = sizes;

    if (image.srcset) {
        element.srcset = image.srcset;
        element.width = image.width;
        element.height = image.height;
    } else {
        element.removeAttribute('srcset');
        element.removeAttribute('width');
        element.removeAttribute('height');
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
        const lightboxFigure = lightboxEl?.querySelector('.product-lightbox__figure');

        let currentIndex = 0;
        let lightboxCurrentIndex = 0;
        let lastFocusedElement = null;
        let gallerySwapTimer = null;
        let gallerySettleTimer = null;
        let lightboxSwapTimer = null;
        let lightboxCloseTimer = null;

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
                if (mainImage.getAttribute('src') !== (image.src || image.url)) {
                    window.clearTimeout(gallerySwapTimer);
                    window.clearTimeout(gallerySettleTimer);
                    gallery.classList.add('is-changing');

                    const applyImage = () => {
                        applyResponsiveImage(mainImage, image, '(min-width: 1024px) 640px, 100vw');
                        gallerySettleTimer = window.setTimeout(() => {
                            gallery.classList.remove('is-changing');
                        }, motionDuration.long);
                    };

                    if (prefersReducedMotion()) {
                        applyImage();
                    } else {
                        gallerySwapTimer = window.setTimeout(applyImage, motionDuration.micro / 2);
                    }
                } else {
                    gallery.classList.remove('is-changing');
                    mainImage.alt = image.alt || '';
                }
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

        const renderLightbox = (animate = true) => {
            if (!lightboxImg) return;

            const image = images[lightboxCurrentIndex];
            const applyImage = () => {
                applyResponsiveImage(lightboxImg, image, '95vw');
            };

            window.clearTimeout(lightboxSwapTimer);

            if (!animate || prefersReducedMotion() || !lightboxImg.getAttribute('src')) {
                applyImage();
            } else if (lightboxImg.getAttribute('src') !== (image.src || image.url)) {
                lightboxFigure?.classList.add('is-changing');
                lightboxSwapTimer = window.setTimeout(applyImage, motionDuration.micro / 2);
            }

            if (lightboxCounter) {
                lightboxCounter.textContent = `${lightboxCurrentIndex + 1} / ${images.length}`;
            }

            if (lightboxCaption) {
                lightboxCaption.textContent = image.alt || '';
            }
        };

        const showLightboxImage = (index, options = {}) => {
            lightboxCurrentIndex = normalizeIndex(index);
            renderLightbox(options.animate !== false);
            setActiveIndex(lightboxCurrentIndex, { scrollThumb: true });
        };

        const openLightbox = (index) => {
            if (!lightboxEl || !lightboxImg) return;

            window.clearTimeout(lightboxCloseTimer);
            lastFocusedElement = document.activeElement;
            showLightboxImage(index, { animate: false });
            lightboxEl.classList.remove('hidden');
            lightboxEl.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
            requestAnimationFrame(() => {
                lightboxEl.classList.add('is-open');
                lightboxCloseButton?.focus({ preventScroll: true });
            });
        };

        const closeLightbox = () => {
            if (!lightboxEl || !lightboxImg) return;

            lightboxEl.setAttribute('aria-hidden', 'true');
            lightboxEl.classList.remove('is-open');
            document.body.classList.remove('overflow-hidden');

            const finishClose = () => {
                lightboxEl.classList.add('hidden');
                lightboxImg.removeAttribute('src');
                lightboxFigure?.classList.remove('is-changing');
                lastFocusedElement?.focus?.({ preventScroll: true });
            };

            if (prefersReducedMotion()) {
                finishClose();
            } else {
                lightboxCloseTimer = window.setTimeout(finishClose, motionDuration.short);
            }
        };

        mainImage?.addEventListener('load', () => {
            window.clearTimeout(gallerySettleTimer);
            requestAnimationFrame(() => gallery.classList.remove('is-changing'));
        });

        lightboxImg?.addEventListener('load', () => {
            requestAnimationFrame(() => lightboxFigure?.classList.remove('is-changing'));
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

    let transitionToken = 0;

    const activateTab = (button) => {
        const target = button.dataset.target;
        if (!target) return;

        const incomingContent = document.getElementById(target);
        const activeButton = tabButtons.find((tabButton) => tabButton.getAttribute('aria-selected') === 'true');

        if (!incomingContent || activeButton === button) return;

        transitionToken += 1;
        const currentToken = transitionToken;

        tabButtons.forEach((tabButton) => {
            const isActive = tabButton === button;
            tabButton.classList.toggle('active', isActive);
            tabButton.classList.toggle('text-white', isActive);
            tabButton.classList.toggle('border-primary', isActive);
            tabButton.classList.toggle('text-gray-400', !isActive);
            tabButton.classList.toggle('border-transparent', !isActive);
            tabButton.setAttribute('aria-selected', String(isActive));
            tabButton.setAttribute('tabindex', isActive ? '0' : '-1');
        });

        tabContents.forEach((content) => {
            if (content === incomingContent) {
                content.classList.remove('hidden', 'is-leaving');
                content.classList.add('is-entering');

                requestAnimationFrame(() => {
                    if (currentToken !== transitionToken) return;

                    content.classList.remove('is-entering');
                    content.classList.add('is-active');
                });
                return;
            }

            if (!content.classList.contains('hidden')) {
                content.classList.remove('is-active', 'is-entering');
                content.classList.add('is-leaving');

                window.setTimeout(() => {
                    if (currentToken !== transitionToken) return;

                    content.classList.add('hidden');
                    content.classList.remove('is-leaving');
                }, prefersReducedMotion() ? 0 : motionDuration.micro);
            }
        });
    };

    tabButtons.forEach((button) => {
        const isActive = button.getAttribute('aria-selected') === 'true';
        button.setAttribute('tabindex', isActive ? '0' : '-1');
    });

    tabContents.forEach((content) => {
        content.classList.toggle('is-active', !content.classList.contains('hidden'));
    });

    tabButtons.forEach((button, index) => {
        button.addEventListener('click', () => activateTab(button));

        button.addEventListener('keydown', (event) => {
            if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') return;

            event.preventDefault();
            const offset = event.key === 'ArrowRight' ? 1 : -1;
            const nextButton = tabButtons[(index + offset + tabButtons.length) % tabButtons.length];
            nextButton.focus({ preventScroll: true });
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
                displayPrice.classList.remove('is-updated');
                requestAnimationFrame(() => displayPrice.classList.add('is-updated'));
                window.setTimeout(() => displayPrice.classList.remove('is-updated'), motionDuration.short);
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

export function initProductDetail() {
    initProductGallery();
    initProductTabs();
    initProductVariants();
}
