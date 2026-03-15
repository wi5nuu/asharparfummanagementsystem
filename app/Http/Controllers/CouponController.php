<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(15);
        return view('coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('coupons.create');
    }

    public function store(\App\Http\Requests\StoreCouponRequest $request)
    {
        $validated = $request->validated();

        Coupon::create($validated);
        return redirect()->route('coupons.index')->with('success', 'Kupon berhasil dibuat');
    }

    public function show(Coupon $coupon)
    {
        return view('coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        return view('coupons.edit', compact('coupon'));
    }

    public function update(\App\Http\Requests\StoreCouponRequest $request, Coupon $coupon)
    {
        $validated = $request->validated();

        $coupon->update($validated);
        return redirect()->route('coupons.index')->with('success', 'Kupon berhasil diperbarui');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('coupons.index')->with('success', 'Kupon berhasil dihapus');
    }

    public function redeem(Request $request, Coupon $coupon)
    {
        if ($coupon->used_count >= $coupon->max_usage) {
            return response()->json(['message' => 'Kupon sudah mencapai batas penggunaan'], 400);
        }

        if ($coupon->expiration_date < now()) {
            return response()->json(['message' => 'Kupon sudah kadaluarsa'], 400);
        }

        $coupon->increment('used_count');
        return response()->json(['message' => 'Kupon berhasil digunakan']);
    }
}
