
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk</title>
    @vite(['resources/css/user.css'])
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="header-title">
                <h1>Katalog Produk</h1>
                <p>Selamat berbelanja di minimarket kami.</p>
            </div>

            <div class="header-actions">
                <a href="{{ route('user.cart.index') }}" class="btn">
                    Lihat Keranjang
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>
        <p>
            <a href="{{ route('user.dashboard') }}">Kembali ke Dashboard</a>
        </p>

        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <div class="dashboard-card">
            <form
                action="{{ route('user.products.index') }}"
                method="GET"
                style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;"
            >
                <input
                    type="text"
                    name="search"
                    placeholder="Cari nama atau kode produk..."
                    value="{{ request('search') }}"
                >

                <select name="category_id">
                    <option value="">Semua Kategori</option>

                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit">Cari</button>

                <a href="{{ route('user.products.index') }}" class="btn">
                    Reset
                </a>
            </form>

            <p>
                Jumlah produk ditemukan: {{ $products->total() }}
            </p>

            <div class="product-grid">
                @forelse ($products as $product)
                    <div class="product-item">
                        @if ($product->photo)
                            <img
                                src="{{ asset('storage/' . $product->photo) }}"
                                alt="{{ $product->name }}"
                                class="product-image"
                            >
                        @else
                            <div class="product-no-image">
                                Tidak ada foto
                            </div>
                        @endif

                        <h3>{{ $product->name }}</h3>

                        <p>Kode: {{ $product->code }}</p>

                        <p>
                            Kategori:
                            {{ $product->category->name ?? 'Tanpa kategori' }}
                        </p>

                        <p>
                            Harga:
                            <strong>
                                Rp{{ number_format($product->selling_price, 0, ',', '.') }}
                            </strong>
                        </p>

                        <p>
                            Stok: {{ $product->stock }} {{ $product->unit }}
                        </p>

                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <a href="{{ route('user.products.show', $product) }}" class="btn">
                                Detail Produk
                            </a>

                            <form action="{{ route('user.cart.add', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn">
                                    Tambah ke Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p>Produk tidak ditemukan atau stok sedang habis.</p>
                @endforelse
            </div>

            <div style="margin-top: 20px;">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</body>
</html>
