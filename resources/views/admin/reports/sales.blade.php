@extends('admin.layouts.app')

@section('title', 'Laporan Penjualan')
@section('page_title', 'Laporan Penjualan')
@section('page_subtitle', 'Menampilkan ringkasan dan rincian penjualan YoungMart.')

@push('styles')
    @vite('resources/css/admin/reports.css')
@endpush

@section('content')
@php
    $periodLabels = ['daily' => 'Harian', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan'];

    // Nilai terbesar dipakai untuk panjang batang proporsi
    $maxProduct  = max(1, (float) collect($productSales)->max('total_sales'));
    $maxCategory = max(1, (float) collect($categorySales)->max('total_sales'));
    $maxCashier  = max(1, (float) collect($cashierSales)->max('total_sales'));
@endphp

<div class="report-page">

    <div class="report-toolbar">
        <a href="{{ route('admin.reports.index') }}" class="report-btn report-btn--ghost">
            &larr; Kembali ke laporan
        </a>

        <div class="report-toolbar-actions">
            <button type="button" class="report-btn" onclick="window.print()">Cetak</button>
        </div>
    </div>

    {{-- ===== Filter periode ===== --}}
    <form action="{{ route('admin.reports.sales') }}" method="GET"
          class="report-card report-filter">

        <div class="report-field">
            <label for="period">Periode laporan</label>
            <select name="period" id="period">
                @foreach ($periodLabels as $value => $label)
                    <option value="{{ $value }}" {{ $period == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="report-field">
            <label for="date">Tanggal acuan</label>
            <input type="date" name="date" id="date" value="{{ $date }}">
        </div>

        <button type="submit" class="report-btn">Tampilkan</button>

    </form>

    <span class="report-hint">
        Laporan {{ strtolower($periodLabels[$period] ?? $period) }}
        dengan tanggal acuan {{ \Illuminate\Support\Carbon::parse($date)->format('d-m-Y') }}
    </span>

    {{-- ===== Ringkasan penjualan ===== --}}
    <div class="report-summary">

        <div class="report-tile report-tile--accent">
            <span>Total penjualan</span>
            <strong>Rp {{ number_format($totalSales, 0, ',', '.') }}</strong>
        </div>

        <div class="report-tile">
            <span>Total transaksi</span>
            <strong>{{ number_format($totalTransactions, 0, ',', '.') }}</strong>
        </div>

        <div class="report-tile">
            <span>Produk terjual</span>
            <strong>{{ number_format($totalProductsSold, 0, ',', '.') }}</strong>
        </div>

    </div>

    {{-- ===== Penjualan berdasarkan produk ===== --}}
    <section class="report-card report-section">
        <div class="report-section-head">
            <h2>Penjualan berdasarkan produk</h2>
            <p>Produk diurutkan sesuai data dari laporan.</p>
        </div>

        <div class="report-table-wrap">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode produk</th>
                        <th>Nama produk</th>
                        <th class="is-num">Jumlah terjual</th>
                        <th class="is-num">Total penjualan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($productSales as $index => $product)
                        <tr>
                            <td><span class="report-rank">{{ $index + 1 }}</span></td>
                            <td><span class="report-code">{{ $product->code }}</span></td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td class="is-num">{{ $product->total_quantity }}</td>
                            <td class="is-bar">
                                <span class="report-amount">Rp {{ number_format($product->total_sales, 0, ',', '.') }}</span>
                                <span class="report-bar"><i style="width: {{ round($product->total_sales / $maxProduct * 100) }}%"></i></span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="report-empty">Belum ada data penjualan produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- ===== Penjualan berdasarkan kategori ===== --}}
    <section class="report-card report-section">
        <div class="report-section-head">
            <h2>Penjualan berdasarkan kategori</h2>
            <p>Perbandingan penjualan tiap kategori produk.</p>
        </div>

        <div class="report-table-wrap">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th class="is-num">Jumlah terjual</th>
                        <th class="is-num">Total penjualan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($categorySales as $index => $category)
                        <tr>
                            <td><span class="report-rank">{{ $index + 1 }}</span></td>
                            <td><strong>{{ $category->category_name }}</strong></td>
                            <td class="is-num">{{ $category->total_quantity }}</td>
                            <td class="is-bar">
                                <span class="report-amount">Rp {{ number_format($category->total_sales, 0, ',', '.') }}</span>
                                <span class="report-bar"><i style="width: {{ round($category->total_sales / $maxCategory * 100) }}%"></i></span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="report-empty">Belum ada data penjualan kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- ===== Penjualan berdasarkan kasir ===== --}}
    <section class="report-card report-section">
        <div class="report-section-head">
            <h2>Penjualan berdasarkan kasir</h2>
            <p>Jumlah transaksi dan nilai penjualan tiap kasir.</p>
        </div>

        <div class="report-table-wrap">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama kasir</th>
                        <th class="is-num">Jumlah transaksi</th>
                        <th class="is-num">Total penjualan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($cashierSales as $index => $cashier)
                        <tr>
                            <td><span class="report-rank">{{ $index + 1 }}</span></td>
                            <td><strong>{{ $cashier->cashier_name }}</strong></td>
                            <td class="is-num">{{ $cashier->total_transactions }}</td>
                            <td class="is-bar">
                                <span class="report-amount">Rp {{ number_format($cashier->total_sales, 0, ',', '.') }}</span>
                                <span class="report-bar"><i style="width: {{ round($cashier->total_sales / $maxCashier * 100) }}%"></i></span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="report-empty">Belum ada data penjualan kasir.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</div>
@endsection