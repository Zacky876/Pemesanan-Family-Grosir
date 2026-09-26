<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Minimarket</title>

    @vite(['resources/css/login.css', 'resources/js/login.js'])
</head>

<body>

    <div class="login-container">

        <div class="login-card">

            <div class="login-header">
                <h1>Minimarket</h1>
                <p>Silakan masuk ke akun Anda</p>
            </div>

            @if ($errors->any())
                <div class="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

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
                </div>

                <div class="form-group">
                    <label for="password">Password</label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <button type="submit" class="login-button">
                    Masuk
                </button>

            </form>

        </div>

    </div>

</body>

</html>