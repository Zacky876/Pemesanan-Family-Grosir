@extends('admin.layouts.app')

@section('title', 'Ubah Stok')
@section('page_title', 'Ubah Stok')
@section('page_subtitle', $product->name)

@push('styles')
    @vite('resources/css/admin/stock.css')
@endpush

@section('content')
<div class="stock-page">

    <div class="stock-toolbar">
        <a href="{{ route('admin.stock.index') }}" class="stock-btn stock-btn--ghost">
            Kembali
        </a>
    </div>

    {{-- Pesan error global sudah ditampilkan otomatis oleh layout --}}

    {{-- ===== Ringkasan stok ===== --}}
    <div class="stock-summary">

        <div class="stock-tile">
            <span>Produk</span>
            <strong>{{ $product->name }}</strong>
        </div>

        <div class="stock-tile">
            <span>Stok saat ini</span>
            <strong>{{ $product->stock }} {{ $product->unit }}</strong>
        </div>

        <div class="stock-tile stock-tile--accent">
            <span>Stok setelah perubahan</span>
            <strong id="stockAfter">-</strong>
        </div>

    </div>

    {{-- ===== Form ===== --}}
    <form action="{{ route('admin.stock.store', $product) }}"
          method="POST"
          id="stockForm"
          data-current="{{ $product->stock }}"
          data-unit="{{ $product->unit }}"
          class="stock-card stock-form">

        @csrf

        <div class="stock-section">
            <h2>Perubahan stok</h2>
            <p>Catat stok masuk, stok keluar, atau penyesuaian hasil stok opname.</p>

            <div class="stock-grid">

                <div class="stock-field">
                    <label for="type">Jenis perubahan <span class="req">*</span></label>
                    <select name="type" id="type"
                            class="@error('type') is-invalid @enderror" required>
                        <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>Stok masuk</option>
                        <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Stok keluar</option>
                        <option value="adjustment" {{ old('type') == 'adjustment' ? 'selected' : '' }}>Penyesuaian stok</option>
                    </select>
                    @error('type') <span class="stock-error">{{ $message }}</span> @enderror
                </div>

                <div class="stock-field">
                    <label for="quantity">Jumlah ({{ $product->unit }}) <span class="req">*</span></label>
                    <input type="number" name="quantity" id="quantity"
                           min="0" value="{{ old('quantity') }}"
                           class="@error('quantity') is-invalid @enderror" required>
                    <span class="stock-hint" id="quantityHint">
                        Untuk stok masuk/keluar, isi jumlah perubahan.
                        Untuk penyesuaian, isi jumlah stok fisik terbaru.
                    </span>
                    @error('quantity') <span class="stock-error">{{ $message }}</span> @enderror
                </div>

                <div class="stock-field is-full">
                    <label for="reason">Alasan / keterangan <span class="req">*</span></label>
                    <textarea name="reason" id="reason" rows="4"
                              class="@error('reason') is-invalid @enderror"
                              placeholder="Contoh: Restock supplier atau hasil stok opname"
                              required>{{ old('reason') }}</textarea>
                    @error('reason') <span class="stock-error">{{ $message }}</span> @enderror
                </div>

            </div>
        </div>

        <div class="stock-actions">
            <a href="{{ route('admin.stock.index') }}" class="stock-btn stock-btn--ghost">Batal</a>
            <button type="submit" class="stock-btn">Simpan perubahan</button>
        </div>

    </form>

</div>
@endsection

@push('scripts')
    @vite('resources/js/admin/stock.js')
@endpush