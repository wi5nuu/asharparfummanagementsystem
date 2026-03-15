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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['customer', 'user']);

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by customer type
        if ($request->filled('customer_type')) {
            $query->where('customer_type', $request->customer_type);
        }

        $transactions = $query->latest()
            ->paginate(20)
            ->withQueryString();
        
        return view('transactions.index', compact('transactions'));
    }
    
    public function create()
    {
        $products = Product::where('is_active', true)
            ->with(['inventory', 'category'])
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

            // Check if there is an active shift for the user
            $activeShift = \App\Models\Shift::where('user_id', auth()->id())
                ->where('status', 'open')
                ->first();
                
            if (!$activeShift) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Anda tidak bisa melakukan transaksi karena shift belum dibuka! Silakan buka shift terlebih dahulu di menu Shift & Closing Kasir.'
                ], 403);
            }

            // Generate invoice number
            $invoiceNumber = 'INV-' . Carbon::now()->format('Ymd') . '-' . strtoupper(Str::random(6));
            
            // Calculate totals
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            
            $discount = $validated['discount_amount'] ?? 0;
            $discountType = $validated['discount_type'] ?? 'fixed';
            $discountPercent = $validated['discount_percent'] ?? 0;
            
            $taxEnabled = $validated['tax_enabled'] ?? true;
            $taxableAmount = $subtotal - $discount;
            $tax = $taxEnabled ? round($taxableAmount * 0.10) : 0; // 10% PPN if enabled
            
            $total = $taxableAmount + $tax;
            $paid = $validated['paid_amount'];
            $change = $paid - $total;
            
            // Handle coupon
            $coupon = null;
            if ($validated['coupon_code'] ?? false) {
                $coupon = \App\Models\Coupon::where('code', $validated['coupon_code'])
                    ->where('is_active', true)
                    ->where('expiration_date', '>=', now())
                    ->first();
                
                if ($coupon) {
                    $coupon->increment('used_count');
                    $coupon->update(['last_used_at' => now()]);
                }
            }
            
            // Calculate payment status and debt
            $paymentStatus = 'paid';
            $debtAmount = 0;
            if ($paid < $total) {
                $paymentStatus = ($paid > 0) ? 'partial' : 'unpaid';
                $debtAmount = $total - $paid;
            }

            // Create transaction (fields must match migration)
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $validated['customer_id'] ?? null,
                'customer_type' => $validated['customer_type'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'discount_type' => $discountType,
                'discount_percent' => $discountPercent,
                'tax_amount' => $tax,
                'total_amount' => $total,
                'final_amount' => $total,
                'paid_amount' => $paid,
                'change_amount' => max(0, $change),
                'payment_method' => $validated['payment_method'],
                'receipt_visibility' => $request->receipt_visibility ?? 'public',
                'payment_status' => $paymentStatus,
                'debt_amount' => $debtAmount,
                'notes' => $validated['notes'] ?? null,
                'coupon_id' => $coupon?->id,
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'ewallet_type' => $validated['ewallet_type'] ?? null,
                'tax_enabled' => $taxEnabled
            ]);
            
            // Create transaction details and update inventory
            foreach ($validated['items'] as $item) {
                $bonusQty  = intval($item['bonus_quantity'] ?? 0);
                $bonusNote = $item['bonus_note'] ?? null;

                // Get product to fetch current purchase_price
                $product = Product::find($item['product_id']);
                $purchasePrice = $product ? $product->purchase_price : 0;

                TransactionDetail::create([
                    'transaction_id'  => $transaction->id,
                    'product_id'      => $item['product_id'],
                    'quantity'        => $item['quantity'],
                    'size'            => $item['size'] ?? null,
                    'price'           => $item['price'],
                    'purchase_price'  => $purchasePrice,
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
            
            // Loyalty Points Awarding (1 point per 50,000 spend)
            if ($transaction->customer_id) {
                $pointsAwarded = floor($transaction->total_amount / 50000);
                if ($pointsAwarded > 0) {
                    $transaction->customer->increment('points', $pointsAwarded);
                }
            }
            
            return response()->json([
                'success' => true,
                'invoice_number' => $invoiceNumber,
                'transaction_id' => $transaction->id,
                'total' => $total,
                'change' => $change,
                'whatsapp_message' => $this->generateWhatsAppMessage($transaction)
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

    public function destroy(Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            // 1. Restore Stock
            foreach ($transaction->details as $detail) {
                // Restore main product stock
                $inventory = Inventory::where('product_id', $detail->product_id)->latest()->first();
                if ($inventory) {
                    $inventory->update([
                        'stock_out'     => max(0, $inventory->stock_out - $detail->quantity),
                        'current_stock' => $inventory->current_stock + $detail->quantity
                    ]);
                }

                // Restore bonus stock if bonus_quantity field was used
                if ($detail->bonus_quantity > 0) {
                    $this->restoreBonusStock($detail->product_id, $detail->bonus_quantity);
                }
            }

            // 2. Revert Points
            if ($transaction->customer_id) {
                $pointsAwarded = floor($transaction->total_amount / 50000);
                if ($pointsAwarded > 0) {
                    $transaction->customer->decrement('points', $pointsAwarded);
                }
            }

            // 3. Revert Coupon Usage
            if ($transaction->coupon_id) {
                $coupon = Coupon::find($transaction->coupon_id);
                if ($coupon) {
                    $coupon->decrement('used_count');
                }
            }

            // 4. Delete Transaction Details and then Transaction
            $transaction->details()->delete();
            $transaction->delete();

            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus dan stok telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
    
    public function printInvoice(Transaction $transaction)
    {
        $transaction->load(['details.product', 'customer']);
        
        // Generate only QR Code for Authenticity/Digital Link
        $invoiceUrl    = url("/transactions/{$transaction->id}/receipt");
        $qrUrl         = "https://barcodeapi.org/api/qr/" . urlencode($invoiceUrl);
        
        $qrBase64      = $this->getBase64Image($qrUrl);
        
        $pdf = PDF::loadView('transactions.invoice', compact('transaction', 'qrBase64'));
        return $pdf->stream('invoice-' . $transaction->invoice_number . '.pdf');
    }

    private function getBase64Image($url, $mimeType = 'image/png')
    {
        try {
            $ctx = stream_context_create([
                "ssl" => [
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ],
                "http" => [
                    "timeout" => 5
                ]
            ]);
            $data = @file_get_contents($url, false, $ctx);
            if (!$data) return null;
            
            return 'data:' . $mimeType . ';base64,' . base64_encode($data);
        } catch (\Exception $e) {
            return null;
        }
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

    private function restoreBonusStock(int $premiumProductId, int $bonusQty): void
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

        // Fallback: restore to the same product's sedang inventory if no match
        $targetProductId = $sedangProduct ? $sedangProduct->id : $premiumProductId;

        $inventory = Inventory::where('product_id', $targetProductId)
            ->latest()->first();

        if ($inventory) {
            $inventory->update([
                'stock_out'     => max(0, $inventory->stock_out - $bonusQty),
                'current_stock' => $inventory->current_stock + $bonusQty,
            ]);
        }
    }

    private function generateWhatsAppMessage(Transaction $transaction): string
    {
        $transaction->load(['details.product', 'customer']);
        
        $message = "*NOTA PENJUALAN - AL'ASHAR PARFUM*\n";
        $message .= "------------------------------------------------\n";
        $message .= "No. Invoice: *" . $transaction->invoice_number . "*\n";
        $message .= "Tanggal: " . $transaction->created_at->format('d/m/Y H:i') . "\n";
        $message .= "Pelanggan: " . ($transaction->customer->name ?? 'Umum') . "\n";
        $message .= "------------------------------------------------\n";
        
        $totalSavings = 0;
        foreach ($transaction->details as $detail) {
            $isBonus = ($detail->price == 0);
            $name = $detail->product->name;
            if ($detail->size) $name .= " (" . $detail->size . ")";
            
            if ($isBonus) {
                $itemValue = $detail->quantity * 43333;
                $totalSavings += $itemValue;
                $message .= "* " . $name . " x" . $detail->quantity . " : _GRATIS (Bonus)_\n";
            } else {
                $message .= "* " . $name . " x" . $detail->quantity . " : Rp " . number_format($detail->subtotal, 0, ',', '.') . "\n";
            }
        }
        
        $message .= "------------------------------------------------\n";
        $message .= "Subtotal: Rp " . number_format($transaction->subtotal, 0, ',', '.') . "\n";
        
        if ($transaction->discount > 0) {
            $label = "Diskon";
            if ($transaction->discount_type === 'percent') {
                $label .= " (" . $transaction->discount_percent . "%)";
            }
            $message .= $label . ": -Rp " . number_format($transaction->discount, 0, ',', '.') . "\n";
        }
        
        $message .= "PPN (10%): Rp " . number_format($transaction->tax_amount, 0, ',', '.') . "\n";
        $message .= "TOTAL: *Rp " . number_format($transaction->total_amount, 0, ',', '.') . "*\n";
        $message .= "------------------------------------------------\n";
        $message .= "Bayar: Rp " . number_format($transaction->paid_amount, 0, ',', '.') . "\n";
        $message .= "Kembali: Rp " . number_format($transaction->change_amount, 0, ',', '.') . "\n";
        
        if ($totalSavings > 0) {
            $message .= "*Hemat: Rp " . number_format($totalSavings, 0, ',', '.') . "*\n";
        }

        if ($transaction->customer_id && $transaction->customer->points > 0) {
            $pointsEarned = floor($transaction->total_amount / 50000);
            $message .= "------------------------------------------------\n";
            $message .= "Poin Baru: +" . $pointsEarned . " Poin\n";
            $message .= "Total Tabungan Poin: " . $transaction->customer->points . " Poin\n";
        }
        
        $message .= "------------------------------------------------\n";
        $message .= "Terima kasih telah berbelanja di *Al'Ashar Parfum*!\n";
        $message .= "www.ashargrosirparfum.com\n";
        $message .= "_Sistem dikelola oleh APMS_";
        
        return rawurlencode($message);
    }
}