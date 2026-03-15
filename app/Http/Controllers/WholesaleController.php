<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WholesaleOrder;
use App\Models\WholesaleOrderDetail;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WholesaleController extends Controller
{
    public function index(Request $request)
    {
        $query = WholesaleOrder::with(['user', 'customer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%")
                  ->orWhere('recipient_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(10);

        return view('wholesale.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::all();
        $customers = Customer::all();
        return view('wholesale.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'package_target_amount' => 'required|numeric|min:0',
            'recipient_name' => 'required|string',
            'recipient_phone' => 'required|string',
            'shipping_address' => 'required|string',
            'items' => 'required|array|min:1',
        ]);

        try {
            DB::beginTransaction();

            $order = WholesaleOrder::create([
                'invoice_number' => 'GROSIR-' . Carbon::now()->format('YmdHis') . '-' . strtoupper(str()->random(4)),
                'user_id' => Auth::id(),
                'customer_id' => $request->customer_id,
                'package_target_amount' => $request->package_target_amount,
                'recipient_name' => $request->recipient_name,
                'recipient_phone' => $request->recipient_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_courier' => $request->shipping_courier,
                'delivery_handler' => $request->delivery_handler,
                'packing_days' => $request->packing_days ?? 1,
                'status' => 'pending',
            ]);

            $totalAmount = 0;
            foreach ($request->items as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $totalAmount += $subtotal;

                WholesaleOrderDetail::create([
                    'wholesale_order_id' => $order->id,
                    'product_id' => $item['product_id'] ?? null,
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'volume_ml' => $item['volume_ml'] ?? null,
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('wholesale.index')->with('success', 'Pesanan Grosir berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    public function show(WholesaleOrder $order)
    {
        $order->load(['user', 'customer', 'details']);
        $whatsappUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $order->recipient_phone) . "?text=" . $this->generateWhatsAppMessage($order);
        return view('wholesale.show', compact('order', 'whatsappUrl'));
    }

    public function print(WholesaleOrder $order)
    {
        $order->load(['user', 'customer', 'details']);
        return view('wholesale.invoice', compact('order'));
    }

    public function confirm(WholesaleOrder $order)
    {
        // Owner and Admin only
        if (!in_array(Auth::user()->role, ['owner', 'admin'])) {
            return back()->with('error', 'Hanya Admin atau Owner yang dapat mengkonfirmasi pesanan grosir!');
        }

        $order->update([
            'status' => 'on_progress',
            'confirmed_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Pesanan dikonfirmasi dan sedang diproses!');
    }

    public function markReady(WholesaleOrder $order)
    {
        $order->update([
            'status' => 'ready_to_ship',
            'barcode' => 'SHP-' . $order->id . '-' . time(),
        ]);

        return back()->with('success', 'Pesanan sudah siap dan invoice telah dibuat!');
    }

    public function complete(WholesaleOrder $order)
    {
        $order->update([
            'status' => 'completed',
            'completed_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Pesanan telah selesai dikirim!');
    }

    private function generateWhatsAppMessage(WholesaleOrder $order): string
    {
        $message = "*NOTA PESANAN GROSIR - AL'ASHAR PARFUM*\n";
        $message .= "------------------------------------------------\n";
        $message .= "No. Invoice: *" . $order->invoice_number . "*\n";
        $message .= "Tanggal: " . $order->created_at->format('d/m/Y') . "\n";
        $message .= "Status: " . strtoupper($order->status) . "\n";
        $message .= "------------------------------------------------\n";
        $message .= "*Detail Pesanan:*\n";
        
        foreach ($order->details as $detail) {
            $message .= "- " . $detail->product_name;
            if ($detail->volume_ml) $message .= " (" . $detail->volume_ml . "ml)";
            $message .= " x" . $detail->quantity . " : Rp " . number_format($detail->subtotal, 0, ',', '.') . "\n";
        }
        
        $message .= "------------------------------------------------\n";
        $message .= "TOTAL NILAI: *Rp " . number_format($order->total_amount, 0, ',', '.') . "*\n";
        $message .= "Target Paket: Rp " . number_format($order->package_target_amount, 0, ',', '.') . "\n";
        
        if ($order->total_amount < $order->package_target_amount) {
            $message .= "Sisa Kekurangan: -Rp " . number_format($order->package_target_amount - $order->total_amount, 0, ',', '.') . "\n";
        }
        
        $message .= "------------------------------------------------\n";
        $message .= "*Informasi Pengiriman:*\n";
        $message .= "Penerima: " . $order->recipient_name . "\n";
        $message .= "Alamat: " . $order->shipping_address . "\n";
        $message .= "Estimasi Packing: " . ($order->packing_days ?? 1) . " Hari\n";
        
        $message .= "------------------------------------------------\n";
        $message .= "Lihat Invoice Digital:\n";
        $message .= route('wholesale.print', $order->id) . "\n\n";
        $message .= "Terima kasih telah memesan di *Al'Ashar Parfum*!\n";
        $message .= "_Sistem dikelola oleh APMS_";
        
        return rawurlencode($message);
    }
}
