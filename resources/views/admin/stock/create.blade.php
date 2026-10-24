<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ubah Stok</title>

    @vite(['resources/css/admin.css'])
</head>

<body>

<div class="dashboard-container">

    <div class="dashboard-header">

        <div>
            <h1>Ubah Stok</h1>

            <p>
                {{ $product->name }}
            </p>
        </div>

        <a
            href="{{ route('admin.stock.index') }}"
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

        <div style="margin-bottom: 20px;">

            <strong>Stok Saat Ini:</strong>

            {{ $product->stock }}
            {{ $product->unit }}

        </div>

        <form
            action="{{ route('admin.stock.store', $product) }}"
            method="POST">

            @csrf

            <div class="form-group">
                <label for="type">Jenis Perubahan</label>

                <select name="type" id="type" required>
                    <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>
                        Stok Masuk
                    </option>

                    <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>
                        Stok Keluar
                    </option>

                    <option value="adjustment" {{ old('type') == 'adjustment' ? 'selected' : '' }}>
                        Penyesuaian Stok
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="quantity">Jumlah</label>

                <input
                    type="number"
                    name="quantity"
                    id="quantity"
                    min="0"
                    value="{{ old('quantity') }}"
                    required>

                <small>
                    Untuk stok masuk/keluar, isi jumlah perubahan.
                    Untuk penyesuaian, isi jumlah stok fisik terbaru.
                </small>
            </div>

            <div class="form-group">
                <label for="reason">Alasan / Keterangan</label>

                <textarea
                    name="reason"
                    id="reason"
                    rows="4"
                    placeholder="Contoh: Restock supplier atau hasil stok opname"
                    required>{{ old('reason') }}</textarea>
            </div>

            <button type="submit">Simpan Perubahan</button>

            <a href="{{ route('admin.stock.index') }}" class="btn">
                Batal
            </a>

        </form>

    </div>

</div>

</body>

</html>