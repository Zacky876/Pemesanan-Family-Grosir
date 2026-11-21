@extends('admin.layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat Transaksi')
@section('page_subtitle', 'Pantau semua transaksi penjualan minimarket.')

@push('styles')
    @vite('resources/css/admin/transactions.css')
@endpush

@section('content')
@php
    // Label dan warna lencana untuk tiap status transaksi
    $statusMap = [
        'completed' => ['Selesai', 'success'],
        'pending'   => ['Menunggu', 'warning'],
        'cancelled' => ['Dibatalkan', 'danger'],
    ];
@endphp

<div class="transaction-page">

    {{-- Pesan sukses/error ditampilkan otomatis oleh layout --}}

    <div class="transaction-toolbar">
        <span class="transaction-hint">
            {{ $transactions->total() }} transaksi ditemukan
        </span>
    </div>

    {{-- ===== Cari dan filter ===== --}}
    <form action="{{ route('admin.transactions.index') }}" method="GET"
          class="transaction-card transaction-filter">

        <div class="transaction-field">
            <label for="search">Cari transaksi</label>
            <input type="text" id="search" name="search"
                   placeholder="Cari kode transaksi"
                   value="{{ request('search') }}">
        </div>

        <div class="transaction-field">
            <label for="start_date">Tanggal mulai</label>
            <input type="date" id="start_date" name="start_date"
                   value="{{ request('start_date') }}">
        </div>

        <div class="transaction-field">
            <label for="end_date">Tanggal akhir</label>
            <input type="date" id="end_date" name="end_date"
                   value="{{ request('end_date') }}">
        </div>

        <div class="transaction-field">
            <label for="cashier">Kasir</label>
            <select name="cashier" id="cashier">
                <option value="">Semua kasir</option>
                @foreach ($cashiers as $cashier)
                    <option value="{{ $cashier->id }}"
                        {{ request('cashier') == $cashier->id ? 'selected' : '' }}>
                        {{ $cashier->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="transaction-field">
            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="">Semua status</option>
                @foreach ($statusMap as $value => [$label])
                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="transaction-filter-actions">
            <button type="submit" class="transaction-btn">Filter</button>
            <a href="{{ route('admin.transactions.index') }}"
               class="transaction-btn transaction-btn--ghost">Reset</a>
        </div>

    </form>

    {{-- ===== Tabel riwayat transaksi ===== --}}
    <div class="transaction-table-wrap">
        <table class="transaction-table">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode transaksi</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
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

                        <td><span class="transaction-code">{{ $transaction->transaction_code }}</span></td>

                        <td>{{ $transaction->transaction_date->format('d-m-Y H:i') }}</td>

                        <td>{{ $transaction->user->name ?? '-' }}</td>

                        <td class="is-num"><strong>Rp {{ number_format($transaction->total, 0, ',', '.') }}</strong></td>

                        <td>
                            <span class="transaction-badge transaction-badge--{{ $badge }}">{{ $label }}</span>
                        </td>

                        <td>
                            <a href="{{ route('admin.transactions.show', $transaction->id) }}"
                               class="transaction-btn transaction-btn--ghost transaction-btn--small">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="transaction-empty">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    @if ($transactions->hasPages())
        <div class="transaction-pagination">
            {{ $transactions->withQueryString()->links('pagination::default') }}
        </div>
    @endif

</div>
@endsection