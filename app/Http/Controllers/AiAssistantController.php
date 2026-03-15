<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Inventory;
use App\Models\WholesaleOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AiAssistantController extends Controller
{
    protected $strategicService;

    public function __construct(\App\Services\AiStrategicService $strategicService)
    {
        $this->strategicService = $strategicService;
    }

    /**
     * Handle user questions to the AI Assistant.
     */
    public function ask(Request $request)
    {
        $message = strtolower($request->input('message', ''));
        $response = "";

        // Common keywords for strategic advice
        $isAskingTips = str_contains($message, 'saran') || str_contains($message, 'tips') || str_contains($message, 'strategi') || str_contains($message, 'promo') || str_contains($message, 'jual');

        if ($isAskingTips) {
            $advices = $this->strategicService->getSellingAdvices();
            if (count($advices) > 0) {
                $response = "Berdasarkan data statistik terbaru, berikut saran saya untuk meningkatkan penjualan:\n\n";
                foreach ($advices as $advice) {
                    $response .= "💡 **{$advice['title']}**: {$advice['text']}\n\n";
                }
                $response .= "Semoga tips ini membantu Anda cuan hari ini! 🚀";
            } else {
                $response = "Saya sedang menganalisis pola penjualan Anda. Saat ini belum ada saran spesifik, namun teruslah mencatat transaksi agar saya bisa memberikan tips yang lebih akurat!";
            }
        }
        elseif (str_contains($message, 'halo') || str_contains($message, 'hi') || str_contains($message, 'siapa')) {
            $response = "Halo! Saya adalah **APMS Assistant**. Saya bisa membantu Anda memantau stok, melihat tren penjualan, atau memandu Anda menggunakan fitur aplikasi ini. Apa yang ingin Anda ketahui?";
        } 
        elseif (str_contains($message, 'penjualan') || str_contains($message, 'omzet')) {
            $todaySales = Transaction::whereDate('created_at', Carbon::today())->sum('total_amount');
            $response = "Penjualan eceran hari ini mencapai **Rp " . number_format($todaySales, 0, ',', '.') . "**. ";
            
            $wholesale = WholesaleOrder::whereDate('created_at', Carbon::today())->where('status', '!=', 'cancelled')->sum('total_amount');
            if ($wholesale > 0) {
                $response .= "Selain itu, ada penjualan grosir sebesar **Rp " . number_format($wholesale, 0, ',', '.') . "**.";
            }
        } 
        elseif (str_contains($message, 'stok') || str_contains($message, 'habis') || str_contains($message, 'inventory')) {
            $lowStock = DB::table('inventories')
                ->join('products', 'inventories.product_id', '=', 'products.id')
                ->whereColumn('inventories.current_stock', '<=', 'inventories.minimum_stock')
                ->select('products.name', 'inventories.current_stock')
                ->get();

            if ($lowStock->count() > 0) {
                $response = "Ada **{$lowStock->count()} produk** dengan stok menipis: \n";
                foreach ($lowStock->take(3) as $item) {
                    $response .= "- {$item->name} ({$item->current_stock} pcs)\n";
                }
                if ($lowStock->count() > 3) $response .= "...dan " . ($lowStock->count() - 3) . " produk lainnya.";
            } else {
                $response = "Kabar baik! Semua stok produk saat ini masih terpantau aman di atas batas minimum.";
            }
        } 
        elseif (str_contains($message, 'terlaris') || str_contains($message, 'populer') || str_contains($message, 'laku')) {
            $top = DB::table('transaction_details')
                ->join('products', 'transaction_details.product_id', '=', 'products.id')
                ->select('products.name', DB::raw('SUM(transaction_details.quantity) as total'))
                ->where('transaction_details.created_at', '>=', Carbon::now()->subDays(30))
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total')
                ->first();

            if ($top && is_object($top)) {
                $response = "Produk paling populer dalam 30 hari terakhir adalah **{$top->name}** dengan total penjualan {$top->total} unit.";
            } else {
                $response = "Saya belum menemukan data transaksi yang cukup untuk menyimpulkan produk terlaris.";
            }
        }
        elseif (str_contains($message, 'help') || str_contains($message, 'bantuan') || str_contains($message, 'cara')) {
            $response = "Anda bisa bertanya kepada saya hal-hal seperti:\n- \"Berapa penjualan hari ini?\"\n- \"Berikan saran jualan\"\n- \"Produk apa yang paling laku?\"\n- \"Cek stok yang hampir habis\"";
        }
        else {
            $response = "Maaf, saya belum memahami pertanyaan itu. Cobalah bertanya tentang 'saran jualan', 'penjualan', 'stok', atau 'produk terlaris'. Saya terus belajar untuk menjadi lebih pintar!";
        }

        return response()->json([
            'reply' => $response,
            'timestamp' => Carbon::now()->format('H:i')
        ]);
    }
}
