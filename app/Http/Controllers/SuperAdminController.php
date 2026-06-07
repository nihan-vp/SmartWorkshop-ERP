<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use App\Models\User;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SuperAdminController extends Controller
{
    public function index()
    {
        // Leave impersonation when returning to the global control panel.
        session()->forget(['active_workshop_id', 'active_workshop_name']);

        $workshops = Workshop::with([
            'users' => fn ($query) => $query->where('role', 'admin')->orderBy('name'),
        ])->withCount([
            'bills' => fn ($query) => $query->withoutGlobalScopes(),
        ])->orderBy('name')->get();

        $totalSuperAdmins = User::where('role', 'super_admin')->count();
        $totalWorkshops = Workshop::count();
        $totalUsers = User::where('role', '!=', 'super_admin')->count();
        $totalBills = Bill::withoutGlobalScopes()->count();
        $totalRevenue = (float) Bill::withoutGlobalScopes()->sum('total');

        $productKeys = \App\Models\ProductKey::with('workshop')
            ->orderBy('created_at', 'desc')
            ->get();
        $totalProductKeys = $productKeys->count();
        $unusedProductKeys = $productKeys->where('status', 'unused')->count();
        $usedProductKeys = $productKeys->where('status', 'used')->count();

        // System Settings
        $defaultTrialDuration = (int) \App\Models\SystemSetting::getVal('default_trial_duration', 14);

        // Activity Logs
        $activityLogs = \App\Models\ActivityLog::with(['user', 'workshop'])
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get();

        return view('super_admin.dashboard', compact(
            'workshops',
            'totalSuperAdmins',
            'totalWorkshops',
            'totalUsers',
            'totalBills',
            'totalRevenue',
            'productKeys',
            'totalProductKeys',
            'unusedProductKeys',
            'usedProductKeys',
            'defaultTrialDuration',
            'activityLogs'
        ));
    }
    public function storeWorkshop(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'gstin' => 'nullable|string|max:50',
            'subscription_status' => 'required|in:trial,active,suspended,fix,fixed,training,traing',
            'trial_ends_at' => 'nullable|date',
            'restrict_features_on_expiry' => 'nullable|boolean',
            'admin_extend_allowed' => 'nullable|boolean',
            // Admin user details
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:users,email',
            'admin_password' => 'required|string|min:8',
        ], [
            'admin_email.unique' => 'This email is already registered. Use a different email for the workshop administrator (not your super admin login email).',
        ]);

        DB::transaction(function () use ($validated) {
            $workshop = Workshop::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'gstin' => $validated['gstin'],
                'subscription_status' => $validated['subscription_status'],
                'trial_ends_at' => $validated['trial_ends_at'] ?? null,
                'restrict_features_on_expiry' => $validated['restrict_features_on_expiry'] ?? true,
                'admin_extend_allowed' => $validated['admin_extend_allowed'] ?? false,
            ]);

            $user = User::create([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['admin_password']),
                'workshop_id' => $workshop->id,
                'role' => 'admin',
            ]);

            \App\Models\ActivityLog::log('workshop_create', "Created workshop {$workshop->name} and admin user {$user->name}.");
        });

        return redirect()
            ->route('super_admin.dashboard')
            ->with('success', 'Workshop and administrator created successfully!');
    }

    public function updateWorkshop(Request $request, Workshop $workshop)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'gstin' => 'nullable|string|max:50',
            'subscription_status' => 'required|in:trial,active,suspended,fix,fixed,training,traing',
            'trial_ends_at' => 'nullable|date',
            'restrict_features_on_expiry' => 'nullable|boolean',
            'admin_extend_allowed' => 'nullable|boolean',
            'alert_message' => 'nullable|string|max:1000',
            'alert_expires_at' => 'nullable|date',
            // Admin user details
            'admin_name' => 'required|string|max:255',
            'admin_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($request->input('admin_user_id') ?: ($workshop->users->first()?->id ?: 0)),
            ],
            'admin_password' => 'nullable|string|min:8',
        ], [
            'admin_email.unique' => 'This email is already registered. Use a different email for the workshop administrator.',
        ]);

        DB::transaction(function () use ($validated, $workshop) {
            $oldStatus = $workshop->subscription_status;
            $oldExpiry = $workshop->trial_ends_at;

            $workshop->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'gstin' => $validated['gstin'],
                'subscription_status' => $validated['subscription_status'],
                'trial_ends_at' => $validated['trial_ends_at'] ?? null,
                'restrict_features_on_expiry' => $validated['restrict_features_on_expiry'] ?? false,
                'admin_extend_allowed' => $validated['admin_extend_allowed'] ?? false,
                'alert_message' => $validated['alert_message'] ?? null,
                'alert_expires_at' => $validated['alert_expires_at'] ?? null,
            ]);

            $adminUser = $workshop->users()->where('role', 'admin')->first();
            if (!$adminUser) {
                $adminUser = new User();
                $adminUser->workshop_id = $workshop->id;
                $adminUser->role = 'admin';
            }

            $adminUser->name = $validated['admin_name'];
            $adminUser->email = $validated['admin_email'];
            if (!empty($validated['admin_password'])) {
                $adminUser->password = Hash::make($validated['admin_password']);
            }
            $adminUser->save();

            // Log changes
            $changesDesc = "Updated details of {$workshop->name}.";
            if ($oldStatus !== $workshop->subscription_status) {
                $changesDesc .= " Subscription status changed from '{$oldStatus}' to '{$workshop->subscription_status}'.";
            }
            if ($oldExpiry != $workshop->trial_ends_at) {
                $changesDesc .= " Expiration/trial end date changed to " . ($workshop->trial_ends_at ? $workshop->trial_ends_at->toDateTimeString() : 'None') . ".";
            }
            \App\Models\ActivityLog::log('workshop_update', $changesDesc, null, $workshop->id);
        });

        return redirect()
            ->route('super_admin.dashboard')
            ->with('success', 'Workshop and administrator updated successfully!');
    }

    public function destroyWorkshop(Workshop $workshop)
    {
        $name = $workshop->name;
        // Deleting the workshop will cascade delete all its related records in tenant tables (due to our migration's cascade constraints).
        $workshop->delete();

        \App\Models\ActivityLog::log('workshop_delete', "Deleted workshop {$name} and all associated data.");

        return redirect()
            ->route('super_admin.dashboard')
            ->with('success', 'Workshop and all its associated data deleted successfully!');
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'default_trial_duration' => 'required|integer|min:1|max:1000',
        ]);

        \App\Models\SystemSetting::setVal('default_trial_duration', $validated['default_trial_duration']);

        \App\Models\ActivityLog::log('system_settings_update', "Default trial duration updated to {$validated['default_trial_duration']} days.");

        return redirect()
            ->back()
            ->with('success', 'System settings updated successfully!');
    }

    public function destroyLog(\App\Models\ActivityLog $activityLog)
    {
        $activityLog->delete();
        return redirect()
            ->route('super_admin.dashboard', ['tab' => 'logs'])
            ->with('success', 'Activity log deleted successfully!');
    }

    public function clearLogs()
    {
        \App\Models\ActivityLog::truncate();
        \App\Models\ActivityLog::log('logs_clear', 'All system activity logs have been cleared.');
        return redirect()
            ->route('super_admin.dashboard', ['tab' => 'logs'])
            ->with('success', 'All activity logs cleared successfully!');
    }

    public function activateLicense(Request $request, Workshop $workshop)
    {
        $request->validate([
            'product_key' => 'nullable|string',
            'duration_days' => 'nullable|integer|min:1',
        ]);

        $inputKey = strtoupper(trim($request->product_key ?? ''));

        if (empty($inputKey) && $request->filled('duration_days')) {
            $durationDays = (int) $request->duration_days;
            $keyStr = \App\Models\ProductKey::generateSecureKey();
            
            DB::transaction(function () use ($keyStr, $durationDays, $workshop) {
                $productKey = \App\Models\ProductKey::create([
                    'key' => $keyStr,
                    'duration_days' => $durationDays,
                    'status' => 'used',
                    'used_by_workshop_id' => $workshop->id,
                    'used_at' => now(),
                ]);

                // Always set from NOW — super admin sets exact duration, not extension
                $newExpiration = now()->addDays($durationDays);

                $workshop->update([
                    'subscription_status' => 'active',
                    'trial_ends_at' => $newExpiration,
                ]);

                \App\Models\ActivityLog::log('license_activate', "Super Admin auto-generated and activated key {$keyStr} ({$durationDays} days) for workshop {$workshop->name}.", null, $workshop->id);
            });

            return redirect()
                ->back()
                ->with('success', "License auto-generated and activated successfully for workshop '{$workshop->name}' until " . $workshop->fresh()->trial_ends_at->format('M d, Y') . ".");
        }

        $productKey = \App\Models\ProductKey::where('key', $inputKey)->first();

        if (!$productKey) {
            return redirect()
                ->back()
                ->with('error', 'Incorrect activation key. Please enter a valid product key.');
        }

        if ($productKey->isUsed()) {
            return redirect()
                ->back()
                ->with('error', 'This product key has already been redeemed.');
        }

        DB::transaction(function () use ($productKey, $workshop) {
            // Always set from NOW — super admin sets exact duration, not extension
            $newExpiration = now()->addDays($productKey->duration_days);

            $workshop->update([
                'subscription_status' => 'active',
                'trial_ends_at' => $newExpiration,
            ]);

            $productKey->update([
                'status' => 'used',
                'used_by_workshop_id' => $workshop->id,
                'used_at' => now(),
            ]);

            \App\Models\ActivityLog::log('license_activate', "Super Admin activated license for workshop {$workshop->name} using key {$productKey->key}.", null, $workshop->id);
        });

        return redirect()
            ->back()
            ->with('success', "License activated successfully for workshop '{$workshop->name}' until " . $workshop->fresh()->trial_ends_at->format('M d, Y') . ".");
    }

    public function impersonate(Workshop $workshop)
    {
        session(['active_workshop_id' => $workshop->id]);
        session(['active_workshop_name' => $workshop->name]);
        
        $adminUser = $workshop->users()->where('role', 'admin')->first();
        session(['active_workshop_admin_name' => $adminUser ? $adminUser->name : 'Workshop Admin']);

        return redirect()->route('dashboard')->with('success', "Inspecting workshop: {$workshop->name}");
    }
}
