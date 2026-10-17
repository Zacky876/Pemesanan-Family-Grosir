
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pelanggan</title>
    @vite(['resources/css/admin.css'])
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Tambah Pelanggan</h1>

            <a href="{{ route('admin.customers.index') }}" class="btn">
                Kembali
            </a>
        </div>

        <div class="dashboard-card">
            @if ($errors->any())
                <div class="error-message">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.customers.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Nama Pelanggan</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                    >
                    <small>Password minimal 8 karakter.</small>
                </div>

                <button type="submit">Simpan Pelanggan</button>

                <a href="{{ route('admin.customers.index') }}" class="btn">
                    Batal
                </a>
            </form>
        </div>
    </div>
</body>
</html>
