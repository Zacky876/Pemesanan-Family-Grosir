
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan</title>
    @vite(['resources/css/user.css'])
</head>
<body>
    <main class="dashboard-container">
        <div class="dashboard-header">
            <div>
                <h1>Riwayat Pesanan</h1>
                <p>Lihat pesanan dan status pembelian kamu.</p>
            </div>

            <a href="{{ route('user.products.index') }}" class="btn">
                Kembali Belanja
            </a>
        </div>

        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <section class="dashboard-card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Kode Pesanan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->transaction_code }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($order->transaction_date)->format('d-m-Y H:i') }}
                                </td>
                                <td>
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if ($order->status === 'pending')
                                        Menunggu diproses
                                    @elseif ($order->status === 'completed')
                                        Selesai
                                    @elseif ($order->status === 'cancelled')
                                        Dibatalkan
                                    @else
                                        {{ ucfirst($order->status) }}
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('user.orders.show', $order->id) }}" class="btn">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    Kamu belum memiliki pesanan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px;">
                {{ $orders->links() }}
            </div>
        </section>
    </main>
</body>
</html>
