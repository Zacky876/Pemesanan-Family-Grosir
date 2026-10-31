<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pembelian</title>

    @vite(['resources/css/transactions.css'])
</head>
<body>

<div class="transaction-container">

    <h2>Detail Pembelian</h2>

    <p>
        <strong>Kode Transaksi:</strong>
        {{ $order->transaction_code }}
    </p>

    <p>
        <strong>Tanggal:</strong>
        {{ $order->transaction_date->format('d-m-Y H:i') }}
    </p>

    <p>
        <strong>Status Pesanan:</strong>
        {{ ucfirst($order->status) }}
    </p>

    <p>
        <strong>Status Pembayaran:</strong>
        {{ ucfirst($order->payment_status ?? 'pending') }}
    </p>

    <p>
        <strong>Metode Pembayaran:</strong>
        {{ strtoupper($order->payment_method ?? '-') }}
    </p>

    <h3>Produk yang Dibeli</h3>

    <table class="transaction-table">

        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($order->details as $detail)
                <tr>
                    <td>{{ $detail->product->name ?? 'Produk tidak tersedia' }}</td>

                    <td>
                        Rp{{ number_format($detail->price, 0, ',', '.') }}
                    </td>

                    <td>{{ $detail->quantity }}</td>

                    <td>
                        Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    <h3>
        Total Pembelian:
        Rp{{ number_format($order->total, 0, ',', '.') }}
    </h3>

    <a href="{{ route('user.orders.index') }}">
        Kembali ke Riwayat Pembelian
    </a>

</div>

</body>
</html>