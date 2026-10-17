
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    @vite(['resources/css/user.css'])
</head>
<body>
    <main class="dashboard-container">
        <header class="dashboard-header">
            <h1>Keranjang Belanja</h1>
            <a href="{{ route('user.products.index') }}" class="btn">
                Kembali ke Produk
            </a>
        </header>

        @if (session('success'))
            <p class="success-message">{{ session('success') }}</p>
        @endif

        @if (session('error'))
            <p class="error-message">{{ session('error') }}</p>
        @endif

        @if (count($items) > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $item['product']->name }}</td>

                                <td>
                                    Rp {{ number_format($item['product']->selling_price, 0, ',', '.') }}
                                </td>

                                <td>
                                    <form action="{{ route('user.cart.update', $item['product']) }}"
                                        method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <input type="number"
                                            name="quantity"
                                            min="1"
                                            max="{{ $item['product']->stock }}"
                                            value="{{ $item['quantity'] }}"
                                            required>

                                        <button type="submit" class="btn">
                                            Perbarui
                                        </button>
                                    </form>
                                </td>

                                <td>
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </td>

                                <td>
                                    <form action="{{ route('user.cart.remove', $item['product']) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus produk ini dari keranjang?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center;">
                                    Keranjang belanja masih kosong.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <section class="dashboard-card">
                <h2>Total Belanja</h2>
                <h3>Rp {{ number_format($total, 0, ',', '.') }}</h3>
                <p>Biaya pengiriman belum dihitung.</p>
                <a href="{{ route('user.checkout.index') }}" class="btn">
                    Lanjut ke Checkout
                </a>
            </section>
        @else
            <section class="dashboard-card">
                <p>Keranjang kamu masih kosong.</p>
                <a href="{{ route('user.products.index') }}" class="btn">
                    Mulai Belanja
                </a>
            </section>
        @endif
    </main>
</body>
</html>
