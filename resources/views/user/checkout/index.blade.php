@extends('user.layout.app')

@section('title', 'Konfirmasi Checkout')
@section('page_title', 'Konfirmasi Checkout')
@section('page_subtitle', 'Periksa pesanan sebelum dikirim.')

@section('content')
<div class="shop-page">

    {{-- Pesan error ditampilkan otomatis oleh layout --}}

    <div class="shop-toolbar">
        <a href="{{ route('user.cart.index') }}" class="shop-btn shop-btn--ghost">
            &larr; Kembali ke keranjang
        </a>
    </div>

    <div class="shop-layout">

        {{-- ===== Detail pesanan ===== --}}
        <div class="shop-card">
            <div class="shop-section" style="padding-bottom: 0;">
                <h2>Detail pesanan</h2>
                <p>Produk yang akan Anda pesan.</p>
            </div>

            <div class="shop-table-wrap" style="border: 0; border-radius: 0; box-shadow: none;">
                <table class="shop-table">

                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="is-num">Harga</th>
                            <th class="is-num">Jumlah</th>
                            <th class="is-num">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td><span class="shop-product">{{ $item['product']->name }}</span></td>
                                <td class="is-num">
                                    Rp {{ number_format($item['product']->selling_price, 0, ',', '.') }}
                                </td>
                                <td class="is-num">{{ $item['quantity'] }}</td>
                                <td class="is-num">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="3" class="is-num">Total</td>
                            <td class="is-num">Rp {{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>

        {{-- ===== Metode pembayaran ===== --}}
        <form action="{{ route('user.checkout.store') }}"
              method="POST"
              class="shop-card shop-section"
              onsubmit="return confirm('Apakah kamu yakin ingin membuat pesanan ini?')">
            @csrf

            <h2>Metode pembayaran</h2>
            <p>Pilih metode pembayaran untuk pesanan ini.</p>

            <div class="shop-options">

                <label class="shop-option">
                    <input type="radio"
                           name="payment_method"
                           value="qris"
                           {{ old('payment_method', 'qris') === 'qris' ? 'checked' : '' }}
                           required>
                    <div>
                        <strong>QRIS</strong>
                        <span>
                            Bayar menggunakan QRIS dan unggah bukti pembayaran
                            untuk diperiksa admin atau kasir.
                        </span>
                    </div>
                </label>

                <label class="shop-option">
                    <input type="radio"
                           name="payment_method"
                           value="cash"
                           {{ old('payment_method') === 'cash' ? 'checked' : '' }}
                           required>
                    <div>
                        <strong>Tunai di kasir</strong>
                        <span>Bayar langsung kepada kasir saat melakukan pembayaran.</span>
                    </div>
                </label>

            </div>

            <div class="shop-status">
                <span>Status pesanan setelah checkout: <strong>Menunggu diproses</strong></span>
                <span>Status pembayaran awal: <strong>Belum dibayar</strong></span>
            </div>

            <button type="submit" class="shop-btn shop-btn--block">
                Konfirmasi pesanan
            </button>
        </form>

    </div>

</div>
@endsection