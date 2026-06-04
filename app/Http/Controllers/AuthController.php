<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->isSuperAdmin()) {
                return redirect()->route('super_admin.dashboard');
            }
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Clean up impersonation session if logging in fresh
            $request->session()->forget(['active_workshop_id', 'active_workshop_name']);

            if (Auth::user()->isSuperAdmin()) {
                return redirect()->route('super_admin.dashboard')->with('success', 'Logged in to Super Admin Panel!');
            }

            return redirect()->intended('/dashboard')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (\App\Models\Workshop::count() >= 2) {
            return redirect()->route('login')->with('error', 'The registration limit of 2 workshop accounts has been reached.');
        }

        if (Auth::check()) {
            if (Auth::user()->isSuperAdmin()) {
                return redirect()->route('super_admin.dashboard');
            }
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        if (\App\Models\Workshop::count() >= 2) {
            return redirect()->route('login')->with('error', 'The registration limit of 2 workshop accounts has been reached.');
        }

        $validated = $request->validate([
            'workshop_name' => 'required|string|max:255',
            'workshop_phone' => 'required|string|max:20',
            'workshop_email' => 'nullable|email|max:255',
            'workshop_address' => 'nullable|string',
            'workshop_gstin' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, &$user) {
            $defaultTrialDays = (int) \App\Models\SystemSetting::getVal('default_trial_duration', 14);
            $trialEnds = now()->addDays($defaultTrialDays);

            $workshop = \App\Models\Workshop::create([
                'name' => $validated['workshop_name'],
                'phone' => $validated['workshop_phone'],
                'email' => $validated['workshop_email'],
                'address' => $validated['workshop_address'],
                'gstin' => $validated['workshop_gstin'],
                'subscription_status' => 'trial',
                'trial_ends_at' => $trialEnds,
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'workshop_id' => $workshop->id,
                'role' => 'admin',
            ]);

            \App\Models\ActivityLog::log('workshop_register', "Workshop registered successfully with a {$defaultTrialDays}-day trial.", $user->id, $workshop->id);
        });

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Workshop registered and logged in successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }
}
