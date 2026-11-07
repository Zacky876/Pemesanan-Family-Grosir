<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Stok</title>

    @vite(['resources/css/admin/admin.css'])
</head>

<body>

<div class="dashboard-container">

    <div class="dashboard-header">

        <div>

            <h1>Riwayat Stok</h1>

            <p>
                {{ $product->name }}
            </p>

        </div>

        <div>

            <a
                href="{{ route('admin.stock.create', $product) }}"
                class="btn">
                Ubah Stok
            </a>

            <a
                href="{{ route('admin.stock.index') }}"
                class="btn">
                Kembali
            </a>

        </div>

    </div>

    <div class="dashboard-card">

        <p>
            <strong>Stok Saat Ini:</strong>
            {{ $product->stock }} {{ $product->unit }}
        </p>

        <table>

            <thead>

                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Stok Sebelum</th>
                    <th>Stok Sesudah</th>
                    <th>Keterangan</th>
                    <th>User</th>
                </tr>

            </thead>

            <tbody>

                @forelse($histories as $history)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $history->created_at->format('d-m-Y H:i') }}
                        </td>

                        <td>

                            @if($history->type === 'in')
                                Stok Masuk
                            @else
                                Stok Keluar
                            @endif

                        </td>

                        <td>
                            {{ $history->quantity }}
                        </td>

                        <td>
                            {{ $history->stock_before }}
                        </td>

                        <td>
                            {{ $history->stock_after }}
                        </td>

                        <td>
                            {{ $history->reason ?? '-' }}
                        </td>

                        <td>
                            {{ $history->user->name ?? '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8">
                            Belum ada riwayat perubahan stok.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</body>

</html>