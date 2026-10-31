<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    // Menampilkan riwayat pembelian pelanggan
    public function index(Request $request)
    {
        $query = Transaction::where('user_id', $request->user()->id)
            ->latest('transaction_date');

        // Pencarian kode transaksi
        if ($request->filled('search')) {
            $query->where(
                'transaction_code',
                'like',
                '%' . $request->search . '%'
            );
        }

        // Filter status pesanan
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('user.orders.index', compact('orders'));
    }

    // Menampilkan detail pembelian pelanggan
    public function show(Request $request, int $transaction)
    {
        $order = Transaction::with('details.product')
            ->where('user_id', $request->user()->id)
            ->findOrFail($transaction);

        return view('user.orders.show', compact('order'));
    }
}
