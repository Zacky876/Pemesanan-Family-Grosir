<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>

    @vite(['resources/css/admin/transactions.css'])
</head>
<body>

<div class="transaction-container">

    <h2>Riwayat Transaksi</h2>

    <form action="{{ route('admin.transactions.index') }}" method="GET" class="filter-form">

        <!-- Pencarian Transaksi -->
        <div class="filter-group">
            <label for="search">Cari Transaksi</label>

            <input type="text" id="search" name="search" placeholder="Cari kode transaksi..."value="{{ request('search') }}">
        </div>

        <!-- Tanggal Mulai -->
        <div class="filter-group">
            <label for="start_date">Tanggal Mulai</label>

            <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}">
        </div>

        <!-- Tanggal Akhir -->
        <div class="filter-group">
            <label for="end_date">Tanggal Akhir</label>

            <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}">
        </div>

        <!-- Filter Kasir -->
        <div class="filter-group">
            <label for="cashier">Kasir</label>

            <select name="cashier" id="cashier">
                <option value="">Semua Kasir</option>

                @foreach ($cashiers as $cashier)
                    <option value="{{ $cashier->id }}" {{ request('cashier') == $cashier->id ? 'selected' : '' }}>
                        {{ $cashier->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Filter Status -->
        <div class="filter-group">
            <label for="status">Status</label>

            <select name="status" id="status">
                <option value="">Semua Status</option>

                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                    Completed
                </option>

                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                    Cancelled
                </option>
            </select>
        </div>

        <!-- Tombol Filter dan Reset -->
        <div class="filter-actions">
            <button type="submit" class="filter-button">
                Filter
            </button>

            <a href="{{ route('admin.transactions.index') }}" class="reset-button">
                Reset
            </a>
        </div>

    </form>

    <!-- Tabel Riwayat Transaksi -->
    <table class="transaction-table">

        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Tanggal</th>
                <th>Kasir</th>
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

                    <td>
                        {{ $transaction->transaction_code }}
                    </td>

                    <td>
                        {{ $transaction->transaction_date->format('d-m-Y H:i') }}
                    </td>

                    <td>
                        {{ $transaction->user->name ?? '-' }}
                    </td>

                    <td>
                        Rp{{ number_format($transaction->total, 0, ',', '.') }}
                    </td>

                    <td>
                        {{ ucfirst($transaction->status) }}
                    </td>

                    <td>
                        <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="detail-link">
                            Detail
                        </a>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">
                        Belum ada transaksi.
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>

    {{ $transactions->links() }}

</div>

</body>
</html>