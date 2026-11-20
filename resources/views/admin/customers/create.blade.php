@extends('admin.layouts.app')

@section('title', 'Tambah Pelanggan')
@section('page_title', 'Tambah Pelanggan')
@section('page_subtitle', 'Tambahkan akun pelanggan baru agar bisa melakukan pemesanan.')

@push('styles')
    @vite('resources/css/admin/customers.css')
@endpush

@section('content')
<div class="customer-page">

    <div class="customer-toolbar">
        <a href="{{ route('admin.customers.index') }}" class="customer-btn customer-btn--ghost">
            Kembali
        </a>
    </div>

    {{-- Pesan error global sudah ditampilkan otomatis oleh layout --}}

    <form action="{{ route('admin.customers.store') }}"
          method="POST"
          class="customer-card customer-form">

        @csrf

        <div class="customer-section">
            <h2>Informasi pelanggan</h2>
            <p>Email dan password dipakai pelanggan untuk masuk ke akunnya.</p>

            <div class="customer-grid">

                <div class="customer-field">
                    <label for="name">Nama pelanggan <span class="req">*</span></label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name') }}"
                           class="@error('name') is-invalid @enderror"
                           placeholder="Contoh: Budi Santoso" required>
                    @error('name') <span class="customer-error">{{ $message }}</span> @enderror
                </div>

                <div class="customer-field">
                    <label for="email">Email <span class="req">*</span></label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           class="@error('email') is-invalid @enderror"
                           placeholder="contoh@email.com" required>
                    @error('email') <span class="customer-error">{{ $message }}</span> @enderror
                </div>

                <div class="customer-field">
                    <label for="password">Password <span class="req">*</span></label>
                    <input type="password" id="password" name="password"
                           class="@error('password') is-invalid @enderror"
                           placeholder="Minimal 8 karakter" required>
                    @error('password') <span class="customer-error">{{ $message }}</span> @enderror
                </div>

            </div>
        </div>

        <div class="customer-actions">
            <a href="{{ route('admin.customers.index') }}" class="customer-btn customer-btn--ghost">Batal</a>
            <button type="submit" class="customer-btn">Simpan pelanggan</button>
        </div>

    </form>

</div>
@endsection