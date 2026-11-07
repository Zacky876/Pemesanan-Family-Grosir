<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Produk</title>

    @vite(['resources/css/admin/admin.css'])
</head>

<body>

<div class="dashboard-container">

    <div class="dashboard-header">

        <div>
            <h1>Tambah Produk</h1>
            <p>Tambahkan produk baru ke dalam sistem.</p>
        </div>

        <a href="{{ route('admin.products.index') }}" class="btn">
            Kembali
        </a>

    </div>

    @if($errors->any())
        <div class="error-message">

            <strong>Terjadi kesalahan:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif

    <div class="dashboard-card">

        <form
            action="{{ route('admin.products.store') }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label for="code">Kode Produk</label>

                <input
                    type="text"
                    id="code"
                    name="code"
                    value="{{ old('code') }}"
                    placeholder="Contoh: PRD001"
                    required>
            </div>

            <div class="form-group">
                <label for="name">Nama Produk</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Masukkan nama produk"
                    required>
            </div>

            <div class="form-group">
                <label for="category_id">Kategori</label>

                <select
                    id="category_id"
                    name="category_id"
                    required>

                    <option value="">-- Pilih Kategori --</option>

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}>

                            {{ $category->name }}

                        </option>

                    @endforeach

                </select>
            </div>

            <div class="form-group">
                <label for="purchase_price">Harga Beli</label>

                <input
                    type="number"
                    id="purchase_price"
                    name="purchase_price"
                    value="{{ old('purchase_price') }}"
                    min="0"
                    placeholder="Contoh: 5000"
                    required>
            </div>

            <div class="form-group">
                <label for="selling_price">Harga Jual</label>

                <input
                    type="number"
                    id="selling_price"
                    name="selling_price"
                    value="{{ old('selling_price') }}"
                    min="0"
                    placeholder="Contoh: 7000"
                    required>
            </div>

            <div class="form-group">
                <label for="stock">Stok</label>

                <input
                    type="number"
                    id="stock"
                    name="stock"
                    value="{{ old('stock', 0) }}"
                    min="0"
                    required>
            </div>

            <div class="form-group">
                <label for="unit">Satuan</label>

                <select
                    id="unit"
                    name="unit"
                    required>

                    <option value="pcs"
                        {{ old('unit') == 'pcs' ? 'selected' : '' }}>
                        pcs
                    </option>

                    <option value="botol"
                        {{ old('unit') == 'botol' ? 'selected' : '' }}>
                        botol
                    </option>

                    <option value="kg"
                        {{ old('unit') == 'kg' ? 'selected' : '' }}>
                        kg
                    </option>

                    <option value="liter"
                        {{ old('unit') == 'liter' ? 'selected' : '' }}>
                        liter
                    </option>

                    <option value="pack"
                        {{ old('unit') == 'pack' ? 'selected' : '' }}>
                        pack
                    </option>

                </select>
            </div>

            <div class="form-group">
                <label for="photo">Foto Produk</label>

                <input
                    type="file"
                    id="photo"
                    name="photo"
                    accept=".jpg,.jpeg,.png,.webp">

                <small>
                    Format: JPG, JPEG, PNG, WEBP. Maksimal 2 MB.
                </small>
            </div>

            <div class="form-group">
                <label for="status">Status Produk</label>

                <select
                    id="status"
                    name="status"
                    required>

                    <option value="active"
                        {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option value="inactive"
                        {{ old('status') == 'inactive' ? 'selected' : '' }}>
                        Tidak Aktif
                    </option>

                </select>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>

                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    placeholder="Masukkan deskripsi produk">{{ old('description') }}</textarea>
            </div>

            <button type="submit">
                Simpan Produk
            </button>

            <a
                href="{{ route('admin.products.index') }}"
                class="btn">
                Batal
            </a>

        </form>

    </div>

</div>

</body>
</html>