
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    @vite(['resources/css/user.css'])
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Detail Produk</h1>

            <a href="{{ route('user.products.index') }}" class="btn">
                Kembali ke Katalog
            </a>
        </div>

        <div class="dashboard-card">
            @if ($product->photo)
                <img
                    src="{{ asset('storage/' . $product->photo) }}"
                    alt="{{ $product->name }}"
                    class="product-detail-image"
                >
            @else
                <div class="product-no-image">
                    Tidak ada foto produk
                </div>
            @endif

            <h2>{{ $product->name }}</h2>

            <table>
                <tbody>
                    <tr>
                        <th>Kode Produk</th>
                        <td>{{ $product->code }}</td>
                    </tr>

                    <tr>
                        <th>Kategori</th>
                        <td>{{ $product->category->name ?? 'Tanpa kategori' }}</td>
                    </tr>

                    <tr>
                        <th>Harga</th>
                        <td>
                            Rp{{ number_format($product->selling_price, 0, ',', '.') }}
                        </td>
                    </tr>

                    <tr>
                        <th>Stok</th>
                        <td>{{ $product->stock }} {{ $product->unit }}</td>
                    </tr>

                    <tr>
                        <th>Deskripsi</th>
                        <td>
                            {{ $product->description ?: 'Tidak ada deskripsi.' }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                <a href="{{ route('user.products.index') }}" class="btn">
                    Kembali ke Katalog
                </a>
            </div>
        </div>
    </div>
</body>
</html>
