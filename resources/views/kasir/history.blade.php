@extends('kasir.layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat Transaksi Kasir')
@section('page_subtitle', 'Transaksi yang sudah Anda proses.')

@push('styles')
    @vite('resources/css/kasir/transactions.css')
@endpush

@section('content')
@php
    $statusMap = [
        'completed' => ['Selesai', 'success'],
        'pending'   => ['Menunggu', 'warning'],
        'cancelled' => ['Dibatalkan', 'danger'],
    ];
@endphp

<div class="history-page">

    <div class="history-toolbar">
        <span class="history-hint">{{ $transactions->total() }} transaksi ditemukan</span>

        <a href="{{ route('kasir.pos') }}" class="history-btn">+ Transaksi baru</a>
    </div>

    {{-- ===== Filter ===== --}}
    <form action="{{ route('kasir.history') }}" method="GET" class="history-card filter-form">

        <div class="filter-group">
            <label for="search">Cari transaksi</label>
            <input type="text" id="search" name="search"
                   placeholder="Cari kode transaksi"
                   value="{{ request('search') }}">
        </div>

        <div class="filter-group">
            <label for="start_date">Tanggal mulai</label>
            <input type="date" id="start_date" name="start_date"
                   value="{{ request('start_date') }}">
        </div>

        <div class="filter-group">
            <label for="end_date">Tanggal akhir</label>
            <input type="date" id="end_date" name="end_date"
                   value="{{ request('end_date') }}">
        </div>

        <div class="filter-actions">
            <button type="submit" class="filter-button">Filter</button>
            <a href="{{ route('kasir.history') }}" class="reset-button">Reset</a>
        </div>

    </form>

    {{-- ===== Tabel ===== --}}
    <div class="history-table-wrap">
        <table class="transaction-table">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode transaksi</th>
                    <th>Tanggal</th>
                    <th class="is-num">Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($transactions as $transaction)
                    @php
                        [$label, $badge] = $statusMap[$transaction->status]
                            ?? [ucfirst($transaction->status), 'muted'];
                    @endphp

                    <tr>
                        <td>{{ $transactions->firstItem() + $loop->index }}</td>

                        <td><span class="history-code">{{ $transaction->transaction_code }}</span></td>

                        <td>{{ $transaction->transaction_date->format('d-m-Y H:i') }}</td>

                        <td class="is-num"><strong>Rp {{ number_format($transaction->total, 0, ',', '.') }}</strong></td>

                        <td><span class="history-badge history-badge--{{ $badge }}">{{ $label }}</span></td>

                        <td>
                            <a href="{{ route('kasir.transaction.receipt', $transaction->id) }}"
                               class="detail-link">Detail / cetak struk</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="history-empty">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    @if ($transactions->hasPages())
        <div class="history-pagination">
            {{ $transactions->withQueryString()->links('pagination::default') }}
        </div>
    @endif

</div>
@endsection