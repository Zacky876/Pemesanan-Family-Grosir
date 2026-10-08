<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Produk</title>

    @vite(['resources/css/admin.css'])
</head>

<body>

<div class="dashboard-container">

    <div class="dashboard-header">

        <div>
            <h1>Data Produk</h1>
            <p>Kelola produk minimarket.</p>
        </div>

        <div>

            <a
                href="{{ route('admin.dashboard') }}"
                class="btn">
                Dashboard
            </a>

            <a
                href="{{ route('admin.products.create') }}"
                class="btn">
                + Tambah Produk
            </a>

        </div>

    </div>

    @if(session('success'))

        <div class="success-message">
            {{ session('success') }}
        </div>

    @endif

    {{-- Search dan Filter --}}

    <div class="dashboard-card" style="margin-bottom: 20px;">

        <form
            action="{{ route('admin.products.index') }}"
            method="GET">

            <div class="form-group">

                <label for="search">
                    Cari Produk
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama atau kode produk">

            </div>

            <div class="form-group">

                <label for="category_id">
                    Filter Kategori
                </label>

                <select
                    id="category_id"
                    name="category_id">

                    <option value="">
                        Semua Kategori
                    </option>

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>

                            {{ $category->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <button type="submit">
                Cari
            </button>

            <a
                href="{{ route('admin.products.index') }}"
                class="btn">
                Reset
            </a>

        </form>

    </div>

    {{-- Data Produk --}}

    <div class="dashboard-card">

        @if($products->count() > 0)

            <table>

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($products as $product)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>

                                @if($product->photo)

                                    <img
                                        src="{{ asset('storage/' . $product->photo) }}"
                                        alt="{{ $product->name }}"
                                        style="width:60px;height:60px;object-fit:cover;">

                                @else

                                    Tidak ada

                                @endif

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
                                Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                            </td>

                            <td>
                                Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                            </td>

                            <td>
                                {{ $product->stock }}
                                {{ $product->unit }}
                            </td>

                            <td>
                                {{ $product->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                            </td>

                            <td>

                                <a
                                    href="{{ route('admin.products.show', $product) }}"
                                    class="btn">
                                    Detail
                                </a>

                                <a
                                    href="{{ route('admin.products.edit', $product) }}"
                                    class="btn">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('admin.products.destroy', $product) }}"
                                    method="POST"
                                    style="display:inline;">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                        Hapus
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <p>
                Tidak ada produk yang ditemukan.
            </p>

            <a
                href="{{ route('admin.products.create') }}"
                class="btn">
                Tambah Produk
            </a>

        @endif

    </div>

</div>

</body>
</html>