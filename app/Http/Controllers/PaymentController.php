<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    // Menampilkan instruksi pembayaran QRIS
    public function show(Request $request, int $transaction)
    {
        $order = DB::table('transactions')
            ->where('id', $transaction)
            ->where('user_id', $request->user()->id)
            ->first();

        abort_unless($order, 404);

        if ($order->payment_method !== 'qris') {
            return redirect()
                ->route('user.checkout.success', $order->id);
        }

        return view('user.payment.show', compact('order'));
    }

    // Menyimpan bukti pembayaran QRIS
    public function uploadProof(Request $request, int $transaction)
    {
        $request->validate([
            'payment_proof' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $order = DB::table('transactions')
            ->where('id', $transaction)
            ->where('user_id', $request->user()->id)
            ->first();

        abort_unless($order, 404);

        if ($order->payment_method !== 'qris') {
            return back()->withErrors([
                'payment_proof' => 'Pesanan ini tidak menggunakan QRIS.',
            ]);
        }

        if ($order->payment_status === 'paid') {
            return back()->withErrors([
                'payment_proof' => 'Pembayaran pesanan ini sudah dikonfirmasi.',
            ]);
        }

        if ($order->payment_status === 'failed') {
            return back()->withErrors([
                'payment_proof' => 'Pembayaran gagal. Silakan hubungi admin.',
            ]);
        }

        $path = $request->file('payment_proof')
            ->store('payment-proofs', 'local');

        DB::table('transactions')
            ->where('id', $order->id)
            ->update([
                'payment_proof' => $path,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('user.payment.show', $order->id)
            ->with('success', 'Bukti pembayaran berhasil dikirim. Silakan tunggu verifikasi admin atau kasir.');
    }
}