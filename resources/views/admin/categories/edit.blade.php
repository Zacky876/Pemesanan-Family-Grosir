@extends('admin.layouts.app')

@section('title', 'Edit Kategori')
@section('page_title', 'Edit Kategori')
@section('page_subtitle', 'Perbarui informasi kategori produk.')

@push('styles')
    @vite('resources/css/admin/categories.css')
@endpush

@section('content')
<div class="category-page">

    <div class="category-toolbar">
        <a href="{{ route('admin.categories.index') }}" class="category-btn category-btn--ghost">
            Kembali
        </a>
    </div>

    {{-- Pesan error global sudah ditampilkan otomatis oleh layout --}}

    <form action="{{ route('admin.categories.update', $category) }}"
          method="POST"
          class="category-card category-form">

        @csrf
        @method('PUT')

        <div class="category-section">
            <h2>Informasi kategori</h2>
            <p>Nama kategori akan tampil di daftar produk dan kasir.</p>

            <div class="category-grid">

                <div class="category-field">
                    <label for="name">Nama kategori <span class="req">*</span></label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $category->name) }}"
                           class="@error('name') is-invalid @enderror" required>
                    @error('name') <span class="category-error">{{ $message }}</span> @enderror
                </div>

                <div class="category-field">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" rows="4"
                              class="@error('description') is-invalid @enderror"
                              placeholder="Deskripsi singkat kategori (boleh dikosongkan)">{{ old('description', $category->description) }}</textarea>
                    @error('description') <span class="category-error">{{ $message }}</span> @enderror
                </div>

            </div>
        </div>

        <div class="category-actions">
            <a href="{{ route('admin.categories.index') }}" class="category-btn category-btn--ghost">Batal</a>
            <button type="submit" class="category-btn">Simpan perubahan</button>
        </div>

    </form>

</div>
@endsection