
@extends('admin.layouts.app')

@section('title', 'Dashboard Admin - YoungMart')
@section('page-title', 'Dashboard Admin')

@push('styles')
    @vite(['resources/css/admin/dashboard.css'])
@endpush

@section('content')

    <!-- RINGKASAN -->
    <div class="stats-grid">

        <!-- Total User -->
        <div class="stat-card">
            <div class="stat-icon blue">♙</div>

            <div class="stat-info">
                <span>Total User</span>
                <h2>{{ number_format($totalUsers) }}</h2>
                <small>Seluruh akun terdaftar</small>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="stat-card">
            <div class="stat-icon purple">▣</div>

            <div class="stat-info">
                <span>Total Produk</span>
                <h2>{{ number_format($totalProducts) }}</h2>
                <small>Produk dalam database</small>
            </div>
        </div>

        <!-- Transaksi Hari Ini -->
        <div class="stat-card">
            <div class="stat-icon orange">▤</div>

            <div class="stat-info">
                <span>Transaksi Hari Ini</span>
                <h2>{{ number_format($transactionsToday) }}</h2>
                <small>Transaksi berhasil dibayar</small>
            </div>
        </div>

        <!-- Pendapatan -->
        <div class="stat-card">
            <div class="stat-icon green">Rp</div>

            <div class="stat-info">
                <span>Pendapatan Bulan Ini</span>
                <h2 class="revenue-number">
                    Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
                </h2>

                <small class="{{ $revenueGrowth >= 0 ? 'growth-up' : 'growth-down' }}">
                    {{ $revenueGrowth >= 0 ? '+' : '' }}{{ number_format($revenueGrowth, 1) }}%
                    dari bulan lalu
                </small>
            </div>
        </div>

    </div>


    <!-- GRAFIK PENDAPATAN -->
    <section class="dashboard-section chart-section">

        <div class="section-header">
            <div>
                <h2>Grafik Pendapatan</h2>
                <p>Pendapatan bulanan tahun {{ date('Y') }}</p>
            </div>
        </div>

        <div class="chart-container">
            <canvas
                id="revenueChart"
                data-labels='@json($chartLabels)'
                data-values='@json($chartData)'>
            </canvas>
        </div>

    </section>


    <!-- RINGKASAN STOK -->
    <div class="stock-summary-grid">

        <div class="stock-summary-card">
            <div class="stock-summary-header">
                <div class="stock-icon warning">!</div>
                <span>Stok Menipis</span>
            </div>

            <h2>{{ number_format($lowStockCount) }}</h2>

            <p>Produk perlu segera ditambah</p>

            <a href="{{ route('admin.stock.index') }}">
                Lihat stok &rarr;
            </a>
        </div>


        <div class="stock-summary-card">
            <div class="stock-summary-header">
                <div class="stock-icon danger">×</div>
                <span>Stok Habis</span>
            </div>

            <h2>{{ number_format($outOfStockCount) }}</h2>

            <p>Produk tidak tersedia</p>

            <a href="{{ route('admin.stock.index') }}">
                Lihat stok &rarr;
            </a>
        </div>

    </div>

    <div class="analytics-grid">

        <!-- GRAFIK KATEGORI TERLARIS -->
        <section class="dashboard-section">

            <div class="section-header">
                <div>
                    <h2>Kategori Produk Terlaris</h2>
                    <p>Berdasarkan jumlah produk yang terjual</p>
                </div>
            </div>

            <div class="category-chart-container">
                <canvas
                    id="categoryChart"
                    data-labels='@json($categoryLabels)'
                    data-values='@json($categoryData)'>
                </canvas>
            </div>

        </section>


        <!-- TOP PELANGGAN -->
        <section class="dashboard-section">

            <div class="section-header">
                <div>
                    <h2>Top 5 Pelanggan</h2>
                    <p>Pelanggan berdasarkan jumlah transaksi</p>
                </div>
            </div>

            <div class="customer-ranking">

                @forelse($topCustomers as $index => $customer)

                    <div class="customer-ranking-item">

                        <div class="customer-rank">
                            {{ $index + 1 }}
                        </div>

                        <div class="customer-info">
                            <strong>{{ $customer->name }}</strong>

                            <span>
                                {{ $customer->total_transactions }}
                                transaksi pembelian
                            </span>
                        </div>

                        <div class="customer-total">
                            Rp {{ number_format($customer->total_spent, 0, ',', '.') }}
                        </div>

                    </div>

                @empty

                    <div class="empty-customer">
                        Belum ada data pembelian pelanggan.
                    </div>

                @endforelse

            </div>

        </section>

    </div>

@endsection

@push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/js/admin/dashboard.js'])

@endpush
