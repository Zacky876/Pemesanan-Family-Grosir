@extends('admin.layouts.app')

@section('title', 'Detail Produk')
@section('page_title', 'Detail Produk')
@section('page_subtitle', 'Informasi lengkap produk.')

@push('styles')
    @vite('resources/css/admin/products.css')
@endpush

@section('content')
<div class="product-page">

    <div class="product-toolbar">
        <a href="{{ route('admin.products.index') }}" class="product-btn product-btn--ghost">
            Kembali
        </a>

        <a href="{{ route('admin.products.edit', $product) }}" class="product-btn">
            Edit produk
        </a>
    </div>

    <div class="product-card product-detail">

        {{-- Foto --}}
        <div>
            @if ($product->photo)
                <img src="{{ asset('storage/' . $product->photo) }}"
                     alt="{{ $product->name }}"
                     class="product-photo product-photo--large">
            @else
                <div class="product-photo-empty">Belum ada foto</div>
            @endif
        </div>

        {{-- Informasi --}}
        <dl class="product-dl">

            <dt>Kode produk</dt>
            <dd>{{ $product->code }}</dd>

            <dt>Nama produk</dt>
            <dd><strong>{{ $product->name }}</strong></dd>

            <dt>Kategori</dt>
            <dd>{{ $product->category->name ?? '-' }}</dd>

            <dt>Harga beli</dt>
            <dd>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</dd>

            <dt>Harga jual</dt>
            <dd>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</dd>

            <dt>Stok</dt>
            <dd>{{ $product->stock }} {{ $product->unit }}</dd>

            <dt>Satuan</dt>
            <dd>{{ $product->unit }}</dd>

            <dt>Status</dt>
            <dd>
                @if ($product->status == 'active')
                    <span class="product-badge product-badge--active">Aktif</span>
                @else
                    <span class="product-badge product-badge--inactive">Tidak aktif</span>
                @endif
            </dd>

            <dt>Deskripsi</dt>
            <dd>{{ $product->description ?: '-' }}</dd>

        </dl>

    </div>

</div>
@endsection