@extends('user.layout.app')

@section('title', 'Dashboard Pelanggan')
@section('page_title', 'Dashboard Pelanggan')
@section('page_subtitle', 'Selamat datang, ' . auth()->user()->name)

@push('styles')
    @vite('resources/css/user/dashboard.css')
@endpush

@section('content')
@php
    // Route pertama yang ada dari daftar nama; jika belum ada, link menjadi "#"
    $pilih = function (array $names) {
        foreach ($names as $name) {
            if (\Illuminate\Support\Facades\Route::has($name)) {
                return route($name);
            }
        }
        return '#';
    };

    $produk  = $pilih(['user.products.index', 'user.products', 'user.catalog']);
    $belanja = $pilih(['user.cart.index', 'user.shop', 'user.shopping', 'user.cart']);
    $riwayat = $pilih(['user.orders.index', 'user.transactions.index', 'user.history']);
@endphp

<div class="welcome-card">
    <h2>Selamat datang, {{ auth()->user()->name }}</h2>

    <p>
        Anda login sebagai
        <strong>{{ ucfirst(auth()->user()->role) }}</strong>.
        Lihat produk, belanja, dan cek riwayat pembelian Anda dari satu tempat.
    </p>
</div>

<div class="card-grid">

    <a href="{{ $produk }}" class="card">
        <span class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M21 8l-9-5-9 5v8l9 5 9-5z"/><path d="M3 8l9 5 9-5M12 13v8"/></svg>
        </span>
        <span class="card-label">Produk</span>
        <strong>Lihat produk</strong>
        <p>Lihat katalog, cari produk, dan periksa harga.</p>
        <span class="card-link">Buka katalog &rarr;</span>
    </a>

    <a href="{{ $belanja }}" class="card">
        <span class="card-icon card-icon--orange">
            <svg viewBox="0 0 24 24"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.6 12.4a2 2 0 002 1.6h8.1a2 2 0 002-1.5L21 8H6"/></svg>
        </span>
        <span class="card-label">Belanja</span>
        <strong>Belanja sekarang</strong>
        <p>Pilih produk dan tambahkan ke keranjang.</p>
        <span class="card-link">Mulai belanja &rarr;</span>
    </a>

    <a href="{{ $riwayat }}" class="card">
        <span class="card-icon card-icon--indigo">
            <svg viewBox="0 0 24 24"><path d="M6 3h12v18l-3-2-3 2-3-2-3 2z"/><path d="M9 8h6M9 12h6"/></svg>
        </span>
        <span class="card-label">Pembelian</span>
        <strong>Riwayat pembelian</strong>
        <p>Lihat daftar pembelian yang pernah Anda lakukan.</p>
        <span class="card-link">Lihat riwayat &rarr;</span>
    </a>

</div>
@endsection

@push('scripts')
    @vite('resources/js/user/dashboard.js')
@endpush