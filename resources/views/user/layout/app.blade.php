<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pelanggan') - Youngmart</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/user/layout.css'])

    @stack('styles')
</head>
<body>

@php
    $nama = auth()->user()->name ?? 'Pelanggan';

    // Ambil route pertama yang ada dari daftar nama; jika belum ada, link menjadi "#"
    $pilih = function (array $names) {
        foreach ($names as $name) {
            if (\Illuminate\Support\Facades\Route::has($name)) {
                return route($name);
            }
        }
        return '#';
    };

    $link = [
        'dashboard' => $pilih(['user.dashboard']),
        'produk'    => $pilih(['user.products.index', 'user.products', 'user.catalog']),
        'belanja'   => $pilih(['user.cart.index', 'user.shop', 'user.shopping', 'user.cart']),
        'riwayat'   => $pilih(['user.orders.index', 'user.transactions.index', 'user.history']),
        'logout'    => \Illuminate\Support\Facades\Route::has('logout') ? route('logout') : url('/logout'),
    ];

    // Menu aktif: cocokkan dengan nama route halaman yang sedang dibuka
    $aktif = fn (string ...$pola) => request()->routeIs(...$pola) ? 'active' : '';
@endphp

<aside class="sidebar">
    <div class="brand">
        <div class="brand-logo brand-logo--img">
            <img src="{{ asset('images/logo-youngmart.png') }}" alt="Youngmart">
        </div>
        <span>Panel pelanggan</span>
    </div>

    <div class="nav-group">
        <div class="nav-title">Utama</div>
        <a href="{{ $link['dashboard'] }}" class="nav-link {{ $aktif('user.dashboard') }}">
            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
            Dashboard
        </a>
    </div>

    <div class="nav-group">
        <div class="nav-title">Belanja</div>
        <a href="{{ $link['produk'] }}" class="nav-link {{ $aktif('user.products.*') }}">
            <svg viewBox="0 0 24 24"><path d="M21 8l-9-5-9 5v8l9 5 9-5z"/><path d="M3 8l9 5 9-5M12 13v8"/></svg>
            Lihat produk
        </a>
        <a href="{{ $link['belanja'] }}" class="nav-link {{ $aktif('user.cart.*', 'user.checkout.*') }}">
            <svg viewBox="0 0 24 24"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.6 12.4a2 2 0 002 1.6h8.1a2 2 0 002-1.5L21 8H6"/></svg>
            Belanja sekarang
        </a>
        <a href="{{ $link['riwayat'] }}" class="nav-link {{ $aktif('user.orders.*', 'user.payment.*', 'user.payments.*') }}">
            <svg viewBox="0 0 24 24"><path d="M6 3h12v18l-3-2-3 2-3-2-3 2z"/><path d="M9 8h6M9 12h6"/></svg>
            Riwayat pembelian
        </a>
    </div>

    <div class="sidebar-user" style="margin-top:28px">
        <div class="avatar">{{ strtoupper(mb_substr($nama, 0, 1)) }}</div>
        <div>
            <b>{{ $nama }}</b>
            <span>Pelanggan</span>
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
            <h1>@yield('page_title', 'Pelanggan')</h1>
            @hasSection('page_subtitle')
                <p>@yield('page_subtitle')</p>
            @endif
        </div>
        <div class="profile-chip">
            <div class="avatar">{{ strtoupper(mb_substr($nama, 0, 1)) }}</div>
            <div>
                <b>{{ $nama }}</b>
                <span>Pelanggan</span>
            </div>
        </div>
    </header>

    <main class="content">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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
                    <span>Toko grosir: satu sistem untuk stok, kasir, dan belanja pelanggan.</span>
                </div>
            </div>
            <div class="footer-links">
                <a href="{{ $link['produk'] }}">Lihat produk</a>
                <a href="{{ $link['riwayat'] }}">Riwayat</a>
            </div>
        </div>
        <div class="copy">&copy; {{ date('Y') }} Youngmart Grub.</div>
    </footer>
</div>

@stack('scripts')
</body>
</html>