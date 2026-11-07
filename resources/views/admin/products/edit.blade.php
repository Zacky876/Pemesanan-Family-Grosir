<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Produk</title>

    @vite(['resources/css/admin/admin.css'])
</head>

<body>

<div class="dashboard-container">

    <div class="dashboard-header">

        <div>
            <h1>Edit Produk</h1>
            <p>Perbarui informasi produk.</p>
        </div>

        <a
            href="{{ route('admin.products.index') }}"
            class="btn">
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
            action="{{ route('admin.products.update', $product) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="form-group">

                <label for="code">
                    Kode Produk
                </label>

                <input
                    type="text"
                    id="code"
                    name="code"
                    value="{{ old('code', $product->code) }}"
                    required>

            </div>

            <div class="form-group">

                <label for="name">
                    Nama Produk
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $product->name) }}"
                    required>

            </div>

            <div class="form-group">

                <label for="category_id">
                    Kategori
                </label>

                <select
                    id="category_id"
                    name="category_id"
                    required>

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>

                            {{ $category->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="form-group">

                <label for="purchase_price">
                    Harga Beli
                </label>

                <input
                    type="number"
                    id="purchase_price"
                    name="purchase_price"
                    value="{{ old('purchase_price', $product->purchase_price) }}"
                    min="0"
                    required>

            </div>

            <div class="form-group">

                <label for="selling_price">
                    Harga Jual
                </label>

                <input
                    type="number"
                    id="selling_price"
                    name="selling_price"
                    value="{{ old('selling_price', $product->selling_price) }}"
                    min="0"
                    required>

            </div>

            <div class="form-group">

                <label for="stock">
                    Stok
                </label>

                <input
                    type="number"
                    id="stock"
                    name="stock"
                    value="{{ old('stock', $product->stock) }}"
                    min="0"
                    required>

            </div>

            <div class="form-group">

                <label for="unit">
                    Satuan
                </label>

                <select
                    id="unit"
                    name="unit"
                    required>

                    @foreach(['pcs', 'botol', 'kg', 'liter', 'pack'] as $unit)

                        <option
                            value="{{ $unit }}"
                            {{ old('unit', $product->unit) == $unit ? 'selected' : '' }}>

                            {{ $unit }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="form-group">

                <label>Foto Saat Ini</label>

                @if($product->photo)

                    <div style="margin-bottom:10px;">

                        <img
                            src="{{ asset('storage/' . $product->photo) }}"
                            alt="{{ $product->name }}"
                            style="width:120px;height:120px;object-fit:cover;">

                    </div>

                @else

                    <p>Belum ada foto.</p>

                @endif

            </div>

            <div class="form-group">

                <label for="photo">
                    Ganti Foto
                </label>

                <input
                    type="file"
                    id="photo"
                    name="photo"
                    accept=".jpg,.jpeg,.png,.webp">

            </div>

            <div class="form-group">

                <label for="status">
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    required>

                    <option
                        value="active"
                        {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option
                        value="inactive"
                        {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>
                        Tidak Aktif
                    </option>

                </select>

            </div>

            <div class="form-group">

                <label for="description">
                    Deskripsi
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="5">{{ old('description', $product->description) }}</textarea>

            </div>

            <button type="submit">
                Simpan Perubahan
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