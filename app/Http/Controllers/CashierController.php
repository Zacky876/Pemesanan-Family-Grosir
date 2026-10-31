<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\StockHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CashierController extends Controller
{
    // Dashboard kasir
    public function dashboard() {
        return view('kasir.dashboard');
    }

    // Halaman POS dan keranjang
    public function pos(Request $request) {
        $search = $request->query('search');

        $products = Product::with('category')
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('code', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $cart = $request->session()->get('cashier_cart', []);

        $cartProducts = Product::whereIn('id', array_keys($cart))
            ->where('status', 'active')
            ->get()
            ->keyBy('id');

        $cartItems = [];
        $total = 0;

        foreach ($cart as $id => $quantity) {
            $product = $cartProducts->get($id);

            if (!$product) {
                continue;
            }

            $subtotal = $product->selling_price * $quantity;
            $total += $subtotal;

            $cartItems[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        }

        return view('kasir.pos', compact(
            'products',
            'search',
            'cartItems',
            'total'
        ));
    }

    // Menambahkan produk ke keranjang
    public function addToCart(Request $request, Product $product) {
        if ($product->status !== 'active' || $product->stock < 1) {
            return back()->with('error', 'Produk tidak tersedia atau stok habis.');
        }

        $cart = $request->session()->get('cashier_cart', []);

        $currentQuantity = (int) ($cart[$product->id] ?? 0);

        if ($currentQuantity + 1 > $product->stock) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia.');
        }

        $cart[$product->id] = $currentQuantity + 1;

        $request->session()->put('cashier_cart', $cart);

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    // Mengubah jumlah produk
    public function updateCart(Request $request, Product $product) {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($product->status !== 'active') {
            return back()->with('error', 'Produk sudah tidak tersedia.');
        }

        if ($validated['quantity'] > $product->stock) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia.');
        }

        $cart = $request->session()->get('cashier_cart', []);

        if (!array_key_exists($product->id, $cart)) {
            return back()->with('error', 'Produk tidak ditemukan di keranjang.');
        }

        $cart[$product->id] = (int) $validated['quantity'];

        $request->session()->put('cashier_cart', $cart);

        return back()->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    // Menghapus produk dari keranjang
    public function removeFromCart(Request $request, Product $product) {
        $cart = $request->session()->get('cashier_cart', []);

        unset($cart[$product->id]);

        $request->session()->put('cashier_cart', $cart);

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    // Proses pembayaran tunai
    public function store(Request $request) {
        $validated = $request->validate([
            'payment' => ['required', 'numeric', 'min:0'],
        ]);

        $cart = $request->session()->get('cashier_cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang masih kosong.');
        }

        $transaction = DB::transaction(function () use ($cart, $validated) {

            $total = 0;
            $products = [];

            // Periksa stok dan hitung total belanja
            foreach ($cart as $productId => $quantity) {
                $product = Product::whereKey($productId)
                    ->lockForUpdate()
                    ->first();

                if (!$product || $product->status !== 'active') {
                    throw ValidationException::withMessages([
                        'cart' => 'Ada produk yang sudah tidak tersedia.',
                    ]);
                }

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'cart' => 'Stok produk ' . $product->name . ' tidak mencukupi.',
                    ]);
                }

                $subtotal = $product->selling_price * $quantity;
                $total += $subtotal;

                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
            }

            // Validasi uang pembayaran
            if ($validated['payment'] < $total) {
                throw ValidationException::withMessages([
                    'payment' => 'Uang pembayaran kurang dari total belanja.',
                ]);
            }

            $change = $validated['payment'] - $total;

            // Simpan transaksi
            $transaction = Transaction::create([
                'transaction_code' => 'TRX-' . now()->format('YmdHis') . '-' . random_int(100, 999),
                'user_id' => Auth::id(),
                'transaction_date' => now(),
                'total' => $total,
                'payment' => $validated['payment'],
                'change_amount' => $change,
                'status' => 'completed',
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

            // Simpan detail transaksi dan kurangi stok
            foreach ($products as $item) {
                $product = $item['product'];
                $quantity = $item['quantity'];
                $stockBefore = $product->stock;
                $stockAfter = $stockBefore - $quantity;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->selling_price,
                    'subtotal' => $item['subtotal'],
                ]);

                $product->stock = $stockAfter;
                $product->save();

                // Catat riwayat stok keluar
                StockHistory::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'reason' => 'Penjualan kasir ' . $transaction->transaction_code,
                    'user_id' => Auth::id(),
                ]);
            }

            return $transaction;
        });

        // Kosongkan keranjang setelah pembayaran berhasil
        $request->session()->forget('cashier_cart');

        return redirect()
            ->route('kasir.transaction.receipt', $transaction->id)
            ->with('success', 'Pembayaran berhasil.');
    }


    // Menampilkan struk transaksi
    public function receipt(Transaction $transaction) {
        // Kasir hanya dapat melihat transaksi miliknya
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke struk ini.');
        }

        $transaction->load('user', 'details.product');

        return view('kasir.receipt', compact('transaction'));
    }
}
