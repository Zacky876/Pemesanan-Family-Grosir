<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>

    @vite(['resources/css/admin/reports.css'])
</head>
<body>

<div class="report-container">

    <a href="{{ route('admin.reports.index') }}">← Kembali ke Laporan</a>

    <h2>Laporan Penjualan</h2>
    <p>Menampilkan ringkasan dan rincian penjualan YoungMart.</p>

    {{-- Filter Periode --}}
    <form action="{{ route('admin.reports.sales') }}" method="GET" class="filter-form">

        <div>
            <label for="period">Periode Laporan</label>
            <select name="period" id="period">
                <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>
                    Harian
                </option>

                <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>
                    Mingguan
                </option>

                <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>
                    Bulanan
                </option>
            </select>
        </div>

        <div>
            <label for="date">Tanggal Acuan</label>
            <input type="date" name="date" id="date" value="{{ $date }}">
        </div>

        <button type="submit">Tampilkan</button>

    </form>

    {{-- Ringkasan Penjualan --}}
    <h3>Ringkasan Penjualan</h3>

    <div class="report-grid">

        <div class="report-card">
            <h3>Total Penjualan</h3>
            <h2>Rp {{ number_format($totalSales, 0, ',', '.') }}</h2>
        </div>

        <div class="report-card">
            <h3>Total Transaksi</h3>
            <h2>{{ number_format($totalTransactions, 0, ',', '.') }}</h2>
        </div>

        <div class="report-card">
            <h3>Produk Terjual</h3>
            <h2>{{ number_format($totalProductsSold, 0, ',', '.') }}</h2>
        </div>

    </div>

    {{-- Penjualan Berdasarkan Produk --}}
    <h3>Penjualan Berdasarkan Produk</h3>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Jumlah Terjual</th>
                    <th>Total Penjualan</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($productSales as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->code }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->total_quantity }}</td>
                        <td>
                            Rp {{ number_format($product->total_sales, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-data">
                            Belum ada data penjualan produk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Penjualan Berdasarkan Kategori --}}
    <h3>Penjualan Berdasarkan Kategori</h3>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Jumlah Terjual</th>
                    <th>Total Penjualan</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($categorySales as $index => $category)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $category->category_name }}</td>
                        <td>{{ $category->total_quantity }}</td>
                        <td>
                            Rp {{ number_format($category->total_sales, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty-data">
                            Belum ada data penjualan kategori.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Penjualan Berdasarkan Kasir --}}
    <h3>Penjualan Berdasarkan Kasir</h3>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kasir</th>
                    <th>Jumlah Transaksi</th>
                    <th>Total Penjualan</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($cashierSales as $index => $cashier)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $cashier->cashier_name }}</td>
                        <td>{{ $cashier->total_transactions }}</td>
                        <td>
                            Rp {{ number_format($cashier->total_sales, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty-data">
                            Belum ada data penjualan kasir.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

</body>
</html>