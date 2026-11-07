<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS</title>
    
    @vite(['resources/css/user/user.css'])
</head>
<body>
    <main class="dashboard-container">
        <div class="dashboard-header">
            <div>
                <h1>Pembayaran QRIS</h1>
                <p>Selesaikan pembayaran pesanan kamu.</p>
            </div>

            <a
                href="{{ route('user.checkout.success', $order->id) }}"
                class="btn"
            >
                Kembali ke Pesanan
            </a>
        </div>

        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

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
            <h2>Informasi Pesanan</h2>

            <p>
                Nomor transaksi:
                <strong>{{ $order->transaction_code }}</strong>
            </p>

            <h2>
                Total pembayaran:
                Rp {{ number_format($order->total, 0, ',', '.') }}
            </h2>

            <p>
                Metode pembayaran:
                <strong>QRIS</strong>
            </p>

            <p>
                Status pembayaran:
                <strong>
                    @if ($order->payment_status === 'paid')
                        Dibayar
                    @elseif ($order->payment_status === 'failed')
                        Gagal
                    @elseif (!empty($order->payment_proof))
                        Menunggu verifikasi
                    @else
                        Belum dibayar
                    @endif
                </strong>
            </p>

            @if ($order->payment_status === 'pending')
                <h2>Scan QRIS</h2>

                <p>
                    Scan QRIS resmi merchant menggunakan aplikasi pembayaran
                    yang mendukung QRIS. Pastikan pembayaran sesuai dengan
                    total pesanan.
                </p>

                <img
                    src="{{ asset('images/qris.png') }}"
                    alt="QRIS Merchant"
                    style="display:block; max-width:280px; width:100%; height:auto; margin:20px auto;"
                >

                @if (empty($order->payment_proof))
                    <p>
                        Setelah membayar, unggah bukti pembayaran untuk
                        diperiksa admin atau kasir.
                    </p>

                    <form
                        action="{{ route('user.payment.proof', $order->id) }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        <div>
                            <label for="payment_proof">
                                Bukti pembayaran (JPG, PNG, atau WebP,
                                maksimal 2 MB)
                            </label>

                            <input
                                type="file"
                                id="payment_proof"
                                name="payment_proof"
                                accept=".jpg,.jpeg,.png,.webp"
                                required
                            >
                        </div>

                        <button type="submit" class="btn">
                            Kirim Bukti Pembayaran
                        </button>
                    </form>
                @else
                    <p>
                        Bukti pembayaran sudah dikirim dan sedang menunggu
                        verifikasi admin atau kasir.
                    </p>
                @endif

            @elseif ($order->payment_status === 'paid')
                <p>Pembayaran telah dikonfirmasi oleh admin atau kasir.</p>

            @else
                <p>
                    Pembayaran gagal. Silakan hubungi admin atau kasir
                    untuk mendapatkan bantuan.
                </p>
            @endif
        </section>
    </main>
</body>
</html>