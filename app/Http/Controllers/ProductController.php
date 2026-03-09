<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk.
     */
    public function index()
    {
        // Eager loading untuk category dan inventory agar query lebih efisien
        $products = Product::with(['category'])
            ->latest()
            ->paginate(20);
            
        $categories = ProductCategory::all();
        
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Menampilkan form tambah produk.
     */
    public function create()
    {
        $categories = ProductCategory::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Menyimpan produk baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|unique:products,barcode',
            'product_category_id' => 'required|exists:product_categories,id',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'initial_stock' => 'required|integer|min:0',
            'minimum_stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'size' => 'required',
            'unit' => 'required'
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 2. Handle Upload Gambar
                $imagePath = null;
                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('products', 'public');
                }

                // 3. Simpan ke tabel Products
                $product = Product::create([
                    'name' => $request->name,
                    'barcode' => $request->barcode,
                    'product_category_id' => $request->product_category_id,
                    'brand' => $request->brand,
                    'size' => $request->size,
                    'unit' => $request->unit,
                    'purchase_price' => $request->purchase_price,
                    'selling_price' => $request->selling_price,
                    'wholesale_price' => $request->wholesale_price,
                    'initial_stock' => $request->initial_stock,
                    'image' => $imagePath,
                    'description' => $request->description,
                ]);

                // 4. Simpan ke tabel Inventory (Menyesuaikan Model Inventory kamu)
                Inventory::create([
                    'product_id' => $product->id,
                    'current_stock' => $request->initial_stock,
                    'minimum_stock' => $request->minimum_stock ?? 10,
                    'cost_per_unit' => $request->purchase_price,
                ]);
            });

            return redirect()->route('products.index')
                ->with('success', 'Produk dan stok awal berhasil ditambahkan!');

        } catch (\Exception $e) {
            // Jika gagal, hapus gambar yang sudah terupload (jika ada)
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail produk.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'inventories']);
        return view('products.show', compact('product'));
    }

    /**
     * Menampilkan form edit produk.
     */
    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update produk.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_category_id' => 'required|exists:product_categories,id',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'wholesale_price' => 'nullable|numeric',
            'minimum_stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'size' => 'required',
            'unit' => 'required'
        ]);

        try {
            DB::transaction(function () use ($request, $product) {
                // Handle Upload Gambar Baru (opsional)
                if ($request->hasFile('image')) {
                    // Hapus gambar lama
                    if ($product->image) {
                        Storage::disk('public')->delete($product->image);
                    }
                    $imagePath = $request->file('image')->store('products', 'public');
                    $product->image = $imagePath;
                }

                // Update produk
                $product->update([
                    'name' => $request->name,
                    'product_category_id' => $request->product_category_id,
                    'brand' => $request->brand,
                    'size' => $request->size,
                    'unit' => $request->unit,
                    'purchase_price' => $request->purchase_price,
                    'selling_price' => $request->selling_price,
                    'wholesale_price' => $request->wholesale_price,
                    'description' => $request->description,
                ]);

                // Update inventory jika ada
                if ($product->inventory) {
                    $product->inventory->update([
                        'cost_per_unit' => $request->purchase_price,
                        'minimum_stock' => $request->minimum_stock ?? 10,
                    ]);
                }
            });

            return redirect()->route('products.index')
                ->with('success', 'Produk berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus produk.
     */
    public function destroy(Product $product)
    {
        try {
            DB::transaction(function () use ($product) {
                // Hapus gambar
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }

                // Hapus inventory
                $product->inventory?->delete();

                // Hapus produk
                $product->delete();
            });

            return redirect()->route('products.index')
                ->with('success', 'Produk berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Print barcode produk.
     */
    public function printBarcode(Product $product)
    {
        return view('products.barcode', compact('product'));
    }
}