<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok</title>

    @vite(['resources/css/reports.css'])
</head>
<body>

<div class="report-container">

    <a href="{{ route('admin.reports.index') }}">← Kembali ke Laporan</a>

    <h2>Laporan Stok Produk</h2>
    <p>Menampilkan informasi ketersediaan stok seluruh produk YoungMart.</p>

    {{-- Ringkasan Stok --}}
    <div class="report-grid">

        <div class="report-card">
            <h3>Total Produk</h3>
            <h2>{{ $totalProducts }}</h2>
        </div>

        <div class="report-card">
            <h3>Stok Tersedia</h3>
            <h2>{{ $availableProducts }}</h2>
        </div>

        <div class="report-card">
            <h3>Stok Menipis</h3>
            <h2>{{ $lowStockProducts }}</h2>
        </div>

        <div class="report-card">
            <h3>Stok Habis</h3>
            <h2>{{ $outOfStockProducts }}</h2>
        </div>

    </div>

    {{-- Tabel Stok Produk --}}
    <h3>Daftar Stok Produk</h3>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Stok Minimum</th>
                    <th>Satuan</th>
                    <th>Status Stok</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($products as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->code }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->minimum_stock }}</td>
                        <td>{{ $product->unit }}</td>

                        <td>
                            @if ($product->stock <= 0)
                                <span class="stock-status out">
                                    Stok Habis
                                </span>
                            @elseif ($product->stock <= $product->minimum_stock)
                                <span class="stock-status low">
                                    Stok Menipis
                                </span>
                            @else
                                <span class="stock-status available">
                                    Tersedia
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-data">
                            Belum ada data produk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

</body>
</html>