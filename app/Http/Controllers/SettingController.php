<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class SettingController extends Controller
{
    public function index()
    {
        $settings = \App\Models\Setting::pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', 'store_logo']);
        
        // Handle Logo Upload
        if ($request->hasFile('store_logo')) {
            $path = $request->file('store_logo')->store('logos', 'public');
            \App\Models\Setting::setValue('store_logo', $path);
        }
        
        foreach ($data as $key => $value) {
            \App\Models\Setting::setValue($key, $value);
        }

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil diperbarui');
    }

    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $loginActivities = \App\Models\LoginActivity::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        return view('settings.profile', compact('user', 'loginActivities'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
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

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah');
    }

    public function backup()
    {
        try {
            $tables = DB::select('SHOW TABLES');
            $dbName = config('database.connections.mysql.database');
            $tableNameColumn = "Tables_in_" . $dbName;
            
            $sqlDump = "-- APMS Database Backup\n";
            $sqlDump .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                $tableName = $table->$tableNameColumn;
                
                // Structure
                $createTable = DB::select("SHOW CREATE TABLE `$tableName`")[0];
                $createTableArr = (array)$createTable;
                $sqlDump .= "DROP TABLE IF EXISTS `$tableName`;\n";
                $sqlDump .= $createTableArr['Create Table'] . ";\n\n";
                
                // Data
                $rows = DB::table($tableName)->get();
                foreach ($rows as $row) {
                    $row = (array)$row;
                    $keys = array_keys($row);
                    $values = array_values($row);
                    
                    $escapedValues = array_map(function($value) {
                        if ($value === null) return 'NULL';
                        return "'" . addslashes($value) . "'";
                    }, $values);
                    
                    $sqlDump .= "INSERT INTO `$tableName` (`" . implode('`, `', $keys) . "`) VALUES (" . implode(', ', $escapedValues) . ");\n";
                }
                $sqlDump .= "\n";
            }
            
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;";
            
            $filename = "backup_apms_" . date('Y_m_d_His') . ".sql";
            
            return response($sqlDump)
                ->header('Content-Type', 'application/sql')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
                
        } catch (\Exception $e) {
            return redirect()->route('settings.index')->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt'
        ]);

        try {
            $sql = file_get_contents($request->file('backup_file')->getRealPath());
            
            // Clean comments and execute in chunks
            DB::beginTransaction();
            DB::unprepared("SET FOREIGN_KEY_CHECKS=0;");
            
            // Basic splitter (can be improved for complex dumps)
            $queries = array_filter(explode(";\n", $sql));
            foreach ($queries as $query) {
                if (trim($query)) {
                    DB::unprepared($query . ";");
                }
            }
            
            DB::unprepared("SET FOREIGN_KEY_CHECKS=1;");
            DB::commit();

            return redirect()->route('settings.index')->with('success', 'Database berhasil di-restore dari file backup.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('settings.index')->with('error', 'Gagal melakukan restore: ' . $e->getMessage());
        }
    }
}
