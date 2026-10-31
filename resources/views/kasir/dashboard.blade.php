<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Kasir</title>

    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])
</head>

<body>

    <div class="dashboard">

        <header class="header">

            <div>
                <h1>Dashboard Kasir</h1>
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
                <h2>Kasir</h2>

                <p>
                    Anda login sebagai
                    <strong>{{ auth()->user()->role }}</strong>.
                </p>
            </div>

            <div class="card-grid">

                <a href="{{ route('kasir.pos') }}" class="card">
                    <span>Transaksi</span>
                    <strong>Transaksi Baru</strong>
                </a>

                {{-- <div class="card">
                    <span>Produk</span>
                    <strong>Lihat Produk</strong>
                </div> --}}

                <a href="{{ route('kasir.history') }}" class="card">
                    <span>Transaksi</span>
                    <strong>Riwayat Transaksi</strong>
                </a>

            </div>

        </main>

    </div>

</body>

</html>