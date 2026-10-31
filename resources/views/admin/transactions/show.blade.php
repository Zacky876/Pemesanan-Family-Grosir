<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>

    @vite(['resources/css/transactions.css'])
</head>
<body>

<div class="transaction-container">

    <h2>Detail Transaksi</h2>

    <p>
        <strong>Kode Transaksi:</strong>
        {{ $transaction->transaction_code }}
    </p>

    <p>
        <strong>Tanggal:</strong>
        {{ $transaction->transaction_date->format('d-m-Y H:i') }}
    </p>

    <p>
        <strong>Kasir:</strong>
        {{ $transaction->user->name }}
    </p>

    <p>
        <strong>Metode Pembayaran:</strong>
        {{ strtoupper($transaction->payment_method ?? '-') }}
    </p>

    <p>
        <strong>Status Transaksi:</strong>
        {{ ucfirst($transaction->status) }}
    </p>

    <p>
        <strong>Status Pembayaran:</strong>
        {{ ucfirst($transaction->payment_status ?? '-') }}
    </p>

    <h3>Daftar Produk</h3>

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
            @foreach ($transaction->details as $detail)
                <tr>
                    <td>{{ $detail->product->name }}</td>
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
        Total:
        Rp{{ number_format($transaction->total, 0, ',', '.') }}
    </h3>

    <p>
        <strong>Uang Dibayarkan:</strong>
        Rp{{ number_format($transaction->payment, 0, ',', '.') }}
    </p>

    <p>
        <strong>Kembalian:</strong>
        Rp{{ number_format($transaction->change_amount, 0, ',', '.') }}
    </p>

    <a href="{{ route('admin.transactions.index') }}">
        Kembali ke Riwayat Transaksi
    </a>

</div>

</body>
</html>