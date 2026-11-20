@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Admin')
@section('page_subtitle', 'Selamat datang, ' . (auth()->user()->name ?? 'Administrator'))

@push('styles')
    @vite('resources/css/admin/dashboard.css')
@endpush

@section('content')

@php
    $rt = fn ($name) => \Illuminate\Support\Facades\Route::has($name) ? route($name) : '#';

    $link = [
        'dashboard'  => $rt('admin.dashboard'),
        'produk'     => $rt('admin.products.index'),
        'kategori'   => $rt('admin.categories.index'),
        'stok'       => $rt('admin.stock.index'),
        'transaksi'  => $rt('admin.transactions.index'),
        'pembayaran' => $rt('admin.payments.index'),
        'pelanggan'  => $rt('admin.customers.index'),
        'laporan'    => $rt('admin.reports.index'),
        'logout'     => \Illuminate\Support\Facades\Route::has('logout') ? route('logout') : url('/logout'),
    ];

    $user = auth()->user();
    $nama = $user->name ?? 'Administrator';

    $totalProduk    = $totalProduk    ?? 248;
    $penjualanBulan = $penjualanBulan ?? 48250000;
    $transaksiHari  = $transaksiHari  ?? 36;
    $pelangganBaru  = $pelangganBaru  ?? 12;

    $labelBulan = $labelBulan ?? ['Mei','Jun','Jul','Agu','Sep','Okt','Nov'];
    $dataPenjualan = $dataPenjualan ?? [31.2, 36.5, 33.8, 41.0, 44.6, 46.1, 48.3];

    $kategoriLabel = $kategoriLabel ?? ['Sembako','Minuman','Snack','Kebersihan','Lainnya'];
    $kategoriData  = $kategoriData  ?? [82, 54, 48, 36, 28];

    $labelHari = $labelHari ?? ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
    $dataTransaksi = $dataTransaksi ?? [28, 34, 31, 39, 45, 58, 36];

    $metodeLabel = $metodeLabel ?? ['Tunai','Transfer','QRIS'];
    $metodeData  = $metodeData  ?? [46, 31, 23];

    $stokMenipis = $stokMenipis ?? [
        ['nama' => 'Beras Premium 5 kg', 'sisa' => 4,  'min' => 20],
        ['nama' => 'Minyak Goreng 2 L',  'sisa' => 9,  'min' => 25],
        ['nama' => 'Gula Pasir 1 kg',    'sisa' => 14, 'min' => 30],
        ['nama' => 'Mi Instan (dus)',    'sisa' => 22, 'min' => 30],
    ];

    $transaksiTerbaru = $transaksiTerbaru ?? [
        ['kode' => 'TRX-0245', 'waktu' => '14 Nov, 10:42', 'pelanggan' => 'Toko Berkah',     'item' => 12, 'metode' => 'Transfer', 'total' => 1250000, 'status' => 'Lunas'],
        ['kode' => 'TRX-0244', 'waktu' => '14 Nov, 10:15', 'pelanggan' => 'Warung Bu Sri',   'item' => 7,  'metode' => 'QRIS',     'total' => 640000,  'status' => 'Menunggu'],
        ['kode' => 'TRX-0243', 'waktu' => '14 Nov, 09:48', 'pelanggan' => 'Andi Pratama',    'item' => 3,  'metode' => 'Tunai',    'total' => 215000,  'status' => 'Lunas'],
        ['kode' => 'TRX-0242', 'waktu' => '14 Nov, 09:20', 'pelanggan' => 'Kios Makmur',     'item' => 9,  'metode' => 'Transfer', 'total' => 980000,  'status' => 'Gagal'],
        ['kode' => 'TRX-0241', 'waktu' => '14 Nov, 08:55', 'pelanggan' => 'Rina Wulandari',  'item' => 2,  'metode' => 'Tunai',    'total' => 175000,  'status' => 'Lunas'],
        ['kode' => 'TRX-0240', 'waktu' => '13 Nov, 17:30', 'pelanggan' => 'Toko Sumber Rejeki','item' => 15,'metode' => 'Transfer', 'total' => 2100000, 'status' => 'Lunas'],
    ];

    $labelMinggu    = $labelMinggu    ?? ['M1','M2','M3','M4','M5','M6','M7','M8'];
    $dataPendapatan = $dataPendapatan ?? [9.8, 11.2, 10.5, 12.9, 12.1, 13.8, 13.2, 14.6];
    $dataLaba       = $dataLaba       ?? [2.1, 2.5, 2.2, 3.0, 2.8, 3.3, 3.1, 3.6];

    $targetBulan    = $targetBulan ?? 60000000;
    $persenTarget   = min(100, round($penjualanBulan / $targetBulan * 100));
    $sisaTarget     = max(0, $targetBulan - $penjualanBulan);

    $produkTerlaris = $produkTerlaris ?? [
        ['nama' => 'Beras Premium 5 kg', 'terjual' => 182],
        ['nama' => 'Minyak Goreng 2 L',  'terjual' => 156],
        ['nama' => 'Gula Pasir 1 kg',    'terjual' => 131],
        ['nama' => 'Mi Instan (dus)',    'terjual' => 104],
        ['nama' => 'Air Mineral 600 ml', 'terjual' => 88],
    ];

    $pelangganTeratas = $pelangganTeratas ?? [
        ['nama' => 'Toko Sumber Rejeki', 'trx' => 14, 'total' => 8450000],
        ['nama' => 'Toko Berkah',        'trx' => 11, 'total' => 6120000],
        ['nama' => 'Warung Bu Sri',      'trx' => 9,  'total' => 3880000],
        ['nama' => 'Kios Makmur',        'trx' => 7,  'total' => 2975000],
    ];

    $aktivitas = $aktivitas ?? [
        ['teks' => 'Pembayaran TRX-0244 menunggu verifikasi', 'waktu' => '5 menit lalu',  'tipe' => 'wait'],
        ['teks' => 'Transaksi TRX-0245 lunas via Transfer',   'waktu' => '32 menit lalu', 'tipe' => 'ok'],
        ['teks' => 'Stok Beras Premium 5 kg di bawah batas',  'waktu' => '1 jam lalu',    'tipe' => 'fail'],
        ['teks' => 'Pelanggan baru: Rina Wulandari',          'waktu' => '2 jam lalu',    'tipe' => 'info'],
        ['teks' => '20 dus Mi Instan masuk ke stok',          'waktu' => 'Kemarin',       'tipe' => 'ok'],
    ];

    $statusCount = array_count_values(array_column($transaksiTerbaru, 'status'));

    $rupiah = fn($n) => 'Rp ' . number_format($n, 0, ',', '.');

    // Data grafik untuk resources/js/admin/dashboard.js
    $chartData = [
        'labelBulan'     => $labelBulan,
        'dataPenjualan'  => $dataPenjualan,
        'kategoriLabel'  => $kategoriLabel,
        'kategoriData'   => $kategoriData,
        'labelHari'      => $labelHari,
        'dataTransaksi'  => $dataTransaksi,
        'metodeLabel'    => $metodeLabel,
        'metodeData'     => $metodeData,
        'labelMinggu'    => $labelMinggu,
        'dataPendapatan' => $dataPendapatan,
        'dataLaba'       => $dataLaba,
        'penjualanBulan' => $penjualanBulan,
        'sisaTarget'     => $sisaTarget,
        'terlarisLabel'  => array_column($produkTerlaris, 'nama'),
        'terlarisData'   => array_column($produkTerlaris, 'terjual'),
    ];
