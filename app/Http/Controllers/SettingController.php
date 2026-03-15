<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;

class SettingController extends Controller
{
    public function index()
    {
        $settings = \App\Models\Setting::pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token']);
        
        foreach ($data as $key => $value) {
            \App\Models\Setting::setValue($key, $value);
        }

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil diperbarui');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('settings.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only(['name', 'email', 'phone']));

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah');
    }

    public function backup()
    {
        try {
            Artisan::call('backup:run', ['--only-db' => true]);
            return redirect()->route('settings.index')->with('success', 'Backup database berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->route('settings.index')->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        // Simple placeholder for restore logic
        return redirect()->route('settings.index')->with('info', 'Fitur restore memerlukan akses manual ke server database untuk keamanan.');
    }
}
