import { reducedMotionMedia, prefersReducedMotion } from './motion';

/**
 * Lightweight animated release network for the Home hero background.
 */
export function initHomeSignalField() {
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

