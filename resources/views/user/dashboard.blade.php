<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard User</title>

    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])
</head>

<body>

    <div class="dashboard">

        <header class="header">

            <div>
                <h1>Dashboard User</h1>
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

                <a href="{{ route('user.products.index') }}" class="card">
                    <span>Produk</span>
                    <strong>Lihat Produk</strong>
                    <p>Lihat katalog, cari produk, dan periksa harga.</p>
                </a>

                <a href="{{ route('user.products.index') }}" class="card">
                    <span>Belanja</span>
                    <strong>Belanja Sekarang</strong>
                    <p>Pilih produk dan tambahkan ke keranjang.</p>
                </a>

                <a href="{{ route('user.orders.index') }}" class="card">
                    <span>Pembelian</span>
                    <strong>Riwayat Pembelian</strong>
                </a>

            </div>

        </main>

    </div>

</body>

</html>