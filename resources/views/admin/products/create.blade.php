@extends('admin.layouts.app')

@section('title', 'Tambah Produk')
@section('page_title', 'Tambah Produk')
@section('page_subtitle', 'Tambahkan produk baru ke dalam sistem.')

@push('styles')
    @vite('resources/css/admin/products.css')
@endpush

@section('content')
<div class="product-page">

    <div class="product-toolbar">
        <a href="{{ route('admin.products.index') }}" class="product-btn product-btn--ghost">
            Kembali
        </a>
    </div>

    {{-- Pesan error global sudah ditampilkan otomatis oleh layout --}}

    <form action="{{ route('admin.products.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="product-card product-form">

        @csrf

        {{-- ===== Informasi produk ===== --}}
        <div class="product-section">
            <h2>Informasi produk</h2>
            <p>Data utama yang tampil di daftar produk dan kasir.</p>

            <div class="product-grid">

                <div class="product-field">
                    <label for="code">Kode produk <span class="req">*</span></label>
                    <input type="text" id="code" name="code"
                           value="{{ old('code') }}"
                           class="@error('code') is-invalid @enderror"
                           placeholder="Contoh: PRD001" required>
                    @error('code') <span class="product-error">{{ $message }}</span> @enderror
                </div>

                <div class="product-field">
                    <label for="name">Nama produk <span class="req">*</span></label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name') }}"
                           class="@error('name') is-invalid @enderror"
                           placeholder="Masukkan nama produk" required>
                    @error('name') <span class="product-error">{{ $message }}</span> @enderror
                </div>

                <div class="product-field">
                    <label for="category_id">Kategori <span class="req">*</span></label>
                    <select id="category_id" name="category_id"
                            class="@error('category_id') is-invalid @enderror" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="product-error">{{ $message }}</span> @enderror
                </div>

                <div class="product-field">
                    <label for="unit">Satuan <span class="req">*</span></label>
                    <select id="unit" name="unit"
                            class="@error('unit') is-invalid @enderror" required>
                        @foreach (['pcs', 'botol', 'kg', 'liter', 'pack'] as $unit)
                            <option value="{{ $unit }}" {{ old('unit', 'pcs') == $unit ? 'selected' : '' }}>
                                {{ $unit }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit') <span class="product-error">{{ $message }}</span> @enderror
                </div>

            </div>
        </div>

        {{-- ===== Harga dan stok ===== --}}
        <div class="product-section">
            <h2>Harga dan stok</h2>
            <p>Harga dalam rupiah, tanpa titik atau koma.</p>

            <div class="product-grid">

                <div class="product-field">
                    <label for="purchase_price">Harga beli <span class="req">*</span></label>
                    <div class="product-input-group">
                        <span>Rp</span>
                        <input type="number" id="purchase_price" name="purchase_price"
                               value="{{ old('purchase_price') }}" min="0"
                               class="@error('purchase_price') is-invalid @enderror"
                               placeholder="5000" required>
                    </div>
                    @error('purchase_price') <span class="product-error">{{ $message }}</span> @enderror
                </div>

                <div class="product-field">
                    <label for="selling_price">Harga jual <span class="req">*</span></label>
                    <div class="product-input-group">
                        <span>Rp</span>
                        <input type="number" id="selling_price" name="selling_price"
                               value="{{ old('selling_price') }}" min="0"
                               class="@error('selling_price') is-invalid @enderror"
                               placeholder="7000" required>
                    </div>
                    @error('selling_price') <span class="product-error">{{ $message }}</span> @enderror
                </div>

                <div class="product-field">
                    <label for="stock">Stok awal <span class="req">*</span></label>
                    <input type="number" id="stock" name="stock"
                           value="{{ old('stock', 0) }}" min="0"
                           class="@error('stock') is-invalid @enderror" required>
                    @error('stock') <span class="product-error">{{ $message }}</span> @enderror
                </div>

                <div class="product-field">
                    <label for="status">Status produk <span class="req">*</span></label>
                    <select id="status" name="status"
                            class="@error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak aktif</option>
                    </select>
                    @error('status') <span class="product-error">{{ $message }}</span> @enderror
                </div>

            </div>
        </div>

        {{-- ===== Foto dan deskripsi ===== --}}
        <div class="product-section">
            <h2>Foto dan deskripsi</h2>
            <p>Bagian ini boleh dikosongkan.</p>

            <div class="product-grid">

                <div class="product-field is-full">
                    <label for="photo">Foto produk</label>
                    <input type="file" id="photo" name="photo"
                           class="@error('photo') is-invalid @enderror"
                           accept=".jpg,.jpeg,.png,.webp">
                    <span class="product-hint">Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.</span>
                    @error('photo') <span class="product-error">{{ $message }}</span> @enderror
                </div>

                <div class="product-field is-full">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" rows="5"
                              class="@error('description') is-invalid @enderror"
                              placeholder="Masukkan deskripsi produk">{{ old('description') }}</textarea>
                    @error('description') <span class="product-error">{{ $message }}</span> @enderror
                </div>

            </div>
        </div>

        {{-- ===== Tombol ===== --}}
        <div class="product-actions">
            <a href="{{ route('admin.products.index') }}" class="product-btn product-btn--ghost">Batal</a>
            <button type="submit" class="product-btn">Simpan produk</button>
        </div>

    </form>

</div>
@endsection