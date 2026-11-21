@extends('admin.layouts.app')

@section('title', 'Detail Pelanggan')
@section('page_title', 'Detail Pelanggan')
@section('page_subtitle', $customer->name)

@push('styles')
    @vite('resources/css/admin/customers.css')
@endpush

@section('content')
<div class="customer-page">

    <div class="customer-toolbar">
        <a href="{{ route('admin.customers.index') }}" class="customer-btn customer-btn--ghost">
            Kembali
        </a>

        <div class="customer-toolbar-actions">
            <form action="{{ route('admin.customers.toggle-status', $customer) }}"
                  method="POST"
                  onsubmit="return confirm('Yakin ingin mengubah status pelanggan ini?')">
                @csrf
                @method('PATCH')

                <button type="submit"
                        class="customer-btn {{ $customer->is_active ? 'customer-btn--danger' : 'customer-btn--success' }}">
                    {{ $customer->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>

            <a href="{{ route('admin.customers.edit', $customer) }}" class="customer-btn">
                Edit pelanggan
            </a>
        </div>
    </div>

    {{-- ===== Ringkasan ===== --}}
    <div class="customer-summary">

        <div class="customer-tile customer-tile--accent">
            <span>Status akun</span>
            <strong>{{ $customer->is_active ? 'Aktif' : 'Nonaktif' }}</strong>
        </div>

        <div class="customer-tile">
            <span>Terdaftar sejak</span>
            <strong>{{ $customer->created_at?->format('d-m-Y') ?? '-' }}</strong>
        </div>

        <div class="customer-tile">
            <span>Terakhir diperbarui</span>
            <strong>{{ $customer->updated_at?->format('d-m-Y') ?? '-' }}</strong>
        </div>

    </div>

    {{-- ===== Informasi pelanggan ===== --}}
    <div class="customer-card">

        <div class="customer-profile">
            <span class="customer-avatar customer-avatar--large">{{ strtoupper(mb_substr($customer->name, 0, 1)) }}</span>

            <div>
                <h2>{{ $customer->name }}</h2>
                <p>{{ $customer->email }}</p>

                @if ($customer->is_active)
                    <span class="customer-badge customer-badge--active">Aktif</span>
                @else
                    <span class="customer-badge customer-badge--inactive">Nonaktif</span>
                @endif
            </div>
        </div>

        <dl class="customer-dl">
            <div>
                <dt>ID pelanggan</dt>
                <dd>{{ $customer->id }}</dd>
            </div>

            <div>
                <dt>Nama</dt>
                <dd>{{ $customer->name }}</dd>
            </div>

            <div>
                <dt>Email</dt>
                <dd>{{ $customer->email }}</dd>
            </div>

            <div>
                <dt>Role</dt>
                <dd>{{ ucfirst($customer->role) }}</dd>
            </div>

            <div>
                <dt>Status akun</dt>
                <dd>{{ $customer->is_active ? 'Aktif' : 'Nonaktif' }}</dd>
            </div>

            <div>
                <dt>Tanggal dibuat</dt>
                <dd>{{ $customer->created_at?->format('d-m-Y H:i') ?? '-' }}</dd>
            </div>

            <div>
                <dt>Terakhir diperbarui</dt>
                <dd>{{ $customer->updated_at?->format('d-m-Y H:i') ?? '-' }}</dd>
            </div>
        </dl>

    </div>

</div>
@endsection