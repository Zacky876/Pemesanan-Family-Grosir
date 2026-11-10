<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin') - Minimarket</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- CSS & JS template (dipakai semua halaman admin) --}}
    @vite([
        'resources/css/admin/layouts.css',
        'resources/js/admin/layouts.js'
    ])

    {{-- CSS khusus halaman: @push('styles') di halaman masing-masing --}}
    @stack('styles')
</head>

<body>

@php
    $menuGroups = [
        'Utama' => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'match' => 'admin.dashboard',
             'icon' => 'M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z'],
        ],
        'Katalog' => [
            ['label' => 'Produk', 'route' => 'admin.products.index', 'match' => 'admin.products.*',
             'icon' => 'M21 8l-9-5-9 5v8l9 5 9-5zM3 8l9 5 9-5M12 13v8'],
            ['label' => 'Kategori', 'route' => 'admin.categories.index', 'match' => 'admin.categories.*',
             'icon' => 'M20 12l-8 8-9-9V3h8zM7.5 7.5h.01'],
            ['label' => 'Stok', 'route' => 'admin.stock.index', 'match' => 'admin.stock.*',
             'icon' => 'M12 3l9 5-9 5-9-5zM3 13l9 5 9-5'],
        ],
        'Penjualan' => [
            ['label' => 'Transaksi', 'route' => 'admin.transactions.index', 'match' => 'admin.transactions.*',
             'icon' => 'M6 3h12v18l-3-2-3 2-3-2-3 2zM9 8h6M9 12h6'],
            ['label' => 'Pembayaran', 'route' => 'admin.payments.index', 'match' => 'admin.payments.*',
             'icon' => 'M3 6h18v12H3zM3 10h18'],
            ['label' => 'Pelanggan', 'route' => 'admin.customers.index', 'match' => 'admin.customers.*',
             'icon' => 'M16 20v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2M9.5 10a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M21 20v-2a4 4 0 0 0-3-3.9'],
        ],
        'Laporan' => [
            ['label' => 'Laporan', 'route' => 'admin.reports.index', 'match' => 'admin.reports.*',
             'icon' => 'M4 20V10M10 20V4M16 20v-7M22 20H2'],
        ],
    ];

    $user = auth()->user();
    $initial = strtoupper(mb_substr($user->name, 0, 1));
@endphp

<div class="layout">

    {{-- ================= SIDEBAR ================= --}}
    <aside class="sidebar" id="sidebar" aria-label="Menu admin">

        <div class="sidebar-brand">
            <span class="brand-mark" aria-hidden="true">M</span>
            <div>
                <strong>Minimarket</strong>
                <small>Panel admin</small>
            </div>
        </div>

        <nav class="sidebar-nav">
            @foreach ($menuGroups as $group => $items)
                <p class="nav-title">{{ $group }}</p>

                @foreach ($items as $item)
                    @php
                        $href = Route::has($item['route']) ? route($item['route']) : '#';
                        $active = request()->routeIs($item['match']);
                    @endphp

                    <a href="{{ $href }}"
                       class="nav-link {{ $active ? 'is-active' : '' }}"
                       @if ($active) aria-current="page" @endif>
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="{{ $item['icon'] }}" />
                        </svg>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            @endforeach
        </nav>

        <div class="sidebar-user">
            <span class="avatar" aria-hidden="true">{{ $initial }}</span>
            <div class="user-info">
                <strong>{{ $user->name }}</strong>
                <small>{{ $user->role }}</small>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button" title="Keluar" aria-label="Keluar">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9" />
                    </svg>
                </button>
            </form>
        </div>

    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- ================= AREA UTAMA ================= --}}
    <div class="main">

        {{-- ---------- HEADER ---------- --}}
        <header class="topbar">
            <button type="button" class="menu-toggle" id="menuToggle"
                    aria-label="Buka menu" aria-controls="sidebar" aria-expanded="false">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>

            <div class="topbar-title">
                <h1>@yield('page_title', 'Dashboard')</h1>
                @hasSection('page_subtitle')
                    <p>@yield('page_subtitle')</p>
                @endif
            </div>

            <div class="user-chip">
                <span class="avatar avatar--small" aria-hidden="true">{{ $initial }}</span>
                <div>
                    <strong>{{ $user->name }}</strong>
                    <small>{{ $user->role }}</small>
                </div>
            </div>
        </header>

        {{-- ---------- ISI HALAMAN ---------- --}}
        <main class="content">

            @if (session('success'))
                <div class="ui-alert ui-alert--success" role="status" data-autohide>{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="ui-alert ui-alert--error" role="alert">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="ui-alert ui-alert--error" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </main>

        {{-- ---------- FOOTER ---------- --}}
        <footer class="footer">
            <div class="footer-brand">
                <span class="brand-mark brand-mark--small" aria-hidden="true">M</span>
                <div>
                    <strong>Minimarket</strong>
                    <p>Sistem penjualan dan pengelolaan toko berbasis web.</p>
                </div>
            </div>

            <nav class="footer-links" aria-label="Tautan footer">
                <a href="{{ Route::has('admin.reports.index') ? route('admin.reports.index') : '#' }}">Laporan</a>
                <a href="{{ Route::has('admin.payments.index') ? route('admin.payments.index') : '#' }}">Pembayaran</a>
                <a href="#">Bantuan</a>
            </nav>

            <p class="footer-copy">&copy; {{ date('Y') }} Minimarket. Dibuat dengan Laravel.</p>
        </footer>

    </div>
</div>

@stack('scripts')

</body>

</html>