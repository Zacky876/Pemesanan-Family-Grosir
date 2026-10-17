<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CustomerOrderController extends Controller {

    // Menampilkan riwayat pesanan pelanggan
    public function index(Request $request) {
        $orders = DB::table('transactions')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('transaction_date')
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    // Menampilkan detail pesanan milik pelanggan
    public function show(Request $request, int $transaction) {
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
                'products.code',
                'transaction_details.quantity',
                'transaction_details.price',
                'transaction_details.subtotal'
            )
            ->get();

        return view('user.orders.show', compact('order', 'items'));
    }
}
