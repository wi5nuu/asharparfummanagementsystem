<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Expense;
use App\Models\TransactionDetail;
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
        $lowStockProductsCount = DB::table('inventories')
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->count() ?? 0;
        
        $outOfStockProductsCount = DB::table('inventories')
            ->where('current_stock', '<=', 0)
            ->count() ?? 0;
        
        // Detailed alerts for dashboard display
        $lowStockAlerts = DB::table('inventories')
            ->join('products', 'inventories.product_id', '=', 'products.id')
            ->select('products.name', 'inventories.current_stock', 'inventories.minimum_stock')
            ->whereColumn('inventories.current_stock', '<=', 'inventories.minimum_stock')
            ->orWhere('inventories.current_stock', '<', 5)
            ->take(5)
            ->get();
            
        $expiringAlerts = collect();
        
        // 3. Transaksi Terbaru
        $recentTransactions = Transaction::with(['customer'])
            ->latest()
            ->take(10)
            ->get();
        
        // 4. Pengeluaran & Profit RIIL
        $monthExpenses = Expense::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount') ?? 0;
        
        // Calculate COGS (HPP) for the month
        $monthCOGS = TransactionDetail::join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->whereMonth('transactions.created_at', $month)
            ->whereYear('transactions.created_at', $year)
            ->select(DB::raw('SUM(transaction_details.purchase_price * transaction_details.quantity) as total_cogs'))
            ->first()->total_cogs ?? 0;
            
        $grossProfit = $monthSales - $monthCOGS;
        $profit = $grossProfit - $monthExpenses; // Net Profit
        
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

        // 7. Active Shift
        $activeShift = \App\Models\Shift::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();
            
        return view('dashboard.index', compact(
            'todaySales', 'monthSales', 'totalProducts', 'lowStockProductsCount',
            'outOfStockProductsCount', 'totalCustomers', 'recentTransactions',
            'topProducts', 'monthExpenses', 'profit', 'salesData',
            'lowStockAlerts', 'expiringAlerts', 'monthCOGS', 'grossProfit',
            'activeShift'
        ));
    }
    
    public function getStats()
    {
        $todaySales = Transaction::whereDate('created_at', Carbon::today())->sum('total_amount');
        $monthSales = Transaction::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');
        
        $monthExpenses = Expense::whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount') ?? 0;
            
        $monthCOGS = TransactionDetail::join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->whereMonth('transactions.created_at', Carbon::now()->month)
            ->whereYear('transactions.created_at', Carbon::now()->year)
            ->select(DB::raw('SUM(transaction_details.purchase_price * transaction_details.quantity) as total_cogs'))
            ->first()->total_cogs ?? 0;
            
        $netProfit = $monthSales - $monthCOGS - $monthExpenses;
        
        $lowStockCount = DB::table('inventories')
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->orWhere('current_stock', '<', 5)
            ->count();
            
        $totalCustomers = Customer::count();

        return response()->json([
            'todaySales' => 'Rp ' . number_format($todaySales, 0, ',', '.'),
            'monthSales' => 'Rp ' . number_format($monthSales, 0, ',', '.'),
            'netProfit' => 'Rp ' . number_format($netProfit, 0, ',', '.'),
            'lowStockCount' => $lowStockCount,
            'totalCustomers' => $totalCustomers
        ]);
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