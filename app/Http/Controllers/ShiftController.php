<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Transaction;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shifts = Shift::with('user')->latest()->paginate(20);
        $activeShift = Shift::where('user_id', auth()->id())->where('status', 'open')->first();
        
        return view('shifts.index', compact('shifts', 'activeShift'));
    }

    /**
     * Store a newly created shift (Open Shift).
     */
    public function store(Request $request)
    {
        $request->validate([
            'initial_cash' => 'required|numeric|min:0',
        ]);

        // Check if there's already an open shift for this user
        $activeShift = Shift::where('user_id', auth()->id())->where('status', 'open')->first();
        if ($activeShift) {
            return back()->with('error', 'Anda masih memiliki shift yang terbuka. Tutup shift tersebut terlebih dahulu.');
        }

        Shift::create([
            'user_id' => auth()->id(),
            'start_time' => now(),
            'initial_cash' => $request->initial_cash,
            'status' => 'open',
        ]);

        return redirect()->route('dashboard')->with('success', 'Shift berhasil dibuka! Selamat bekerja.');
    }

    /**
     * Update the shift (Close Shift).
     */
    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'actual_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($shift->status === 'closed') {
            return back()->with('error', 'Shift ini sudah ditutup.');
        }

        // Calculate expected cash
        // Expected = Initial + Cash Sales - Cash Expenses
        
        $cashSales = Transaction::where('user_id', $shift->user_id)
            ->where('payment_method', 'cash')
            ->whereBetween('created_at', [$shift->start_time, now()])
            ->sum('paid_amount'); // Use paid_amount to include debt payments if any, or just total if simple

        // Actually, paid_amount - change_amount is what went into the drawer
        $cashSales = Transaction::where('user_id', $shift->user_id)
            ->where('payment_method', 'cash')
            ->whereBetween('created_at', [$shift->start_time, now()])
            ->selectRaw('SUM(paid_amount - change_amount) as net_cash')
            ->first()->net_cash ?? 0;

        $cashExpenses = Expense::where('user_id', $shift->user_id)
            // Assuming we add a payment_method to expenses or just assume all are cash for now
            // For now let's assume all are cash as it's a small shop
            ->whereBetween('date', [$shift->start_time->format('Y-m-d H:i:s'), now()->format('Y-m-d H:i:s')])
            ->sum('amount') ?? 0;

        $expectedCash = $shift->initial_cash + $cashSales - $cashExpenses;
        $discrepancy = $request->actual_cash - $expectedCash;

        $shift->update([
            'end_time' => now(),
            'expected_cash' => $expectedCash,
            'actual_cash' => $request->actual_cash,
            'discrepancy' => $discrepancy,
            'status' => 'closed',
            'notes' => $request->notes,
        ]);

        return redirect()->route('shifts.index')->with('success', 'Shift berhasil ditutup. Laporan selisih: Rp ' . number_format($discrepancy, 0, ',', '.'));
    }

    /**
     * Get active shift data for the current user.
     */
    public static function getActiveShift()
    {
        return Shift::where('user_id', auth()->id())->where('status', 'open')->first();
    }
}
