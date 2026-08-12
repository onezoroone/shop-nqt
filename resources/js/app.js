import './bootstrap';

const reducedMotionMedia = window.matchMedia('(prefers-reduced-motion: reduce)');
const motionDuration = Object.freeze({
    micro: 120,
    short: 220,
    long: 420,
});

reducedMotionMedia.addEventListener('change', (event) => {
    document.documentElement.classList.toggle('motion-enabled', !event.matches);

    if (event.matches) {
        document.querySelectorAll('.motion-item').forEach((element) => element.classList.add('visible'));
        document.querySelector('.hm-hero')?.classList.add('is-entered');
    }
});

function prefersReducedMotion() {
    return reducedMotionMedia.matches;
}

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
        element.classList.add('motion-item', 'motion-item--hero');
        element.style.setProperty('--motion-order', String(index));
    });

    contentElements.forEach((element) => {
        element.classList.add('motion-item');

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

    const fontReadiness = document.fonts?.ready || Promise.resolve();
    const entranceDeadline = new Promise((resolve) => {
        window.setTimeout(resolve, motionDuration.long);
    });

    Promise.race([fontReadiness, entranceDeadline]).then(() => {
        requestAnimationFrame(() => requestAnimationFrame(enterHero));
    });

    if (window.matchMedia('(max-width: 39.999rem)').matches) {
        contentElements.forEach((element) => element.classList.add('visible'));
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
 * Lightweight animated release network for the Home hero background.
 */
function initHomeSignalField() {
    const canvas = document.querySelector('[data-home-signal-canvas]');
    const scene = canvas?.closest('[data-home-signal-field]');
    const hero = canvas?.closest('.home-command');

    if (!(canvas instanceof HTMLCanvasElement) || !scene || !hero) return;

    const context = canvas.getContext('2d', { alpha: true });
    if (!context) return;

    const precisePointerMedia = window.matchMedia('(hover: hover) and (pointer: fine)');
    const routes = [
        [0.46, 0.16, 0.68, 0.16],
        [0.68, 0.16, 0.68, 0.3],
        [0.68, 0.3, 0.92, 0.3],
        [0.53, 0.43, 0.84, 0.43],
        [0.84, 0.43, 0.84, 0.61],
        [0.84, 0.61, 0.96, 0.61],
        [0.42, 0.72, 0.64, 0.72],
        [0.64, 0.55, 0.64, 0.72],
        [0.64, 0.55, 0.76, 0.55],
        [0.76, 0.55, 0.76, 0.84],
        [0.76, 0.84, 0.94, 0.84],
        [0.9, 0.1, 0.9, 0.22],
    ];
    const packets = Array.from({ length: 9 }, (_, index) => ({
        route: index % routes.length,
        phase: (index * 0.173) % 1,
        speed: 0.000035 + ((index % 4) * 0.000008),
    }));
    const pointer = {
        x: 0.78,
        y: 0.42,
        targetX: 0.78,
        targetY: 0.42,
    };
    let width = 0;
    let height = 0;
    let animationFrame = null;
    let pointerFrame = null;
    let lastPaint = 0;
    let isVisible = true;

    const styles = getComputedStyle(hero);
    const accentColor = styles.getPropertyValue('--color-accent').trim();
    const focusColor = styles.getPropertyValue('--color-focus').trim();
    const ruleColor = styles.getPropertyValue('--color-rule-2').trim();

    const resize = () => {
        const bounds = scene.getBoundingClientRect();
        const pixelRatio = Math.min(window.devicePixelRatio || 1, 1.5);
        width = Math.max(1, bounds.width);
        height = Math.max(1, bounds.height);
        canvas.width = Math.round(width * pixelRatio);
        canvas.height = Math.round(height * pixelRatio);
        canvas.style.width = `${width}px`;
        canvas.style.height = `${height}px`;
        context.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);
        draw(performance.now());
    };

    const drawNode = (x, y, emphasis = false) => {
        const size = emphasis ? 5 : 3;
        context.globalAlpha = emphasis ? 0.82 : 0.38;
        context.strokeStyle = emphasis ? accentColor : focusColor;
        context.lineWidth = 1;
        context.strokeRect(x - size, y - size, size * 2, size * 2);
        context.fillStyle = emphasis ? accentColor : focusColor;
        context.fillRect(x - 1, y - 1, 2, 2);
    };

    const draw = (time) => {
        context.clearRect(0, 0, width, height);
        pointer.x += (pointer.targetX - pointer.x) * 0.065;
        pointer.y += (pointer.targetY - pointer.y) * 0.065;

        const orbitX = width * (0.8 + ((pointer.x - 0.5) * 0.035));
        const orbitY = height * (0.47 + ((pointer.y - 0.5) * 0.025));
        const orbitUnit = Math.min(width, height);

        context.save();
        context.strokeStyle = ruleColor;
        context.lineWidth = 1;
        [0.14, 0.23, 0.32].forEach((radiusScale, index) => {
            context.globalAlpha = 0.22 - (index * 0.035);
            context.setLineDash([18 + (index * 6), 12 + (index * 4)]);
            context.lineDashOffset = (time * 0.008 * (index % 2 === 0 ? -1 : 1));
            context.beginPath();
            context.arc(orbitX, orbitY, orbitUnit * radiusScale, -0.88, Math.PI * 1.58);
            context.stroke();
        });
        context.restore();

        context.save();
        context.setLineDash([]);
        routes.forEach((route, index) => {
            const [startX, startY, endX, endY] = route;
            context.globalAlpha = index % 3 === 0 ? 0.34 : 0.2;
            context.strokeStyle = index % 3 === 0 ? focusColor : ruleColor;
            context.lineWidth = 1;
            context.beginPath();
            context.moveTo(startX * width, startY * height);
            context.lineTo(endX * width, endY * height);
            context.stroke();

            drawNode(startX * width, startY * height, index % 4 === 0);
            if (index === 2 || index === 5 || index === 10) {
                drawNode(endX * width, endY * height, true);
            }
        });

        packets.forEach((packet) => {
            const route = routes[packet.route];
            const progress = (packet.phase + (time * packet.speed)) % 1;
            const x = (route[0] + ((route[2] - route[0]) * progress)) * width;
            const y = (route[1] + ((route[3] - route[1]) * progress)) * height;
            const isHorizontal = route[1] === route[3];

            context.globalAlpha = 0.82;
            context.fillStyle = packet.route % 3 === 0 ? accentColor : focusColor;
            context.fillRect(x - (isHorizontal ? 6 : 1.5), y - (isHorizontal ? 1.5 : 6), isHorizontal ? 12 : 3, isHorizontal ? 3 : 12);
        });
        context.restore();
    };

    const render = (time) => {
        if (!isVisible || document.hidden || prefersReducedMotion()) {
            animationFrame = null;
            return;
        }

        if (time - lastPaint >= 24) {
            draw(time);
            lastPaint = time;
        }

        animationFrame = requestAnimationFrame(render);
    };

    const start = () => {
        if (!animationFrame && isVisible && !document.hidden && !prefersReducedMotion()) {
            animationFrame = requestAnimationFrame(render);
        }
    };

    const stop = () => {
        if (animationFrame) cancelAnimationFrame(animationFrame);
        animationFrame = null;
    };

    const updatePointer = (event) => {
        if (!precisePointerMedia.matches || prefersReducedMotion()) return;

        const bounds = hero.getBoundingClientRect();
        pointer.targetX = Math.min(1, Math.max(0, (event.clientX - bounds.left) / bounds.width));
        pointer.targetY = Math.min(1, Math.max(0, (event.clientY - bounds.top) / bounds.height));

        if (!pointerFrame) {
            pointerFrame = requestAnimationFrame(() => {
                hero.style.setProperty('--hero-pointer-x', `${(pointer.targetX * 100).toFixed(2)}%`);
                hero.style.setProperty('--hero-pointer-y', `${(pointer.targetY * 100).toFixed(2)}%`);
                pointerFrame = null;
            });
        }
    };

    const resetPointer = () => {
        pointer.targetX = 0.78;
        pointer.targetY = 0.42;
        hero.style.setProperty('--hero-pointer-x', '78%');
        hero.style.setProperty('--hero-pointer-y', '42%');
    };

    const visibilityObserver = new IntersectionObserver(([entry]) => {
        isVisible = entry.isIntersecting;
        if (isVisible) {
            start();
        } else {
            stop();
        }
    }, { rootMargin: '120px 0px' });

    new ResizeObserver(resize).observe(scene);
    visibilityObserver.observe(hero);
    hero.addEventListener('pointermove', updatePointer, { passive: true });
    hero.addEventListener('pointerleave', resetPointer);
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stop();
        } else {
            start();
        }
    });
    reducedMotionMedia.addEventListener('change', () => {
        if (prefersReducedMotion()) {
            stop();
            resetPointer();
            draw(0);
        } else {
            start();
        }
    });

    resize();
    if (prefersReducedMotion()) {
        draw(0);
    } else {
        start();
    }
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
                if (mainImage.getAttribute('src') !== image.url) {
                    window.clearTimeout(gallerySwapTimer);
                    window.clearTimeout(gallerySettleTimer);
                    gallery.classList.add('is-changing');

                    const applyImage = () => {
                        mainImage.src = image.url;
                        mainImage.alt = image.alt || '';
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
                lightboxImg.src = image.url;
                lightboxImg.alt = image.alt || '';
            };

            window.clearTimeout(lightboxSwapTimer);

            if (!animate || prefersReducedMotion() || !lightboxImg.getAttribute('src')) {
                applyImage();
            } else if (lightboxImg.getAttribute('src') !== image.url) {
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
    initHomeSignalField();
    initPointerSignal();
    initPointerMotion();
    initScrollMotion();
    initMobileNav();
    initUserMenu();
    initSmoothScroll();
    initNavbarScroll();
    initCart();
    initNavSearchShortcut();
    initProductGallery();
    initProductTabs();
    initProductVariants();
    initCopyButtons();
    initPasswordToggles();
});
