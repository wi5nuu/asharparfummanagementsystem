<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Shift;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Expense;
use App\Services\ApmsKnowledgeBase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OfflineAiController extends Controller
{
    private array $kb;

    public function __construct()
    {
        $this->kb = ApmsKnowledgeBase::getAll();
    }

    // ─── Entry Point ──────────────────────────────────────────────────────────
    public function chat(Request $request)
    {
        $raw     = $request->input('message', '');
        $message = strtolower(trim($raw));
        $message = str_replace(['?', '!', '.', ','], '', $message);

        $response = $this->processIntent($message);
        return response()->json(['reply' => $response]);
    }

    // ─── Master Intent Router ─────────────────────────────────────────────────
    private function processIntent(string $msg): string
    {
        // ① Greetings / Identity
        if ($this->anyOf($msg, ['halo', 'hai', 'pagi', 'siang', 'sore', 'malam', 'selamat', 'kamu siapa', 'siapa kamu', 'help', 'bantuan', 'bisa apa', 'fitur apa'])) {
            return $this->greetingMessage();
        }

        // ② Tentang APMS
        if ($this->anyOf($msg, ['apms itu', 'apa itu apms', 'penjelasan', 'fitur apms', 'sistem ini'])) {
            return $this->kbAnswer('tentang_apms');
        }

        // ③ Knowledge Base Search — FULL HOW-TO MATCH (highest priority)
        $kbResult = $this->searchKnowledgeBase($msg);
        if ($kbResult) return $kbResult;

        // ④ Data Live: Penjualan / Transaksi / Omzet
        if ($this->anyOf($msg, ['penjualan', 'pendapatan', 'omzet', 'omset', 'transaksi', 'uang masuk', 'pemasukan', 'revenue', 'laba kotor'])) {
            return $this->handleSalesIntent($msg);
        }

        // ⑤ Data Live: Stok / Inventory
        if ($this->anyOf($msg, ['stok', 'sisa', 'habis', 'barang', 'parfum', 'aroma', 'inventory', 'gudang', 'persediaan', 'kritis', 'menipis'])) {
            return $this->handleStockIntent($msg);
        }

        // ⑥ Data Live: Shift / Kasir Aktif
        if ($this->anyOf($msg, ['siapa kasir', 'siapa yang jaga', 'kasir aktif', 'shift sekarang', 'open shift sekarang'])) {
            return $this->handleShiftStatusIntent();
        }

        // ⑦ Data Live: Pelanggan
        if ($this->anyOf($msg, ['berapa pelanggan', 'jumlah member', 'total customer', 'jumlah pelanggan'])) {
            $total = Customer::count();
            return "👥 Saat ini terdaftar <strong>{$total} pelanggan</strong> aktif di sistem APMS.<br><br>👉 " . $this->btn('Lihat Semua Pelanggan', '/customers');
        }

        // ⑧ Data Live: Pengeluaran
        if ($this->anyOf($msg, ['pengeluaran', 'biaya', 'expense', 'ongkos'])) {
            return $this->handleExpenseIntent($msg);
        }

        // ⑨ Data Live: Grosir
        if ($this->anyOf($msg, ['grosir', 'pesanan grosir', 'order partai', 'wholesale', 'paket'])) {
            return $this->handleWholesaleIntent();
        }

        // ⑩ Data Live: Produk Terlaris
        if ($this->anyOf($msg, ['terlaris', 'paling laku', 'best seller', 'populer', 'sering dibeli'])) {
            return $this->handleTopProducts();
        }

        // ⑪ Navigation 
        if ($this->anyOf($msg, ['dimana', 'letak', 'cara', 'buka menu', 'halaman', 'menu', 'navigate', 'pergi ke', 'tuju', 'akses'])) {
            return $this->handleNavigationIntent($msg);
        }

        // ⑫ Fallback
        return $this->fallbackMessage($msg);
    }

    // ─── Knowledge Base Search ────────────────────────────────────────────────
    private function searchKnowledgeBase(string $msg): ?string
    {
        foreach ($this->kb as $key => $entry) {
            foreach ($entry['keywords'] as $keyword) {
                if (str_contains($msg, $keyword)) {
                    // Dynamic entries (e.g., top products)
                    if (isset($entry['dynamic'])) {
                        if ($entry['dynamic'] === 'top_products') return $this->handleTopProducts();
                    }
                    // Static answer
                    $html = $entry['answer'];
                    if (!empty($entry['url'])) {
                        $html .= "<br><br>👉 " . $this->btn($entry['label'], $entry['url']);
                    }
                    return $html;
                }
            }
        }
        return null;
    }

    // ─── Sales Handler ────────────────────────────────────────────────────────
    private function handleSalesIntent(string $msg): string
    {
        $now = Carbon::now();

        if ($this->anyOf($msg, ['bulan lalu', 'bulan kemarin'])) {
            $last  = $now->copy()->subMonth();
            $sales = Transaction::whereMonth('created_at', '=', $last->month)->whereYear('created_at', '=', $last->year)->sum('total_amount') ?? 0;
            $count = Transaction::whereMonth('created_at', '=', $last->month)->whereYear('created_at', '=', $last->year)->count() ?? 0;
            return "📅 Bulan lalu (<strong>{$last->translatedFormat('F Y')}</strong>):<br>Total <strong>{$this->rp($sales)}</strong> dari <strong>{$count} transaksi</strong>.<br><br>👉 " . $this->btn('Laporan Lengkap', '/reports');
        }

        if ($this->anyOf($msg, ['bulan ini', 'month', 'monthly'])) {
            $sales = Transaction::whereMonth('created_at', '=', $now->month)->whereYear('created_at', '=', $now->year)->sum('total_amount') ?? 0;
            $count = Transaction::whereMonth('created_at', '=', $now->month)->whereYear('created_at', '=', $now->year)->count() ?? 0;
            return "📅 <strong>{$now->translatedFormat('F Y')}</strong>:<br>Terkumpul <strong>{$this->rp($sales)}</strong> dari <strong>{$count} transaksi</strong> kasir.<br><br>👉 " . $this->btn('Lihat Laporan Bulanan', '/reports');
        }

        if ($this->anyOf($msg, ['minggu ini', 'week', 'pekan ini'])) {
            $sales = Transaction::where('created_at', '>=', $now->copy()->startOfWeek())->sum('total_amount') ?? 0;
            $count = Transaction::where('created_at', '>=', $now->copy()->startOfWeek())->count() ?? 0;
            return "📆 Minggu ini: <strong>{$this->rp($sales)}</strong> dari <strong>{$count} transaksi</strong>.<br><br>👉 " . $this->btn('Lihat Laporan', '/reports');
        }

        if ($this->anyOf($msg, ['kemarin', 'yesterday'])) {
            $yd    = $now->copy()->subDay()->toDateString();
            $sales = Transaction::whereDate('created_at', '=', $yd)->sum('total_amount') ?? 0;
            $count = Transaction::whereDate('created_at', '=', $yd)->count() ?? 0;
            return "📈 Kemarin (<strong>{$now->copy()->subDay()->format('d/m/Y')}</strong>): <strong>{$this->rp($sales)}</strong> dari <strong>{$count} transaksi</strong>.<br><br>👉 " . $this->btn('Detail Transaksi', '/transactions');
        }

        // Default: hari ini
        $sales  = Transaction::whereDate('created_at', '=', $now->toDateString())->sum('total_amount') ?? 0;
        $count  = Transaction::whereDate('created_at', '=', $now->toDateString())->count() ?? 0;

        if ($sales > 0) {
            return "🌟 Hari ini (<strong>{$now->format('d M Y')}</strong>):<br>Omzet <strong>{$this->rp($sales)}</strong> dari <strong>{$count} transaksi</strong>. Alhamdulillah!<br><br>" .
                   "👉 " . $this->btn('Lihat Transaksi', '/transactions') . ' ' . $this->btn('Buka Kasir', '/transactions/create', 'outline-success');
        }
        return "Hari ini belum ada transaksi. Sudahkah kasir <em>Open Shift</em>?<br><br>👉 " . $this->btn('Buka Kasir', '/transactions/create');
    }

    // ─── Stock Handler ────────────────────────────────────────────────────────
    private function handleStockIntent(string $msg): string
    {
        // Sub-intent: barang habis
        if ($this->anyOf($msg, ['yang habis', 'stok habis', 'out of stock', 'habis semua', 'mana yang habis'])) {
            $out = Inventory::where('current_stock', '<=', 0)->with('product')->take(6)->get();
            if ($out->isEmpty()) return "✅ Tidak ada produk yang stoknya habis saat ini. Gudang aman!";
            $list = "🔴 <strong>Produk HABIS</strong>:<br>";
            foreach ($out as $i) $list .= "- " . ($i->product->name ?? '?') . " ({$i->product->size}{$i->product->unit})<br>";
            return $list . "<br>👉 " . $this->btn('Isi Stok Sekarang', '/inventory');
        }

        // Sub-intent: stok kritis
        if ($this->anyOf($msg, ['kritis', 'menipis', 'hampir habis', 'low stock', 'batas minimum', 'rendah'])) {
            $low = Inventory::whereColumn('current_stock', '<=', 'minimum_stock')->where('current_stock', '>', 0)->with('product')->take(6)->get();
            if ($low->isEmpty()) return "✅ Semua stok masih di atas batas minimum. Aman!";
            $list = "🟡 <strong>Produk Stok KRITIS</strong>:<br>";
            foreach ($low as $i) $list .= "- " . ($i->product->name ?? '?') . " (sisa: <strong>{$i->current_stock}</strong>)<br>";
            return $list . "<br>👉 " . $this->btn('Restok via Inventory', '/inventory');
        }

        // Sub-intent: ringkasan gudang
        $stop  = ['cek', 'stok', 'sisa', 'parfum', 'berapa', 'barang', 'habis', 'ada', 'dong', 'tolong', 'info', 'dimana', 'letak', 'cari', 'aroma', 'produk', 'inventory', 'persediaan', 'gudang', 'sekarang'];
        $query = trim(str_replace($stop, '', $msg));

        if (empty($query) || strlen($query) <= 2) {
            $lowCount  = Inventory::whereColumn('current_stock', '<=', 'minimum_stock')->count();
            $outCount  = Inventory::where('current_stock', '<=', 0)->count();
            $totalVal  = Inventory::sum(DB::raw('current_stock * COALESCE(cost_per_unit, 0)'));
            $totalProd = Product::count();
            return "📊 <strong>Ringkasan Gudang:</strong><br>"
                . "- Total produk katalog: <strong>{$totalProd}</strong><br>"
                . "- Produk stok kritis: <strong class='text-warning'>{$lowCount}</strong><br>"
                . "- Produk stok habis: <strong class='text-danger'>{$outCount}</strong><br>"
                . "- Estimasi nilai gudang: <strong>{$this->rp($totalVal)}</strong><br><br>"
                . "👉 " . $this->btn('Buka Inventory Lengkap', '/inventory');
        }

        // Specific product search
        $products = Product::where('name', 'like', "%{$query}%")->with('inventories')->take(5)->get();
        if ($products->isEmpty()) {
            return "Parfum <strong>'{$query}'</strong> tidak ditemukan di katalog.<br><br>👉 " . $this->btn('Tambah Produk Baru', '/products');
        }
        $reply = "🔍 Hasil stok untuk <strong>'{$query}'</strong>:<br>";
        foreach ($products as $p) {
            $inv   = $p->inventories->first();
            $stock = $inv ? $inv->current_stock : 0;
            $badge = $stock <= 0 ? "🔴 <strong>HABIS</strong>" : ($stock < 10 ? "🟡 Kritis ({$stock})" : "🟢 Aman ({$stock})");
            $reply .= "- {$p->name} ({$p->size}{$p->unit}): {$badge}<br>";
        }
        return $reply . "<br>👉 " . $this->btn('Kelola Inventory', '/inventory');
    }

    // ─── Shift Status ─────────────────────────────────────────────────────────
    private function handleShiftStatusIntent(): string
    {
        $shift = Shift::whereNull('end_time')->with('user')->first();
        if ($shift) {
            $name  = $shift->user->name ?? 'Tim Toko';
            $waktu = $shift->start_time->format('H:i');
            $modal = $this->rp($shift->starting_balance);
            return "👤 <strong>Shift Aktif:</strong><br>Kasir: <strong>{$name}</strong><br>Buka: <strong>{$waktu}</strong><br>Modal Kas: <strong>{$modal}</strong><br><br>👉 " . $this->btn('Lihat Log Shift', '/shifts');
        }
        return "⚠️ <strong>Tidak ada kasir aktif</strong>! Belum ada yang Open Shift.<br><br>👉 " . $this->btn('Buka POS & Open Shift', '/transactions/create');
    }

    // ─── Expense Handler ──────────────────────────────────────────────────────
    private function handleExpenseIntent(string $msg): string
    {
        $now      = Carbon::now();
        $todayExp = Expense::whereDate('created_at', '=', $now->toDateString())->sum('amount') ?? 0;
        $monthExp = Expense::whereMonth('created_at', '=', $now->month)->sum('amount') ?? 0;
        return "💸 <strong>Pengeluaran:</strong><br>"
            . "- Hari ini: <strong>{$this->rp($todayExp)}</strong><br>"
            . "- Bulan ini: <strong>{$this->rp($monthExp)}</strong><br><br>"
            . "👉 " . $this->btn('Kelola Pengeluaran', '/expenses') . ' ' . $this->btn('Laporan Laba Rugi', '/reports', 'outline-primary');
    }

    // ─── Wholesale Handler ────────────────────────────────────────────────────
    private function handleWholesaleIntent(): string
    {
        $pending    = \App\Models\WholesaleOrder::where('status', 'pending')->count();
        $onProgress = \App\Models\WholesaleOrder::where('status', 'on_progress')->count();
        $done       = \App\Models\WholesaleOrder::where('status', 'completed')->whereMonth('created_at', now()->month)->count();
        $txt = $pending > 0 ? "⚠️ Ada <strong>{$pending} pesanan PENDING</strong> menunggu konfirmasi Admin!" : "✅ Tidak ada pesanan pending.";
        return "🚚 <strong>Status Grosir Saat Ini:</strong><br>"
            . $txt . "<br>"
            . "- Sedang diproses: <strong>{$onProgress}</strong><br>"
            . "- Selesai bulan ini: <strong>{$done}</strong><br><br>"
            . "👉 " . $this->btn('Lihat Semua Pesanan', '/wholesale') . ' ' . $this->btn('Buat Pesanan Baru', '/wholesale/create', 'outline-primary');
    }

    // ─── Top Products ─────────────────────────────────────────────────────────
    private function handleTopProducts(): string
    {
        $tops = DB::table('transaction_details')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(transaction_details.quantity) as total_qty'), DB::raw('SUM(transaction_details.subtotal) as total_rev'))
            ->where('transaction_details.created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        if ($tops->isEmpty()) return "Belum ada data transaksi yang cukup untuk menentukan produk terlaris. Mulai catat penjualan via Kasir!";
        $html = "🏆 <strong>Top 5 Produk Terlaris (30 Hari):</strong><br>";
        $i = 1;
        foreach ($tops as $t) {
            $medal = ['🥇','🥈','🥉','4️⃣','5️⃣'];
            $html .= "{$medal[$i-1]} {$t->name} — {$t->total_qty} pcs ({$this->rp($t->total_rev)})<br>";
            $i++;
        }
        return $html . "<br>👉 " . $this->btn('Lihat Laporan Lengkap', '/reports');
    }

    // ─── Navigation Handler ───────────────────────────────────────────────────
    private function handleNavigationIntent(string $msg): string
    {
        $routes = [
            ['keywords' => ['kasir', 'pos', 'jual eceran', 'retail'],            'label' => 'Mesin Kasir POS',      'url' => '/transactions/create'],
            ['keywords' => ['riwayat', 'transaksi lama', 'history'],             'label' => 'Riwayat Transaksi',    'url' => '/transactions'],
            ['keywords' => ['produk', 'katalog', 'aroma', 'item'],               'label' => 'Katalog Produk',       'url' => '/products'],
            ['keywords' => ['grosir', 'partai', 'paket', 'agen'],                'label' => 'Manajemen Grosir',     'url' => '/wholesale'],
            ['keywords' => ['inventory', 'gudang', 'stok'],                      'label' => 'Inventory Gudang',     'url' => '/inventory'],
            ['keywords' => ['pengaturan', 'setting', 'nota', 'pajak', 'backup'], 'label' => 'Pengaturan Toko',      'url' => '/settings'],
            ['keywords' => ['bon', 'hutang', 'utang', 'piutang'],                'label' => 'Buku Kas Bon',         'url' => '/debts'],
            ['keywords' => ['karyawan', 'pegawai', 'staf'],                      'label' => 'Manajemen Karyawan',   'url' => '/employees'],
            ['keywords' => ['absensi', 'absen', 'hadir', 'kehadiran'],           'label' => 'Absensi Kehadiran',    'url' => '/attendances'],
            ['keywords' => ['kupon', 'promo', 'diskon', 'voucher'],              'label' => 'Kupon & Loyalty',      'url' => '/coupons'],
            ['keywords' => ['laporan', 'report', 'grafik', 'analisa'],           'label' => 'Dasbor Laporan',       'url' => '/reports'],
            ['keywords' => ['audit', 'opname', 'cek fisik'],                     'label' => 'Audit Stok',           'url' => '/stock_audits'],
            ['keywords' => ['shift', 'closing', 'tutup toko'],                   'label' => 'Shift & Closing',      'url' => '/shifts'],
            ['keywords' => ['pelanggan', 'member', 'customer'],                  'label' => 'Data Pelanggan',       'url' => '/customers'],
            ['keywords' => ['pengeluaran', 'biaya', 'expense'],                  'label' => 'Pengeluaran',          'url' => '/expenses'],
            ['keywords' => ['dashboard', 'beranda', 'home', 'utama'],            'label' => 'Dashboard Utama',      'url' => '/dashboard'],
            ['keywords' => ['profil', 'akun saya', 'password'],                  'label' => 'Profil & Password',    'url' => '/settings/profile'],
        ];

        foreach ($routes as $route) {
            if ($this->anyOf($msg, $route['keywords'])) {
                return "🚀 Saya antar Anda ke <strong>{$route['label']}</strong>!<br><br>👉 " . $this->btn("Pergi ke {$route['label']}", $route['url']);
            }
        }

        return "Saya bisa mengantarkan Anda ke modul mana saja! Menu utama APMS:<br>"
            . $this->miniChip('🏪 Kasir', '/transactions/create')
            . $this->miniChip('📦 Gudang', '/inventory')
            . $this->miniChip('📊 Laporan', '/reports')
            . $this->miniChip('⚙️ Pengaturan', '/settings')
            . $this->miniChip('👥 Pelanggan', '/customers')
            . $this->miniChip('🚚 Grosir', '/wholesale')
            . $this->miniChip('📒 Kas Bon', '/debts')
            . $this->miniChip('📋 Audit', '/stock_audits');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────
    private function anyOf(string $msg, array $keywords): bool
    {
        foreach ($keywords as $kw) {
            if (str_contains($msg, $kw)) return true;
        }
        return false;
    }

    private function rp(float $val): string
    {
        return 'Rp ' . number_format($val, 0, ',', '.');
    }

    private function btn(string $label, string $url, string $style = 'primary'): string
    {
        return "<a href='{$url}' class='btn btn-{$style} btn-sm mt-1' style='border-radius:6px;font-size:0.78rem;font-weight:600;'>{$label}</a>";
    }

    private function miniChip(string $label, string $url): string
    {
        return "<a href='{$url}' style='display:inline-block;background:#e8f0fe;color:#2c7be5;border-radius:20px;padding:3px 10px;margin:2px 3px;font-size:0.78rem;text-decoration:none;font-weight:600;'>{$label}</a>";
    }

    private function kbAnswer(string $key): string
    {
        $entry = $this->kb[$key] ?? null;
        if (!$entry) return $this->fallbackMessage($key);
        $html = $entry['answer'];
        if (!empty($entry['url'])) $html .= "<br><br>👉 " . $this->btn($entry['label'], $entry['url']);
        return $html;
    }

    private function greetingMessage(): string
    {
        $h = (int) Carbon::now()->format('H');
        $salam = $h < 11 ? "Selamat Pagi" : ($h < 15 ? "Selamat Siang" : ($h < 18 ? "Selamat Sore" : "Selamat Malam"));
        return "{$salam}! 👋 Saya <strong>APMS Copilot</strong> — asisten bisnis Anda.<br><br>"
            . "Saya mengerti bahasa alami. Coba tanyakan:<br>"
            . "▸ <em>\"Berapa penjualan hari ini?\"</em><br>"
            . "▸ <em>\"Stok parfum baccarat sisa berapa?\"</em><br>"
            . "▸ <em>\"Gimana cara buat pesanan grosir?\"</em><br>"
            . "▸ <em>\"Cara cetak struk pelanggan?\"</em><br>"
            . "▸ <em>\"Produk apa yang paling laku?\"</em><br>"
            . "▸ <em>\"Dimana letak pengaturan pajak?\"</em><br><br>"
            . "Atau tekan chip di atas untuk akses cepat! ⚡";
    }

    private function fallbackMessage(string $msg): string
    {
        return "Saya mengenali <em>'{$msg}'</em> namun belum memahami konteksnya dengan tepat 🤔<br><br>"
            . "Coba tanyakan dengan kata kunci lebih spesifik, misal:<br>"
            . "▸ <em>\"cara tambah stok\"</em><br>"
            . "▸ <em>\"berapa transaksi bulan ini\"</em><br>"
            . "▸ <em>\"stok yang habis\"</em><br>"
            . "▸ <em>\"cara open shift kasir\"</em><br>"
            . "▸ <em>\"dimana halaman laporan\"</em><br><br>"
            . "👉 " . $this->btn('Kembali ke Dashboard', '/dashboard', 'outline-secondary');
    }
}
