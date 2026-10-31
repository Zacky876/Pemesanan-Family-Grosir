<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembelian</title>

    @vite(['resources/css/transactions.css'])
</head>
<body>

<div class="transaction-container">

    <h2>Riwayat Pembelian Saya</h2>

    <form action="{{ route('user.orders.index') }}"
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
            <label for="status">Status Pesanan</label>

            <select name="status" id="status">
                <option value="">Semua Status</option>

                <option value="pending"
                    {{ request('status') == 'pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option value="completed"
                    {{ request('status') == 'completed' ? 'selected' : '' }}>
                    Completed
                </option>

                <option value="cancelled"
                    {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                    Cancelled
                </option>
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="filter-button">
                Cari
            </button>

            <a href="{{ route('user.orders.index') }}"
               class="reset-button">
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
                <th>Status Pesanan</th>
                <th>Status Pembayaran</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>
                        {{ $orders->firstItem() + $loop->index }}
                    </td>

                    <td>{{ $order->transaction_code }}</td>

                    <td>
                        {{ $order->transaction_date->format('d-m-Y H:i') }}
                    </td>

                    <td>
                        Rp{{ number_format($order->total, 0, ',', '.') }}
                    </td>

                    <td>{{ ucfirst($order->status) }}</td>

                    <td>{{ ucfirst($order->payment_status ?? 'pending') }}</td>

                    <td>
                        <a
                            href="{{ route('user.orders.show', $order->id) }}"
                            class="detail-link"
                        >
                            Detail
                        </a>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">
                        Belum ada riwayat pembelian.
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>

    {{ $orders->links() }}

    <p>
        <a href="{{ route('user.dashboard') }}">Kembali ke Dashboard</a>
    </p>

</div>

</body>
</html>