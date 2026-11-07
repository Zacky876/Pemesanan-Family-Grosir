<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Kategori</title>

    @vite(['resources/css/admin/admin.css'])
</head>

<body>

    <div class="dashboard-container">

        <h1>Tambah Kategori</h1>

        @if($errors->any())
            <div class="error-message">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="dashboard-card">

            <form action="{{ route('admin.categories.store') }}" method="POST">

                @csrf

                <div>
                    <label for="name">Nama Kategori</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                    >
                </div>

                <br>

                <div>
                    <label for="description">Deskripsi</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                    >{{ old('description') }}</textarea>
                </div>

                <br>

                <button type="submit">
                    Simpan
                </button>

                <a href="{{ route('admin.categories.index') }}">
                    Kembali
                </a>

            </form>

        </div>

    </div>

</body>
</html>