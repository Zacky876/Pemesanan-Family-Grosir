<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">

    <title>Masuk - Youngmart</title>

    @vite(['resources/css/login.css', 'resources/js/login.js'])
</head>

<body>

    @php
        // Tab yang aktif: kembali ke "Daftar" jika pendaftaran gagal validasi
        $activeTab = old('_form') === 'register' ? 'register' : 'login';
        $loginError = $activeTab === 'login' && $errors->any();
    @endphp

    <div class="login-page">

        {{-- ===== PANEL KIRI (BRANDING) ===== --}}
        <aside class="login-brand">

            <div class="brand-content">
                <div class="brand-top">
                    <div class="brand-logo">
                        @if (file_exists(public_path('images/logo-youngmart.png')))
                            <img src="{{ asset('images/logo-youngmart.png') }}" alt="Logo Youngmart">
                        @else
                            <span>YM</span>
                        @endif
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

        {{-- ===== PANEL KANAN ===== --}}
        <main class="login-panel">

            <div class="login-box">

                {{-- Menu Masuk / Daftar --}}
                <div class="auth-tabs" data-active="{{ $activeTab }}" role="tablist">
                    <span class="tab-thumb" aria-hidden="true"></span>

                    <button type="button" class="tab-btn {{ $activeTab === 'login' ? 'active' : '' }}"
                        data-tab="login" role="tab" aria-selected="{{ $activeTab === 'login' ? 'true' : 'false' }}">
                        Masuk
                    </button>

                    <button type="button" class="tab-btn {{ $activeTab === 'register' ? 'active' : '' }}"
                        data-tab="register" role="tab" aria-selected="{{ $activeTab === 'register' ? 'true' : 'false' }}">
                        Daftar
                    </button>
                </div>

                {{-- ================= FORM MASUK ================= --}}
                <section class="auth-pane" id="paneLogin" {{ $activeTab === 'login' ? '' : 'hidden' }}>

                    <h1 class="login-title">
                        {{ $loginError ? 'Masuk' : 'Selamat Datang' }}
                    </h1>
                    <p class="login-subtitle">Silakan masuk ke akun Anda.</p>

                    @if (session('success'))
                        <p class="login-success" role="status">{{ session('success') }}</p>
                    @endif

                    <form action="{{ route('login.process') }}" method="POST" id="loginForm" novalidate>
                        @csrf

                        <div class="form-group" style="animation-delay: .2s">
                            <label for="email">Nama Pengguna</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ $activeTab === 'login' ? old('email') : '' }}"
                                placeholder="Masukan email"
                                autocomplete="username"
                                required
                            >
                        </div>

                        <div class="form-group" style="animation-delay: .27s">
                            <label for="password">Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Masukan kata Sandi"
                                autocomplete="current-password"
                                required
                            >
                        </div>

                        <div class="forgot-row">
                            <a href="{{ Route::has('password.request') ? route('password.request') : '#' }}">Lupa Password?</a>
                        </div>

                        <button type="submit" class="login-button" id="loginButton">
                            Masuk
                        </button>

                        @if ($loginError)
                            <p class="login-error" role="alert">
                                Email atau Password Anda Salah!!!
                            </p>
                        @endif

                        <p class="register-link">
                            Belum memiliki akun?
                            <a href="{{ route('register') }}" data-switch="register">Daftar sekarang</a>
                        </p>
                    </form>
                </section>

                {{-- ================= FORM DAFTAR ================= --}}
                <section class="auth-pane" id="paneRegister" {{ $activeTab === 'register' ? '' : 'hidden' }}>

                    <h1 class="login-title">Daftar Akun</h1>
                    <p class="login-subtitle">Buat akun pelanggan untuk mulai berbelanja.</p>

                    <form action="{{ route('register.process') }}" method="POST" id="registerForm" novalidate>
                        @csrf
                        <input type="hidden" name="_form" value="register">

                        <div class="form-group">
                            <label for="reg_name">Nama Lengkap</label>
                            <input
                                type="text"
                                id="reg_name"
                                name="name"
                                value="{{ $activeTab === 'register' ? old('name') : '' }}"
                                placeholder="Masukan nama lengkap"
                                autocomplete="name"
                                required
                            >
                            @if ($activeTab === 'register')
                                @error('name') <small class="field-error">{{ $message }}</small> @enderror
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="reg_email">Email</label>
                            <input
                                type="email"
                                id="reg_email"
                                name="email"
                                value="{{ $activeTab === 'register' ? old('email') : '' }}"
                                placeholder="Masukan email"
                                autocomplete="email"
                                required
                            >
                            @if ($activeTab === 'register')
                                @error('email') <small class="field-error">{{ $message }}</small> @enderror
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="reg_password">Password</label>
                            <input
                                type="password"
                                id="reg_password"
                                name="password"
                                placeholder="Minimal 8 karakter"
                                autocomplete="new-password"
                                required
                            >
                            @if ($activeTab === 'register')
                                @error('password') <small class="field-error">{{ $message }}</small> @enderror
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="reg_password_confirmation">Konfirmasi Password</label>
                            <input
                                type="password"
                                id="reg_password_confirmation"
                                name="password_confirmation"
                                placeholder="Ulangi password"
                                autocomplete="new-password"
                                required
                            >
                        </div>

                        <button type="submit" class="login-button">Daftar</button>

                        <p class="register-link">
                            Sudah memiliki akun?
                            <a href="{{ route('login') }}" data-switch="login">Masuk di sini</a>
                        </p>
                    </form>
                </section>

            </div>

        </main>

    </div>

</body>

</html>