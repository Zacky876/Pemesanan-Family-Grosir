@extends('admin.layouts.app')

@section('title', 'Manajemen Stok')
@section('page_title', 'Manajemen Stok')
@section('page_subtitle', 'Kelola stok produk minimarket.')

@push('styles')
    @vite('resources/css/admin/stock.css')
@endpush

@section('content')
@php
    $lowStockProducts = $products->filter(fn ($product) => $product->stock <= $product->minimum_stock);
@endphp

<div class="stock-page">

    {{-- Pesan sukses/error ditampilkan otomatis oleh layout --}}

    <div class="stock-toolbar">
        <span class="stock-hint" style="margin:0;">
            {{ $products->count() }} produk
        </span>

        <a href="{{ route('admin.products.index') }}" class="stock-btn stock-btn--ghost">
            Kelola produk
        </a>
    </div>

    {{-- ===== Peringatan stok menipis ===== --}}
    @if ($lowStockProducts->isNotEmpty())
        <div class="stock-alert" role="alert">
            <strong>Perhatian! Ada {{ $lowStockProducts->count() }} produk dengan stok hampir habis.</strong>

            <ul>
                @foreach ($lowStockProducts as $product)
                    <li>
                        {{ $product->name }}:
                        stok {{ $product->stock }},
                        minimum {{ $product->minimum_stock }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ===== Tabel stok ===== --}}
    <div class="stock-table-wrap">
        <table class="stock-table stock-table--wide">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Stok minimum</th>
                    <th>Satuan</th>
                    <th>Status stok</th>
                    <th>Status produk</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($products as $product)
                    @php $isLow = $product->stock <= $product->minimum_stock; @endphp

                    <tr class="{{ $isLow ? 'is-low' : '' }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->code }}</td>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td><strong>{{ $product->stock }}</strong></td>
                        <td>{{ $product->minimum_stock }}</td>
                        <td>{{ $product->unit }}</td>

                        <td>
                            @if ($isLow)
                                <span class="stock-badge stock-badge--danger">Hampir habis</span>
                            @else
                                <span class="stock-badge stock-badge--safe">Aman</span>
                            @endif
                        </td>

                        <td>
                            @if ($product->status == 'active')
                                <span class="stock-badge stock-badge--safe">Aktif</span>
                            @else
                                <span class="stock-badge stock-badge--muted">Tidak aktif</span>
                            @endif
                        </td>

                        <td>
                            <div class="stock-row-actions">
                                <a href="{{ route('admin.stock.create', $product) }}"
                                   class="stock-btn stock-btn--small">Ubah stok</a>

                                <a href="{{ route('admin.stock.history', $product) }}"
                                   class="stock-btn stock-btn--ghost stock-btn--small">Riwayat</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="stock-empty">Belum ada produk.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>
@endsection