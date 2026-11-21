document.addEventListener('DOMContentLoaded', () => {
    /* ===== MENU MASUK / DAFTAR ===== */
    const tabs = document.querySelector('.auth-tabs');
    const tabButtons = document.querySelectorAll('.tab-btn');
    const panes = {
        login: document.getElementById('paneLogin'),
        register: document.getElementById('paneRegister'),
    };

    const showTab = (name) => {
        if (!panes[name] || !tabs) return;

        tabs.dataset.active = name;

        tabButtons.forEach((btn) => {
            const active = btn.dataset.tab === name;
            btn.classList.toggle('active', active);
            btn.setAttribute('aria-selected', active ? 'true' : 'false');
        });

        Object.entries(panes).forEach(([key, pane]) => {
            if (pane) pane.hidden = key !== name;
        });

        document.title = name === 'register' ? 'Daftar - Youngmart' : 'Masuk - Youngmart';
    };

    tabButtons.forEach((btn) => {
        btn.addEventListener('click', () => showTab(btn.dataset.tab));
    });

    // Tautan "Daftar sekarang" / "Masuk di sini" ikut memakai tab
    document.querySelectorAll('[data-switch]').forEach((link) => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            showTab(link.dataset.switch);
        });
    });
});