<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi</title>

    @vite(['resources/css/receipt.css'])
</head>
<body>

    <div class="receipt-container">

        <div class="receipt-header">
            <h2>YOUNGMART</h2>
            <p>Struk Pembelian</p>
            <p>Terima kasih telah berbelanja</p>
        </div>

        <hr>

        <div class="receipt-info">
            <p>
                <span>Kode Transaksi</span>
                <strong>{{ $transaction->transaction_code }}</strong>
            </p>

            <p>
                <span>Tanggal</span>
                <strong>{{ $transaction->transaction_date->format('d-m-Y H:i') }}</strong>
            </p>

            <p>
                <span>Kasir</span>
                <strong>{{ $transaction->user->name }}</strong>
            </p>

            <p>
                <span>Metode Pembayaran</span>
                <strong>Tunai</strong>
            </p>
        </div>

        <hr>

        <h3>Detail Pembelian</h3>

        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($transaction->details as $detail)
                    <tr>
                        <td>
                            {{ $detail->product->name }}
                            <small>
                                Rp{{ number_format($detail->price, 0, ',', '.') }}
                            </small>
                        </td>

                        <td>{{ $detail->quantity }}</td>

                        <td>
                            Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr>

        <div class="receipt-payment">
            <p>
                <span>Total Belanja</span>
                <strong>
                    Rp{{ number_format($transaction->total, 0, ',', '.') }}
                </strong>
            </p>

            <p>
                <span>Uang Dibayarkan</span>
                <strong>
                    Rp{{ number_format($transaction->payment, 0, ',', '.') }}
                </strong>
            </p>

            <p class="change">
                <span>Uang Kembalian</span>
                <strong>
                    Rp{{ number_format($transaction->change_amount, 0, ',', '.') }}
                </strong>
            </p>
        </div>

        <hr>

        <div class="receipt-footer">
            <p>Barang yang sudah dibeli tidak dapat dikembalikan.</p>
            <strong>Terima Kasih</strong>
        </div>

        <div class="receipt-actions no-print">
            <button onclick="window.print()" class="print-button">
                Cetak Struk
            </button>

            <a href="{{ route('kasir.pos') }}" class="back-button">
                Kembali ke POS
            </a>
        </div>

    </div>

</body>
</html>