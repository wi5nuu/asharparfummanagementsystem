<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Expense;
use App\Models\Customer;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        // Combined Revenue: RetailTransactions + WholesaleOrders
        $retailRevenue = Transaction::whereMonth('created_at', now()->month)->sum('total_amount') ?? 0;
        $wholesaleRevenue = \App\Models\WholesaleOrder::whereMonth('created_at', now()->month)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount') ?? 0;
        $totalCombinedRevenue = $retailRevenue + $wholesaleRevenue;

        // Housing Stats (New)
        $housingStats = [
            'mess' => \App\Models\User::where('is_staying_in_mess', true)->count(),
            'house' => \App\Models\User::where('is_staying_in_mess', false)->count(),
        ];

        // Build report cards
        $reportCards = [
            [
                'title' => 'Total Omzet (Gabungan)',
                'value' => 'Rp ' . number_format($totalCombinedRevenue, 0, ',', '.'),
                'color' => 'primary',
                'icon' => 'fas fa-chart-line',
                'link' => route('reports.sales')
            ],
            [
                'title' => 'Total Transaksi',
                'value' => Transaction::whereMonth('created_at', now()->month)->count(),
                'color' => 'info',
                'icon' => 'fas fa-exchange-alt',
                'link' => route('reports.sales')
            ],
            [
                'title' => 'Total Produk',
                'value' => Product::count(),
                'color' => 'success',
                'icon' => 'fas fa-box',
                'link' => '#'
            ],
            [
                'title' => 'Staf di Mes',
                'value' => $housingStats['mess'] . ' Orang',
                'color' => 'warning',
                'icon' => 'fas fa-home',
                'link' => '#'
            ]
        ];

        $recentReports = collect([]);
        
        // Monthly statistics
        $totalProductsSold = DB::table('transaction_details')
            ->whereMonth('created_at', now()->month)
            ->sum('quantity') ?? 0;
            
        // Calculate Expenses for the month
        $monthlyExpenses = Expense::whereMonth('date', now()->month)->sum('amount') ?? 0;

        $monthlyStats = [
            'revenue' => $totalCombinedRevenue,
            'expenses' => $monthlyExpenses,
            'transactions' => Transaction::whereMonth('created_at', now()->month)->count() ?? 0,
            'products_sold' => $totalProductsSold,
            'profit' => 0
        ];
        $monthlyStats['profit'] = $monthlyStats['revenue'] - $monthlyStats['expenses'];

        // Chart data for monthly performance (last 6 months)
        $monthlyChartData = ['labels' => [], 'revenue' => [], 'expenses' => [], 'profit' => []];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            
            $revRetail = Transaction::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_amount') ?? 0;
            $revWholesale = \App\Models\WholesaleOrder::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount') ?? 0;
            $rev = $revRetail + $revWholesale;

            $exp = Expense::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount') ?? 0;
                
            $monthlyChartData['labels'][] = $date->format('M Y');
            $monthlyChartData['revenue'][] = $rev;
            $monthlyChartData['expenses'][] = $exp;
            $monthlyChartData['profit'][] = $rev - $exp;
        }

        return view('reports.index', compact('reportCards', 'recentReports', 'monthlyStats', 'monthlyChartData', 'housingStats'));
    }
    
    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        $type = $request->get('type', 'daily');
        
        $query = Transaction::whereBetween('created_at', [$startDate, $endDate]);
        
        if ($type === 'daily') {
            $sales = $query->selectRaw('DATE(created_at) as date, 
                COUNT(*) as transaction_count, 
                SUM(total_amount) as total_sales')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        } elseif ($type === 'monthly') {
            $sales = $query->selectRaw('MONTH(created_at) as month, 
                YEAR(created_at) as year,
                COUNT(*) as transaction_count, 
                SUM(total_amount) as total_sales')
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
        }
        
        $totalSales = $sales->sum('total_sales');
        $totalTransactions = $sales->sum('transaction_count');
        
        return view('reports.sales', compact('sales', 'totalSales', 'totalTransactions', 'startDate', 'endDate', 'type'));
    }
    
    public function inventory()
    {
        $lowStock = DB::table('inventories')
            ->join('products', 'inventories.product_id', '=', 'products.id')
            ->whereColumn('inventories.current_stock', '<', 'inventories.minimum_stock')
            ->where('inventories.current_stock', '>', 0)
            ->select('products.name', 'inventories.*')
            ->get();
        
        $outOfStock = DB::table('inventories')
            ->join('products', 'inventories.product_id', '=', 'products.id')
            ->where('inventories.current_stock', 0)
            ->select('products.name', 'inventories.*')
            ->get();
        
        $expiringSoon = DB::table('inventories')
            ->join('products', 'inventories.product_id', '=', 'products.id')
            ->whereNotNull('inventories.expiration_date')
            ->where('inventories.expiration_date', '<=', Carbon::now()->addDays(30))
            ->select('products.name', 'inventories.*')
            ->get();
        
        return view('reports.inventory', compact('lowStock', 'outOfStock', 'expiringSoon'));
    }
    
    public function profitLoss(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        
        $revenue = Transaction::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('total_amount');
        
        $expenses = Expense::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');
        
        $profit = $revenue - $expenses;
        
        // Expense breakdown
        $expenseBreakdown = Expense::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->join('expense_categories', 'expenses.category_id', '=', 'expense_categories.id')
            ->selectRaw('expense_categories.name, SUM(expenses.amount) as total')
            ->groupBy('expense_categories.name')
            ->get();
        
        // Revenue by payment method
        $revenueByMethod = Transaction::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->selectRaw('payment_method, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->get();
        
        return view('reports.profit-loss', compact(
            'revenue', 'expenses', 'profit', 'month', 'year',
            'expenseBreakdown', 'revenueByMethod'
        ));
    }
    
    public function exportSales(Request $request)
    {
        $period = $request->get('period', 'this_month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($period !== 'custom') {
            switch ($period) {
                case 'today':
                    $startDate = Carbon::today();
                    $endDate = Carbon::today()->endOfDay();
                    break;
                case 'yesterday':
                    $startDate = Carbon::yesterday();
                    $endDate = Carbon::yesterday()->endOfDay();
                    break;
                case 'this_week':
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                    break;
                case 'last_week':
                    $startDate = Carbon::now()->subWeek()->startOfWeek();
                    $endDate = Carbon::now()->subWeek()->endOfWeek();
                    break;
                case 'this_month':
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    break;
                case 'last_month':
                    $startDate = Carbon::now()->subMonth()->startOfMonth();
                    $endDate = Carbon::now()->subMonth()->endOfMonth();
                    break;
            }
        } else {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
        }

        $sales = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->with(['customer', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalSales = $sales->sum('total_amount');
        
        $pdf = Pdf::loadView('reports.exports.sales-pdf', compact('sales', 'startDate', 'endDate', 'period', 'totalSales'));
        return $pdf->stream('sales-report-' . $period . '-' . date('Y-m-d') . '.pdf');
    }
    
    public function customerAnalytics()
    {
        $topCustomers = Customer::withSum('transactions', 'total_amount')
            ->orderBy('transactions_sum_total_amount', 'desc')
            ->limit(10)
            ->get();
        
        $customerGrowth = Customer::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        $customerTypes = Customer::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get();
        
        return view('reports.customers', compact('topCustomers', 'customerGrowth', 'customerTypes'));
    }

    public function exportLowStock()
    {
        $lowStock = DB::table('inventories')
            ->join('products', 'inventories.product_id', '=', 'products.id')
            ->whereColumn('inventories.current_stock', '<', 'inventories.minimum_stock')
            ->where('inventories.current_stock', '>', 0)
            ->select('products.name', 'inventories.*')
            ->get();
            
        $pdf = Pdf::loadView('reports.exports.inventory-pdf', [
            'items' => $lowStock,
            'title' => 'Laporan Stok Rendah',
            'type' => 'low_stock'
        ]);
        return $pdf->stream('low-stock-report-' . date('Y-m-d') . '.pdf');
    }

    public function exportExpiry()
    {
        $expiringSoon = DB::table('inventories')
            ->join('products', 'inventories.product_id', '=', 'products.id')
            ->whereNotNull('inventories.expiration_date')
            ->where('inventories.expiration_date', '<=', Carbon::now()->addDays(30))
            ->select('products.name', 'inventories.*')
            ->get();
            
        $pdf = Pdf::loadView('reports.exports.inventory-pdf', [
            'items' => $expiringSoon,
            'title' => 'Laporan Produk Akan Kadaluarsa',
            'type' => 'expiry'
        ]);
        return $pdf->stream('expiry-report-' . date('Y-m-d') . '.pdf');
    }
}