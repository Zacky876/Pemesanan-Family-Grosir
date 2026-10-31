<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Admin</title>

    @vite(['resources/css/reports.css'])
</head>
<body>

<div class="report-container">

    <h2>Laporan Admin</h2>
    <p>Pilih jenis laporan yang ingin dilihat.</p>

    <div class="report-grid">

        <a href="{{ route('admin.reports.sales') }}" class="report-card">
            <h3>Laporan Penjualan</h3>
            <p>Melihat laporan penjualan harian, mingguan, bulanan,
                berdasarkan produk, kategori, dan kasir.</p>
        </a>

        <a href="{{ route('admin.reports.stock') }}" class="report-card">
            <h3>Laporan Stok</h3>
            <p>Melihat stok tersedia, produk hampir habis,
                dan produk yang sudah habis.</p>
        </a>

    </div>

    <a href="{{ route('admin.dashboard') }}">Kembali ke Dashboard</a>

</div>

</body>
</html>