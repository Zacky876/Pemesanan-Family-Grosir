<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pembayaran</title>
    
    @vite(['resources/css/admin/admin.css'])
</head>
<body>
    <main class="dashboard-container">
        <div class="dashboard-header">
            <div>
                <h1>Verifikasi Pembayaran</h1>
                <p>Periksa pembayaran pelanggan sebelum transaksi masuk ke penjualan.</p>
            </div>
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
            <h2>Pesanan Menunggu Pembayaran</h2>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Kode Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Metode</th>
                            <th>Total</th>
                            <th>Bukti QRIS</th>
                            <th>Konfirmasi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->transaction_code }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>
                                    {{ $order->payment_method === 'qris' ? 'QRIS' : 'Tunai' }}
                                </td>
                                <td>
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>

                                <td>
                                    @if ($order->payment_method === 'qris')
                                        @if ($order->payment_proof)
                                            <a
                                                href="{{ route('admin.payments.proof', $order->id) }}"
                                                target="_blank"
                                                rel="noopener"
                                            >
                                                Lihat Bukti
                                            </a>
                                        @else
                                            Belum diunggah
                                        @endif
                                    @else
                                        Tidak diperlukan
                                    @endif
                                </td>

                                <td>
                                    @if ($order->payment_method === 'qris' && empty($order->payment_proof))
                                        <p>Menunggu bukti QRIS</p>
                                    @else
                                        <form
                                            action="{{ route('admin.payments.confirm', $order->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Pastikan pembayaran benar-benar sudah diterima. Konfirmasi transaksi ini?')"
                                        >
                                            @csrf

                                            @if ($order->payment_method === 'cash')
                                                <label for="payment-{{ $order->id }}">
                                                    Uang diterima (Rp)
                                                </label>
                                                <input
                                                    id="payment-{{ $order->id }}"
                                                    type="number"
                                                    name="payment"
                                                    min="{{ $order->total }}"
                                                    step="1"
                                                    required
                                                >
                                            @endif

                                            <button type="submit" class="btn">
                                                Konfirmasi Dibayar
                                            </button>
                                        </form>

                                        <form
                                            action="{{ route('admin.payments.fail', $order->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Tandai pembayaran ini gagal dan batalkan pesanan?')"
                                        >
                                            @csrf
                                            <button type="submit" class="btn">
                                                Tandai Gagal
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    Belum ada pesanan yang menunggu pembayaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>