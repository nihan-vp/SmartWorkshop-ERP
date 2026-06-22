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
    public function index(Request $request)
    {
        // Leave impersonation when returning to the global control panel.
        session()->forget(['active_workshop_id', 'active_workshop_name']);

        $limit = (int) $request->get('limit', 15);
        $search = $request->get('search');

        $workshopQuery = Workshop::with([
            'users' => fn ($query) => $query->where('role', 'admin')->orderBy('name'),
        ])->withCount([
            'bills' => fn ($query) => $query->withoutGlobalScopes(),
        ])->orderBy('name');

        if ($search) {
            $workshopQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $workshops = $workshopQuery->paginate($limit, ['*'], 'workshops_page')->appends($request->query());

        $totalSuperAdmins = User::where('role', 'super_admin')->count();
        $totalWorkshops = Workshop::count();
        $totalUsers = User::where('role', '!=', 'super_admin')->whereNotNull('workshop_id')->count();
        $totalBills = Bill::withoutGlobalScopes()->count();
        $totalRevenue = (float) Bill::withoutGlobalScopes()->sum('total');

        $productKeysQuery = \App\Models\ProductKey::with('workshop')
            ->orderBy('created_at', 'desc');
            
        $productKeys = $productKeysQuery->paginate($limit, ['*'], 'keys_page')->appends($request->query());
            
        $totalProductKeys = \App\Models\ProductKey::count();
        $unusedProductKeys = \App\Models\ProductKey::where('status', 'unused')->count();
        $usedProductKeys = \App\Models\ProductKey::where('status', 'used')->count();

        // System Settings
        $defaultTrialDuration = (int) \App\Models\SystemSetting::getVal('default_trial_duration', 14);
        if ($defaultTrialDuration <= 0) $defaultTrialDuration = 14;

        // Activity Logs
        $activityLogs = \App\Models\ActivityLog::with(['user', 'workshop'])
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'logs_page')->appends($request->query());

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
            'alert_message' => 'nullable|string|max:1000',
            'alert_expires_at' => 'nullable|date',
            'client_notes' => 'nullable|string',
            // Admin user details
            'admin_name' => 'nullable|string|max:255',
            'admin_email' => 'nullable|email|max:255|unique:users,email',
            'admin_password' => 'nullable|string|min:8',
        ], [
            'admin_email.unique' => 'This email is already registered. Use a different email for the workshop administrator (not your super admin login email).',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $workshop = Workshop::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'address' => $validated['address'] ?? null,
                'gstin' => $validated['gstin'] ?? null,
                'subscription_status' => $validated['subscription_status'],
                'trial_ends_at' => $validated['trial_ends_at'] ?? null,
                'restrict_features_on_expiry' => $request->boolean('restrict_features_on_expiry'),
                'admin_extend_allowed' => $request->boolean('admin_extend_allowed'),
                'alert_message' => $validated['alert_message'] ?? null,
                'alert_expires_at' => $validated['alert_expires_at'] ?? null,
                'alert_dismissed' => false,
                'client_notes' => $validated['client_notes'] ?? null,
            ]);

            if (!empty($validated['admin_email'])) {
                $user = User::create([
                    'name' => $validated['admin_name'] ?? 'Workshop Admin',
                    'email' => $validated['admin_email'],
                    'password' => Hash::make($validated['admin_password'] ?? '12345678'),
                    'workshop_id' => $workshop->id,
                    'role' => 'admin',
                ]);
                \App\Models\ActivityLog::log('workshop_create', "Created workshop {$workshop->name} and admin user {$user->name}.");
            } else {
                \App\Models\ActivityLog::log('workshop_create', "Created workshop {$workshop->name} without an admin user.");
            }
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
            'client_notes' => 'nullable|string',
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

        DB::transaction(function () use ($validated, $workshop, $request) {
            $oldStatus = $workshop->subscription_status;
            $oldExpiry = $workshop->trial_ends_at;

            $workshop->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'address' => $validated['address'] ?? null,
                'gstin' => $validated['gstin'] ?? null,
                'subscription_status' => $validated['subscription_status'],
                'trial_ends_at' => $validated['trial_ends_at'] ?? null,
                'restrict_features_on_expiry' => $request->boolean('restrict_features_on_expiry'),
                'admin_extend_allowed' => $request->boolean('admin_extend_allowed'),
                'alert_message' => $validated['alert_message'] ?? null,
                'alert_expires_at' => $validated['alert_expires_at'] ?? null,
                'alert_dismissed' => false,
                'client_notes' => $validated['client_notes'] ?? null,
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

        return redirect()->back()
            ->with('success', 'Workshop and administrator updated successfully!');
    }

    public function updateStatus(Request $request, Workshop $workshop)
    {
        $validated = $request->validate([
            'subscription_status' => 'required|in:trial,active,suspended,fix,fixed,training,traing',
        ]);

        $oldStatus = $workshop->subscription_status;
        $status = $validated['subscription_status'];
        
        $duration = (int) \App\Models\SystemSetting::getVal('default_trial_duration', 14);
        if ($duration <= 0) $duration = 14;
        
        if ($status === 'training') $duration = 7;
        elseif ($status === 'active') $duration = 365;
        elseif (in_array($status, ['suspended', 'fix'])) $duration = 0;

        $updateData = ['subscription_status' => $status];
        if ($duration > 0) {
            $updateData['trial_ends_at'] = now()->addDays($duration);
        }

        $workshop->update($updateData);

        \App\Models\ActivityLog::log('workshop_status_update', "Updated status of {$workshop->name} from '{$oldStatus}' to '{$status}'.", null, $workshop->id);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'status' => $validated['subscription_status'],
        ]);
    }

    public function destroyWorkshop(Workshop $workshop)
    {
        $name = $workshop->name;
        // Delete users associated with this workshop
        $workshop->users()->delete();
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
            'default_trial_duration' => 'required|integer|min:0|max:1000',
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

    public function impersonate(Workshop $workshop)
    {
        session(['active_workshop_id' => $workshop->id]);
        session(['active_workshop_name' => $workshop->name]);
        
        $adminUser = $workshop->users()->where('role', 'admin')->first();
        session(['active_workshop_admin_name' => $adminUser ? $adminUser->name : 'Workshop Admin']);

        return redirect()->route('dashboard')->with('success', "Inspecting workshop: {$workshop->name}");
    }
}
