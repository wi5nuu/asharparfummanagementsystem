<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Inventory;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AiStrategicService
{
    /**
     * Get strategic selling tips based on deep data analysis.
     */
    public function getSellingAdvices()
    {
        $advices = [];

        // 1. Cross-Sell Opportunities (Frequently bought together)
        $crossSell = $this->getCrossSellOpportunity();
        if ($crossSell && is_object($crossSell)) {
            $advices[] = [
                'type' => 'cross_sell',
                'title' => 'Peluang Paket Hemat',
                'text' => "Pelanggan Anda sering membeli **{$crossSell->p1_name}** bersamaan dengan **{$crossSell->p2_name}**. Pertimbangkan untuk membuat paket bundling atau menawarkan item kedua saat kasir melayani pembeli.",
                'icon' => 'fa-layer-group',
                'color' => 'text-success'
            ];
        }

        // 2. Slow-Moving Inventory (Needs promotion)
        $slowMoving = $this->getSlowMovingProduct();
        if ($slowMoving && is_object($slowMoving)) {
            $advices[] = [
                'type' => 'slow_moving',
                'title' => 'Produk Butuh Sorotan',
                'text' => "Stok **{$slowMoving->name}** sudah ada lebih dari 30 hari tanpa penjualan baru. Cobalah berikan diskon 5-10% atau letakkan di barisan depan etalase untuk mempercepat perputarannya.",
                'icon' => 'fa-clock',
                'color' => 'text-warning'
            ];
        }

        // 3. High-Margin Advice (Profitable items)
        $highMargin = $this->getHighProfitPotential();
        if ($highMargin && is_object($highMargin)) {
            $advices[] = [
                'type' => 'high_profit',
                'title' => 'Fokus Laba Maksimal',
                'text' => "Produk **{$highMargin->name}** memiliki selisih harga (laba) yang sangat baik. Jika Anda bisa meningkatkan penjualannya meski hanya sedikit, laba bersih toko akan meningkat signifikan.",
                'icon' => 'fa-chart-pie',
                'color' => 'text-primary'
            ];
        }

        return $advices;
    }

    protected function getCrossSellOpportunity()
    {
        // Find pairs of products that appear in the same transactions
        return DB::table('transaction_details as t1')
            ->join('transaction_details as t2', 't1.transaction_id', '=', 't2.transaction_id')
            ->join('products as p1', 't1.product_id', '=', 'p1.id')
            ->join('products as p2', 't2.product_id', '=', 'p2.id')
            ->select('p1.name as p1_name', 'p2.name as p2_name', DB::raw('COUNT(*) as frequency'))
            ->where('t1.product_id', '<', 't2.product_id') // Avoid duplicates and self-joins
            ->where('t1.created_at', '>=', Carbon::now()->subMonths(3))
            ->groupBy('p1.id', 'p2.id', 'p1.name', 'p2.name')
            ->orderByDesc('frequency')
            ->first();
    }

    protected function getSlowMovingProduct()
    {
        // Products with stock > 0 but 0 sales in the last 30 days
        $recentlySoldIds = TransactionDetail::where('created_at', '>=', Carbon::now()->subDays(30))
            ->pluck('product_id')
            ->unique()
            ->toArray();

        return Product::join('inventories', 'products.id', '=', 'inventories.product_id')
            ->whereNotIn('products.id', $recentlySoldIds)
            ->where('inventories.current_stock', '>', 5)
            ->select('products.name', 'inventories.current_stock')
            ->orderByDesc('inventories.current_stock')
            ->first();
    }

    protected function getHighProfitPotential()
    {
        // Products where (price - purchase_price) is highest ratio
        // We look for products that have enough stock to push
        return Product::join('inventories', 'products.id', '=', 'inventories.product_id')
            ->select('products.name', DB::raw('(products.price - inventories.cost_per_unit) as margin'))
            ->where('inventories.current_stock', '>', 0)
            ->orderByDesc('margin')
            ->first();
    }
}
