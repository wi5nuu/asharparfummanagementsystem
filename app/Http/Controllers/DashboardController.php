<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        
        // 1. Statistik Penjualan & Produk
        $todaySales = Transaction::whereDate('created_at', $today)->sum('total_amount') ?? 0;
        $monthSales = Transaction::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('total_amount') ?? 0;
        
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        
        // 2. Logika Stok
        $lowStockProducts = DB::table('inventories')
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->count() ?? 0;
        
        $outOfStockProducts = DB::table('inventories')
            ->where('current_stock', '<=', 0)
            ->count() ?? 0;
        
        $lowStockAlerts = collect();
        $expiringAlerts = collect();
        
        // 3. Transaksi Terbaru
        $recentTransactions = Transaction::with(['customer'])
            ->latest()
            ->take(10)
            ->get();
        
        // 4. Pengeluaran & Profit
        $monthExpenses = Expense::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('amount') ?? 0;
        
        $profit = $monthSales - $monthExpenses;
        
        // 5. Data Grafik (Optimized to single query)
        $salesData = cache()->remember('dashboard_sales_data.' . $year, 3600, function() use ($year) {
            return $this->getMonthlySalesData($year);
        });
        
        // 6. Top Selling (Disesuaikan agar tidak error kolom selling_price)
        $topProducts = cache()->remember('dashboard_top_products.' . $month, 3600, function() use ($month) {
            return DB::table('transaction_details')
                ->join('products', 'transaction_details.product_id', '=', 'products.id')
                ->select('products.id', 'products.name', 'products.selling_price',
                    DB::raw('SUM(transaction_details.quantity) as total_sold'))
                ->whereMonth('transaction_details.created_at', $month)
                ->groupBy('products.id', 'products.name', 'products.selling_price')
                ->orderBy('total_sold', 'desc')
                ->take(5)
                ->get();
        });

        return view('dashboard.index', compact(
            'todaySales', 'monthSales', 'totalProducts', 'lowStockProducts',
            'outOfStockProducts', 'totalCustomers', 'recentTransactions',
            'topProducts', 'monthExpenses', 'profit', 'salesData',
            'lowStockAlerts', 'expiringAlerts'
        ));
    }
    
    private function getMonthlySalesData($year)
    {
        $monthlySales = Transaction::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as sales')
            )
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $data[] = [
                'month' => Carbon::create()->month($m)->format('M'),
                'sales' => $monthlySales->get($m)->sales ?? 0
            ];
        }
        return $data;
    }
}