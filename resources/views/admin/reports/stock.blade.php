@extends('admin.layouts.app')

@section('title', 'Laporan Stok')
@section('page_title', 'Laporan Stok Produk')
@section('page_subtitle', 'Menampilkan informasi ketersediaan stok seluruh produk YoungMart.')

@push('styles')
    @vite('resources/css/admin/reports.css')
@endpush

@section('content')
@php
    // Hitung komposisi dari daftar produk supaya sama dengan status di tabel
    $jumlahHabis   = $products->filter(fn ($p) => $p->stock <= 0)->count();
    $jumlahMenipis = $products->filter(fn ($p) => $p->stock > 0 && $p->stock <= $p->minimum_stock)->count();
    $jumlahAman    = $products->count() - $jumlahHabis - $jumlahMenipis;
    $semua         = max(1, $products->count());
@endphp

<div class="report-page">

    <div class="report-toolbar">
        <a href="{{ route('admin.reports.index') }}" class="report-btn report-btn--ghost">
            &larr; Kembali ke laporan
        </a>

        <div class="report-toolbar-actions">
            <a href="{{ route('admin.stock.index') }}" class="report-btn report-btn--ghost">Kelola stok</a>
            <button type="button" class="report-btn" onclick="window.print()">Cetak</button>
        </div>
    </div>

    {{-- ===== Ringkasan stok ===== --}}
    <div class="report-summary">

        <div class="report-tile report-tile--accent">
            <span>Total produk</span>
            <strong>{{ $totalProducts }}</strong>
        </div>

        <div class="report-tile report-tile--ok">
            <span>Stok tersedia</span>
            <strong>{{ $availableProducts }}</strong>
        </div>

        <div class="report-tile report-tile--low">
            <span>Stok menipis</span>
            <strong>{{ $lowStockProducts }}</strong>
        </div>

        <div class="report-tile report-tile--out">
            <span>Stok habis</span>
            <strong>{{ $outOfStockProducts }}</strong>
        </div>

    </div>

    {{-- ===== Komposisi stok ===== --}}
    @if ($products->count() > 0)
        <section class="report-card report-compose">
            <h2>Komposisi stok produk</h2>

            <div class="report-compose-bar" role="img"
                 aria-label="Aman {{ $jumlahAman }}, menipis {{ $jumlahMenipis }}, habis {{ $jumlahHabis }}">
                <i class="is-ok"  style="width: {{ $jumlahAman / $semua * 100 }}%"></i>
                <i class="is-low" style="width: {{ $jumlahMenipis / $semua * 100 }}%"></i>
                <i class="is-out" style="width: {{ $jumlahHabis / $semua * 100 }}%"></i>
            </div>

            <div class="report-legend">
                <span><i class="is-ok"></i>Tersedia <b>{{ $jumlahAman }}</b></span>
                <span><i class="is-low"></i>Menipis <b>{{ $jumlahMenipis }}</b></span>
                <span><i class="is-out"></i>Habis <b>{{ $jumlahHabis }}</b></span>
            </div>
        </section>
    @endif

    {{-- ===== Tabel stok produk ===== --}}
    <section class="report-card report-section">
        <div class="report-section-head">
            <h2>Daftar stok produk</h2>
            <p>Produk dengan stok menipis atau habis ditandai warna.</p>
        </div>

        <div class="report-table-wrap">
            <table class="report-table report-table--wide">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode produk</th>
                        <th>Nama produk</th>
                        <th>Kategori</th>
                        <th class="is-num">Stok</th>
                        <th class="is-num">Stok minimum</th>
                        <th>Satuan</th>
                        <th>Status stok</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($products as $index => $product)
                        @php
                            $kelasBaris = $product->stock <= 0
                                ? 'is-out'
                                : ($product->stock <= $product->minimum_stock ? 'is-low' : '');
                        @endphp

                        <tr class="{{ $kelasBaris }}">
                            <td>{{ $index + 1 }}</td>
                            <td><span class="report-code">{{ $product->code }}</span></td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td class="is-num"><strong>{{ $product->stock }}</strong></td>
                            <td class="is-num">{{ $product->minimum_stock }}</td>
                            <td>{{ $product->unit }}</td>

                            <td>
                                @if ($product->stock <= 0)
                                    <span class="report-badge report-badge--out">Stok habis</span>
                                @elseif ($product->stock <= $product->minimum_stock)
                                    <span class="report-badge report-badge--low">Stok menipis</span>
                                @else
                                    <span class="report-badge report-badge--ok">Tersedia</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="report-empty">Belum ada data produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</div>
@endsection