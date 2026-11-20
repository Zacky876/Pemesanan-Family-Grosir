// Menghitung "Stok setelah perubahan" secara langsung di halaman Ubah Stok.
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('stockForm');
    if (!form) return;

    const current = Number(form.dataset.current) || 0;
    const unit = form.dataset.unit || '';
    const typeEl = document.getElementById('type');
    const qtyEl = document.getElementById('quantity');
    const afterEl = document.getElementById('stockAfter');
    const hintEl = document.getElementById('quantityHint');

    const hints = {
        in: 'Isi jumlah barang yang masuk. Stok akan bertambah.',
        out: 'Isi jumlah barang yang keluar. Stok akan berkurang.',
        adjustment: 'Isi jumlah stok fisik terbaru hasil stok opname.',
    };

    function update() {
        const type = typeEl.value;
        const qty = Number(qtyEl.value);

        if (hintEl) hintEl.textContent = hints[type] || '';

        if (qtyEl.value === '' || Number.isNaN(qty)) {
            afterEl.textContent = '-';
            afterEl.classList.remove('is-negative');
            return;
        }

        let after = current;
        if (type === 'in') after = current + qty;
        if (type === 'out') after = current - qty;
        if (type === 'adjustment') after = qty;

        afterEl.textContent = `${after} ${unit}`;
        afterEl.classList.toggle('is-negative', after < 0);
    }

    typeEl.addEventListener('change', update);
    qtyEl.addEventListener('input', update);
    update();
});