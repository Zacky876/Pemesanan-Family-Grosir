<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('name')
            ->get();

        return view('admin.stock.index', compact('products'));
    }

    public function create(Product $product)
    {
        return view('admin.stock.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        if (
            $validated['type'] === 'out' &&
            $product->stock < $validated['quantity']
        ) {
            return back()
                ->withErrors([
                    'quantity' => 'Stok tidak mencukupi.',
                ])
                ->withInput();
        }

        DB::transaction(function () use ($validated, $product) {

            $stockBefore = $product->stock;

            if ($validated['type'] === 'in') {
                $product->stock += $validated['quantity'];
            } else {
                $product->stock -= $validated['quantity'];
            }

            $product->save();

            StockHistory::create([
                'product_id' => $product->id,
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'stock_before' => $stockBefore,
                'stock_after' => $product->stock,
                'reason' => $validated['reason'] ?? null,
                'user_id' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('admin.stock.index')
            ->with('success', 'Stok berhasil diperbarui.');
    }

    public function history(Product $product)
    {
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