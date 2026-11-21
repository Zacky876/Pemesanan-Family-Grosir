const body = document.body;
const toggle = document.getElementById('menuToggle');
const overlay = document.getElementById('sidebarOverlay');

function setSidebar(open) {
    body.classList.toggle('sidebar-open', open);
    toggle?.setAttribute('aria-expanded', String(open));
    toggle?.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
}

toggle?.addEventListener('click', () => {
    setSidebar(!body.classList.contains('sidebar-open'));
});

overlay?.addEventListener('click', () => setSidebar(false));

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') setSidebar(false);
});

// Tutup sidebar otomatis kalau layar diperlebar ke ukuran desktop
window.matchMedia('(min-width: 961px)').addEventListener('change', (e) => {
    if (e.matches) setSidebar(false);
});