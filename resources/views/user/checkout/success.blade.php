
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil</title>
    @vite(['resources/css/user.css'])
</head>
<body>
    <main class="dashboard-container">
        <section class="dashboard-card">
            <h1>Pesanan Berhasil Dibuat</h1>

            <p>Kode pesanan: <strong>{{ $order->transaction_code }}</strong></p>
            <p>Status: {{ ucfirst($order->status) }}</p>

            <h2>Detail Pesanan</h2>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td>
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <h2>Total: Rp {{ number_format($order->total, 0, ',', '.') }}</h2>
            <p>Silakan menunggu pesanan diproses oleh kasir.</p>

            <a href="{{ route('user.products.index') }}" class="btn">
                Kembali Belanja
            </a>
        </section>
    </main>
</body>
</html>
