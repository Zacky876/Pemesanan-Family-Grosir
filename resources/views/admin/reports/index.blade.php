@extends('admin.layouts.app')

@section('title', 'Laporan Admin')
@section('page_title', 'Laporan Admin')
@section('page_subtitle', 'Pilih jenis laporan yang ingin dilihat.')

@push('styles')
    @vite('resources/css/admin/reports.css')
@endpush

@section('content')
<div class="report-page">

    <div class="report-menu">

        <a href="{{ route('admin.reports.sales') }}" class="report-menu-card">
            <span class="report-menu-icon">
                <svg viewBox="0 0 24 24"><path d="M4 20V4M4 20h16M9 16v-5M14 16V8M19 16v-3"/></svg>
            </span>
            <h2>Laporan penjualan</h2>
            <p>Melihat laporan penjualan harian, mingguan, bulanan, berdasarkan produk, kategori, dan kasir.</p>
            <span class="report-menu-link">Buka laporan &rarr;</span>
        </a>

        <a href="{{ route('admin.reports.stock') }}" class="report-menu-card">
            <span class="report-menu-icon report-menu-icon--orange">
                <svg viewBox="0 0 24 24"><path d="M12 3l9 5-9 5-9-5z"/><path d="M3 13l9 5 9-5"/></svg>
            </span>
            <h2>Laporan stok</h2>
            <p>Melihat stok tersedia, produk hampir habis, dan produk yang sudah habis.</p>
            <span class="report-menu-link">Buka laporan &rarr;</span>
        </a>

    </div>

    <div class="report-toolbar">
        <a href="{{ route('admin.dashboard') }}" class="report-btn report-btn--ghost">
            Kembali ke dashboard
        </a>
    </div>

</div>
@endsection