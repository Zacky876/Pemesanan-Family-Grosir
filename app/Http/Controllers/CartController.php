<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;


class CartController extends Controller {

    public function index(Request $request) {
        $cart = $request->session()->get('cart', []);
        $productIds = array_keys($cart);

        $products = Product::whereIn('id', $productIds)
            ->where('status', 'active')
            ->get()
            ->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $id => $item) {
            $product = $products->get($id);

            if (!$product) {
                continue;
            }

            $quantity = (int) $item['quantity'];
            $subtotal = $product->selling_price * $quantity;
            $total += $subtotal;

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        }

        return view('user.cart.index', compact('items', 'total'));
    }

    public function add(Request $request, Product $product) {
        if ($product->status !== 'active' || $product->stock < 1) {
            return back()->with('error', 'Produk tidak tersedia.');
        }

        $cart = $request->session()->get('cart', []);
        $id = (string) $product->id;
        $quantity = (int) ($cart[$id]['quantity'] ?? 0);

        if ($quantity + 1 > $product->stock) {
            return back()->with('error', 'Jumlah barang melebihi stok.');
        }

        $cart[$id] = [
            'quantity' => $quantity + 1,
        ];

        $request->session()->put('cart', $cart);

        return redirect()->route('user.cart.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, Product $product) {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($product->status !== 'active') {
            return back()->with('error', 'Produk tidak tersedia.');
        }

        if ($validated['quantity'] > $product->stock) {
            return back()->with('error', 'Jumlah barang melebihi stok.');
        }

        $cart = $request->session()->get('cart', []);
        $id = (string) $product->id;

        if (!isset($cart[$id])) {
            return back()->with('error', 'Produk tidak ditemukan di keranjang.');
        }

        $cart[$id]['quantity'] = (int) $validated['quantity'];
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Jumlah barang berhasil diperbarui.');
    }

    public function remove(Request $request, Product $product) {
        $cart = $request->session()->get('cart', []);
        $id = (string) $product->id;

        unset($cart[$id]);

        $request->session()->put('cart', $cart);

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
