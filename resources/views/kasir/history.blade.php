<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi Kasir</title>

    @vite(['resources/css/kasir/transactions.css'])
</head>
<body>

<div class="transaction-container">

    <h2>Riwayat Transaksi Kasir</h2>

    <form action="{{ route('kasir.history') }}"
          method="GET"
          class="filter-form">

        <div class="filter-group">
            <label for="search">Cari Transaksi</label>
            <input
                type="text"
                id="search"
                name="search"
                placeholder="Cari kode transaksi..."
                value="{{ request('search') }}"
            >
        </div>

        <div class="filter-group">
            <label for="start_date">Tanggal Mulai</label>
            <input
                type="date"
                id="start_date"
                name="start_date"
                value="{{ request('start_date') }}"
            >
        </div>

        <div class="filter-group">
            <label for="end_date">Tanggal Akhir</label>
            <input
                type="date"
                id="end_date"
                name="end_date"
                value="{{ request('end_date') }}"
            >
        </div>

        <div class="filter-actions">
            <button type="submit" class="filter-button">
                Filter
            </button>

            <a href="{{ route('kasir.history') }}" class="reset-button">
                Reset
            </a>
        </div>

    </form>

    <table class="transaction-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td>
                        {{ $transactions->firstItem() + $loop->index }}
                    </td>

                    <td>{{ $transaction->transaction_code }}</td>

                    <td>
                        {{ $transaction->transaction_date->format('d-m-Y H:i') }}
                    </td>

                    <td>
                        Rp{{ number_format($transaction->total, 0, ',', '.') }}
                    </td>

                    <td>{{ ucfirst($transaction->status) }}</td>

                    <td>
                        <a
                            href="{{ route('kasir.transaction.receipt', $transaction->id) }}"
                            class="detail-link"
                        >
                            Detail / Cetak Struk
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">
                        Belum ada transaksi.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $transactions->links() }}

    <p>
        <a href="{{ route('kasir.dashboard') }}">Kembali ke Dashboard</a>
    </p>

</div>

</body>
</html>