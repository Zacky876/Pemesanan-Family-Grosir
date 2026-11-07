<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\Product;

class CheckoutController extends Controller
{
    // Menampilkan halaman konfirmasi checkout
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('user.cart.index')
                ->with('error', 'Keranjang belanja masih kosong.');
        }

        $products = Product::whereIn('id', array_keys($cart))
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

            $quantity = (int) ($item['quantity'] ?? 0);

            if ($quantity < 1) {
                continue;
            }

            $subtotal = $product->selling_price * $quantity;
            $total += $subtotal;

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        }

        if (empty($items)) {
            return redirect()->route('user.cart.index')
                ->with('error', 'Produk di keranjang sudah tidak tersedia.');
        }

        return view('user.checkout.index', compact('items', 'total'));
    }

    // Menyimpan pesanan, tetapi belum mencatat penjualan
    public function store(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('user.cart.index')
                ->with('error', 'Keranjang belanja masih kosong.');
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:qris,cash'],
        ]);

        $transaction = DB::transaction(function () use ($cart, $request, $validated) {
            $ids = array_map('intval', array_keys($cart));

            $products = Product::whereIn('id', $ids)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 0;
            $details = [];

            foreach ($cart as $id => $item) {
                $product = $products->get((int) $id);
                $quantity = (int) ($item['quantity'] ?? 0);

                if (!$product || $product->status !== 'active') {
                    throw ValidationException::withMessages([
                        'cart' => 'Ada produk yang sudah tidak tersedia.',
                    ]);
                }

                if ($quantity < 1 || $quantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'cart' => 'Stok ' . $product->name . ' tidak mencukupi.',
                    ]);
                }

                $price = $product->selling_price;
                $subtotal = $price * $quantity;
                $total += $subtotal;

                $details[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ];
            }

            $now = now();

            $transactionId = DB::table('transactions')->insertGetId([
                'transaction_code' => 'ORD-' . $now->format('YmdHis')
                    . '-' . Str::upper(Str::random(5)),
                'user_id' => $request->user()->id,
                'transaction_date' => $now,
                'total' => $total,
                'payment' => 0,
                'change_amount' => 0,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'payment_proof' => null,
                'paid_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($details as $detail) {
                DB::table('transaction_details')->insert([
                    'transaction_id' => $transactionId,
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'price' => $detail['price'],
                    'subtotal' => $detail['subtotal'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            return $transactionId;
        });

        $request->session()->forget('cart');

        return redirect()
            ->route('user.checkout.success', $transaction)
            ->with('success', 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran.');
    }

    // Menampilkan detail pesanan
    public function success(Request $request, int $transaction)
    {
        $order = DB::table('transactions')
            ->where('id', $transaction)
            ->where('user_id', $request->user()->id)
            ->first();

        abort_unless($order, 404);

        $items = DB::table('transaction_details')
            ->join(
                'products',
                'transaction_details.product_id',
                '=',
                'products.id'
            )
            ->where('transaction_details.transaction_id', $order->id)
            ->select(
                'products.name',
                'transaction_details.quantity',
                'transaction_details.price',
                'transaction_details.subtotal'
            )
            ->get();

        return view('user.checkout.success', compact('order', 'items'));
    }
}