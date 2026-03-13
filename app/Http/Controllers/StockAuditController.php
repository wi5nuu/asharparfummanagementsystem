<?php

namespace App\Http\Controllers;

use App\Models\StockAudit;
use App\Models\StockAuditItem;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAuditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $audits = StockAudit::with(['user', 'items'])->latest()->paginate(20);
        return view('stock_audits.index', compact('audits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stock_audits.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'notes' => 'nullable|string',
            'limit' => 'required|integer|min:1|max:50',
        ]);

        return DB::transaction(function () use ($request) {
            $audit = StockAudit::create([
                'user_id' => auth()->id(),
                'audit_date' => now(),
                'status' => 'draft',
                'notes' => $request->notes,
            ]);

            // Pick random products for audit
            $products = Product::inRandomOrder()->take($request->limit)->get();

            foreach ($products as $product) {
                $inventory = Inventory::where('product_id', $product->id)->latest()->first();
                $systemStock = $inventory ? $inventory->current_stock : 0;

                StockAuditItem::create([
                    'stock_audit_id' => $audit->id,
                    'product_id' => $product->id,
                    'system_stock' => $systemStock,
                ]);
            }

            return redirect()->route('stock_audits.show', $audit->id)
                ->with('success', 'Audit stok acak berhasil dibuat! Silakan hitung fisik barang.');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(StockAudit $stockAudit)
    {
        $stockAudit->load(['items.product', 'user']);
        return view('stock_audits.show', compact('stockAudit'));
    }

    /**
     * Update the items in the audit.
     */
    public function updateItems(Request $request, StockAudit $stockAudit)
    {
        if ($stockAudit->status === 'completed') {
            return back()->with('error', 'Audit ini sudah selesai dan tidak bisa diubah.');
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.physical_stock' => 'required|integer|min:0',
            'items.*.notes' => 'nullable|string',
        ]);

        foreach ($request->items as $itemId => $itemData) {
            $item = StockAuditItem::findOrFail($itemId);
            $item->update([
                'physical_stock' => $itemData['physical_stock'],
                'notes' => $itemData['notes'] ?? null,
            ]);
        }

        if ($request->has('complete')) {
            $stockAudit->update(['status' => 'completed']);
            return redirect()->route('stock_audits.index')
                ->with('success', 'Audit stok telah diselesaikan!');
        }

        return back()->with('success', 'Jumlah fisik berhasil disimpan sementara.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockAudit $stockAudit)
    {
        $stockAudit->delete();
        return redirect()->route('stock_audits.index')->with('success', 'Audit berhasil dihapus.');
    }
}
