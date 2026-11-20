@extends('user.layout.app')

@section('title', 'Katalog Produk')
@section('page_title', 'Katalog Produk')
@section('page_subtitle', 'Selamat berbelanja di minimarket kami.')

@section('content')
@php
    $jumlah = method_exists($products, 'total') ? $products->total() : $products->count();
    $adaKeranjang = \Illuminate\Support\Facades\Route::has('user.cart.add');
    $adaDetail    = \Illuminate\Support\Facades\Route::has('user.products.show');
@endphp

<div class="catalog-page">

    {{-- Pesan sukses/error ditampilkan otomatis oleh layout --}}

    <div class="catalog-toolbar">
        <span class="catalog-hint">{{ $jumlah }} produk ditemukan</span>

        <a href="{{ route('user.cart.index') }}" class="catalog-btn catalog-btn--ghost">
            Lihat keranjang
        </a>
    </div>

    {{-- ===== Cari dan filter ===== --}}
    <form action="{{ route('user.products.index') }}" method="GET" class="catalog-card catalog-filter">

        <div class="catalog-field">
            <label for="search">Cari produk</label>
            <input type="text" id="search" name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama atau kode produk">
        </div>

        <div class="catalog-field">
            <label for="category_id">Kategori</label>
            <select id="category_id" name="category_id">
                <option value="">Semua kategori</option>
                @foreach (($categories ?? []) as $category)
                    <option value="{{ $category->id }}"
                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="catalog-filter-actions">
            <button type="submit" class="catalog-btn">Cari</button>
            <a href="{{ route('user.products.index') }}" class="catalog-btn catalog-btn--ghost">Reset</a>
        </div>

    </form>

    {{-- ===== Daftar produk ===== --}}
    @if ($products->count() > 0)

        <div class="catalog-grid">

            @foreach ($products as $product)
                @php
                    $habis   = $product->stock < 1;
                    $batas   = $product->minimum_stock ?? 5;
                    $menipis = ! $habis && $product->stock <= $batas;
                @endphp

                <article class="catalog-item {{ $habis ? 'is-out' : '' }}">

                    <div class="catalog-photo">
                        @if ($product->photo)
                            <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}">
                        @else
                            <span>Tidak ada foto</span>
                        @endif
                    </div>

                    <div class="catalog-info">

                        <span class="catalog-code">{{ $product->code }}</span>

                        <h3>{{ $product->name }}</h3>

                        <p class="catalog-category">{{ $product->category->name ?? '-' }}</p>

                        <p class="catalog-price">
                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                            <small>/ {{ $product->unit }}</small>
                        </p>

                        <p class="catalog-stock {{ $habis ? 'is-empty' : ($menipis ? 'is-low' : '') }}">
                            @if ($habis)
                                Stok habis
                            @else
                                Stok: {{ $product->stock }} {{ $product->unit }}
                            @endif
                        </p>

                        <div class="catalog-actions">

                            @if ($adaDetail)
                                <a href="{{ route('user.products.show', $product) }}"
                                   class="catalog-btn catalog-btn--ghost catalog-btn--small">Detail</a>
                            @endif

                            @if ($adaKeranjang)
                                <form action="{{ route('user.cart.add', $product) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">

                                    <button type="submit"
                                            class="catalog-btn catalog-btn--small"
                                            {{ $habis ? 'disabled' : '' }}>
                                        {{ $habis ? 'Stok habis' : '+ Keranjang' }}
                                    </button>
                                </form>
                            @endif

                        </div>

                    </div>

                </article>
            @endforeach

        </div>

        @if (method_exists($products, 'hasPages') && $products->hasPages())
            <div class="catalog-pagination">
                {{ $products->withQueryString()->links('pagination::default') }}
            </div>
        @endif

    @else

        <div class="catalog-card catalog-empty">
            <h3>Produk tidak ditemukan</h3>
            <p>Coba gunakan nama, kode, atau kategori produk lainnya.</p>
        </div>

    @endif

</div>
@endsection