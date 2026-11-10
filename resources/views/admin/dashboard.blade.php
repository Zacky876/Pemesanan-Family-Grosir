@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Admin')
@section('page_subtitle', 'Selamat datang, ' . auth()->user()->name)

@push('styles')
    @vite('resources/css/admin/dashboard.css')
@endpush

@section('content')

    <section class="welcome-card">
        <h2>Selamat datang kembali</h2>
        <p>
            Anda login sebagai <strong>{{ auth()->user()->role }}</strong>.
            Kelola produk, pantau stok, dan verifikasi pembayaran dari satu tempat.
        </p>
    </section>

@endsection