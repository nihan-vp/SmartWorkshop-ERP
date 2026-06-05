<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SystemController extends Controller
{
    /**
     * Show system status, configuration, and workshop settings.
     */
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $user = auth()->user();
        $workshop = $user->workshop;

        // Collect server and software metrics
        $systemInfo = [
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'server_os' => PHP_OS,
            'database_driver' => DB::connection()->getDriverName(),
            'database_name' => DB::getDatabaseName(),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug') ? 'Enabled' : 'Disabled',
            'timezone' => config('app.timezone'),
        ];

        // Gather storage space metrics
        $backupDir = storage_path('app/backups');
        $logDir = storage_path('logs');

        $backupSize = 0;
        if (File::exists($backupDir)) {
            foreach (File::files($backupDir) as $file) {
                $backupSize += $file->getSize();
            }
        }

        $logSize = 0;
        if (File::exists($logDir)) {
            foreach (File::files($logDir) as $file) {
                $logSize += $file->getSize();
            }
        }

        $systemInfo['backup_size'] = $this->formatBytes($backupSize);
        $systemInfo['log_size'] = $this->formatBytes($logSize);

        return view('system.index', compact('workshop', 'systemInfo'));
    }

    /**
     * Update workshop organization information.
     */
    public function update(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $user = auth()->user();
        $workshop = $user->workshop;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'gstin' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo image file if it exists to clean up storage
            if ($workshop->logo && Storage::disk('public')->exists($workshop->logo)) {
                Storage::disk('public')->delete($workshop->logo);
            }

            // Store new logo in 'logos' directory inside public storage
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $workshop->update($validated);

        // Log the activity
        \App\Models\ActivityLog::log('workshop_update', "Updated workshop system profile and settings for: {$workshop->name}");

        return redirect()->route('system.index')->with('success', 'Workshop system profile settings updated successfully.');
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($validated['new_password']),
        ]);

        // Log the activity
        \App\Models\ActivityLog::log('password_change', "Changed password for user: {$user->email}");

        return redirect()->route('system.index')->with('success', 'Password changed successfully.');
    }

    /**
     * Clear all business data for the current workshop.
     */
    public function clearData()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $user = auth()->user();
        $workshopId = $user->workshop_id;

        DB::transaction(function () use ($workshopId) {
            // Disable foreign key checks to avoid constraint violations during deletions
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // 1. Delete child records that don't directly have workshop_id but relate to parents
            DB::table('bill_items')->whereIn('bill_id', function ($query) use ($workshopId) {
                $query->select('id')->from('bills')->where('workshop_id', $workshopId);
            })->delete();

            DB::table('bill_template_items')->whereIn('bill_template_id', function ($query) use ($workshopId) {
                $query->select('id')->from('bill_templates')->where('workshop_id', $workshopId);
            })->delete();

            // 2. Delete all direct tenant-scoped table records
            $tables = [
                'bills',
                'warranties',
                'work_orders',
                'salary_advances',
                'employee_payments',
                'salaries',
                'employees',
                'expenses',
                'purchases',
                'bill_templates',
                'services',
                'products',
                'vehicles',
                'customers',
            ];

            foreach ($tables as $table) {
                DB::table($table)->where('workshop_id', $workshopId)->delete();
            }

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        });

        // Log the activity
        \App\Models\ActivityLog::log('workshop_clear_data', "Wiped all business data for workshop ID: {$workshopId}");

        return redirect()->route('system.index')->with('success', 'All business data has been successfully cleared. You can now start with a clean database.');
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
