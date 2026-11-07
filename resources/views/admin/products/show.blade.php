<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Produk</title>

    @vite(['resources/css/admin/admin.css'])
</head>

<body>

<div class="dashboard-container">

    <div class="dashboard-header">

        <div>
            <h1>Detail Produk</h1>
            <p>Informasi lengkap produk.</p>
        </div>

        <div>

            <a
                href="{{ route('admin.products.index') }}"
                class="btn">
                Kembali
            </a>

            <a
                href="{{ route('admin.products.edit', $product) }}"
                class="btn">
                Edit
            </a>

        </div>

    </div>

    <div class="dashboard-card">

        @if($product->photo)

            <div style="margin-bottom: 20px;">

                <img
                    src="{{ asset('storage/' . $product->photo) }}"
                    alt="{{ $product->name }}"
                    style="width:200px;height:200px;object-fit:cover;">

            </div>

        @endif

        <table>

            <tr>
                <th>Kode Produk</th>
                <td>{{ $product->code }}</td>
            </tr>

            <tr>
                <th>Nama Produk</th>
                <td>{{ $product->name }}</td>
            </tr>

            <tr>
                <th>Kategori</th>
                <td>{{ $product->category->name }}</td>
            </tr>

            <tr>
                <th>Harga Beli</th>
                <td>
                    Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                </td>
            </tr>

            <tr>
                <th>Harga Jual</th>
                <td>
                    Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                </td>
            </tr>

            <tr>
                <th>Stok</th>
                <td>
                    {{ $product->stock }} {{ $product->unit }}
                </td>
            </tr>

            <tr>
                <th>Satuan</th>
                <td>{{ $product->unit }}</td>
            </tr>

            <tr>
                <th>Status</th>
                <td>
                    {{ $product->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                </td>
            </tr>

            <tr>
                <th>Deskripsi</th>
                <td>
                    {{ $product->description ?: '-' }}
                </td>
            </tr>

        </table>

    </div>

</div>

</body>
</html>