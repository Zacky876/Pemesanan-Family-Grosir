<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //mengarahkan sesuai role
    public function admin() {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Total seluruh user
        $totalUsers = User::count();

        // Total produk
        $totalProducts = Product::count();

        // Transaksi hari ini yang sudah berhasil dibayar
        $transactionsToday = Transaction::where('payment_status', 'paid')
            ->whereDate('transaction_date', $today)
            ->count();

        // Pendapatan bulan ini
        $monthlyRevenue = Transaction::where('payment_status', 'paid')
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->sum('total');

        // Pendapatan bulan sebelumnya
        $lastMonthRevenue = Transaction::where('payment_status', 'paid')
            ->whereBetween('transaction_date', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ])
            ->sum('total');

        // Persentase perbandingan pendapatan
        if ($lastMonthRevenue > 0) {
            $revenueGrowth = (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        } else {
            $revenueGrowth = $monthlyRevenue > 0 ? 100 : 0;
        }

        // Data grafik pendapatan selama 12 bulan
        $monthlyChart = Transaction::select(
                DB::raw('MONTH(transaction_date) as month'),
                DB::raw('SUM(total) as revenue')
            )
            ->where('payment_status', 'paid')
            ->whereYear('transaction_date', Carbon::now()->year)
            ->groupBy(DB::raw('MONTH(transaction_date)'))
            ->orderBy(DB::raw('MONTH(transaction_date)'))
            ->get()
            ->keyBy('month');

        $chartLabels = [
            'Januari', 'Februari', 'Maret',
            'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September',
            'Oktober', 'November', 'Desember'
        ];

        $chartData = [];

        for ($month = 1; $month <= 12; $month++) {
            $chartData[] = (float) ($monthlyChart[$month]->revenue ?? 0);
        }

        // Produk dengan stok menipis
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->where('stock', '>', 0)
            ->orderBy('stock')
            ->get();

        // Produk yang stoknya habis
        $outOfStockProducts = Product::with('category')
            ->where('stock', '<=', 0)
            ->orderBy('name')
            ->get();

        // Jumlah produk stok menipis
        $lowStockCount = $lowStockProducts->count();

        // Jumlah produk stok habis
        $outOfStockCount = $outOfStockProducts->count();

        $categorySales = DB::table('transaction_details')
        ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
        ->join('products', 'transaction_details.product_id', '=', 'products.id')
        ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
        ->where('transactions.payment_status', 'paid')
        ->select(
            'categories.name as category_name',
            DB::raw('SUM(transaction_details.quantity) as total_sold')
        )
        ->groupBy('categories.id', 'categories.name')
        ->orderByDesc('total_sold')
        ->get();

        $categoryLabels = $categorySales->pluck('category_name')->toArray();

        $categoryData = $categorySales->pluck('total_sold')->toArray();

        $topCustomers = Transaction::join('users', 'transactions.user_id', '=', 'users.id')
        ->where('transactions.payment_status', 'paid')
        ->where('users.role', 'user')
        ->select(
            'users.id',
            'users.name',
            DB::raw('COUNT(transactions.id) as total_transactions'),
            DB::raw('SUM(transactions.total) as total_spent')
        )
        ->groupBy('users.id', 'users.name')
        ->orderByDesc('total_transactions')
        ->orderByDesc('total_spent')
        ->limit(5)
        ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProducts',
            'transactionsToday',
            'monthlyRevenue',
            'lastMonthRevenue',
            'revenueGrowth',
            'chartLabels',
            'chartData',
            'lowStockProducts',
            'outOfStockProducts',
            'lowStockCount',
            'outOfStockCount',
            'categoryLabels',
            'categoryData',
            'topCustomers'
        ));
    }

    public function kasir()
    {
        return view('kasir.dashboard');
    }

    public function user()
    {
        return view('user.dashboard');
    }
}
