<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PaymentVerificationController extends Controller
{
    // Memastikan hanya admin dan kasir yang dapat mengakses fitur ini
    private function authorizeCashier(Request $request): void
    {
        abort_unless(
            $request->user()
                && in_array($request->user()->role, ['admin', 'kasir'], true),
            403,
            'Kamu tidak memiliki akses ke halaman pembayaran.'
        );
    }

    // Menampilkan daftar pesanan yang menunggu pembayaran
    public function index(Request $request)
    {
        $this->authorizeCashier($request);

        $orders = DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.payment_status', 'pending')
            ->select(
                'transactions.*',
                'users.name as customer_name'
            )
            ->orderBy('transactions.created_at')
            ->get();

        return view('admin.payments.index', compact('orders'));
    }

    // Menampilkan bukti QRIS secara privat
    public function proof(Request $request, int $transaction)
    {
        $this->authorizeCashier($request);

        $order = DB::table('transactions')
            ->where('id', $transaction)
            ->first();

        abort_unless(
            $order
                && $order->payment_method === 'qris'
                && !empty($order->payment_proof),
            404
        );

        $path = $order->payment_proof;

        abort_unless(
            Storage::disk('local')->exists($path),
            404,
            'File bukti pembayaran tidak ditemukan.'
        );

        return response()->file(
            Storage::disk('local')->path($path)
        );
    }

    // Mengonfirmasi pembayaran QRIS atau tunai
    public function confirm(Request $request, int $transaction)
    {
        $this->authorizeCashier($request);

        $request->validate([
            'payment' => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($request, $transaction) {
            $order = DB::table('transactions')
                ->where('id', $transaction)
                ->lockForUpdate()
                ->first();

            abort_unless($order, 404);

            if (
                $order->payment_status !== 'pending'
                || $order->status !== 'pending'
            ) {
                throw ValidationException::withMessages([
                    'payment' => 'Pesanan ini sudah diproses atau tidak lagi menunggu pembayaran.',
                ]);
            }

            if ($order->payment_method === 'qris') {
                if (empty($order->payment_proof)) {
                    throw ValidationException::withMessages([
                        'payment' => 'Pelanggan belum mengirim bukti pembayaran QRIS.',
                    ]);
                }

                // Kasir wajib memeriksa bukti dan memastikan dana benar-benar diterima.
                $payment = (float) $order->total;
                $change = 0;
            } elseif ($order->payment_method === 'cash') {
                $payment = (float) $request->input('payment', 0);

                if ($payment < (float) $order->total) {
                    throw ValidationException::withMessages([
                        'payment' => 'Uang tunai yang diterima belum mencukupi total pembayaran.',
                    ]);
                }

                $change = $payment - (float) $order->total;
            } else {
                throw ValidationException::withMessages([
                    'payment' => 'Metode pembayaran tidak valid.',
                ]);
            }

            $details = DB::table('transaction_details')
                ->where('transaction_id', $order->id)
                ->orderBy('product_id')
                ->get();

            if ($details->isEmpty()) {
                throw ValidationException::withMessages([
                    'payment' => 'Detail produk transaksi tidak ditemukan.',
                ]);
            }

            // Kunci semua produk secara berurutan agar stok tidak
            // terjual melebihi jumlah yang tersedia.
            $productIds = $details->pluck('product_id')
                ->unique()
                ->sort()
                ->values();

            $products = DB::table('products')
                ->whereIn('id', $productIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($details as $detail) {
                $product = $products->get($detail->product_id);

                if (
                    !$product
                    || $product->status !== 'active'
                    || (int) $detail->quantity < 1
                    || (int) $product->stock < (int) $detail->quantity
                ) {
                    throw ValidationException::withMessages([
                        'payment' => 'Stok produk tidak mencukupi. Pembayaran belum dikonfirmasi. Periksa pesanan ini terlebih dahulu.',
                    ]);
                }
            }

            // Kurangi stok hanya setelah pembayaran akan dikonfirmasi.
            foreach ($details as $detail) {
                DB::table('products')
                    ->where('id', $detail->product_id)
                    ->decrement('stock', (int) $detail->quantity);
            }

            DB::table('transactions')
                ->where('id', $order->id)
                ->update([
                    'payment' => $payment,
                    'change_amount' => $change,
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'status' => 'completed',
                    'updated_at' => now(),
                ]);
        });

        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil dikonfirmasi dan transaksi masuk ke penjualan.');
    }

    // Menandai pembayaran gagal
    public function fail(Request $request, int $transaction)
    {
        $this->authorizeCashier($request);

        DB::transaction(function () use ($transaction) {
            $order = DB::table('transactions')
                ->where('id', $transaction)
                ->lockForUpdate()
                ->first();

            abort_unless($order, 404);

            if (
                $order->payment_status !== 'pending'
                || $order->status !== 'pending'
            ) {
                throw ValidationException::withMessages([
                    'payment' => 'Hanya pesanan yang masih menunggu pembayaran yang dapat ditandai gagal.',
                ]);
            }

            DB::table('transactions')
                ->where('id', $order->id)
                ->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled',
                    'updated_at' => now(),
                ]);
        });

        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'Pembayaran ditandai gagal. Transaksi tidak masuk laporan penjualan.');
    }
}
