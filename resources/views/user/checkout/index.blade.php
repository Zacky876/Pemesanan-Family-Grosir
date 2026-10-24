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
            <h2>Detail Pesanan</h2>

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

            <h2>
                Total: Rp {{ number_format($total, 0, ',', '.') }}
            </h2>

            <hr>

            <h2>Metode Pembayaran</h2>
            <p>Pilih metode pembayaran untuk pesanan ini.</p>

            <form
                action="{{ route('user.checkout.store') }}"
                method="POST"
                onsubmit="return confirm('Apakah kamu yakin ingin membuat pesanan ini?')"
            >
                @csrf

                <div>
                    <label>
                        <input
                            type="radio"
                            name="payment_method"
                            value="qris"
                            {{ old('payment_method', 'qris') === 'qris' ? 'checked' : '' }}
                            required
                        >
                        **QRIS**
                    </label>
                    <p>
                        Bayar menggunakan QRIS dan unggah bukti pembayaran
                        untuk diperiksa admin atau kasir.
                    </p>
                </div>

                <div>
                    <label>
                        <input
                            type="radio"
                            name="payment_method"
                            value="cash"
                            {{ old('payment_method') === 'cash' ? 'checked' : '' }}
                            required
                        >
                        **Tunai di Kasir**
                    </label>
                    <p>
                        Bayar langsung kepada kasir saat melakukan pembayaran.
                    </p>
                </div>

                <hr>

                <p>
                    Status pesanan setelah checkout: Menunggu diproses.
                </p>
                <p>
                    Status pembayaran awal: Belum dibayar.
                </p>

                <button type="submit" class="btn">
                    Konfirmasi Pesanan
                </button>
            </form>
        </section>
    </main>
</body>
</html>