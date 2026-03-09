<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function backup()
    {
        try {
            // Use Artisan command for backup
            \Artisan::call('backup:run');
            return redirect()->route('settings.index')->with('success', 'Backup berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->route('settings.index')->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        try {
            // Restore logic here
            return redirect()->route('settings.index')->with('success', 'Restore berhasil');
        } catch (\Exception $e) {
            return redirect()->route('settings.index')->with('error', 'Gagal restore: ' . $e->getMessage());
        }
    }
}
