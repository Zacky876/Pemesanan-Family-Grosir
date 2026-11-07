<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    // Halaman utama laporan
    public function index() {
        return view('admin.reports.index');
    }

    // Laporan penjualan
    public function sales(Request $request) {
        $period = $request->input('period', 'monthly');

        if (!in_array($period, ['daily', 'weekly', 'monthly'])) {
            $period = 'monthly';
        }

        $date = $request->input('date', now()->toDateString());

        try {
            $selectedDate = Carbon::createFromFormat('Y-m-d', $date);
        } catch (\Exception $e) {
            $selectedDate = now();
            $date = now()->toDateString();
        }

        if ($period === 'daily') {
            $startDate = $selectedDate->copy()->startOfDay();
            $endDate = $selectedDate->copy()->endOfDay();
        } elseif ($period === 'weekly') {
            $startDate = $selectedDate->copy()->startOfWeek();
            $endDate = $selectedDate->copy()->endOfWeek();
        } else {
            $startDate = $selectedDate->copy()->startOfMonth();
            $endDate = $selectedDate->copy()->endOfMonth();
        }

        // Hanya menghitung transaksi yang sudah dibayar
        $baseQuery = Transaction::where('payment_status', 'paid')
            ->whereBetween('transaction_date', [$startDate, $endDate]);

        $totalSales = (clone $baseQuery)->sum('total');
        $totalTransactions = (clone $baseQuery)->count();

        $totalProductsSold = DB::table('transaction_details')
            ->join(
                'transactions',
                'transaction_details.transaction_id',
                '=',
                'transactions.id'
            )
            ->where('transactions.payment_status', 'paid')
            ->whereBetween('transactions.transaction_date', [
                $startDate,
                $endDate
            ])
            ->sum('transaction_details.quantity');

        // Penjualan berdasarkan produk
        $productSales = DB::table('transaction_details')
            ->join(
                'transactions',
                'transaction_details.transaction_id',
                '=',
                'transactions.id'
            )
            ->join(
                'products',
                'transaction_details.product_id',
                '=',
                'products.id'
            )
            ->where('transactions.payment_status', 'paid')
            ->whereBetween('transactions.transaction_date', [
                $startDate,
                $endDate
            ])
            ->select(
                'products.name',
                'products.code',
                DB::raw('SUM(transaction_details.quantity) as total_quantity'),
                DB::raw('SUM(transaction_details.subtotal) as total_sales')
            )
            ->groupBy('products.id', 'products.name', 'products.code')
            ->orderByDesc('total_sales')
            ->get();

        // Penjualan berdasarkan kategori
        $categorySales = DB::table('transaction_details')
            ->join(
                'transactions',
                'transaction_details.transaction_id',
                '=',
                'transactions.id'
            )
            ->join(
                'products',
                'transaction_details.product_id',
                '=',
                'products.id'
            )
            ->leftJoin(
                'categories',
                'products.category_id',
                '=',
                'categories.id'
            )
            ->where('transactions.payment_status', 'paid')
            ->whereBetween('transactions.transaction_date', [
                $startDate,
                $endDate
            ])
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(transaction_details.quantity) as total_quantity'),
                DB::raw('SUM(transaction_details.subtotal) as total_sales')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sales')
            ->get();

        // Penjualan berdasarkan kasir
        $cashierSales = DB::table('transactions')
        ->join('users', 'transactions.user_id', '=', 'users.id')
        ->where('transactions.payment_status', 'paid')
        ->where('users.role', 'kasir')
        ->whereBetween('transactions.transaction_date', [$startDate, $endDate])
        ->select(
            'users.id',
            'users.name as cashier_name',
            DB::raw('COUNT(transactions.id) as total_transactions'),
            DB::raw('SUM(transactions.total) as total_sales')
        )
        ->groupBy('users.id', 'users.name')
        ->get();

        return view('admin.reports.sales', compact(
            'period',
            'date',
            'startDate',
            'endDate',
            'totalSales',
            'totalTransactions',
            'totalProductsSold',
            'productSales',
            'categorySales',
            'cashierSales'
        ));
    }

    // Laporan stok
    public function stock() {
        $products = Product::with('category')
            ->orderBy('name')
            ->get();

        $totalProducts = $products->count();

        $availableProducts = $products->filter(function ($product) {
            return $product->stock > $product->minimum_stock;
        })->count();

        $lowStockProducts = $products->filter(function ($product) {
            return $product->stock > 0 &&
                $product->stock <= $product->minimum_stock;
        })->count();

        $outOfStockProducts = $products->filter(function ($product) {
            return $product->stock <= 0;
        })->count();

        return view('admin.reports.stock', compact(
            'products',
            'totalProducts',
            'availableProducts',
            'lowStockProducts',
            'outOfStockProducts'
        ));
    }
}
