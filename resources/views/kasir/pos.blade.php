@extends('kasir.layouts.app')

@section('title', 'POS Kasir')
@section('page_title', 'POS Kasir')
@section('page_subtitle', 'Selamat datang, ' . auth()->user()->name)

@push('styles')
    @vite('resources/css/kasir/cashier.css')
@endpush

@section('content')

    {{-- Pesan sukses/error ditampilkan otomatis oleh layout --}}

    <div class="pos-layout">

        {{-- ================= KIRI: DAFTAR PRODUK ================= --}}
        <section class="product-panel">

            <div class="panel-head">
                <h2>Daftar produk</h2>
                <p>Pilih produk yang ingin dibeli pelanggan.</p>
            </div>

            {{-- Pencarian --}}
            <section class="search-section">
                <form action="{{ route('kasir.pos') }}" method="GET">

                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ $search }}"
                        placeholder="Cari nama atau kode produk"
                    >

                    <button type="submit" class="search-button">Cari</button>

                    <a href="{{ route('kasir.pos') }}" class="reset-button">Reset</a>

                </form>
            </section>

            {{-- Produk --}}
            <section class="product-section">

                <div class="product-grid">

                    @forelse ($products as $product)
                        @php
                            $habis  = $product->stock < 1;
                            $batas  = $product->minimum_stock ?? 5;
                            $menipis = ! $habis && $product->stock <= $batas;
                        @endphp

                        <div class="product-card {{ $habis ? 'is-out' : '' }}">

                            <div class="product-image">
                                @if ($product->photo)
                                    <img src="{{ asset('storage/' . $product->photo) }}"
                                         alt="{{ $product->name }}">
                                @else
                                    <div class="no-image">Tidak ada foto</div>
                                @endif
                            </div>

                            <div class="product-info">

                                <span class="product-code">{{ $product->code }}</span>

                                <h3>{{ $product->name }}</h3>

                                <p class="product-category">{{ $product->category->name ?? '-' }}</p>

                                <p class="product-price">
                                    Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                </p>

                                <p class="product-stock {{ $habis ? 'is-empty' : ($menipis ? 'is-low' : '') }}">
                                    Stok: {{ $product->stock }} {{ $product->unit }}
                                </p>

                                <form action="{{ route('kasir.cart.add', $product) }}" method="POST">
                                    @csrf

                                    <button type="submit"
                                            class="add-button"
                                            {{ $habis ? 'disabled' : '' }}>
                                        {{ $habis ? 'Stok habis' : '+ Tambah ke keranjang' }}
                                    </button>
                                </form>

                            </div>

                        </div>

                    @empty

                        <div class="empty-product">
                            <h3>Produk tidak ditemukan</h3>
                            <p>Coba gunakan nama atau kode produk lainnya.</p>
                        </div>

                    @endforelse

                </div>

                @if ($products->hasPages())
                    <div class="pagination">
                        {{ $products->withQueryString()->links('pagination::default') }}
                    </div>
                @endif

            </section>

        </section>

        {{-- ================= KANAN: KERANJANG ================= --}}
        <aside class="cart-sidebar">

            <div class="cart-header">
                <h2>Keranjang transaksi</h2>
                <span>{{ count($cartItems) }} produk</span>
            </div>

            @if (count($cartItems) > 0)

                <div class="cart-items">

                    @foreach ($cartItems as $item)

                        <div class="cart-item">

                            <div class="cart-item-info">
                                <strong>{{ $item['product']->name }}</strong>
                                <span>
                                    Rp {{ number_format($item['product']->selling_price, 0, ',', '.') }}
                                    / {{ $item['product']->unit }}
                                </span>
                            </div>

                            <div class="cart-item-subtotal">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </div>

                            <div class="cart-item-actions">

                                <form action="{{ route('kasir.cart.update', $item['product']) }}"
                                      method="POST"
                                      class="quantity-form">
                                    @csrf
                                    @method('PUT')

                                    <input type="number"
                                           name="quantity"
                                           value="{{ $item['quantity'] }}"
                                           min="1"
                                           max="{{ $item['product']->stock }}"
                                           required>

                                    <button type="submit" class="update-button">Ubah</button>
                                </form>

                                <form action="{{ route('kasir.cart.remove', $item['product']) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus produk dari keranjang?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="remove-button">Hapus</button>
                                </form>

                            </div>

                        </div>

                    @endforeach

                </div>

                <div class="cart-footer">

                    <div class="cart-total">
                        <span>Total belanja</span>
                        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                    </div>

                    <form action="{{ route('kasir.transaction.store') }}" method="POST">
                        @csrf

                        <div class="payment-section">
                            <label for="payment">Uang dibayarkan</label>

                            <input type="number"
                                   name="payment"
                                   id="payment"
                                   min="0"
                                   step="1"
                                   placeholder="Masukkan nominal uang"
                                   required>

                            <button type="submit" class="pay-button">Bayar tunai</button>
                        </div>
                    </form>

                </div>

            @else

                <div class="empty-cart">
                    <h3>Keranjang masih kosong</h3>
                    <p>Silakan pilih produk untuk memulai transaksi.</p>
                </div>

            @endif

        </aside>

    </div>

@endsection

@push('scripts')
    @vite('resources/js/kasir/cashier.js')
@endpush