// Grafik dashboard admin.
// Data dikirim dari Blade lewat <script type="application/json" id="dashboard-data">.
// Library Chart.js dimuat dari CDN sebelum file ini (lihat dashboard.blade.php).

document.addEventListener('DOMContentLoaded', () => {
    const dataEl = document.getElementById('dashboard-data');

    if (!dataEl || typeof Chart === 'undefined') {
        return;
    }

    const d = JSON.parse(dataEl.textContent);

    // Palet warna (sama dengan tema login dan sidebar)
    const biru = '#2347ff';
    const navy = '#0c2a7a';
    const oranye = '#ff7a2f';
    const sky = '#38bdf8';
    const grid = '#e6ebfa';

    Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
    Chart.defaults.color = '#5d6b8f';
    Chart.defaults.maintainAspectRatio = false;

    const legendBawah = {
        position: 'bottom',
        labels: { usePointStyle: true, boxWidth: 8, padding: 14 },
    };

    const sumbu = {
        x: { grid: { display: false } },
        y: { grid: { color: grid }, border: { display: false }, beginAtZero: true },
    };

    const buat = (id, config) => {
        const el = document.getElementById(id);
        if (el) new Chart(el, config);
    };

    // Penjualan 7 bulan terakhir
    buat('chartPenjualan', {
        type: 'line',
        data: {
            labels: d.labelBulan,
            datasets: [{
                label: 'Penjualan (juta Rp)',
                data: d.dataPenjualan,
                borderColor: biru,
                backgroundColor: (ctx) => {
                    const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 270);
                    g.addColorStop(0, 'rgba(35,71,255,.30)');
                    g.addColorStop(1, 'rgba(56,189,248,0)');
                    return g;
                },
                fill: true,
                tension: 0.38,
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: biru,
                pointBorderWidth: 2,
            }],
        },
        options: { plugins: { legend: { display: false } }, scales: sumbu },
    });

    // Produk per kategori
    buat('chartKategori', {
        type: 'doughnut',
        data: {
            labels: d.kategoriLabel,
            datasets: [{
                data: d.kategoriData,
                backgroundColor: [biru, sky, oranye, navy, '#b8c6ff'],
                borderWidth: 3,
                borderColor: '#fff',
            }],
        },
        options: { cutout: '62%', plugins: { legend: legendBawah } },
    });

    // Transaksi per hari
    buat('chartTransaksi', {
        type: 'bar',
        data: {
            labels: d.labelHari,
            datasets: [{
                label: 'Transaksi',
                data: d.dataTransaksi,
                backgroundColor: (ctx) => {
                    const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 240);
                    g.addColorStop(0, sky);
                    g.addColorStop(1, biru);
                    return g;
                },
                borderRadius: 8,
                borderSkipped: false,
                maxBarThickness: 28,
            }],
        },
        options: { plugins: { legend: { display: false } }, scales: sumbu },
    });

    // Metode pembayaran
    buat('chartPembayaran', {
        type: 'doughnut',
        data: {
            labels: d.metodeLabel,
            datasets: [{
                data: d.metodeData,
                backgroundColor: [navy, biru, sky],
                borderWidth: 3,
                borderColor: '#fff',
            }],
        },
        options: { cutout: '62%', plugins: { legend: legendBawah } },
    });

    // Pendapatan dan laba
    buat('chartLaba', {
        type: 'line',
        data: {
            labels: d.labelMinggu,
            datasets: [
                {
                    label: 'Pendapatan',
                    data: d.dataPendapatan,
                    borderColor: biru,
                    backgroundColor: 'rgba(35,71,255,.08)',
                    fill: true,
                    tension: 0.38,
                    borderWidth: 3,
                    pointRadius: 3,
                    pointBackgroundColor: biru,
                },
                {
                    label: 'Laba',
                    data: d.dataLaba,
                    borderColor: oranye,
                    backgroundColor: 'rgba(255,122,47,.08)',
                    fill: true,
                    tension: 0.38,
                    borderWidth: 3,
                    pointRadius: 3,
                    pointBackgroundColor: oranye,
                },
            ],
        },
        options: {
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'top', align: 'end', labels: { usePointStyle: true, boxWidth: 8 } } },
            scales: sumbu,
        },
    });

    // Target bulan ini
    buat('chartTarget', {
        type: 'doughnut',
        data: {
            labels: ['Tercapai', 'Sisa target'],
            datasets: [{
                data: [d.penjualanBulan, d.sisaTarget],
                backgroundColor: [biru, '#e4ebff'],
                borderWidth: 0,
            }],
        },
        options: { cutout: '78%', plugins: { legend: { display: false } } },
    });

    // Produk terlaris
    buat('chartTerlaris', {
        type: 'bar',
        data: {
            labels: d.terlarisLabel,
            datasets: [{
                label: 'Terjual',
                data: d.terlarisData,
                backgroundColor: (ctx) => {
                    const g = ctx.chart.ctx.createLinearGradient(0, 0, 260, 0);
                    g.addColorStop(0, sky);
                    g.addColorStop(1, biru);
                    return g;
                },
                borderRadius: 8,
                borderSkipped: false,
                maxBarThickness: 18,
            }],
        },
        options: {
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: grid }, border: { display: false }, beginAtZero: true },
                y: { grid: { display: false }, ticks: { font: { size: 11.5 } } },
            },
        },
    });
});