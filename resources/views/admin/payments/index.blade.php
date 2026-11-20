@extends('admin.layouts.app')

@section('title', 'Verifikasi Pembayaran')
@section('page_title', 'Verifikasi Pembayaran')
@section('page_subtitle', 'Periksa pembayaran pelanggan sebelum transaksi masuk ke penjualan.')

@push('styles')
    @vite('resources/css/admin/payments.css')
@endpush

@section('content')
@php
    $totalMenunggu = $orders->count();
    $nilaiMenunggu = $orders->sum('total');
    $jumlahQris    = $orders->where('payment_method', 'qris')->count();
@endphp

<div class="payment-page">

    {{-- Pesan sukses/error ditampilkan otomatis oleh layout --}}

    <div class="payment-toolbar">
        <span class="payment-hint">
            {{ $totalMenunggu }} pesanan menunggu verifikasi
        </span>

        <a href="{{ route('admin.transactions.index') }}" class="payment-btn payment-btn--ghost">
            Riwayat transaksi
        </a>
    </div>

    {{-- ===== Ringkasan ===== --}}
    <div class="payment-summary">

        <div class="payment-tile payment-tile--accent">
            <span>Menunggu pembayaran</span>
            <strong>{{ $totalMenunggu }} pesanan</strong>
        </div>

        <div class="payment-tile">
            <span>Total nilai menunggu</span>
            <strong>Rp {{ number_format($nilaiMenunggu, 0, ',', '.') }}</strong>
        </div>

        <div class="payment-tile">
            <span>Via QRIS / Tunai</span>
            <strong>{{ $jumlahQris }} / {{ $totalMenunggu - $jumlahQris }}</strong>
        </div>

    </div>

    {{-- ===== Tabel pesanan ===== --}}
    <section class="payment-card">

        <div class="payment-card-head">
            <h2>Pesanan menunggu pembayaran</h2>
            <p>Pastikan uang benar-benar sudah diterima sebelum menekan Konfirmasi.</p>
        </div>

        <div class="payment-table-wrap">
            <table class="payment-table">

                <thead>
                    <tr>
                        <th>Kode pesanan</th>
                        <th>Pelanggan</th>
                        <th>Metode</th>
                        <th class="is-num">Total</th>
                        <th>Bukti QRIS</th>
                        <th>Konfirmasi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td><span class="payment-code">{{ $order->transaction_code }}</span></td>

                            <td><strong>{{ $order->customer_name }}</strong></td>

                            <td>
                                @if ($order->payment_method === 'qris')
                                    <span class="payment-badge payment-badge--qris">QRIS</span>
                                @else
                                    <span class="payment-badge payment-badge--cash">Tunai</span>
                                @endif
                            </td>

                            <td class="is-num">
                                <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                            </td>

                            <td>
                                @if ($order->payment_method === 'qris')
                                    @if ($order->payment_proof)
                                        <a href="{{ route('admin.payments.proof', $order->id) }}"
                                           target="_blank"
                                           rel="noopener"
                                           class="payment-btn payment-btn--ghost payment-btn--small">
                                            Lihat bukti
                                        </a>
                                    @else
                                        <span class="payment-badge payment-badge--warning">Belum diunggah</span>
                                    @endif
                                @else
                                    <span class="payment-badge payment-badge--muted">Tidak diperlukan</span>
                                @endif
                            </td>

                            <td>
                                @if ($order->payment_method === 'qris' && empty($order->payment_proof))
                                    <span class="payment-hint">Menunggu bukti QRIS</span>
                                @else
                                    <div class="payment-actions">

                                        <form action="{{ route('admin.payments.confirm', $order->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Pastikan pembayaran benar-benar sudah diterima. Konfirmasi transaksi ini?')">
                                            @csrf

                                            @if ($order->payment_method === 'cash')
                                                <div class="payment-field">
                                                    <label for="payment-{{ $order->id }}">Uang diterima (Rp)</label>
                                                    <input id="payment-{{ $order->id }}"
                                                           type="number"
                                                           name="payment"
                                                           min="{{ $order->total }}"
                                                           step="1"
                                                           placeholder="Min. {{ number_format($order->total, 0, ',', '.') }}"
                                                           required>
                                                </div>
                                            @endif

                                            <button type="submit" class="payment-btn payment-btn--small">
                                                Konfirmasi dibayar
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.payments.fail', $order->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Tandai pembayaran ini gagal dan batalkan pesanan?')">
                                            @csrf
                                            <button type="submit" class="payment-btn payment-btn--danger payment-btn--small">
                                                Tandai gagal
                                            </button>
                                        </form>

                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="payment-empty">
                                <strong>Semua pembayaran sudah diproses</strong>
                                Belum ada pesanan yang menunggu pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </section>

</div>
@endsection