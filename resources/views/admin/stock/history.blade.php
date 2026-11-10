@extends('admin.layouts.app')

@section('title', 'Riwayat Stok')
@section('page_title', 'Riwayat Stok')
@section('page_subtitle', $product->name)

@push('styles')
    @vite('resources/css/admin/stock.css')
@endpush

@section('content')
@php
    // Label dan warna lencana untuk tiap jenis perubahan stok
    $typeLabels = [
        'in'         => ['Stok masuk', 'in'],
        'out'        => ['Stok keluar', 'out'],
        'adjustment' => ['Penyesuaian', 'adjustment'],
    ];
@endphp

<div class="stock-page">

    <div class="stock-toolbar">
        <a href="{{ route('admin.stock.index') }}" class="stock-btn stock-btn--ghost">
            Kembali
        </a>

        <a href="{{ route('admin.stock.create', $product) }}" class="stock-btn">
            Ubah stok
        </a>
    </div>

    {{-- ===== Ringkasan ===== --}}
    <div class="stock-summary">

        <div class="stock-tile">
            <span>Produk</span>
            <strong>{{ $product->name }}</strong>
        </div>

        <div class="stock-tile stock-tile--accent">
            <span>Stok saat ini</span>
            <strong>{{ $product->stock }} {{ $product->unit }}</strong>
        </div>

        <div class="stock-tile">
            <span>Catatan perubahan</span>
            <strong>{{ $histories->count() }}</strong>
        </div>

    </div>

    {{-- ===== Tabel riwayat ===== --}}
    <div class="stock-table-wrap">
        <table class="stock-table stock-table--wide">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Stok sebelum</th>
                    <th>Stok sesudah</th>
                    <th>Keterangan</th>
                    <th>User</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($histories as $history)
                    @php
                        [$label, $badge] = $typeLabels[$history->type] ?? [ucfirst($history->type), 'adjustment'];
                    @endphp

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $history->created_at->format('d-m-Y H:i') }}</td>

                        <td>
                            <span class="stock-badge stock-badge--{{ $badge }}">{{ $label }}</span>
                        </td>

                        <td>{{ $history->quantity }}</td>
                        <td>{{ $history->stock_before }}</td>
                        <td><strong>{{ $history->stock_after }}</strong></td>
                        <td>{{ $history->reason ?? '-' }}</td>
                        <td>{{ $history->user->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="stock-empty">Belum ada riwayat perubahan stok.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>
@endsection