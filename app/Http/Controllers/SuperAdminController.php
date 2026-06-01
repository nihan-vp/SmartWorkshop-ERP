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
            'users',
            'bills' => fn ($query) => $query->withoutGlobalScopes(),
        ])->orderBy('name')->get();

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

        return view('super_admin.dashboard', compact(
            'workshops',
            'totalWorkshops',
            'totalUsers',
            'totalBills',
            'totalRevenue',
            'productKeys',
            'totalProductKeys',
            'unusedProductKeys',
            'usedProductKeys'
        ));
    }
    public function storeWorkshop(Request $request)
    {
        if (Workshop::count() >= 2) {
            throw ValidationException::withMessages([
                'name' => 'The system has reached the limit of 2 registered workshop accounts.'
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'gstin' => 'nullable|string|max:50',
            'subscription_status' => 'required|in:trial,active,suspended',
            'trial_ends_at' => 'nullable|date',
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
            ]);

            User::create([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['admin_password']),
                'workshop_id' => $workshop->id,
                'role' => 'admin',
            ]);
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
            'subscription_status' => 'required|in:trial,active,suspended',
            'trial_ends_at' => 'nullable|date',
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
            $workshop->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'gstin' => $validated['gstin'],
                'subscription_status' => $validated['subscription_status'],
                'trial_ends_at' => $validated['trial_ends_at'] ?? null,
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
        });

        return redirect()
            ->route('super_admin.dashboard')
            ->with('success', 'Workshop and administrator updated successfully!');
    }

    public function destroyWorkshop(Workshop $workshop)
    {
        // Deleting the workshop will cascade delete all its related records in tenant tables (due to our migration's cascade constraints).
        $workshop->delete();

        return redirect()
            ->route('super_admin.dashboard')
            ->with('success', 'Workshop and all its associated data deleted successfully!');
    }
}