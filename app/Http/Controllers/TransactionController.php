<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Menampilkan seluruh riwayat transaksi
    public function index(Request $request)
    {
        $query = Transaction::with('user')
            ->latest('transaction_date');

        // Pencarian kode transaksi
        if ($request->filled('search')) {
            $query->where(
                'transaction_code',
                'like',
                '%' . $request->search . '%'
            );
        }

        // Filter tanggal mulai
        if ($request->filled('start_date')) {
            $query->whereDate(
                'transaction_date',
                '>=',
                $request->start_date
            );
        }

        // Filter tanggal akhir
        if ($request->filled('end_date')) {
            $query->whereDate(
                'transaction_date',
                '<=',
                $request->end_date
            );
        }

        // Filter kasir
        if ($request->filled('cashier')) {
            $query->where('user_id', $request->cashier);
        }

        // Filter status transaksi
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->paginate(10)->withQueryString();

        $cashiers = User::where('role', 'kasir')
            ->orderBy('name')
            ->get();

        return view('admin.transactions.index', compact(
            'transactions',
            'cashiers'
        ));
    }

    // Menampilkan detail transaksi
    public function show(Transaction $transaction)
    {
        $transaction->load('user', 'details.product');

        return view('admin.transactions.show', compact(
            'transaction'
        ));
    }
}