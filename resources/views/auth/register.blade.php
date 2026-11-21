<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar - Youngmart</title>

    @vite(['resources/css/register.css'])
</head>

<body>

    <div class="login-page">

        {{-- ===== PANEL KIRI (BRANDING) ===== --}}
        <aside class="login-brand">

            <div class="brand-content">
                <div class="brand-top">
                    <div class="brand-logo">
                        {{-- Jika sudah punya logo, ganti <span> di bawah dengan:
                             <img src="{{ asset('images/logo.png') }}" alt="Logo Youngmart"> --}}
                        <span>YM</span>
                    </div>

                    <div class="brand-name">
                        <strong>TOKO Youngmart Grub</strong>
                        <span>Toko Grosir</span>
                    </div>
                </div>

                <h2 class="brand-title">Belanja grosir,<br>lebih mudah.</h2>
                <p class="brand-desc">
                    Satu sistem untuk stok, kasir, dan belanja pelanggan.
                </p>
            </div>

            <div class="brand-footer">
                <span>2026 Youngmart Grub</span>
                <span>Bantuan &middot; Kebijakan Privasi</span>
            </div>

        </aside>

        {{-- ===== PANEL KANAN (FORM DAFTAR) ===== --}}
        <main class="login-panel">

            <div class="login-box">

                {{-- Menu Masuk / Daftar --}}
                <div class="auth-tabs" data-active="register">
                    <span class="tab-thumb" aria-hidden="true"></span>

                    <a href="{{ route('login') }}" class="tab-btn">Masuk</a>
                    <a href="{{ route('register') }}" class="tab-btn active" aria-current="page">Daftar</a>
                </div>

                <section class="auth-pane">

                    <h1 class="login-title">Daftar Akun</h1>
                    <p class="login-subtitle">Buat akun pelanggan untuk mulai berbelanja.</p>

                    <form action="{{ route('register.process') }}" method="POST" id="registerForm" novalidate>
                        @csrf

                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Masukan nama lengkap"
                                autocomplete="name"
                                required
                            >
                            @error('name')
                                <small class="field-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Masukan email"
                                autocomplete="email"
                                required
                            >
                            @error('email')
                                <small class="field-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Minimal 8 karakter"
                                autocomplete="new-password"
                                required
                            >
                            @error('password')
                                <small class="field-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Ulangi password"
                                autocomplete="new-password"
                                required
                            >
                        </div>

                        <button type="submit" class="login-button">Daftar</button>

                        <p class="register-link">
                            Sudah memiliki akun?
                            <a href="{{ route('login') }}">Masuk di sini</a>
                        </p>
                    </form>

                </section>

            </div>

        </main>

    </div>

</body>

</html>