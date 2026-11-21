@extends('admin.layouts.app')

@section('title', 'Data Produk')
@section('page_title', 'Data Produk')
@section('page_subtitle', 'Kelola produk minimarket.')

@push('styles')
    @vite('resources/css/admin/products.css')
@endpush

@section('content')
<div class="product-page">

    {{-- Pesan sukses/error ditampilkan otomatis oleh layout --}}

    <div class="product-toolbar">
        <span class="product-hint" style="margin:0;">
            {{ $products->count() }} produk ditampilkan
        </span>

        <a href="{{ route('admin.products.create') }}" class="product-btn">
            + Tambah produk
        </a>
    </div>

    {{-- ===== Cari dan filter ===== --}}
    <form action="{{ route('admin.products.index') }}" method="GET"
          class="product-card product-filter">

        <div class="product-field">
            <label for="search">Cari produk</label>
            <input type="text" id="search" name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama atau kode produk">
        </div>

        <div class="product-field">
            <label for="category_id">Kategori</label>
            <select id="category_id" name="category_id">
                <option value="">Semua kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="product-filter-actions">
            <button type="submit" class="product-btn">Cari</button>
            <a href="{{ route('admin.products.index') }}" class="product-btn product-btn--ghost">Reset</a>
        </div>

    </form>

    {{-- ===== Data produk ===== --}}
    @if ($products->count() > 0)

        <div class="product-table-wrap">
            <table class="product-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga beli</th>
                        <th>Harga jual</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                @if ($product->photo)
                                    <img src="{{ asset('storage/' . $product->photo) }}"
                                         alt="{{ $product->name }}"
                                         class="product-thumb">
                                @else
                                    <span class="product-hint" style="margin:0;">Tidak ada</span>
                                @endif
                            </td>

                            <td>{{ $product->code }}</td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }} {{ $product->unit }}</td>

                            <td>
                                @if ($product->status == 'active')
                                    <span class="product-badge product-badge--active">Aktif</span>
                                @else
                                    <span class="product-badge product-badge--inactive">Tidak aktif</span>
                                @endif
                            </td>

                            <td>
                                <div class="product-row-actions">
                                    <a href="{{ route('admin.products.show', $product) }}"
                                       class="product-btn product-btn--ghost product-btn--small">Detail</a>

                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="product-btn product-btn--ghost product-btn--small">Edit</a>

                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="product-btn product-btn--danger product-btn--small"
                                                onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    @else

        <div class="product-card product-empty">
            <p style="margin:0 0 14px;">Tidak ada produk yang ditemukan.</p>
            <a href="{{ route('admin.products.create') }}" class="product-btn">Tambah produk</a>
        </div>

    @endif

</div>
@endsection