<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller {
    public function index() {
        $products = Product::with('category')
            ->orderBy('name')
            ->get();

        return view('admin.stock.index', compact('products'));
    }

    public function create(Product $product) {
        return view('admin.stock.create', compact('product'));
    }

    public function store(Request $request, Product $product) {
        $validated = $request->validate([
            'type' => ['required', 'in:in,out,adjustment'],
            'quantity' => ['required', 'integer', 'min:0'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        if (
            $validated['type'] !== 'adjustment'
            && $validated['quantity'] < 1
        ) {
            return back()
                ->withErrors(['quantity' => 'Jumlah harus minimal 1.'])
                ->withInput();
        }

        DB::transaction(function () use ($validated, $product) {
            $lockedProduct = Product::whereKey($product->id)
                ->lockForUpdate()
                ->firstOrFail();

            $stockBefore = $lockedProduct->stock;
            $type = $validated['type'];
            $quantity = $validated['quantity'];

            if ($type === 'in') {
                $stockAfter = $stockBefore + $quantity;
            } elseif ($type === 'out') {
                if ($stockBefore < $quantity) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'quantity' => 'Stok tidak mencukupi.',
                    ]);
                }

                $stockAfter = $stockBefore - $quantity;
            } else {
                // Quantity adalah jumlah stok fisik terbaru.
                $stockAfter = $quantity;

                // Catat selisih stok pada riwayat.
                $quantity = abs($stockAfter - $stockBefore);
            }

            $lockedProduct->stock = $stockAfter;
            $lockedProduct->save();

            StockHistory::create([
                'product_id' => $lockedProduct->id,
                'type' => $type,
                'quantity' => $quantity,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reason' => $validated['reason'],
                'user_id' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('admin.stock.index')
            ->with('success', 'Stok berhasil diperbarui.');
    }

    public function history(Product $product) {
        $histories = $product->stockHistories()
            ->with('user')
            ->latest()
            ->get();

        return view('admin.stock.history', compact(
            'product',
            'histories'
        ));
    }
}