<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Coupon;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['customer', 'user'])
            ->latest()
            ->paginate(20);
        
        return view('transactions.index', compact('transactions'));
    }
    
    public function create()
    {
        $products = Product::where('is_active', true)
            ->with(['inventories' => function($query) {
                $query->latest()->first();
            }])
            ->get();
        
        $customers = Customer::all();
        $categories = \App\Models\ProductCategory::all();
        
        return view('transactions.create', compact('products', 'customers', 'categories'));
    }
    
    public function store(\App\Http\Requests\StoreTransactionRequest $request)
    {
        $validated = $request->validated();
        
        try {
            DB::beginTransaction();

            // Generate invoice number
            $invoiceNumber = 'INV-' . Carbon::now()->format('Ymd') . '-' . strtoupper(Str::random(6));
            
            // Calculate totals
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            
            $discount = $validated['discount_amount'] ?? 0;
            $total = $subtotal - $discount;
            $paid = $validated['paid_amount'];
            $change = $paid - $total;
            
            // Handle coupon
            $coupon = null;
            if ($validated['coupon_code'] ?? false) {
                $coupon = Coupon::where('code', $validated['coupon_code'])
                    ->where('is_active', true)
                    ->where('expiration_date', '>=', now())
                    ->first();
                
                if ($coupon) {
                    $coupon->increment('used_count');
                    $coupon->update(['last_used_at' => now()]);
                }
            }
            
            // Create transaction (fields must match migration)
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $validated['customer_id'] ?? null,
                'customer_type' => $validated['customer_type'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total_amount' => $total, // Changed to total_amount to match Model
                'final_amount' => $total, // Fallback for legacy column
                'paid_amount' => $paid,
                'change_amount' => max(0, $change),
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
                'coupon_id' => $coupon?->id,
                'user_id' => auth()->id() // Changed cashier_id to user_id to match migration
            ]);
            
            // Create transaction details and update inventory
            foreach ($validated['items'] as $item) {
                $bonusQty  = intval($item['bonus_quantity'] ?? 0);
                $bonusNote = $item['bonus_note'] ?? null;

                TransactionDetail::create([
                    'transaction_id'  => $transaction->id,
                    'product_id'      => $item['product_id'],
                    'quantity'        => $item['quantity'],
                    'price'           => $item['price'],
                    'subtotal'        => $item['price'] * $item['quantity'],
                    'bonus_quantity'  => $bonusQty,
                    'bonus_note'      => $bonusNote,
                ]);
                
                // Update main inventory (deduct sold qty)
                $inventory = Inventory::where('product_id', $item['product_id'])
                    ->latest()->first();
                
                if ($inventory) {
                    $inventory->update([
                        'stock_out'     => $inventory->stock_out + $item['quantity'],
                        'current_stock' => max(0, $inventory->current_stock - $item['quantity']),
                        'date_sold'     => now()
                    ]);
                }

                // Deduct bonus stock from Sedang inventory (same product name)
                if ($bonusQty > 0) {
                    $this->deductBonusStock($item['product_id'], $bonusQty);
                }
            }

            DB::commit();
            
            return response()->json([
                'success' => true,
                'invoice_number' => $invoiceNumber,
                'transaction_id' => $transaction->id,
                'total' => $total,
                'change' => $change
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function show(Transaction $transaction)
    {
        $transaction->load(['details.product', 'customer', 'user', 'coupon']);
        return view('transactions.show', compact('transaction'));
    }
    
    public function printInvoice(Transaction $transaction)
    {
        $transaction->load(['details.product', 'customer']);
        $pdf = PDF::loadView('transactions.invoice', compact('transaction'));
        return $pdf->stream('invoice-' . $transaction->invoice_number . '.pdf');
    }
    
    public function getProductInfo($id)
    {
        $product = Product::with(['inventories', 'category'])->findOrFail($id);
        
        $inventory = $product->inventories->sortByDesc('id')->first();
        $currentStock = $inventory ? $inventory->current_stock : 0;
        $categoryName = $product->category ? strtolower($product->category->name) : '';
        
        return response()->json([
            'id'              => $product->id,
            'name'            => $product->name,
            'selling_price'   => $product->selling_price,
            'wholesale_price' => $product->wholesale_price,
            'current_stock'   => $currentStock,
            'unit'            => $product->unit,
            'category_id'     => $product->product_category_id,
            'category_name'   => $categoryName,
            'is_premium'      => $categoryName === 'premium',
            'size'            => $product->size,
        ]);
    }

    /**
     * Deduct bonus 20ml Sedang stock from inventory
     * Finds the Sedang version of the same product by name and deducts
     */
    private function deductBonusStock(int $premiumProductId, int $bonusQty): void
    {
        $premiumProduct = Product::find($premiumProductId);
        if (!$premiumProduct) return;

        // Get base name (remove size/category suffixes for matching)
        $baseName = preg_replace('/\s*(\(Uv\)|\(Gold\)|\(New\)|Premium|Sedang|Standar)\s*/i', '', $premiumProduct->name);
        $baseName = trim($baseName);

        // Try to find Sedang (category_id=2) version of same product
        $sedangProduct = Product::where('product_category_id', 2) // 2 = Regular/Sedang
            ->where('name', 'like', '%' . $baseName . '%')
            ->where('id', '!=', $premiumProductId)
            ->first();

        // Fallback: deduct from the same product's sedang inventory if no match
        $targetProductId = $sedangProduct ? $sedangProduct->id : $premiumProductId;

        $inventory = Inventory::where('product_id', $targetProductId)
            ->latest()->first();

        if ($inventory && $inventory->current_stock > 0) {
            $inventory->update([
                'stock_out'     => $inventory->stock_out + $bonusQty,
                'current_stock' => max(0, $inventory->current_stock - $bonusQty),
            ]);
        }
    }
    
}