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
use App\Services\SmartInsightService;

class DashboardController extends Controller
{
    protected $insightService;

    public function __construct(SmartInsightService $insightService)
    {
        $this->insightService = $insightService;
    }

    public function index(Request $request)
    {
        $period = $request->get('period', 'this_month');
        $today = Carbon::today();
        
        // Define range based on period
        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                $periodLabel = 'Hari Ini';
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $periodLabel = 'Minggu Ini';
                break;
            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                $periodLabel = 'Tahun Ini';
                break;
            case 'this_month':
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $periodLabel = 'Bulan Ini';
                break;
        }

        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        
        // 1. Statistik Penjualan & Produk (Eceran)
        $todaySales = Transaction::whereDate('created_at', $today)->sum('total_amount') ?? 0;
        $periodSales = Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount') ?? 0;
        
        // Backward compatibility for existing view variables if needed, though we should transition to period-based
        $monthSales = Transaction::whereMonth('created_at', $month)->whereYear('created_at', $year)->sum('total_amount') ?? 0;

        // 1b. Statistik Penjualan (Grosir)
        $wholesaleSalesToday = \App\Models\WholesaleOrder::whereDate('created_at', $today)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount') ?? 0;
            
        $wholesaleSalesPeriod = \App\Models\WholesaleOrder::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount') ?? 0;
        
        $wholesaleSalesMonth = \App\Models\WholesaleOrder::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount') ?? 0;
        
        // 1c. Total Combined Revenue (Retail + Wholesale)
        $totalCombinedRevenue = $periodSales + $wholesaleSalesPeriod;
        
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        
        // 2. Logika Stok
        $lowStockProductsCount = DB::table('inventories')
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->count() ?? 0;
        
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
        
        // 3b. Pesanan Grosir Status Summary
        $wholesaleSummary = \App\Models\WholesaleOrder::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        // 3c. Staf Aktif (Checked-in today, no time_out)
        $activeStaff = \App\Models\Attendance::whereDate('date', $today)
            ->where('status', 'present')
            ->whereNull('time_out')
            ->get();
        
        // 4. Pengeluaran & Profit RIIL based on Period
        $periodExpenses = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount') ?? 0;
        $monthExpenses = Expense::whereMonth('date', $month)->whereYear('date', $year)->sum('amount') ?? 0;
        
        // Calculate COGS (HPP) for the range
        $periodCOGS = TransactionDetail::join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->select(DB::raw('SUM(transaction_details.purchase_price * transaction_details.quantity) as total_cogs'))
            ->first()->total_cogs ?? 0;
            
        $periodGrossProfit = $totalCombinedRevenue - $periodCOGS;
        $periodProfit = $periodGrossProfit - $periodExpenses; // Net Profit for period
        
        // 5. Data Grafik
        $salesData = cache()->remember('dashboard_sales_data.' . $year, 3600, function() use ($year) {
            return $this->getMonthlySalesData($year);
        });
        
        // 6. Top Selling
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
            
        // Generate Smart Insights
        $smartInsights = $this->insightService->generateInsights();
            
        return view('dashboard.index', compact(
            'todaySales', 'periodSales', 'periodLabel', 'period', 'totalProducts', 'lowStockProductsCount',
            'totalCustomers', 'recentTransactions', 'topProducts', 'periodExpenses', 'periodProfit', 
            'salesData', 'lowStockAlerts', 'expiringAlerts', 'periodCOGS', 'periodGrossProfit',
            'activeShift', 'wholesaleSalesToday', 'wholesaleSalesPeriod', 
            // Keep month for backward compatibility if needed, or update view
            'monthSales', 'wholesaleSalesMonth', 'monthExpenses', 'smartInsights',
            'totalCombinedRevenue', 'activeStaff', 'wholesaleSummary'
        ));
    }
    
    public function getStats(Request $request)
    {
        $period = $request->get('period', 'this_month');
        
        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'this_month':
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }

        $todaySales = Transaction::whereDate('created_at', Carbon::today())->sum('total_amount');
        $periodSales = Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
        
        $periodExpenses = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount') ?? 0;
            
        $periodCOGS = TransactionDetail::join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->select(DB::raw('SUM(transaction_details.purchase_price * transaction_details.quantity) as total_cogs'))
            ->first()->total_cogs ?? 0;
            
        $netProfit = $periodSales - $periodCOGS - $periodExpenses;
        
        $lowStockCount = DB::table('inventories')
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->orWhere('current_stock', '<', 5)
            ->count();
            
        $totalCustomers = Customer::count();

        $wholesaleToday = \App\Models\WholesaleOrder::whereDate('created_at', Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount') ?? 0;
            
        $wholesalePeriod = \App\Models\WholesaleOrder::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount') ?? 0;

        return response()->json([
            'todaySales' => 'Rp ' . number_format($todaySales, 0, ',', '.'),
            'periodSales' => 'Rp ' . number_format($periodSales, 0, ',', '.'),
            'wholesaleToday' => 'Rp ' . number_format($wholesaleToday, 0, ',', '.'),
            'wholesalePeriod' => 'Rp ' . number_format($wholesalePeriod, 0, ',', '.'),
            'periodExpenses' => 'Rp ' . number_format($periodExpenses, 0, ',', '.'),
            'netProfit' => 'Rp ' . number_format($netProfit, 0, ',', '.'),
            'lowStockCount' => $lowStockCount,
            'totalCustomers' => $totalCustomers,
            'smartInsights' => $this->insightService->generateInsights()
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