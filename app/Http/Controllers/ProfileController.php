<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PDF;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(20);
        
        return view('products.index', compact('products'));
    }
    
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'size' => 'required|string|max:50',
            'unit' => 'required|in:ml,pcs,liter',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);
        
        // Generate barcode
        $validated['barcode'] = $this->generateBarcode();
        $validated['internal_id'] = 'PRD-' . strtoupper(Str::random(8));
        
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        
        $product = Product::create($validated);
        
        // Create initial inventory
        Inventory::create([
            'product_id' => $product->id,
            'stock_in' => 0,
            'stock_out' => 0,
            'current_stock' => 0,
            'cost_per_unit' => $product->purchase_price,
            'date_received' => now(),
            'minimum_stock' => 10
        ]);
        
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }
    
    public function show(Product $product)
    {
        $product->load(['inventories', 'category']);
        return view('products.show', compact('product'));
    }
    
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }
    
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'size' => 'required|string|max:50',
            'unit' => 'required|in:ml,pcs,liter',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);
        
        if ($request->hasFile('image')) {
            if ($product->image) {
                \Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        
        $product->update($validated);
        
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }
    
    public function destroy(Product $product)
    {
        if ($product->image) {
            \Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
    
    public function printBarcode(Product $product)
    {
        $pdf = PDF::loadView('products.barcode', compact('product'));
        return $pdf->stream('barcode-' . $product->barcode . '.pdf');
    }
    
    private function generateBarcode()
    {
        do {
            $barcode = '88' . mt_rand(1000000000, 9999999999);
        } while (Product::where('barcode', $barcode)->exists());
        
        return $barcode;
    }
}