document.addEventListener('DOMContentLoaded', () => {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const hasMouse = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    // Cahaya mengikuti kursor hanya untuk perangkat dengan mouse
    if (reduceMotion || !hasMouse) return;

    document.querySelectorAll('.card').forEach((card) => {
        card.addEventListener('pointermove', (e) => {
            const rect = card.getBoundingClientRect();

            card.style.setProperty('--mx', `${e.clientX - rect.left}px`);
            card.style.setProperty('--my', `${e.clientY - rect.top}px`);
        });
    });
});