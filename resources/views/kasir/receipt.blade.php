<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi - Youngmart</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/kasir/receipt.css'])
</head>
<body>

    <div class="receipt-container">

        <div class="receipt-header">
            <span class="receipt-logo">YM</span>
            <h2>YOUNGMART</h2>
            <p>Struk pembelian</p>
            <p>Terima kasih telah berbelanja</p>
        </div>

        <hr>

        <div class="receipt-info">
            <p>
                <span>Kode transaksi</span>
                <strong>{{ $transaction->transaction_code }}</strong>
            </p>

            <p>
                <span>Tanggal</span>
                <strong>{{ $transaction->transaction_date->format('d-m-Y H:i') }}</strong>
            </p>

            <p>
                <span>Kasir</span>
                <strong>{{ $transaction->user->name ?? '-' }}</strong>
            </p>

            <p>
                <span>Metode pembayaran</span>
                <strong>Tunai</strong>
            </p>
        </div>

        <hr>

        <h3>Detail pembelian</h3>

        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="is-num">Qty</th>
                    <th class="is-num">Subtotal</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($transaction->details as $detail)
                    <tr>
                        <td>
                            {{ $detail->product->name ?? '-' }}
                            <small>Rp {{ number_format($detail->price, 0, ',', '.') }}</small>
                        </td>

                        <td class="is-num">{{ $detail->quantity }}</td>

                        <td class="is-num">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr>

        <div class="receipt-payment">
            <p>
                <span>Total belanja</span>
                <strong>Rp {{ number_format($transaction->total, 0, ',', '.') }}</strong>
            </p>

            <p>
                <span>Uang dibayarkan</span>
                <strong>Rp {{ number_format($transaction->payment, 0, ',', '.') }}</strong>
            </p>

            <p class="change">
                <span>Uang kembalian</span>
                <strong>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</strong>
            </p>
        </div>

        <hr>

        <div class="receipt-footer">
            <p>Barang yang sudah dibeli tidak dapat dikembalikan.</p>
            <strong>Terima kasih</strong>
        </div>

        <div class="receipt-actions no-print">
            <button onclick="window.print()" class="print-button">
                Cetak struk
            </button>

            <a href="{{ route('kasir.pos') }}" class="back-button">
                Kembali ke POS
            </a>
        </div>

    </div>

</body>
</html>