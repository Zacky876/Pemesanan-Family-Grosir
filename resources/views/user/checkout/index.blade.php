
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Checkout</title>
    @vite(['resources/css/user.css'])
</head>
<body>
    <main class="dashboard-container">
        <div class="dashboard-header">
            <div>
                <h1>Konfirmasi Checkout</h1>
                <p>Periksa pesanan sebelum dikirim.</p>
            </div>

            <a href="{{ route('user.cart.index') }}" class="btn">
                Kembali ke Keranjang
            </a>
        </div>

        @if ($errors->any())
            <div class="error-message">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="dashboard-card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item['product']->name }}</td>
                                <td>
                                    Rp {{ number_format($item['product']->selling_price, 0, ',', '.') }}
                                </td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <h2>Total: Rp {{ number_format($total, 0, ',', '.') }}</h2>
            <p>Status pesanan setelah checkout: Menunggu diproses.</p>
            <p>Pembayaran dilakukan saat pesanan diproses oleh kasir.</p>

            <form action="{{ route('user.checkout.store') }}" method="POST"
                  onsubmit="return confirm('Apakah kamu yakin ingin membuat pesanan ini?')">
                @csrf
                <button type="submit" class="btn">
                    Konfirmasi Pesanan
                </button>
            </form>
        </section>
    </main>
</body>
</html>
