
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>POS Kasir</title>

    @vite([
        'resources/css/cashier.css',
        'resources/js/cashier.js'
    ])
</head>

<body>

    <div class="cashier-container">

        <!-- HEADER -->
        <header class="cashier-header">
            <div>
                <h1>POS Kasir</h1>
                <p>Selamat datang, {{ auth()->user()->name }}</p>
            </div>

            <a href="{{ route('kasir.dashboard') }}" class="back-button">
                Kembali
            </a>
        </header>


        <main class="cashier-content">

            <!-- NOTIFIKASI -->
            @if(session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="error-message">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif


            <!-- LAYOUT POS -->
            <div class="pos-layout">

                <!-- BAGIAN KIRI: DAFTAR PRODUK -->
                <section class="product-panel">

                    <section class="page-title">
                        <h2>Daftar Produk</h2>
                        <p>Pilih produk yang ingin dibeli pelanggan.</p>
                    </section>


                    <!-- PENCARIAN -->
                    <section class="search-section">

                        <form action="{{ route('kasir.pos') }}" method="GET">

                            <input
                                type="text"
                                name="search"
                                id="search"
                                value="{{ $search }}"
                                placeholder="Cari nama atau kode produk..."
                            >

                            <button type="submit">Cari</button>

                            <a href="{{ route('kasir.pos') }}" class="reset-button">
                                Reset
                            </a>

                        </form>

                    </section>


                    <!-- PRODUK -->
                    <section class="product-section">

                        <div class="product-grid">

                            @forelse($products as $product)

                                <div class="product-card">

                                    <div class="product-image">

                                        @if($product->photo)
                                            <img
                                                src="{{ asset('storage/' . $product->photo) }}"
                                                alt="{{ $product->name }}"
                                            >
                                        @else
                                            <div class="no-image">
                                                Tidak ada foto
                                            </div>
                                        @endif

                                    </div>


                                    <div class="product-info">

                                        <span class="product-code">
                                            {{ $product->code }}
                                        </span>

                                        <h3>{{ $product->name }}</h3>

                                        <p class="product-category">
                                            {{ $product->category->name ?? '-' }}
                                        </p>

                                        <p class="product-price">
                                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                        </p>

                                        <p class="product-stock">
                                            Stok: {{ $product->stock }} {{ $product->unit }}
                                        </p>


                                        <form
                                            action="{{ route('kasir.cart.add', $product) }}"
                                            method="POST"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="add-button"
                                                {{ $product->stock < 1 ? 'disabled' : '' }}
                                            >
                                                {{ $product->stock < 1 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
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


                        <!-- PAGINATION -->
                        <div class="pagination">
                            {{ $products->links() }}
                        </div>

                    </section>

                </section>


                <!-- BAGIAN KANAN: KERANJANG -->
                <aside class="cart-sidebar">

                    <div class="cart-header">
                        <h2>Keranjang Transaksi</h2>

                        <span>
                            {{ count($cartItems) }} produk
                        </span>
                    </div>


                    @if(count($cartItems) > 0)

                        <!-- DAFTAR ITEM KERANJANG -->
                        <div class="cart-items">

                            @foreach($cartItems as $item)

                                <div class="cart-item">

                                    <div class="cart-item-info">

                                        <strong>
                                            {{ $item['product']->name }}
                                        </strong>

                                        <span>
                                            Rp {{ number_format($item['product']->selling_price, 0, ',', '.') }}
                                            / {{ $item['product']->unit }}
                                        </span>

                                    </div>


                                    <div class="cart-item-subtotal">
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </div>


                                    <!-- UBAH JUMLAH DAN HAPUS -->
                                    <div class="cart-item-actions">

                                        <form
                                            action="{{ route('kasir.cart.update', $item['product']) }}"
                                            method="POST"
                                            class="quantity-form"
                                        >
                                            @csrf
                                            @method('PUT')

                                            <input
                                                type="number"
                                                name="quantity"
                                                value="{{ $item['quantity'] }}"
                                                min="1"
                                                max="{{ $item['product']->stock }}"
                                                required
                                            >

                                            <button type="submit" class="update-button">
                                                Ubah
                                            </button>

                                        </form>


                                        <form
                                            action="{{ route('kasir.cart.remove', $item['product']) }}"
                                            method="POST"
                                            onsubmit="return confirm('Hapus produk dari keranjang?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="remove-button">
                                                Hapus
                                            </button>

                                        </form>

                                    </div>

                                </div>

                            @endforeach

                        </div>


                        <!-- TOTAL -->
                        <div class="cart-footer">

                            <div class="cart-total">

                                <span>Total Belanja</span>

                                <strong>
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </strong>

                            </div>
                            <form action="{{ route('kasir.transaction.store') }}" method="POST">
                            @csrf

                            <div class="payment-section">
                                <label for="payment">Uang Dibayarkan</label>

                                <input
                                    type="number"
                                    name="payment"
                                    id="payment"
                                    min="0"
                                    step="1"
                                    placeholder="Masukkan nominal uang"
                                    required
                                >

                                @error('payment')
                                    <small class="error-message">{{ $message }}</small>
                                @enderror

                                @error('cart')
                                    <small class="error-message">{{ $message }}</small>
                                @enderror

                                <button type="submit" class="pay-button">
                                    Bayar Tunai
                                </button>
                            </div>
                        </form>

                        </div>


                    @else

                        <!-- KERANJANG KOSONG -->
                        <div class="empty-cart">
                            <h3>Keranjang masih kosong</h3>

                            <p>
                                Silakan pilih produk untuk memulai transaksi.
                            </p>
                        </div>

                    @endif

                </aside>

            </div>

        </main>

    </div>

</body>
</html>
