<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Youngmart</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/admin/layout.css'])

    @stack('styles')
</head>
<body>

@php
    $rt = fn ($name) => \Illuminate\Support\Facades\Route::has($name) ? route($name) : '#';

    $link = [
        'dashboard'  => $rt('admin.dashboard'),
        'produk'     => $rt('admin.products.index'),
        'kategori'   => $rt('admin.categories.index'),
        'stok'       => $rt('admin.stock.index'),
        'transaksi'  => $rt('admin.transactions.index'),
        'pembayaran' => $rt('admin.payments.index'),
        'pelanggan'  => $rt('admin.customers.index'),
        'laporan'    => $rt('admin.reports.index'),
        'logout'     => \Illuminate\Support\Facades\Route::has('logout') ? route('logout') : url('/logout'),
    ];

    // Menu yang sedang aktif (cocokkan dengan nama route)
    $aktif = fn (string $pola) => request()->routeIs($pola) ? 'active' : '';

    $nama = auth()->user()->name ?? 'Administrator';
@endphp

{{-- ================= SIDEBAR ================= --}}
<aside class="sidebar">
    <div class="brand">
        <div class="brand-logo brand-logo--img">
            <img src="{{ asset('images/logo-youngmart.png') }}" alt="Youngmart">
        </div>
        <span>Panel admin</span>
    </div>

    <div class="nav-group">
        <div class="nav-title">Utama</div>
        <a href="{{ $link['dashboard'] }}" class="nav-link {{ $aktif('admin.dashboard') }}">
            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
            Dashboard
        </a>
    </div>

    <div class="nav-group">
        <div class="nav-title">Katalog</div>
        <a href="{{ $link['produk'] }}" class="nav-link {{ $aktif('admin.products.*') }}">
            <svg viewBox="0 0 24 24"><path d="M21 8l-9-5-9 5v8l9 5 9-5z"/><path d="M3 8l9 5 9-5M12 13v8"/></svg>
            Produk
        </a>
        <a href="{{ $link['kategori'] }}" class="nav-link {{ $aktif('admin.categories.*') }}">
            <svg viewBox="0 0 24 24"><path d="M20.6 13.4l-7.2 7.2a2 2 0 01-2.8 0L3 13V3h10l7.6 7.6a2 2 0 010 2.8z"/><circle cx="7.5" cy="7.5" r="1.2"/></svg>
            Kategori
        </a>
        <a href="{{ $link['stok'] }}" class="nav-link {{ $aktif('admin.stock.*') }}">
            <svg viewBox="0 0 24 24"><path d="M12 3l9 5-9 5-9-5z"/><path d="M3 13l9 5 9-5"/></svg>
            Stok
        </a>
    </div>

    <div class="nav-group">
        <div class="nav-title">Penjualan</div>
        <a href="{{ $link['transaksi'] }}" class="nav-link {{ $aktif('admin.transactions.*') }}">
            <svg viewBox="0 0 24 24"><path d="M6 3h12v18l-3-2-3 2-3-2-3 2z"/><path d="M9 8h6M9 12h6"/></svg>
            Transaksi
        </a>
        <a href="{{ $link['pembayaran'] }}" class="nav-link {{ $aktif('admin.payments.*') }}">
            <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18"/></svg>
            Pembayaran
        </a>
        <a href="{{ $link['pelanggan'] }}" class="nav-link {{ $aktif('admin.customers.*') }}">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            Pelanggan
        </a>
    </div>

    <div class="nav-group">
        <div class="nav-title">Laporan</div>
        <a href="{{ $link['laporan'] }}" class="nav-link {{ $aktif('admin.reports.*') }}">
            <svg viewBox="0 0 24 24"><path d="M4 20V4M4 20h16M9 16v-5M14 16V8M19 16v-3"/></svg>
            Laporan
        </a>
    </div>

    <div class="sidebar-user" style="margin-top:28px">
        <div class="avatar">{{ strtoupper(substr($nama, 0, 1)) }}</div>
        <div>
            <b>{{ $nama }}</b>
            <span>Admin</span>
        </div>
        <form method="POST" action="{{ $link['logout'] }}">
            @csrf
            <button type="submit" class="logout-btn" title="Keluar" aria-label="Keluar">
                <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><path d="M16 17l5-5-5-5M21 12H9"/></svg>
            </button>
        </form>
    </div>
</aside>

{{-- ================= MAIN ================= --}}
<div class="main">
    <header class="topbar">
        <div>
            <h1>@yield('page_title', 'Admin')</h1>
            @hasSection('page_subtitle')
                <p>@yield('page_subtitle')</p>
            @endif
        </div>
        <div class="profile-chip">
            <div class="avatar">{{ strtoupper(substr($nama, 0, 1)) }}</div>
            <div>
                <b>{{ $nama }}</b>
                <span>Admin</span>
            </div>
        </div>
    </header>

    <main class="content">
        {{-- Pesan sukses / error otomatis --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <div>
                    Data belum bisa disimpan. Periksa isian berikut:
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer">
        <div class="footer-top">
            <div class="footer-brand">
                <div class="brand-logo">YM</div>
                <div>
                    <b>Youngmart Grub</b>
                    <span>Sistem penjualan dan pengelolaan toko berbasis web.</span>
                </div>
            </div>
            <div class="footer-links">
                <a href="{{ $link['laporan'] }}">Laporan</a>
                <a href="{{ $link['pembayaran'] }}">Pembayaran</a>
                <a href="#">Bantuan</a>
            </div>
        </div>
        <div class="copy">&copy; {{ date('Y') }} Youngmart Grub.</div>
    </footer>
</div>

@stack('scripts')
</body>
</html>