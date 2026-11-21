@extends('kasir.layouts.app')

@section('title', 'Dashboard Kasir')
@section('page_title', 'Dashboard Kasir')
@section('page_subtitle', 'Selamat datang, ' . auth()->user()->name)

@push('styles')
    @vite('resources/css/kasir/dashboard.css')
@endpush

@section('content')

    <div class="welcome-card">
        <h2>Siap melayani pelanggan</h2>

        <p>
            Anda login sebagai
            <strong>{{ ucfirst(auth()->user()->role) }}</strong>.
            Mulai transaksi baru atau lihat riwayat penjualan Anda.
        </p>
    </div>

    <div class="card-grid">

        <a href="{{ route('kasir.pos') }}" class="card card--primary">
            <span class="card-icon">
                <svg viewBox="0 0 24 24"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.6 12.4a2 2 0 002 1.6h8.1a2 2 0 002-1.5L21 8H6"/></svg>
            </span>
            <span class="card-label">Transaksi</span>
            <strong>Transaksi baru</strong>
            <p>Pilih produk, hitung total belanja, dan terima pembayaran tunai.</p>
            <span class="card-link">Mulai transaksi &rarr;</span>
        </a>

        <a href="{{ route('kasir.history') }}" class="card">
            <span class="card-icon card-icon--orange">
                <svg viewBox="0 0 24 24"><path d="M6 3h12v18l-3-2-3 2-3-2-3 2z"/><path d="M9 8h6M9 12h6"/></svg>
            </span>
            <span class="card-label">Transaksi</span>
            <strong>Riwayat transaksi</strong>
            <p>Lihat transaksi yang sudah selesai dan cetak ulang struk.</p>
            <span class="card-link">Lihat riwayat &rarr;</span>
        </a>

    </div>

@endsection

@push('scripts')
    @vite('resources/js/kasir/dashboard.js')
@endpush