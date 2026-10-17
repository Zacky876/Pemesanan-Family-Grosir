
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>
    @vite(['resources/css/user.css'])
</head>
<body>
    <main class="dashboard-container">
        <div class="dashboard-header">
            <div>
                <h1>Detail Pesanan</h1>
                <p>Kode: {{ $order->transaction_code }}</p>
            </div>

            <a href="{{ route('user.orders.index') }}" class="btn">
                Kembali ke Riwayat
            </a>
        </div>

        <section class="dashboard-card">
            <p>
                <strong>Tanggal Pesanan:</strong>
                {{ \Carbon\Carbon::parse($order->transaction_date)->format('d-m-Y H:i') }}
            </p>

            <p>
                <strong>Status:</strong>
                @if ($order->status === 'pending')
                    Menunggu diproses
                @elseif ($order->status === 'completed')
                    Selesai
                @elseif ($order->status === 'cancelled')
                    Dibatalkan
                @else
                    {{ ucfirst($order->status) }}
                @endif
            </p>

            <h2>Daftar Produk</h2>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Kode Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->code }}</td>
                                <td>
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">Detail produk tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <h2>
                Total Pesanan:
                Rp {{ number_format($order->total, 0, ',', '.') }}
            </h2>
        </section>
    </main>
</body>
</html>
