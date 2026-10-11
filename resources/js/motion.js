export const reducedMotionMedia = window.matchMedia('(prefers-reduced-motion: reduce)');
export const motionDuration = Object.freeze({
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

export function prefersReducedMotion() {
    return reducedMotionMedia.matches;
}

