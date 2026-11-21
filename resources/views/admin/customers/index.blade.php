@extends('admin.layouts.app')

@section('title', 'Manajemen Pelanggan')
@section('page_title', 'Manajemen Pelanggan')
@section('page_subtitle', 'Daftar akun pelanggan minimarket.')

@push('styles')
    @vite('resources/css/admin/customers.css')
@endpush

@section('content')
<div class="customer-page">

    {{-- Pesan sukses/error ditampilkan otomatis oleh layout --}}

    <div class="customer-toolbar">
        <span class="customer-hint">
            {{ $customers->total() }} pelanggan
        </span>

        <a href="{{ route('admin.customers.create') }}" class="customer-btn">
            + Tambah pelanggan
        </a>
    </div>

    {{-- ===== Pencarian ===== --}}
    <form action="{{ route('admin.customers.index') }}" method="GET"
          class="customer-card customer-filter">

        <div class="customer-field">
            <label for="search">Cari pelanggan</label>
            <input type="text" id="search" name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama atau email pelanggan">
        </div>

        <div class="customer-filter-actions">
            <button type="submit" class="customer-btn">Cari</button>
            <a href="{{ route('admin.customers.index') }}"
               class="customer-btn customer-btn--ghost">Reset</a>
        </div>

    </form>

    {{-- ===== Tabel pelanggan ===== --}}
    <div class="customer-table-wrap">
        <table class="customer-table">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($customers as $customer)
                    <tr>
                        <td>{{ $customers->firstItem() + $loop->index }}</td>

                        <td>
                            <div class="customer-name">
                                <span class="customer-avatar">{{ strtoupper(mb_substr($customer->name, 0, 1)) }}</span>
                                {{ $customer->name }}
                            </div>
                        </td>

                        <td>{{ $customer->email }}</td>

                        <td>
                            @if ($customer->is_active)
                                <span class="customer-badge customer-badge--active">Aktif</span>
                            @else
                                <span class="customer-badge customer-badge--inactive">Nonaktif</span>
                            @endif
                        </td>

                        <td>
                            <div class="customer-row-actions">
                                <a href="{{ route('admin.customers.show', $customer) }}"
                                   class="customer-btn customer-btn--ghost customer-btn--small">Detail</a>

                                <a href="{{ route('admin.customers.edit', $customer) }}"
                                   class="customer-btn customer-btn--ghost customer-btn--small">Edit</a>

                                <form action="{{ route('admin.customers.toggle-status', $customer) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin mengubah status pelanggan ini?')">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                            class="customer-btn customer-btn--small {{ $customer->is_active ? 'customer-btn--danger' : 'customer-btn--success' }}">
                                        {{ $customer->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="customer-empty">Tidak ada data pelanggan.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    @if ($customers->hasPages())
        <div class="customer-pagination">
            {{ $customers->withQueryString()->links('pagination::default') }}
        </div>
    @endif

</div>
@endsection