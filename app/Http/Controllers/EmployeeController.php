<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        // Hide only the 'owner' role from the employee list. 
        // Admin, Cashier, etc. should remain visible for management purposes.
        $employees = User::where('role', '!=', 'owner')->latest()->paginate(10);
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
            'full_name' => 'nullable|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string',
            'role' => 'required|in:cashier,manager,supervisor,packing,admin',
            'password' => 'required|string|min:8|confirmed',
            'skills' => 'nullable|string',
            'is_staying_in_mess' => 'nullable|boolean',
            'living_address' => 'nullable|string',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['is_staying_in_mess'] = $request->boolean('is_staying_in_mess');
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
            'full_name' => 'nullable|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'phone' => 'nullable|string',
            'role' => 'required|in:cashier,manager,supervisor,packing,admin',
            'skills' => 'nullable|string',
            'is_staying_in_mess' => 'nullable|boolean',
            'living_address' => 'nullable|string',
        ]);

        $validated['is_staying_in_mess'] = $request->boolean('is_staying_in_mess');
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
