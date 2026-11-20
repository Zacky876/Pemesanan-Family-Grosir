@extends('user.layout.app')

@section('title', 'Riwayat Pembelian')
@section('page_title', 'Riwayat Pembelian Saya')
@section('page_subtitle', 'Pantau status pesanan dan pembayaran Anda.')

@section('content')
@php
    // Daftar pesanan (nama variabel dari controller: $orders atau $transactions)
    $daftar = $orders ?? $transactions ?? collect();
    $jumlah = method_exists($daftar, 'total') ? $daftar->total() : $daftar->count();
    $adaDetail = \Illuminate\Support\Facades\Route::has('user.orders.show');

    // Label dan warna lencana status pesanan
    $statusPesanan = [
        'pending'    => ['Menunggu diproses', 'warning'],
        'processing' => ['Diproses', 'info'],
        'completed'  => ['Selesai', 'success'],
        'cancelled'  => ['Dibatalkan', 'danger'],
    ];

    // Label dan warna lencana status pembayaran
    $statusBayar = [
        'unpaid'  => ['Belum dibayar', 'warning'],
        'pending' => ['Menunggu verifikasi', 'warning'],
        'paid'    => ['Lunas', 'success'],
        'failed'  => ['Gagal', 'danger'],
    ];
@endphp

<div class="catalog-page">

    {{-- Pesan sukses/error ditampilkan otomatis oleh layout --}}

    <div class="catalog-toolbar">
        <span class="catalog-hint">{{ $jumlah }} pesanan ditemukan</span>

        <a href="{{ route('user.products.index') }}" class="catalog-btn catalog-btn--ghost">
            + Belanja lagi
        </a>
    </div>

    {{-- ===== Cari dan filter ===== --}}
    <form action="{{ route('user.orders.index') }}" method="GET" class="catalog-card catalog-filter">

        <div class="catalog-field">
            <label for="search">Cari transaksi</label>
            <input type="text" id="search" name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari kode transaksi">
        </div>

        <div class="catalog-field">
            <label for="status">Status pesanan</label>
            <select id="status" name="status">
                <option value="">Semua status</option>
                @foreach ($statusPesanan as $nilai => [$label])
                    <option value="{{ $nilai }}" {{ request('status') == $nilai ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="catalog-filter-actions">
            <button type="submit" class="catalog-btn">Cari</button>
            <a href="{{ route('user.orders.index') }}" class="catalog-btn catalog-btn--ghost">Reset</a>
        </div>

    </form>

    {{-- ===== Tabel riwayat ===== --}}
    <div class="shop-table-wrap">
        <table class="shop-table order-table">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode transaksi</th>
                    <th>Tanggal</th>
                    <th class="is-num">Total</th>
                    <th>Status pesanan</th>
                    <th>Status pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($daftar as $order)
                    @php
                        $nomor = method_exists($daftar, 'firstItem')
                            ? $daftar->firstItem() + $loop->index
                            : $loop->iteration;

                        $kodeStatus = strtolower((string) $order->status);
                        [$labelStatus, $warnaStatus] = $statusPesanan[$kodeStatus]
                            ?? [ucfirst((string) $order->status), 'muted'];

                        $kodeBayar = strtolower((string) ($order->payment_status ?? ''));
                        [$labelBayar, $warnaBayar] = $statusBayar[$kodeBayar]
                            ?? [($order->payment_status ?? '-') ?: '-', 'muted'];

                        $tanggal = $order->transaction_date ?? $order->created_at;
                    @endphp

                    <tr>
                        <td>{{ $nomor }}</td>

                        <td><span class="order-code">{{ $order->transaction_code }}</span></td>

                        <td>{{ $tanggal?->format('d-m-Y H:i') ?? '-' }}</td>

                        <td class="is-num"><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td>

                        <td><span class="order-badge order-badge--{{ $warnaStatus }}">{{ $labelStatus }}</span></td>

                        <td><span class="order-badge order-badge--{{ $warnaBayar }}">{{ ucfirst($labelBayar) }}</span></td>

                        <td>
                            @if ($adaDetail)
                                <a href="{{ route('user.orders.show', $order) }}"
                                   class="catalog-btn catalog-btn--ghost catalog-btn--small">Detail</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="catalog-empty" style="padding: 44px 20px;">
                            Belum ada riwayat pembelian.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    @if (method_exists($daftar, 'hasPages') && $daftar->hasPages())
        <div class="catalog-pagination">
            {{ $daftar->withQueryString()->links('pagination::default') }}
        </div>
    @endif

</div>
@endsection