@endphp

    {{-- Banner sambutan --}}
    <section class="hero">
        <h2>Selamat datang kembali</h2>
        <p>Anda login sebagai <strong>Admin</strong>. Kelola produk, pantau stok, dan verifikasi pembayaran dari satu tempat.</p>
    </section>

    {{-- Ringkasan angka (mengikuti menu Produk, Penjualan, Transaksi, Pelanggan) --}}
    <section class="stats">
        <div class="stat">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24"><path d="M21 8l-9-5-9 5v8l9 5 9-5z"/><path d="M3 8l9 5 9-5M12 13v8"/></svg>
            </div>
            <div>
                <small>Total produk</small>
                <strong>{{ number_format($totalProduk, 0, ',', '.') }}</strong>
                <em class="up">+8 minggu ini</em>
            </div>
        </div>
        <div class="stat">
            <div class="stat-icon green">
                <svg viewBox="0 0 24 24"><path d="M4 20V4M4 20h16M9 16v-5M14 16V8M19 16v-3"/></svg>
            </div>
            <div>
                <small>Penjualan bulan ini</small>
                <strong>{{ $rupiah($penjualanBulan) }}</strong>
                <em class="up">+4,8% dari bulan lalu</em>
            </div>
        </div>
        <div class="stat">
            <div class="stat-icon orange">
                <svg viewBox="0 0 24 24"><path d="M6 3h12v18l-3-2-3 2-3-2-3 2z"/><path d="M9 8h6M9 12h6"/></svg>
            </div>
            <div>
                <small>Transaksi hari ini</small>
                <strong>{{ $transaksiHari }}</strong>
                <em class="down">-3 dari kemarin</em>
            </div>
        </div>
        <div class="stat">
            <div class="stat-icon amber">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            </div>
            <div>
                <small>Pelanggan baru</small>
                <strong>{{ $pelangganBaru }}</strong>
                <em class="up">+5 minggu ini</em>
            </div>
        </div>
    </section>

    {{-- Baris 1: Laporan penjualan + Kategori --}}
    <section class="grid grid-2-1">
        <div class="card">
            <div class="card-head">
                <div>
                    <h3>Penjualan 7 bulan terakhir</h3>
                    <p>Dalam jutaan rupiah</p>
                </div>
                <a href="{{ $link['laporan'] }}">Lihat laporan</a>
            </div>
            <div class="chart-box"><canvas id="chartPenjualan"></canvas></div>
        </div>

        <div class="card">
            <div class="card-head">
                <div>
                    <h3>Produk per kategori</h3>
                    <p>Jumlah produk aktif</p>
                </div>
                <a href="{{ $link['kategori'] }}">Kelola</a>
            </div>
            <div class="chart-box"><canvas id="chartKategori"></canvas></div>
        </div>
    </section>

    {{-- Baris 2: Transaksi, Pembayaran, Stok --}}
    <section class="grid grid-3">
        <div class="card">
            <div class="card-head">
                <div>
                    <h3>Transaksi minggu ini</h3>
                    <p>Jumlah transaksi per hari</p>
                </div>
                <a href="{{ $link['transaksi'] }}">Detail</a>
            </div>
            <div class="chart-box short"><canvas id="chartTransaksi"></canvas></div>
        </div>

        <div class="card">
            <div class="card-head">
                <div>
                    <h3>Metode pembayaran</h3>
                    <p>Persentase transaksi bulan ini</p>
                </div>
                <a href="{{ $link['pembayaran'] }}">Detail</a>
            </div>
            <div class="chart-box short"><canvas id="chartPembayaran"></canvas></div>
        </div>

        <div class="card">
            <div class="card-head">
                <div>
                    <h3>Stok hampir habis</h3>
                    <p>Sisa dibanding batas minimum</p>
                </div>
                <a href="{{ $link['stok'] }}">Kelola</a>
            </div>
            <ul class="stock-list">
                @foreach ($stokMenipis as $s)
                    @php
                        $persen = min(100, round($s['sisa'] / $s['min'] * 100));
                        $kelas = $persen < 30 ? 'low' : ($persen < 70 ? 'mid' : '');
                    @endphp
                    <li class="stock-item">
                        <div class="row">
                            <strong>{{ $s['nama'] }}</strong>
                            <span>{{ $s['sisa'] }} / {{ $s['min'] }}</span>
                        </div>
                        <div class="bar"><i class="{{ $kelas }}" style="width: {{ $persen }}%"></i></div>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- Baris 3: Pendapatan vs laba + Target --}}
    <section class="grid grid-2-1">
        <div class="card">
            <div class="card-head">
                <div>
                    <h3>Pendapatan dan laba</h3>
                    <p>8 minggu terakhir, dalam jutaan rupiah</p>
                </div>
                <a href="{{ $link['laporan'] }}">Lihat laporan</a>
            </div>
            <div class="chart-box"><canvas id="chartLaba"></canvas></div>
        </div>

        <div class="card">
            <div class="card-head">
                <div>
                    <h3>Target bulan ini</h3>
                    <p>Pencapaian penjualan</p>
                </div>
            </div>
            <div class="gauge">
                <canvas id="chartTarget"></canvas>
                <div class="gauge-center">
                    <strong>{{ $persenTarget }}%</strong>
                    <span>tercapai</span>
                </div>
            </div>
            <div class="gauge-meta">
                <div>Terjual<b>{{ $rupiah($penjualanBulan) }}</b></div>
                <div style="text-align:right">Sisa target<b>{{ $rupiah($sisaTarget) }}</b></div>
            </div>
        </div>
    </section>

    {{-- Baris 4: Produk terlaris, Pelanggan teratas, Aktivitas --}}
    <section class="grid grid-3">
        <div class="card">
            <div class="card-head">
                <div>
                    <h3>Produk terlaris</h3>
                    <p>Jumlah terjual bulan ini</p>
                </div>
                <a href="{{ $link['produk'] }}">Produk</a>
            </div>
            <div class="chart-box short"><canvas id="chartTerlaris"></canvas></div>
        </div>

        <div class="card">
            <div class="card-head">
                <div>
                    <h3>Pelanggan teratas</h3>
                    <p>Berdasarkan total belanja</p>
                </div>
                <a href="{{ $link['pelanggan'] }}">Pelanggan</a>
            </div>
            <ul class="rank-list">
                @foreach ($pelangganTeratas as $i => $c)
                    <li class="rank-item">
                        <span class="rank-no">{{ $i + 1 }}</span>
                        <div>
                            <b>{{ $c['nama'] }}</b>
                            <small>{{ $c['trx'] }} transaksi</small>
                        </div>
                        <span class="amount num">{{ $rupiah($c['total']) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="card">
            <div class="card-head">
                <div>
                    <h3>Aktivitas terbaru</h3>
                    <p>Yang baru terjadi di toko</p>
                </div>
            </div>
            <ul class="timeline">
                @foreach ($aktivitas as $a)
                    <li class="{{ $a['tipe'] }}">
                        <b>{{ $a['teks'] }}</b>
                        <small>{{ $a['waktu'] }}</small>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- Baris 5: Penjualan terakhir --}}
    <section class="grid">
        <div class="card">
            <div class="card-head">
                <div>
                    <h3>Penjualan terakhir</h3>
                    <p>Pembayaran yang perlu diverifikasi ditandai Menunggu</p>
                </div>
                <a href="{{ $link['transaksi'] }}">Lihat semua</a>
            </div>

            <div class="chips">
                <span class="chip ok"><i></i>Lunas {{ $statusCount['Lunas'] ?? 0 }}</span>
                <span class="chip wait"><i></i>Menunggu {{ $statusCount['Menunggu'] ?? 0 }}</span>
                <span class="chip fail"><i></i>Gagal {{ $statusCount['Gagal'] ?? 0 }}</span>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Waktu</th>
                            <th>Pelanggan</th>
                            <th>Item</th>
                            <th>Metode</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksiTerbaru as $t)
                            @php
                                $kelasBadge = $t['status'] === 'Lunas' ? 'ok' : ($t['status'] === 'Menunggu' ? 'wait' : 'fail');
                            @endphp
                            <tr>
                                <td><strong>{{ $t['kode'] }}</strong></td>
                                <td class="num">{{ $t['waktu'] }}</td>
                                <td>{{ $t['pelanggan'] }}</td>
                                <td>{{ $t['item'] }}</td>
                                <td>{{ $t['metode'] }}</td>
                                <td class="num"><strong>{{ $rupiah($t['total']) }}</strong></td>
                                <td><span class="badge {{ $kelasBadge }}">{{ $t['status'] }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>


{{-- Data grafik untuk JavaScript --}}
<script type="application/json" id="dashboard-data">@json($chartData)</script>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    @vite('resources/js/admin/dashboard.js')
@endpush