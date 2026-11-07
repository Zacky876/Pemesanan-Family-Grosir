<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register YoungMart</title>

    @vite(['resources/css/register.css'])
</head>
<body>

<div class="register-container">

    <h2>Daftar Akun YoungMart</h2>
    <p>Buat akun pelanggan untuk mulai berbelanja.</p>

    <form action="{{ route('register.process') }}" method="POST" class="register-form">
        @csrf

        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Masukkan nama lengkap"
                required
            >

            @error('name')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Masukkan email"
                required
            >

            @error('email')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Minimal 8 karakter"
                required
            >

            @error('password')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Ulangi password"
                required
            >
        </div>

        <button type="submit">Daftar</button>
    </form>

    <p class="register-link">
        Sudah memiliki akun?
        <a href="{{ route('login') }}">Login di sini</a>
    </p>

</div>

</body>
</html>