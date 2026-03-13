<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\DebtPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebtController extends Controller
{
    /**
     * Display a listing of unpaid and partially paid transactions.
     */
    public function index()
    {
        $transactions = Transaction::with('customer')
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->latest()
            ->paginate(20);
            
        return view('debts.index', compact('transactions'));
    }

    /**
     * Store a payment for a debt.
     */
    public function storePayment(Request $request, Transaction $transaction)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        if ($request->amount > $transaction->debt_amount) {
            return back()->with('error', 'Jumlah pembayaran melebihi sisa hutang.');
        }

        return DB::transaction(function () use ($request, $transaction) {
            // Create payment record
            DebtPayment::create([
                'transaction_id' => $transaction->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_date' => now(),
                'notes' => $request->notes,
            ]);

            // Update transaction
            $newDebt = $transaction->debt_amount - $request->amount;
            $newPaid = $transaction->paid_amount + $request->amount;
            
            $status = 'partial';
            if ($newDebt <= 0) {
                $status = 'paid';
            }

            $transaction->update([
                'debt_amount' => $newDebt,
                'paid_amount' => $newPaid,
                'payment_status' => $status,
            ]);

            return back()->with('success', 'Pembayaran hutang berhasil dicatat! Sisa hutang: Rp ' . number_format($newDebt, 0, ',', '.'));
        });
    }
}
