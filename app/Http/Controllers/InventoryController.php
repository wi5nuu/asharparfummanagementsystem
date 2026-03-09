<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
public function index()
{
    // Gunakan eager loading 'product' di semua query agar ringan di view
    $inventories = Inventory::with('product')->get();

    // 2. Filter stok rendah 
    $lowStock = Inventory::with('product')
                ->whereColumn('current_stock', '<', 'minimum_stock')
                ->where('current_stock', '>', 0)
                ->get();

    // 3. Filter stok habis
    $outOfStock = Inventory::with('product')
                ->where('current_stock', '<=', 0)
                ->get();

    // 4. Filter akan kadaluarsa
    // Gunakan startOfDay agar perbandingan tanggal lebih akurat
    $now = Carbon::now()->startOfDay();
    $thirtyDaysFromNow = Carbon::now()->addDays(30)->endOfDay();

    $expiringSoon = Inventory::with('product')
                ->whereNotNull('expiration_date')
                ->whereBetween('expiration_date', [$now, $thirtyDaysFromNow])
                ->get();

    // 5. Hitung total nilai inventory
    $totalInventoryValue = $inventories->sum(function($item) {
        return $item->current_stock * ($item->cost_per_unit ?? 0);
    });

    // 6. Ambil data produk untuk modal
    // Jika di modal butuh data stok terbaru dari tabel inventory, 
    // pastikan relasi di model Product sudah diset.
    $products = Product::all(); 

    return view('inventory.index', compact(
        'inventories', 
        'lowStock', 
        'outOfStock', 
        'expiringSoon', 
        'totalInventoryValue',
        'products'
    ));
}
public function adjust(\App\Http\Requests\AdjustInventoryRequest $request)
{
    $validated = $request->validated();

    try {
        DB::beginTransaction();

        // Cari inventory berdasarkan product_id
        // Asumsi: 1 produk memiliki 1 record di inventory
        $inventory = Inventory::where('product_id', $validated['product_id'])->first();

        if (!$inventory) {
            return response()->json(['message' => 'Data inventory tidak ditemukan'], 404);
        }

        $oldStock = $inventory->current_stock;
        $adjustmentQty = $validated['quantity'];
        $newStock = $oldStock;

        // 2. Logika perhitungan stok
        switch ($validated['adjustment_type']) {
            case 'add':
                $newStock = $oldStock + $adjustmentQty;
                break;
            case 'subtract':
                $newStock = $oldStock - $adjustmentQty;
                break;
            case 'set':
            case 'correction':
                $newStock = $adjustmentQty;
                break;
        }

        // 3. Update stok
        $inventory->update([
            'current_stock' => $newStock,
            // Update cost_per_unit jika ada input baru (opsional)
            'cost_per_unit' => $validated['cost_per_unit'] ?? $inventory->cost_per_unit
        ]);

        DB::commit();
        return response()->json(['message' => 'Stok berhasil diperbarui!']);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
    }
}
}