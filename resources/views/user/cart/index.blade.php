@extends('user.layout.app')

@section('title', 'Keranjang Belanja')
@section('page_title', 'Keranjang Belanja')
@section('page_subtitle', 'Periksa produk pilihan Anda sebelum checkout.')

@section('content')
<div class="shop-page">

    {{-- Pesan sukses/error ditampilkan otomatis oleh layout --}}

    <div class="shop-toolbar">
        <a href="{{ route('user.products.index') }}" class="shop-btn shop-btn--ghost">
            &larr; Kembali ke produk
        </a>

        @if (count($items) > 0)
            <span class="shop-hint">{{ count($items) }} produk di keranjang</span>
        @endif
    </div>

    @if (count($items) > 0)

        <div class="shop-layout">

            {{-- ===== Daftar produk di keranjang ===== --}}
            <div class="shop-table-wrap">
                <table class="shop-table">

                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="is-num">Harga</th>
                            <th>Jumlah</th>
                            <th class="is-num">Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td><span class="shop-product">{{ $item['product']->name }}</span></td>

                                <td class="is-num">
                                    Rp {{ number_format($item['product']->selling_price, 0, ',', '.') }}
                                </td>

                                <td>
                                    <form action="{{ route('user.cart.update', $item['product']) }}"
                                          method="POST"
                                          class="shop-qty">
                                        @csrf
                                        @method('PATCH')

                                        <input type="number"
                                               name="quantity"
                                               min="1"
                                               max="{{ $item['product']->stock }}"
                                               value="{{ $item['quantity'] }}"
                                               required>

                                        <button type="submit" class="shop-btn shop-btn--ghost shop-btn--small">
                                            Perbarui
                                        </button>
                                    </form>
                                </td>

                                <td class="is-num">
                                    <strong>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</strong>
                                </td>

                                <td>
                                    <form action="{{ route('user.cart.remove', $item['product']) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus produk ini dari keranjang?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="shop-btn shop-btn--danger shop-btn--small">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            {{-- ===== Ringkasan ===== --}}
            <aside class="shop-summary">
                <h2>Total belanja</h2>
                <strong class="shop-total">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                <p>Biaya pengiriman belum dihitung.</p>

                <a href="{{ route('user.checkout.index') }}" class="shop-btn shop-btn--block">
                    Lanjut ke checkout
                </a>
            </aside>

        </div>

    @else

        <div class="shop-card shop-empty">
            <p>Keranjang Anda masih kosong.</p>
            <a href="{{ route('user.products.index') }}" class="shop-btn">Mulai belanja</a>
        </div>

    @endif

</div>
@endsection