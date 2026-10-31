<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin</title>

    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])
</head>

<body>

    <div class="dashboard">

        <header class="header">

            <div>
                <h1>Dashboard Admin</h1>
                <p>Selamat datang, {{ auth()->user()->name }}</p>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="logout-button">
                    Logout
                </button>
            </form>

        </header>

        <main class="content">

            <div class="welcome-card">
                <h2>Selamat Datang</h2>

                <p>
                    Anda login sebagai
                    <strong>{{ auth()->user()->role }}</strong>.
                </p>
            </div>

            <div class="card-grid">

                <div class="card">
                    <span>Produk</span>
                    <strong>Kelola Produk</strong>
                </div>

                <div class="card">
                    <span>Kategori</span>
                    <strong>Kelola Kategori</strong>
                </div>

                <div class="card">
                    <span>Transaksi</span>
                    <strong>Lihat Transaksi</strong>
                </div>

                <a href="{{ route('admin.payments.index') }}" class="card">
                    <span>Pembayaran</span>
                    <strong>Verifikasi Pembayaran</strong>
                </a>

                <a href="{{ route('admin.reports.index') }}" class="card">
                    <span>Laporan</span>
                    <strong>Lihat Laporan</strong>
                </a>

            </div>

        </main>

    </div>

</body>

</html>
