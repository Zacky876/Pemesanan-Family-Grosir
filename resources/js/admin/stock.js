const form = document.getElementById('stockForm');

if (form) {
    const current = Number(form.dataset.current) || 0;
    const unit = form.dataset.unit || '';

    const type = document.getElementById('type');
    const qty = document.getElementById('quantity');
    const hint = document.getElementById('quantityHint');
    const after = document.getElementById('stockAfter');

    const hints = {
        in: 'Isi jumlah stok yang masuk.',
        out: 'Isi jumlah stok yang keluar.',
        adjustment: 'Isi jumlah stok fisik terbaru hasil perhitungan.',
    };

    function update() {
        if (hint) hint.textContent = hints[type.value] || '';

        const q = parseInt(qty.value, 10);

        if (Number.isNaN(q)) {
            after.textContent = '-';
            after.classList.remove('is-negative');
            return;
        }

        let result = q;
        if (type.value === 'in') result = current + q;
        if (type.value === 'out') result = current - q;

        after.textContent = `${result} ${unit}`;
        after.classList.toggle('is-negative', result < 0);
    }

    type.addEventListener('change', update);
    qty.addEventListener('input', update);
    update();
}