@extends('admin.layouts.app')

@section('title', 'Detail Transaksi')
@section('page_title', 'Detail Transaksi')
@section('page_subtitle', $transaction->transaction_code)

@push('styles')
    @vite('resources/css/admin/transactions.css')
@endpush

@section('content')
@php
    // Lencana status transaksi
    $statusMap = [
        'completed' => ['Selesai', 'success'],
        'pending'   => ['Menunggu', 'warning'],
        'cancelled' => ['Dibatalkan', 'danger'],
    ];
    [$statusLabel, $statusBadge] = $statusMap[$transaction->status]
        ?? [ucfirst($transaction->status), 'muted'];

    // Lencana status pembayaran (nilai disesuaikan dengan data yang umum dipakai)
    $paymentStatus = strtolower($transaction->payment_status ?? '');
    $paymentBadge = match (true) {
        in_array($paymentStatus, ['paid', 'lunas', 'success', 'settlement', 'completed']) => 'success',
        in_array($paymentStatus, ['pending', 'unpaid', 'waiting']) => 'warning',
        in_array($paymentStatus, ['failed', 'cancelled', 'expired', 'rejected']) => 'danger',
        default => 'muted',
    };
@endphp

<div class="transaction-page">

    <div class="transaction-toolbar">
        <a href="{{ route('admin.transactions.index') }}" class="transaction-btn transaction-btn--ghost">
            Kembali
        </a>

        <button type="button" class="transaction-btn" onclick="window.print()">
            Cetak
        </button>
    </div>

    {{-- ===== Ringkasan pembayaran ===== --}}
    <div class="transaction-summary">

        <div class="transaction-tile transaction-tile--accent">
            <span>Total belanja</span>
            <strong>Rp {{ number_format($transaction->total, 0, ',', '.') }}</strong>
        </div>

        <div class="transaction-tile">
            <span>Uang dibayarkan</span>
            <strong>Rp {{ number_format($transaction->payment, 0, ',', '.') }}</strong>
        </div>

        <div class="transaction-tile">
            <span>Kembalian</span>
            <strong>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</strong>
        </div>

    </div>

    {{-- ===== Informasi transaksi ===== --}}
    <div class="transaction-card transaction-section">
        <h2>Informasi transaksi</h2>

        <dl class="transaction-dl">
            <div>
                <dt>Kode transaksi</dt>
                <dd><span class="transaction-code">{{ $transaction->transaction_code }}</span></dd>
            </div>

            <div>
                <dt>Tanggal</dt>
                <dd>{{ $transaction->transaction_date->format('d-m-Y H:i') }}</dd>
            </div>

            <div>
                <dt>Kasir</dt>
                <dd>{{ $transaction->user->name ?? '-' }}</dd>
            </div>

            <div>
                <dt>Metode pembayaran</dt>
                <dd>{{ strtoupper($transaction->payment_method ?? '-') }}</dd>
            </div>

            <div>
                <dt>Status transaksi</dt>
                <dd><span class="transaction-badge transaction-badge--{{ $statusBadge }}">{{ $statusLabel }}</span></dd>
            </div>

            <div>
                <dt>Status pembayaran</dt>
                <dd>
                    <span class="transaction-badge transaction-badge--{{ $paymentBadge }}">
                        {{ ucfirst($transaction->payment_status ?? '-') }}
                    </span>
                </dd>
            </div>
        </dl>
    </div>

    {{-- ===== Daftar produk ===== --}}
    <div class="transaction-card transaction-items">
        <h2>Daftar produk</h2>

        <div class="transaction-table-wrap">
            <table class="transaction-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th class="is-num">Harga</th>
                        <th class="is-num">Jumlah</th>
                        <th class="is-num">Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($transaction->details as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $detail->product->name ?? '-' }}</strong></td>
                            <td class="is-num">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td class="is-num">{{ $detail->quantity }}</td>
                            <td class="is-num">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="4" class="is-num">Total</td>
                        <td class="is-num">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>

            </table>
        </div>
    </div>

</div>
@endsection