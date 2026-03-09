<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::where('role', '!=', 'admin')->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string',
            'role' => 'required|in:cashier,manager,supervisor',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        User::create($validated);

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function show(User $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(User $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'phone' => 'nullable|string',
            'role' => 'required|in:cashier,manager,supervisor',
        ]);

        $employee->update($validated);
        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil diperbarui');
    }

    public function destroy(User $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus');
    }

    public function attendance(Request $request, User $employee)
    {
        $validated = $request->validate([
            'check_in' => 'nullable|date_format:Y-m-d H:i:s',
            'check_out' => 'nullable|date_format:Y-m-d H:i:s',
            'date' => 'required|date',
        ]);

        // Store attendance logic here
        return response()->json(['message' => 'Absensi berhasil dicatat']);
    }
}
