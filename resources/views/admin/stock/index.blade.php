<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manajemen Stok</title>

    @vite(['resources/css/admin.css'])
</head>

<body>

<div class="dashboard-container">

    <div class="dashboard-header">

        <div>
            <h1>Manajemen Stok</h1>
            <p>Kelola stok produk minimarket.</p>
        </div>

        <div>
            <a
                href="{{ route('admin.dashboard') }}"
                class="btn">
                Dashboard
            </a>

            <a
                href="{{ route('admin.products.index') }}"
                class="btn">
                Produk
            </a>
        </div>

    </div>

    @if(session('success'))

        <div class="success-message">
            {{ session('success') }}
        </div>

    @endif

    <div class="dashboard-card">

        <table>

            <thead>

                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>

            </thead>

            <tbody>

                @forelse($products as $product)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $product->code }}
                        </td>

                        <td>
                            {{ $product->name }}
                        </td>

                        <td>
                            {{ $product->category->name }}
                        </td>

                        <td>
                            {{ $product->stock }}
                        </td>

                        <td>
                            {{ $product->unit }}
                        </td>

                        <td>
                            {{ $product->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                        </td>

                        <td>

                            <a
                                href="{{ route('admin.stock.create', $product) }}"
                                class="btn">
                                Ubah Stok
                            </a>

                            <a
                                href="{{ route('admin.stock.history', $product) }}"
                                class="btn">
                                Riwayat
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8">
                            Belum ada produk.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</body>

</html>