import Chart from 'chart.js/auto';

// Format Rupiah
const rupiah = value =>
    'Rp ' + new Intl.NumberFormat('id-ID').format(value);

// Membaca data dari Blade
const getData = canvas => ({
    labels: JSON.parse(canvas.dataset.labels || '[]'),
    values: JSON.parse(canvas.dataset.values || '[]')
});

// Menampilkan pesan kosong
const emptyChart = canvas => {
    canvas.parentElement.innerHTML =
        '<div class="chart-empty">Belum ada data.</div>';
};

// ===================================
// GRAFIK PENDAPATAN
// ===================================

const revenueCanvas = document.getElementById('revenueChart');

if (revenueCanvas) {
    const { labels, values } = getData(revenueCanvas);

    if (values.length === 0) {
        emptyChart(revenueCanvas);
    } else {
        new Chart(revenueCanvas, {
            type: 'line',

            data: {
                labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: values,
                    borderColor: '#3867f5',
                    backgroundColor: 'rgba(56,103,245,0.12)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointHoverRadius: 7
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                interaction: {
                    intersect: false,
                    mode: 'index'
                },

                plugins: {
                    legend: {
                        position: 'top'
                    },

                    tooltip: {
                        callbacks: {
                            label: context =>
                                'Pendapatan: ' + rupiah(context.raw)
                        }
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true,

                        ticks: {
                            callback: value =>
                                'Rp ' + new Intl.NumberFormat('id-ID', {
                                    notation: 'compact'
                                }).format(value)
                        },

                        grid: {
                            color: '#f0f2f5'
                        }
                    },

                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
}

// ===================================
// GRAFIK KATEGORI TERLARIS
// ===================================

const categoryCanvas = document.getElementById('categoryChart');

if (categoryCanvas) {
    const { labels, values } = getData(categoryCanvas);

    if (labels.length === 0 || values.length === 0) {
        emptyChart(categoryCanvas);
    } else {
        new Chart(categoryCanvas, {
            type: 'doughnut',

            data: {
                labels,
                datasets: [{
                    data: values,

                    backgroundColor: [
                        '#3867f5',
                        '#13b8a6',
                        '#ff9635',
                        '#8b5cf6',
                        '#ec4899',
                        '#facc15',
                        '#14b8a6',
                        '#64748b',
                        '#f97316',
                        '#06b6d4'
                    ],

                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 8
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',

                plugins: {
                    legend: {
                        position: 'bottom',

                        labels: {
                            padding: 15,
                            usePointStyle: true
                        }
                    },

                    tooltip: {
                        callbacks: {
                            label: context =>
                                `${context.label}: ${context.raw} produk terjual`
                        }
                    }
                }
            }
        });
    }
}