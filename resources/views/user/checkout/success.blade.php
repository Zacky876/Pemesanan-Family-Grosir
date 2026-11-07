<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil</title>
    
    @vite(['resources/css/user/user.css'])
</head>
<body>
    <main class="dashboard-container">
        <section class="dashboard-card">
            <h1>Pesanan Berhasil Dibuat</h1>

            <p>
                Kode pesanan:
                <strong>{{ $order->transaction_code }}</strong>
            </p>

            <p>
                Status pesanan:
                <strong>{{ ucfirst($order->status) }}</strong>
            </p>

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

            <h2>
                Total: Rp {{ number_format($order->total, 0, ',', '.') }}
            </h2>

            <hr>

            <h2>Informasi Pembayaran</h2>

            <p>
                Metode pembayaran:
                <strong>
                    @if ($order->payment_method === 'qris')
                        QRIS
                    @elseif ($order->payment_method === 'cash')
                        Tunai
                    @else
                        Belum ditentukan
                    @endif
                </strong>
            </p>

            <p>
                Status pembayaran:
                <strong>
                    @if ($order->payment_status === 'paid')
                        Dibayar
                    @elseif ($order->payment_status === 'failed')
                        Gagal
                    @else
                        Belum dibayar
                    @endif
                </strong>
            </p>

            @if ($order->payment_method === 'qris')
                <div class="dashboard-card">
                    <h2>Pembayaran QRIS</h2>

                    @if ($order->payment_status === 'paid')
                        <p>Pembayaran sudah dikonfirmasi oleh admin atau kasir.</p>
                    @elseif ($order->payment_status === 'failed')
                        <p>
                            Pembayaran gagal. Silakan hubungi admin
                            untuk mendapatkan bantuan.
                        </p>
                    @else
                        <p>
                            Silakan lakukan pembayaran menggunakan QRIS,
                            kemudian unggah bukti pembayaran untuk diperiksa.
                        </p>

                        <a
                            href="{{ route('user.payment.show', $order->id) }}"
                            class="btn"
                        >
                            Lihat Pembayaran QRIS
                        </a>
                    @endif
                </div>
            @elseif ($order->payment_method === 'cash')
                <div class="dashboard-card">
                    <h2>Pembayaran Tunai</h2>

                    @if ($order->payment_status === 'paid')
                        <p>Pembayaran tunai telah diterima oleh kasir.</p>
                    @else
                        <p>
                            Silakan lakukan pembayaran langsung kepada kasir.
                        </p>
                        <p>
                            Pesanan akan menunggu konfirmasi pembayaran.
                        </p>
                    @endif
                </div>
            @endif

            <p>Silakan menunggu pesanan diproses oleh kasir.</p>

            <a href="{{ route('user.products.index') }}" class="btn">
                Kembali Belanja
            </a>
        </section>
    </main>
</body>
</html>