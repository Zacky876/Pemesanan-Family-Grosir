<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class CustomerProductController extends Controller {

    // Menampilkan daftar produk, pencarian, dan filter kategori
    public function index(Request $request) {
        $query = Product::with('category')
            ->where('status', 'active')
            ->where('stock', '>', 0);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('user.products.index', compact(
            'products',
            'categories'
        ));
    }

    // Menampilkan detail produk
    public function show(Product $product) {
        abort_unless(
            $product->status === 'active' && $product->stock > 0,
            404
        );

        $product->load('category');

        return view('user.products.show', compact('product'));
    }
}
