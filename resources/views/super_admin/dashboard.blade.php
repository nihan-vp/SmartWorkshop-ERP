@extends('layouts.app')

@section('title', 'Super Admin Dashboard')
@section('page-title', 'Control Panel')
@section('page-subtitle', 'Multi-tenant system management')

@section('content')
@php
    $workshopFormFields = ['name', 'phone', 'email', 'address', 'gstin', 'trial_days', 'subscription_status', 'trial_ends_at', 'admin_name', 'admin_email', 'admin_password'];
    $showAddWorkshopModal = $errors->hasAny($workshopFormFields) && !old('_method');
    $showEditWorkshopModal = $errors->hasAny($workshopFormFields) && old('_method') === 'PUT';
    $initialActiveWorkshop = [];
    if ($showEditWorkshopModal) {
        $initialActiveWorkshop = [
            'id' => old('workshop_id'),
            'name' => old('name'),
            'phone' => old('phone'),
            'email' => old('email'),
            'gstin' => old('gstin'),
            'address' => old('address'),
            'subscription_status' => old('subscription_status'),
            'trial_ends_at' => old('trial_ends_at'),
            'trial_days' => old('trial_days'),
            'admin_user_id' => old('admin_user_id'),
            'admin_name' => old('admin_name'),
            'admin_email' => old('admin_email'),
        ];
    }
@endphp

<div x-data="workshopAdminPanel()">
<div class="max-w-7xl mx-auto space-y-8 animate-fade-in-up">

    {{-- Header --}}
    <div class="relative bg-white border border-slate-200/80 rounded-3xl p-6 lg:p-8 shadow-sm overflow-hidden flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="absolute -right-16 -bottom-16 w-64 h-64 rounded-full bg-primary-100/30 filter blur-[40px] pointer-events-none"></div>
        <div class="z-10 space-y-1">
            <h3 class="text-xl font-bold text-slate-900">System Control Panel</h3>
            <p class="text-sm text-slate-500 font-medium">Manage all garage workshops, accounts, and global billing from one place.</p>
        </div>
        <div class="z-10 flex flex-wrap gap-2 shrink-0">
            @if($totalWorkshops >= 2)
                <button type="button" x-show="activeTab === 'workshops'" disabled class="btn-primary shadow-sm !py-2.5 !px-5 text-sm z-10 !bg-slate-400 !border-slate-400 opacity-60 cursor-not-allowed flex items-center gap-2" title="Limit of 2 accounts reached">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Workshop (Limit Reached)
                </button>
            @else
                <button type="button" x-show="activeTab === 'workshops'" @click="openAdd()" class="btn-primary shadow-sm !py-2.5 !px-5 text-sm z-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Workshop
                </button>
            @endif
        </div>
    </div>

    @php
        $expiringWorkshops = $workshops->filter(function($w) {
            return $w->subscription_status === 'trial';
        });
    @endphp
    @if($expiringWorkshops->count() > 0)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 sm:p-5 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <h3 class="text-sm font-bold text-amber-800">Warning: Active Trial Periods</h3>
            </div>
            <div class="space-y-2">
                @foreach($expiringWorkshops as $ew)
                    @php
                        $ewAdmin = $ew->users->first();
                        $ewAdminName = $ewAdmin ? $ewAdmin->name : 'No Admin';
                        $ewTrialEnds = $ew->trial_ends_at ? \Carbon\Carbon::parse($ew->trial_ends_at) : null;
                    @endphp
                    @if($ewTrialEnds)
                    <p class="text-xs font-medium text-amber-700 whitespace-normal break-words">
                        <span class="font-bold">{{ $ew->name }}</span> (Admin: {{ $ewAdminName }}) — Trial ends on <span class="font-bold">{{ $ewTrialEnds->format('M d, Y h:i A') }} ({{ $ewTrialEnds->diffForHumans() }})</span>
                    </p>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    {{-- Premium Glassmorphic Tab Selector --}}
    <div class="flex flex-wrap items-center justify-start p-1.5 bg-slate-100/80 backdrop-blur-md rounded-2xl border border-slate-200/50 w-full sm:w-max shadow-sm shrink-0 gap-1 sm:gap-0">
        <button type="button" 
                @click="activeTab = 'dashboard'; window.history.replaceState(null, null, '?tab=dashboard')" 
                :class="activeTab === 'dashboard' ? 'bg-white text-primary-700 shadow-sm font-bold border border-slate-200/40' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs transition-all duration-200 flex-1 sm:flex-initial justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </button>
        <button type="button" 
                @click="activeTab = 'workshops'; window.history.replaceState(null, null, '?tab=workshops')" 
                :class="activeTab === 'workshops' ? 'bg-white text-primary-700 shadow-sm font-bold border border-slate-200/40' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs transition-all duration-200 flex-1 sm:flex-initial justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
            Garages & Workshops
        </button>
        <button type="button" 
                @click="activeTab = 'settings'; window.history.replaceState(null, null, '?tab=settings')" 
                :class="activeTab === 'settings' ? 'bg-white text-amber-700 shadow-sm font-bold border border-slate-200/40' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs transition-all duration-200 flex-1 sm:flex-initial justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            System Settings
        </button>
        <button type="button" 
                @click="activeTab = 'logs'; window.history.replaceState(null, null, '?tab=logs')" 
                :class="activeTab === 'logs' ? 'bg-white text-slate-700 shadow-sm font-bold border border-slate-200/40' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs transition-all duration-200 flex-1 sm:flex-initial justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
            Activity Logs
        </button>
    </div>

    {{-- Dashboard Overview Panel --}}
    <div x-show="activeTab === 'dashboard'" class="space-y-8 animate-fade-in-up" x-cloak>
        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Stat 1: Garages --}}
            <div class="glass-card flex flex-col justify-between border-l-4 border-l-blue-500 relative overflow-hidden group hover:scale-[1.01] transition-transform duration-300">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Garages</span>
                        <span class="badge badge-info">Active</span>
                    </div>
                    <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-outfit truncate">{{ $totalWorkshops }}</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-500 font-medium">
                    <span>Registered workshops</span>
                </div>
            </div>

            {{-- Stat 2: Active Users --}}
            <div class="glass-card flex flex-col justify-between border-l-4 border-l-violet-500 relative overflow-hidden group hover:scale-[1.01] transition-transform duration-300">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Users</span>
                        <span class="badge badge-purple">Members</span>
                    </div>
                    <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-outfit truncate">{{ $totalUsers }}</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-500 font-medium">
                    <span>Staff & Admins</span>
                </div>
            </div>

        </div>

        {{-- Main split: Shortcuts + Recent Activity --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Left column: Shortcuts & Recent Workshops --}}
            <div class="lg:col-span-7 space-y-8">
                {{-- Quick Actions --}}
                <div class="glass-card space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Control Panel Shortcuts</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @if($totalWorkshops >= 2)
                            <button type="button" disabled class="btn-primary !justify-start !py-3 w-full shadow-sm text-sm !bg-slate-400 !border-slate-400 opacity-60 cursor-not-allowed">
                                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Register Workshop (Limit Reached)</span>
                            </button>
                        @else
                            <button type="button" @click="openAdd()" class="btn-primary !justify-start !py-3 w-full shadow-sm text-sm">
                                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Register New Workshop</span>
                            </button>
                        @endif
                        <a href="{{ route('backup.index') }}" class="btn-secondary !justify-start !py-3 w-full text-sm">
                            <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                            <span>Database Backup & Restore</span>
                        </a>
                        <button type="button" @click="activeTab = 'settings'; window.history.replaceState(null, null, '?tab=settings')" class="btn-secondary !justify-start !py-3 w-full text-sm sm:col-span-2">
                            <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Platform Settings</span>
                        </button>
                    </div>
                </div>

                {{-- Recent Workshops list --}}
                <div class="glass-card !p-0 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white">
                        <h3 class="text-base font-bold text-slate-900">Recent Garages</h3>
                        <button type="button" @click="activeTab = 'workshops'; window.history.replaceState(null, null, '?tab=workshops')" class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">View All →</button>
                    </div>
                    @if($workshops->count())
                    <div class="divide-y divide-slate-100 bg-white">
                        @foreach($workshops->take(3) as $w)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center text-primary-700 font-bold text-sm shrink-0">
                                    {{ strtoupper(substr($w->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $w->name }}</p>
                                    <p class="text-xs text-slate-400 font-medium">{{ $w->phone }}</p>
                                </div>
                            </div>
                            <span class="badge {{ $w->subscription_status === 'active' ? 'badge-success' : ($w->subscription_status === 'suspended' ? 'badge-danger' : 'badge-info') }}">
                                {{ ucfirst($w->subscription_status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <p class="text-slate-400 font-semibold text-xs">No workshops registered yet.</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Right column: Key status + logs --}}
            <div class="lg:col-span-5 space-y-8">
                {{-- License Key Summary --}}
                <div class="glass-card">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">License Key Summary</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-sm pb-2 border-b border-slate-100">
                            <span class="font-semibold text-slate-500">Unused / Available Keys</span>
                            <span class="font-extrabold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">{{ $unusedProductKeys }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm pb-2 border-b border-slate-100">
                            <span class="font-semibold text-slate-500">Redeemed Keys</span>
                            <span class="font-extrabold text-slate-700 bg-slate-50 px-2 py-0.5 rounded border border-slate-200">{{ $usedProductKeys }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm pb-2 border-b border-slate-100">
                            <span class="font-semibold text-slate-500">Total Keys Generated</span>
                            <span class="font-extrabold text-slate-800">{{ $totalProductKeys }}</span>
                        </div>
                    </div>
                </div>

                {{-- Latest Audit Logs --}}
                <div class="glass-card space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Latest Activity Logs</h3>
                        <button type="button" @click="activeTab = 'logs'; window.history.replaceState(null, null, '?tab=logs')" class="text-xs font-bold text-slate-400 hover:text-slate-600 uppercase tracking-wider transition-colors">All Logs</button>
                    </div>
                    @if($activityLogs->count())
                    <div class="space-y-3">
                        @foreach($activityLogs->take(4) as $log)
                        <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl space-y-1">
                            <div class="flex items-center justify-between text-[10px] text-slate-400 font-semibold">
                                <span>{{ $log->created_at->diffForHumans() }}</span>
                                <span class="uppercase tracking-wider text-[9px] text-primary-600">{{ str_replace('_', ' ', $log->action) }}</span>
                            </div>
                            <p class="text-xs text-slate-700 font-semibold leading-relaxed">{{ $log->description }}</p>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-xs text-slate-400 text-center py-4 font-semibold">No activity logs recorded yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Workshops Panel --}}
    <div x-show="activeTab === 'workshops'" class="space-y-8 animate-fade-in-up">
        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="glass-card border-l-4 border-l-blue-500">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Garages</span>
                <p class="text-3xl font-extrabold text-slate-900 mt-2">{{ $totalWorkshops }}</p>
                <p class="text-xs text-slate-500 mt-3 font-medium">Active tenants</p>
            </div>
            <div class="glass-card border-l-4 border-l-violet-500">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Accounts</span>
                <p class="text-3xl font-extrabold text-slate-900 mt-2">{{ $totalUsers }}</p>
                <p class="text-xs text-slate-500 mt-3 font-medium">Registered Users</p>
            </div>
        </div>

        {{-- Workshops --}}
        <div class="glass-card !p-0 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Garages / Workshops</h3>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Manage workshops and admin logins — garage staff sign in separately</p>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:w-64">
                        <label for="searchWorkshopQuery" class="sr-only">Search garages</label>
                        <input id="searchWorkshopQuery" autocomplete="off" type="text" x-model="searchWorkshopQuery" placeholder="Search garages..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200/80 rounded-xl text-xs font-medium focus:outline-none focus:border-primary-400 focus:bg-white transition-colors">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-2 rounded-xl whitespace-nowrap shrink-0">{{ $workshops->count() }} registered</span>
                </div>
            </div>

            @if($workshops->isEmpty())
            <div class="text-center py-16 px-6">
                <svg class="w-14 h-14 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                <p class="text-slate-600 font-semibold">No workshops yet</p>
                <p class="text-sm text-slate-400 mt-1 mb-6">Create your first garage to start multi-tenant management.</p>
                <button type="button" @click="openAdd()" class="btn-primary text-sm">Add First Workshop</button>
            </div>
            @else
            {{-- Desktop View --}}
            <div class="hidden md:block overflow-x-auto relative z-0">
                <table class="data-table workshops-table">
                    <thead>
                        <tr>
                            <th>Workshop</th>
                            <th>Phone</th>
                            <th>GSTIN</th>
                            <th>Subscription</th>
                            <th>Admin login</th>
                            <th class="text-center">Staff</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($workshops as $workshop)
                        <tr x-show="matchesWorkshopSearch('{{ strtolower($workshop->name) }}', '{{ strtolower($workshop->phone) }}', '{{ strtolower($workshop->email) }}', '{{ strtolower($workshop->users->first()?->name ?? '') }}', '{{ strtolower($workshop->users->first()?->email ?? '') }}')" x-transition>
                            <td class="min-w-[200px] max-w-[280px]">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center text-primary-700 font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($workshop->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 text-sm break-words">{{ $workshop->name }}</p>
                                        <p class="text-xs text-slate-400 break-all">{{ $workshop->email ?: 'No email' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="font-semibold text-slate-700 whitespace-nowrap">{{ $workshop->phone }}</td>
                            <td class="whitespace-nowrap">
                                @if($workshop->gstin)
                                <span class="badge badge-info">{{ $workshop->gstin }}</span>
                                @else
                                <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap">
                                @if($workshop->isSuspended())
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                    Suspended
                                </span>
                                @elseif($workshop->isTrial())
                                    @if($workshop->isTrialExpired())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Trial Expired
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                        Trial · {{ $workshop->getTrialDaysRemaining() }} Days Left
                                    </span>
                                    @endif
                                @else
                                    @if($workshop->isTrialExpired())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                        License Expired
                                    </span>
                                    @elseif($workshop->trial_ends_at)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Active · {{ $workshop->getTrialDaysRemaining() }} Days Left
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Active (Lifetime)
                                    </span>
                                    @endif
                                @endif
                            </td>
                            <td class="min-w-[160px] max-w-[220px]">
                                @forelse($workshop->users as $adminUser)
                                <div class="text-xs font-semibold text-slate-700 break-words">{{ $adminUser->name }}</div>
                                <div class="text-[11px] text-primary-600 font-medium break-all">{{ $adminUser->email }}</div>
                                @empty
                                <span class="text-slate-400 text-xs">No admin user</span>
                                @endforelse
                            </td>
                            <td class="text-center font-bold text-slate-800 whitespace-nowrap">{{ $workshop->users_count }}</td>
                            <td class="whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" @click="openEdit(@js([
                                        'id' => $workshop->id,
                                        'name' => $workshop->name,
                                        'phone' => $workshop->phone,
                                        'email' => $workshop->email,
                                        'gstin' => $workshop->gstin,
                                        'address' => $workshop->address,
                                        'subscription_status' => $workshop->subscription_status,
                                        'trial_ends_at' => $workshop->trial_ends_at ? $workshop->trial_ends_at->format('Y-m-d\TH:i') : '',
                                        'trial_days' => '',
                                        'alert_message' => $workshop->alert_message,
                                        'alert_expires_at' => $workshop->alert_expires_at ? $workshop->alert_expires_at->format('Y-m-d\TH:i') : '',
                                        'admin_user_id' => $workshop->users->first()?->id,
                                        'admin_name' => $workshop->users->first()?->name,
                                        'admin_email' => $workshop->users->first()?->email,
                                    ]))" class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-all" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('super_admin.destroy_workshop', $workshop) }}" method="POST" onsubmit="return confirm('Delete this workshop and ALL its data permanently?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-all" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards View --}}
            <div class="block md:hidden divide-y divide-slate-100 bg-white">
                @foreach($workshops as $workshop)
                <div class="p-5 space-y-4" x-show="matchesWorkshopSearch('{{ strtolower($workshop->name) }}', '{{ strtolower($workshop->phone) }}', '{{ strtolower($workshop->email) }}', '{{ strtolower($workshop->users->first()?->name ?? '') }}', '{{ strtolower($workshop->users->first()?->email ?? '') }}')" x-transition>
                    <!-- Workshop Info & Header -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center text-primary-700 font-bold text-sm shrink-0">
                                {{ strtoupper(substr($workshop->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-slate-900 text-sm break-words leading-tight">{{ $workshop->name }}</h4>
                                <p class="text-xs text-slate-400 mt-0.5 break-all">{{ $workshop->email ?: 'No email' }}</p>
                            </div>
                        </div>
                        
                        <!-- Subscription Status -->
                        <div>
                            @if($workshop->isSuspended())
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                Suspended
                            </span>
                            @elseif($workshop->isTrial())
                                @if($workshop->isTrialExpired())
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    Trial Expired
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    Trial · {{ $workshop->getTrialDaysRemaining() }} Days Left
                                </span>
                                @endif
                            @else
                                @if($workshop->isTrialExpired())
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    License Expired
                                </span>
                                @elseif($workshop->trial_ends_at)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Active · {{ $workshop->getTrialDaysRemaining() }} Days Left
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Active (Lifetime)
                                </span>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 gap-x-4 gap-y-3 bg-slate-50/50 border border-slate-100 p-3.5 rounded-xl text-xs">
                        <div>
                            <span class="text-slate-400 font-semibold block uppercase tracking-wider text-[9px] mb-0.5">Phone</span>
                            <span class="font-bold text-slate-700">{{ $workshop->phone }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block uppercase tracking-wider text-[9px] mb-0.5">GSTIN</span>
                            @if($workshop->gstin)
                            <span class="badge badge-info !py-0.5 !px-1.5 !rounded-md">{{ $workshop->gstin }}</span>
                            @else
                            <span class="text-slate-400 font-bold">—</span>
                            @endif
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block uppercase tracking-wider text-[9px] mb-0.5">Staff Count</span>
                            <span class="font-bold text-slate-800">{{ $workshop->users_count }} active</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block uppercase tracking-wider text-[9px] mb-0.5">Admin Profile</span>
                            @forelse($workshop->users as $adminUser)
                            <span class="font-bold text-slate-700 block truncate">{{ $adminUser->name }}</span>
                            @empty
                            <span class="text-slate-400">—</span>
                            @endforelse
                        </div>
                        @if($workshop->trial_ends_at)
                        <div class="col-span-2">
                            <span class="text-slate-400 font-semibold block uppercase tracking-wider text-[9px] mb-0.5">
                                {{ $workshop->isTrial() ? 'Trial Ends At' : 'Subscription Ends At' }}
                            </span>
                            <span class="font-bold text-slate-800">
                                {{ $workshop->trial_ends_at->format('M d, Y h:i A') }} 
                                @if($workshop->isTrialExpired())
                                    <span class="text-rose-600 font-bold">(Expired {{ $workshop->trial_ends_at->diffForHumans() }})</span>
                                @else
                                    <span class="text-emerald-600 font-bold">({{ $workshop->trial_ends_at->diffForHumans() }})</span>
                                @endif
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Admin Email & Action Controls -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-slate-150">
                        <div class="min-w-0">
                            @forelse($workshop->users as $adminUser)
                            <p class="text-[10px] text-slate-400 font-medium">Administrator Login:</p>
                            <p class="text-[11px] text-primary-600 font-bold truncate">{{ $adminUser->email }}</p>
                            @empty
                            <p class="text-[10px] text-slate-400 font-medium">No administrator login</p>
                            @endforelse
                        </div>
                        
                        <div class="flex items-center gap-2 w-full sm:w-auto justify-end shrink-0">
                            <button type="button" @click="openEdit(@js([
                                'id' => $workshop->id,
                                'name' => $workshop->name,
                                'phone' => $workshop->phone,
                                'email' => $workshop->email,
                                'gstin' => $workshop->gstin,
                                'address' => $workshop->address,
                                'subscription_status' => $workshop->subscription_status,
                                'trial_ends_at' => $workshop->trial_ends_at ? $workshop->trial_ends_at->format('Y-m-d\TH:i') : '',
                                'trial_days' => '',
                                'alert_message' => $workshop->alert_message,
                                'alert_expires_at' => $workshop->alert_expires_at ? $workshop->alert_expires_at->format('Y-m-d\TH:i') : '',
                                'admin_user_id' => $workshop->users->first()?->id,
                                'admin_name' => $workshop->users->first()?->name,
                                'admin_email' => $workshop->users->first()?->email,
                            ]))" class="btn-secondary !py-1.5 !px-3.5 !rounded-lg !text-xs flex-1 sm:flex-initial justify-center shadow-sm" title="Edit">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </button>
                            <form action="{{ route('super_admin.destroy_workshop', $workshop) }}" method="POST" onsubmit="return confirm('Delete this workshop and ALL its data permanently?')" class="inline flex-1 sm:flex-initial">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger !py-1.5 !px-3.5 !rounded-lg !text-xs !bg-red-50 hover:!bg-red-100 !text-red-600 !border-red-100 w-full justify-center shadow-sm" title="Delete">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- System Settings Panel --}}
    <div x-show="activeTab === 'settings'" class="space-y-6 animate-fade-in-up" x-cloak>

        {{-- Section Header --}}
        <div class="relative bg-gradient-to-br from-amber-500 to-orange-600 rounded-3xl p-6 lg:p-8 overflow-hidden shadow-lg">
            <div class="absolute inset-0 opacity-10" style="background-image: url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-white">System Settings</h3>
                    </div>
                    <p class="text-amber-100 text-sm font-medium">Configure global defaults, trial behavior, and platform-wide policies.</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-4 py-2.5 text-center">
                        <p class="text-2xl font-extrabold text-white">{{ $defaultTrialDuration }}</p>
                        <p class="text-[10px] font-bold text-amber-200 uppercase tracking-wider mt-0.5">Default Trial Days</p>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success') && request()->query('tab') === 'settings')
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-2xl px-5 py-3.5">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm font-semibold text-emerald-700">{{ session('success') }}</p>
        </div>
        @endif

        {{-- Settings Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Trial Duration Card --}}
            <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h4 class="text-sm font-bold text-slate-800">Trial Period Configuration</h4>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Set the default trial duration for all newly created garage accounts.</p>
                </div>
                <form action="{{ route('super_admin.update_settings') }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    <div>
                        <label for="default_trial_duration_main" class="form-label text-xs">Default Trial Duration (Days) *</label>
                        <div class="flex items-center gap-3 mt-1.5">
                            <input id="default_trial_duration_main" autocomplete="off" type="number" name="default_trial_duration" value="{{ $defaultTrialDuration }}" min="1" max="365"
                                class="form-input flex-1 !text-lg !font-bold !text-slate-900 @error('default_trial_duration') border-red-400 @enderror">
                            <span class="text-sm font-semibold text-slate-500 shrink-0">days</span>
                        </div>
                        @error('default_trial_duration')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                        <p class="text-[11px] text-slate-400 mt-2">New garages will be automatically assigned this trial duration when created. Currently set to <strong>{{ $defaultTrialDuration }} days</strong>.</p>
                    </div>

                    {{-- Quick Preset Buttons --}}
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Quick Presets</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach([7 => '7 Days', 14 => '14 Days', 30 => '30 Days', 60 => '60 Days', 90 => '90 Days'] as $days => $label)
                            <button type="button"
                                onclick="this.closest('form').querySelector('input[name=default_trial_duration]').value = {{ $days }}"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold border transition-all duration-150
                                    {{ $defaultTrialDuration == $days
                                        ? 'bg-amber-500 text-white border-amber-500 shadow-sm'
                                        : 'bg-slate-50 text-slate-600 border-slate-200 hover:border-amber-300 hover:text-amber-700 hover:bg-amber-50' }}">
                                {{ $label }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
                        <button type="submit" class="btn-primary text-sm !bg-amber-600 hover:!bg-amber-700 !border-amber-600 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Save Configuration
                        </button>
                        <p class="text-xs text-slate-400">Changes apply to new workshops only.</p>
                    </div>
                </form>
            </div>

            {{-- Info Sidebar --}}
            <div class="space-y-4">
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm p-5 space-y-4">
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Platform Summary</h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2 border-b border-slate-50">
                            <span class="text-xs font-semibold text-slate-500">Total Workshops</span>
                            <span class="text-sm font-extrabold text-slate-900">{{ $totalWorkshops }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-slate-50">
                            <span class="text-xs font-semibold text-slate-500">Total Users</span>
                            <span class="text-sm font-extrabold text-slate-900">{{ $totalUsers }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-slate-50">
                            <span class="text-xs font-semibold text-slate-500">Active Trials</span>
                            <span class="text-sm font-extrabold text-amber-600">{{ $workshops->filter(fn($w) => $w->isTrial())->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-xs font-semibold text-slate-500">Product Keys</span>
                            <span class="text-sm font-extrabold text-violet-600">{{ $totalProductKeys }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <p class="text-xs font-bold text-amber-800 mb-1">Note</p>
                            <p class="text-[11px] text-amber-700 leading-relaxed">Changing this setting only affects newly created workshops. Existing workshops keep their current trial expiry dates.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Activity Logs Panel --}}
    <div x-show="activeTab === 'logs'" class="space-y-6 animate-fade-in-up" x-cloak>

        {{-- Section Header --}}
        <div class="relative bg-gradient-to-br from-slate-700 to-slate-900 rounded-3xl p-6 lg:p-8 overflow-hidden shadow-lg">
            <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
            <div class="absolute -left-10 -bottom-10 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 12h6m-6 4h6"/></svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-white">Activity Logs</h3>
                    </div>
                    <p class="text-slate-400 text-sm font-medium">Complete audit trail of all administrator actions and system events.</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-4 py-2.5 text-center">
                        <p class="text-2xl font-extrabold text-white">{{ $activityLogs->count() }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Recent Events</p>
                    </div>
                </div>
            </div>
        </div>

        @php
            $createLogs = $activityLogs->filter(fn($l) => str_contains($l->action, 'create'))->count();
            $updateLogs = $activityLogs->filter(fn($l) => str_contains($l->action, 'update'))->count();
            $deleteLogs = $activityLogs->filter(fn($l) => str_contains($l->action, 'delete'))->count();
        @endphp

        {{-- Stats Row --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white border border-emerald-100 rounded-2xl p-4 shadow-sm">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Created</span>
                </div>
                <p class="text-2xl font-extrabold text-emerald-600">{{ $createLogs }}</p>
            </div>
            <div class="bg-white border border-blue-100 rounded-2xl p-4 shadow-sm">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Updated</span>
                </div>
                <p class="text-2xl font-extrabold text-blue-600">{{ $updateLogs }}</p>
            </div>
            <div class="bg-white border border-rose-100 rounded-2xl p-4 shadow-sm">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-2 h-2 rounded-full bg-rose-500"></div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Deleted</span>
                </div>
                <p class="text-2xl font-extrabold text-rose-600">{{ $deleteLogs }}</p>
            </div>
        </div>

        {{-- Logs Table --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h4 class="text-sm font-bold text-slate-900">Recent Events</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Showing last 100 events — most recent first</p>
                </div>
                <div x-data="{ logFilter: 'all' }" class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-xl text-xs">
                    <button type="button" @click="logFilter = 'all'" :class="logFilter === 'all' ? 'bg-white shadow-sm text-slate-900 font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-3 py-1.5 rounded-lg transition-all duration-150 font-semibold">All</button>
                    <button type="button" @click="logFilter = 'create'" :class="logFilter === 'create' ? 'bg-white shadow-sm text-emerald-700 font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-3 py-1.5 rounded-lg transition-all duration-150 font-semibold">Create</button>
                    <button type="button" @click="logFilter = 'update'" :class="logFilter === 'update' ? 'bg-white shadow-sm text-blue-700 font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-3 py-1.5 rounded-lg transition-all duration-150 font-semibold">Update</button>
                    <button type="button" @click="logFilter = 'delete'" :class="logFilter === 'delete' ? 'bg-white shadow-sm text-rose-700 font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-3 py-1.5 rounded-lg transition-all duration-150 font-semibold">Delete</button>
                </div>
            </div>

            @if($activityLogs->isEmpty())
            <div class="text-center py-20 px-6">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 12h6m-6 4h6"/></svg>
                </div>
                <p class="text-slate-700 font-bold text-base">No Activity Yet</p>
                <p class="text-sm text-slate-400 mt-1">System events will appear here as actions are performed.</p>
            </div>
            @else
            <div x-data="{ logFilter: 'all' }" class="divide-y divide-slate-50">
                {{-- Mobile / Card View --}}
                <div class="md:hidden divide-y divide-slate-50">
                    @foreach($activityLogs as $log)
                    @php
                        $actionType = str_contains($log->action, 'update') ? 'update' : (str_contains($log->action, 'delete') ? 'delete' : 'create');
                        $badgeClass = $actionType === 'update' ? 'badge-info' : ($actionType === 'delete' ? 'badge-danger' : 'badge-success');
                        $dotClass   = $actionType === 'update' ? 'bg-blue-500' : ($actionType === 'delete' ? 'bg-rose-500' : 'bg-emerald-500');
                    @endphp
                    <div x-show="logFilter === 'all' || logFilter === '{{ $actionType }}'" class="p-4 hover:bg-slate-50/60 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center shrink-0 mt-0.5">
                                <span class="w-2.5 h-2.5 rounded-full {{ $dotClass }}"></span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <span class="badge {{ $badgeClass }} !text-[10px]">{{ strtoupper(str_replace('_', ' ', $log->action)) }}</span>
                                    <span class="text-[10px] text-slate-400 font-medium whitespace-nowrap">{{ $log->created_at->format('M d, h:i A') }}</span>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $log->description }}</p>
                                @if($log->user)
                                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">by {{ $log->user->name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50/80">
                                <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3 whitespace-nowrap">Time</th>
                                <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3 whitespace-nowrap">User</th>
                                <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3 whitespace-nowrap">Action</th>
                                <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3">Description</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($activityLogs as $log)
                            @php
                                $actionType = str_contains($log->action, 'update') ? 'update' : (str_contains($log->action, 'delete') ? 'delete' : 'create');
                                $badgeClass = $actionType === 'update' ? 'badge-info' : ($actionType === 'delete' ? 'badge-danger' : 'badge-success');
                                $dotClass   = $actionType === 'update' ? 'bg-blue-500' : ($actionType === 'delete' ? 'bg-rose-500' : 'bg-emerald-500');
                            @endphp
                            <tr x-show="logFilter === 'all' || logFilter === '{{ $actionType }}'" class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <p class="text-xs font-semibold text-slate-700">{{ $log->created_at->format('M d, Y') }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium">{{ $log->created_at->format('h:i A') }}</p>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    @if($log->user)
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-primary-50 border border-primary-100 flex items-center justify-center text-primary-700 font-bold text-[10px] shrink-0">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-800">{{ $log->user->name }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $log->user->email }}</p>
                                        </div>
                                    </div>
                                    @else
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center shrink-0">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2"/></svg>
                                        </div>
                                        <span class="text-xs text-slate-400 font-medium">System</span>
                                    </div>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="badge {{ $badgeClass }}">{{ strtoupper(str_replace('_', ' ', $log->action)) }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-600 max-w-sm">{{ $log->description }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>

</div>

    {{-- Modal: Add Workshop --}}
    <div
        x-show="openAddModal"
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
        style="display: none;"
        role="dialog"
        aria-modal="true"
    >
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px]" @click="openAddModal = false"></div>
        <div class="relative bg-white border border-slate-200 rounded-2xl w-full max-w-2xl max-h-[min(92vh,900px)] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.35)] flex flex-col overflow-hidden z-10">
            <div class="flex-shrink-0 flex justify-between items-center border-b border-slate-100 px-6 py-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">New Workshop</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Garage profile + administrator account</p>
                </div>
                <button type="button" @click="openAddModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('super_admin.store_workshop') }}" method="POST" class="flex flex-col flex-1 min-h-0" autocomplete="off">
                @csrf
                @if($showAddWorkshopModal)
                <div class="flex-shrink-0 mx-6 mt-4 px-4 py-3 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-sm font-semibold text-red-700">Please fix the errors below and try again.</p>
                </div>
                @endif
                <div class="flex-1 overflow-y-auto px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3.5">
                            <p class="text-xs font-bold text-primary-600 uppercase tracking-wider pb-1 border-b border-slate-100">Workshop details</p>
                            <div>
                                <label for="add_name" class="form-label text-xs">Workshop name *</label>
                                <input id="add_name" autocomplete="organization" type="text" name="name" required placeholder="e.g. Speedsters Garage" value="{{ old('name') }}" class="form-input @error('name') border-red-400 @enderror">
                                @error('name')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="add_phone" class="form-label text-xs">Phone *</label>
                                <input id="add_phone" autocomplete="tel" type="text" name="phone" required placeholder="e.g. +91 9988776655" value="{{ old('phone') }}" class="form-input @error('phone') border-red-400 @enderror">
                                @error('phone')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="add_email" class="form-label text-xs">Email</label>
                                <input id="add_email" autocomplete="email" type="email" name="email" placeholder="garage@example.com" value="{{ old('email') }}" class="form-input @error('email') border-red-400 @enderror">
                                @error('email')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="add_gstin" class="form-label text-xs">GSTIN</label>
                                <input id="add_gstin" autocomplete="off" type="text" name="gstin" placeholder="29XXXXXXXXXX1Z5" value="{{ old('gstin') }}" class="form-input @error('gstin') border-red-400 @enderror">
                                @error('gstin')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="add_status" class="form-label text-xs">Subscription Status *</label>
                                <select id="add_status" autocomplete="off" name="subscription_status" required x-model="newWorkshopStatus" class="form-input @error('subscription_status') border-red-400 @enderror">
                                    <option value="trial">Free Trial</option>
                                    <option value="active">Active</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                                @error('subscription_status')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div x-show="newWorkshopStatus === 'trial' || newWorkshopStatus === 'active'" x-cloak class="animate-fade-in-up">
                                <label for="add_trial" class="form-label text-xs" x-text="newWorkshopStatus === 'trial' ? 'Trial Expiration Date *' : 'Subscription Expiration Date (Optional)'"></label>
                                <input id="add_trial" autocomplete="off" type="datetime-local" name="trial_ends_at" value="{{ old('trial_ends_at', now()->addDays(14)->format('Y-m-d\TH:i')) }}" :required="newWorkshopStatus === 'trial'" class="form-input @error('trial_ends_at') border-red-400 @enderror">
                                @error('trial_ends_at')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-2 mt-4">
                                <label for="add_restrict_features" class="flex items-center gap-2 cursor-pointer select-none">
                                    <input id="add_restrict_features" type="checkbox" name="restrict_features_on_expiry" value="1" checked class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                    <span class="text-xs font-semibold text-slate-700">Restrict features on expiry</span>
                                </label>
                                <label for="add_admin_extend" class="flex items-center gap-2 cursor-pointer select-none">
                                    <input id="add_admin_extend" type="checkbox" name="admin_extend_allowed" value="1" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                    <span class="text-xs font-semibold text-slate-700">Allow Admin to extend trial</span>
                                </label>
                            </div>
                        </div>
                        <div class="space-y-3.5">
                            <p class="text-xs font-bold text-primary-600 uppercase tracking-wider pb-1 border-b border-slate-100">Administrator</p>
                            <div>
                                <label for="add_admin_name" class="form-label text-xs">Admin name *</label>
                                <input id="add_admin_name" autocomplete="name" type="text" name="admin_name" required placeholder="e.g. Rajesh Kumar" value="{{ old('admin_name') }}" class="form-input @error('admin_name') border-red-400 @enderror">
                                @error('admin_name')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="add_admin_email" class="form-label text-xs">Admin email *</label>
                                <input id="add_admin_email" autocomplete="email" type="email" name="admin_email" required placeholder="owner@garage.com" value="{{ old('admin_email') }}" class="form-input @error('admin_email') border-red-400 @enderror">
                                @error('admin_email')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                                <p class="text-[11px] text-slate-500 mt-1">Unique — not your super admin login email.</p>
                            </div>
                            <div>
                                <label for="add_admin_password" class="form-label text-xs">Admin password *</label>
                                <input id="add_admin_password" type="password" name="admin_password" required placeholder="Min 8 characters" autocomplete="new-password" class="form-input @error('admin_password') border-red-400 @enderror">
                                @error('admin_password')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <label for="add_address" class="form-label text-xs">Address</label>
                            <textarea id="add_address" autocomplete="street-address" name="address" rows="2" placeholder="Street, city" class="form-input @error('address') border-red-400 @enderror">{{ old('address') }}</textarea>
                            @error('address')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2 space-y-3.5">
                            <p class="text-xs font-bold text-amber-600 uppercase tracking-wider pb-1 border-b border-amber-100">Notification Alert</p>
                            <div>
                                <label for="add_alert_message" class="form-label text-xs">Alert Message</label>
                                <textarea id="add_alert_message" autocomplete="off" name="alert_message" rows="2" placeholder="Message to display to this garage" class="form-input @error('alert_message') border-red-400 @enderror">{{ old('alert_message') }}</textarea>
                                @error('alert_message')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="add_alert_expires_at" class="form-label text-xs">Alert Expires At</label>
                                <input id="add_alert_expires_at" autocomplete="off" type="datetime-local" name="alert_expires_at" value="{{ old('alert_expires_at') }}" class="form-input @error('alert_expires_at') border-red-400 @enderror">
                                @error('alert_expires_at')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0 px-6 py-4 border-t border-slate-100 bg-white flex justify-end gap-3">
                    <button type="button" @click="openAddModal = false" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Create Workshop & Admin</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Edit Workshop (same layout as Add) --}}
    <div
        x-show="openEditModal"
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
        style="display: none;"
        role="dialog"
        aria-modal="true"
    >
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px]" @click="openEditModal = false"></div>
        <div class="relative bg-white border border-slate-200 rounded-2xl w-full max-w-2xl max-h-[min(92vh,900px)] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.35)] flex flex-col overflow-hidden z-10">
            <div class="flex-shrink-0 flex justify-between items-center border-b border-slate-100 px-6 py-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Edit Workshop</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Garage profile + administrator account</p>
                </div>
                <button type="button" @click="openEditModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form :action="`/super-admin/workshops/${activeWorkshop.id}`" method="POST" class="flex flex-col flex-1 min-h-0" x-show="activeWorkshop.id" autocomplete="off">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="workshop_id" x-model="activeWorkshop.id">
                                <input type="hidden" name="admin_user_id" x-model="activeWorkshop.admin_user_id">

                @if($showEditWorkshopModal)
                <div class="flex-shrink-0 mx-6 mt-4 px-4 py-3 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-sm font-semibold text-red-700">Please fix the errors below and try again.</p>
                </div>
                @endif

                <div class="flex-1 overflow-y-auto px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3.5">
                            <p class="text-xs font-bold text-primary-600 uppercase tracking-wider pb-1 border-b border-slate-100">Workshop details</p>
                            <div>
                                <label for="edit_name" class="form-label text-xs">Workshop name *</label>
                                <input id="edit_name" autocomplete="organization" type="text" name="name" required placeholder="e.g. Speedsters Garage" x-model="activeWorkshop.name" class="form-input @error('name') border-red-400 @enderror">
                                @error('name')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="edit_phone" class="form-label text-xs">Phone *</label>
                                <input id="edit_phone" autocomplete="tel" type="text" name="phone" required placeholder="e.g. +91 9988776655" x-model="activeWorkshop.phone" class="form-input @error('phone') border-red-400 @enderror">
                                @error('phone')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="edit_email" class="form-label text-xs">Email</label>
                                <input id="edit_email" autocomplete="email" type="email" name="email" placeholder="garage@example.com" x-model="activeWorkshop.email" class="form-input @error('email') border-red-400 @enderror">
                                @error('email')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="edit_gstin" class="form-label text-xs">GSTIN</label>
                                <input id="edit_gstin" autocomplete="off" type="text" name="gstin" placeholder="29XXXXXXXXXX1Z5" x-model="activeWorkshop.gstin" class="form-input @error('gstin') border-red-400 @enderror">
                                @error('gstin')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="edit_status" class="form-label text-xs">Subscription Status *</label>
                                <select id="edit_status" autocomplete="off" name="subscription_status" required x-model="activeWorkshop.subscription_status" class="form-input @error('subscription_status') border-red-400 @enderror">
                                    <option value="trial">Free Trial</option>
                                    <option value="active">Active</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                                @error('subscription_status')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div x-show="activeWorkshop.subscription_status === 'trial' || activeWorkshop.subscription_status === 'active'" x-cloak class="animate-fade-in-up">
                                <label for="edit_trial" class="form-label text-xs" x-text="activeWorkshop.subscription_status === 'trial' ? 'Trial Expiration Date *' : 'Subscription Expiration Date (Optional)'"></label>
                                <input id="edit_trial" autocomplete="off" type="datetime-local" name="trial_ends_at" x-model="activeWorkshop.trial_ends_at" :required="activeWorkshop.subscription_status === 'trial'" class="form-input @error('trial_ends_at') border-red-400 @enderror">
                                @error('trial_ends_at')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-2 mt-4">
                                <label for="edit_restrict_features" class="flex items-center gap-2 cursor-pointer select-none">
                                    <input id="edit_restrict_features" type="checkbox" name="restrict_features_on_expiry" value="1" x-model="activeWorkshop.restrict_features_on_expiry" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                    <span class="text-xs font-semibold text-slate-700">Restrict features on expiry</span>
                                </label>
                                <label for="edit_admin_extend" class="flex items-center gap-2 cursor-pointer select-none">
                                    <input id="edit_admin_extend" type="checkbox" name="admin_extend_allowed" value="1" x-model="activeWorkshop.admin_extend_allowed" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                    <span class="text-xs font-semibold text-slate-700">Allow Admin to extend trial</span>
                                </label>
                            </div>
                        </div>
                        <div class="space-y-3.5">
                            <p class="text-xs font-bold text-primary-600 uppercase tracking-wider pb-1 border-b border-slate-100">Administrator</p>
                            <div>
                                <label for="edit_admin_name" class="form-label text-xs">Admin name *</label>
                                <input id="edit_admin_name" autocomplete="name" type="text" name="admin_name" required placeholder="e.g. Rajesh Kumar" x-model="activeWorkshop.admin_name" autocomplete="off" class="form-input @error('admin_name') border-red-400 @enderror">
                                @error('admin_name')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="edit_admin_email" class="form-label text-xs">Admin email *</label>
                                <input id="edit_admin_email" autocomplete="email" type="email" name="admin_email" required placeholder="owner@garage.com" x-model="activeWorkshop.admin_email" autocomplete="off" class="form-input @error('admin_email') border-red-400 @enderror">
                                @error('admin_email')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                                <p class="text-[11px] text-slate-500 mt-1">Unique — not your super admin login email.</p>
                            </div>
                            <div>
                                <label for="edit_admin_password" class="form-label text-xs">Admin password</label>
                                <input id="edit_admin_password" autocomplete="new-password" type="password" name="admin_password" placeholder="Min 8 characters" autocomplete="new-password" class="form-input @error('admin_password') border-red-400 @enderror">
                                @error('admin_password')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                                <p class="text-[11px] text-slate-500 mt-1">Leave blank to keep the current password.</p>
                            </div>
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <label for="edit_address" class="form-label text-xs">Address</label>
                            <textarea id="edit_address" autocomplete="street-address" name="address" rows="2" placeholder="Street, city" x-model="activeWorkshop.address" class="form-input @error('address') border-red-400 @enderror"></textarea>
                            @error('address')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2 space-y-3.5">
                            <p class="text-xs font-bold text-amber-600 uppercase tracking-wider pb-1 border-b border-amber-100">Notification Alert</p>
                            <div>
                                <label for="edit_alert_message" class="form-label text-xs">Alert Message</label>
                                <textarea id="edit_alert_message" autocomplete="off" name="alert_message" rows="2" placeholder="Message to display to this garage" x-model="activeWorkshop.alert_message" class="form-input @error('alert_message') border-red-400 @enderror"></textarea>
                                @error('alert_message')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="edit_alert_expires_at" class="form-label text-xs">Alert Expires At</label>
                                <input id="edit_alert_expires_at" autocomplete="off" type="datetime-local" name="alert_expires_at" x-model="activeWorkshop.alert_expires_at" class="form-input @error('alert_expires_at') border-red-400 @enderror">
                                @error('alert_expires_at')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0 px-6 py-4 border-t border-slate-100 bg-white flex justify-end gap-3">
                    <button type="button" @click="openEditModal = false" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Save Workshop & Admin</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Generate Product Keys --}}
    <div
        x-show="openGenerateKeysModal"
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
        style="display: none;"
        role="dialog"
        aria-modal="true"
    >
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px]" @click="openGenerateKeysModal = false"></div>
        <div class="relative bg-white border border-slate-200 rounded-2xl w-full max-w-md shadow-[0_25px_50px_-12px_rgba(0,0,0,0.35)] flex flex-col overflow-hidden z-10 animate-fade-in-up">
            <div class="flex-shrink-0 flex justify-between items-center border-b border-slate-100 px-6 py-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Generate Product Keys</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Create batch activations for subscription distribution</p>
                </div>
                <button type="button" @click="openGenerateKeysModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('super_admin.store_product_key') }}" method="POST" class="flex flex-col p-6 space-y-4" x-data="{ durationType: '90', customDays: '45' }">
                @csrf
                <div>
                    <label for="gen_duration" class="form-label text-xs">License Duration *</label>
                    <select id="gen_duration" autocomplete="off" x-model="durationType" class="form-input">
                        <option value="30">30 Days (1 Month)</option>
                        <option value="90">90 Days (3 Months)</option>
                        <option value="180">180 Days (6 Months)</option>
                        <option value="365">365 Days (1 Year)</option>
                        <option value="custom">Custom Days...</option>
                    </select>
                </div>

                {{-- Hidden input for actual sent duration_days --}}
                <input type="hidden" name="duration_days" :value="durationType === 'custom' ? customDays : durationType">

                <div x-show="durationType === 'custom'" x-cloak class="animate-fade-in-up">
                    <label for="gen_custom" class="form-label text-xs">Custom Duration (Days) *</label>
                    <input id="gen_custom" autocomplete="off" type="number" x-model="customDays" min="1" max="10000" class="form-input">
                    <p class="text-[11px] text-slate-400 mt-1">Specify any exact number of days (e.g. 15, 45, or 730 days).</p>
                </div>

                <div>
                    <label for="gen_quantity" class="form-label text-xs">Quantity to Generate *</label>
                    <input id="gen_quantity" autocomplete="off" type="number" name="quantity" required min="1" max="100" value="5" class="form-input">
                    <p class="text-[11px] text-slate-400 mt-1">Generates unique random codes in bulk. Maximum 100 per batch.</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="openGenerateKeysModal = false" class="btn-secondary text-sm">Cancel</button>
                    <button type="submit" class="btn-primary text-sm !bg-violet-600 hover:!bg-violet-700 !border-violet-600">Generate Batch</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Edit Product Key --}}
    <div
        x-show="openEditKeyModal"
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
        style="display: none;"
        role="dialog"
        aria-modal="true"
    >
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px]" @click="openEditKeyModal = false"></div>
        <div class="relative bg-white border border-slate-200 rounded-2xl w-full max-w-md shadow-[0_25px_50px_-12px_rgba(0,0,0,0.35)] flex flex-col overflow-hidden z-10 animate-fade-in-up">
            <div class="flex-shrink-0 flex justify-between items-center border-b border-slate-100 px-6 py-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Edit Product Key</h3>
                    <p class="text-xs text-slate-500 mt-0.5 font-mono font-bold bg-slate-50 border border-slate-150 px-2 py-1 rounded" x-text="activeKey.key">Key Code</p>
                </div>
                <button type="button" @click="openEditKeyModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form :action="`/super-admin/product-keys/${activeKey.id}`" method="POST" class="flex flex-col p-6 space-y-4" x-data="{ durationType: '90', customDays: '45' }" x-init="$watch('activeKey.duration_days', value => {
                if (['30', '90', '180', '365'].includes(String(value))) {
                    durationType = String(value);
                } else {
                    durationType = 'custom';
                    customDays = String(value);
                }
            })">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_key_duration" class="form-label text-xs">License Duration *</label>
                    <select id="edit_key_duration" autocomplete="off" x-model="durationType" class="form-input">
                        <option value="30">30 Days (1 Month)</option>
                        <option value="90">90 Days (3 Months)</option>
                        <option value="180">180 Days (6 Months)</option>
                        <option value="365">365 Days (1 Year)</option>
                        <option value="custom">Custom Days...</option>
                    </select>
                </div>

                {{-- Hidden input for actual sent duration_days --}}
                <input type="hidden" name="duration_days" :value="durationType === 'custom' ? customDays : durationType">

                <div x-show="durationType === 'custom'" x-cloak class="animate-fade-in-up">
                    <label for="edit_key_custom" class="form-label text-xs">Custom Duration (Days) *</label>
                    <input id="edit_key_custom" autocomplete="off" type="number" x-model="customDays" min="1" max="10000" class="form-input">
                    <p class="text-[11px] text-slate-400 mt-1">Specify any exact number of days (e.g. 15, 45, or 730 days).</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="openEditKeyModal = false" class="btn-secondary text-sm">Cancel</button>
                    <button type="submit" class="btn-primary text-sm !bg-violet-600 hover:!bg-violet-700 !border-violet-600">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    {{-- System Settings Panel --}}
    <div x-show="activeTab === 'settings'" class="space-y-8 animate-fade-in-up" x-cloak>
        <div class="glass-card !p-0 overflow-hidden shadow-sm max-w-3xl">
            <div class="p-6 border-b border-slate-100 bg-white">
                <h3 class="text-base font-bold text-slate-900">System Settings</h3>
                <p class="text-xs text-slate-500 mt-1 font-medium">Configure global defaults, trial behavior, and platform-wide policies.</p>
            </div>
            <form action="{{ route('super_admin.update_settings') }}" method="POST" class="p-6 space-y-6">
                @csrf
                <div>
                    <label for="default_trial_duration" class="form-label text-sm font-semibold">Default Trial Duration (Days)</label>
                    <input id="default_trial_duration" autocomplete="off" type="number" name="default_trial_duration" value="{{ $defaultTrialDuration }}" min="1" max="1000" class="form-input max-w-xs mt-1">
                    <p class="text-xs text-slate-500 mt-2">New workshops signing up will automatically get this many days of free trial if their status is set to Trial.</p>
                </div>
                
                <div class="pt-4 border-t border-slate-100">
                    <button type="submit" class="btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Activity Logs Panel --}}
    <div x-show="activeTab === 'logs'" class="space-y-8 animate-fade-in-up" x-cloak>
        <div class="glass-card !p-0 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Activity Logs</h3>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Complete audit trail of all administrator actions and system events.</p>
                </div>
                @if(!$activityLogs->isEmpty())
                <div>
                    <form action="{{ route('super_admin.clear_logs') }}" method="POST" onsubmit="return confirm('Clear ALL activity logs permanently?')" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger !py-2 !px-4 text-xs font-bold flex items-center gap-1.5 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Clear All Logs
                        </button>
                    </form>
                </div>
                @endif
            </div>

            @if($activityLogs->isEmpty())
            <div class="text-center py-16 px-6">
                <svg class="w-14 h-14 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 12h6m-6 4h6"/></svg>
                <p class="text-slate-600 font-semibold">No activity logs yet</p>
            </div>
            @else
            <div class="overflow-x-auto relative z-0">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Action</th>
                            <th>User</th>
                            <th>Workshop</th>
                            <th>Details</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activityLogs as $log)
                        <tr>
                            <td class="text-xs text-slate-500 whitespace-nowrap">{{ $log->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <span class="badge badge-info uppercase tracking-wider text-[10px]">{{ str_replace('_', ' ', $log->action) }}</span>
                            </td>
                            <td class="font-semibold text-slate-700 text-sm">
                                {{ $log->user ? $log->user->name : 'System' }}
                            </td>
                            <td class="text-sm font-medium text-slate-600">
                                {{ $log->workshop ? $log->workshop->name : 'Global' }}
                            </td>
                            <td class="text-sm text-slate-700">
                                {{ $log->description }}
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <form action="{{ route('super_admin.destroy_log', $log) }}" method="POST" onsubmit="return confirm('Delete this log entry permanently?')" class="inline m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-all" title="Delete Log">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

</div>

@push('scripts')
<style>
    .workshops-table tbody td {
        white-space: normal !important;
        vertical-align: top;
    }
    .workshops-table thead th {
        white-space: nowrap;
        vertical-align: middle;
    }
</style>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('workshopAdminPanel', () => ({
        openAddModal: @json($showAddWorkshopModal),
        openEditModal: @json($showEditWorkshopModal),
        openGenerateKeysModal: false,
        openEditKeyModal: false,
        activeTab: (new URLSearchParams(window.location.search)).get('tab') || 'dashboard',
        activeWorkshop: @json($initialActiveWorkshop),
        activeKey: { id: '', key: '', duration_days: 90 },
        newWorkshopStatus: 'trial',

        // Search & Filters state
        searchWorkshopQuery: '',
        searchKeyQuery: '',
        keyStatusFilter: 'all',

        matchesWorkshopSearch(name, phone, email, adminName, adminEmail) {
            let q = this.searchWorkshopQuery.toLowerCase().trim();
            if (!q) return true;
            return name.includes(q) || phone.includes(q) || email.includes(q) || adminName.includes(q) || adminEmail.includes(q);
        },

        matchesKeySearch(keyCode, status, workshopName) {
            if (this.keyStatusFilter !== 'all' && status !== this.keyStatusFilter) {
                return false;
            }
            let q = this.searchKeyQuery.toLowerCase().trim();
            if (!q) return true;
            return keyCode.includes(q) || workshopName.includes(q);
        },

        init() {
            this.$watch('openAddModal', () => this.syncBodyScroll());
            this.$watch('openEditModal', () => this.syncBodyScroll());
            this.$watch('openGenerateKeysModal', () => this.syncBodyScroll());
            this.$watch('openEditKeyModal', () => this.syncBodyScroll());
            this.syncBodyScroll();
        },
        syncBodyScroll() {
            document.body.classList.toggle('overflow-hidden', this.openAddModal || this.openEditModal || this.openGenerateKeysModal || this.openEditKeyModal);
        },
        openEdit(workshop) {
            this.activeWorkshop = {
                ...workshop,
                email: workshop.email ?? '',
                gstin: workshop.gstin ?? '',
                address: workshop.address ?? '',
                subscription_status: workshop.subscription_status ?? 'active',
                trial_ends_at: workshop.trial_ends_at ?? '',
                trial_days: '',
                restrict_features_on_expiry: !!workshop.restrict_features_on_expiry,
                admin_extend_allowed: !!workshop.admin_extend_allowed,
                admin_user_id: workshop.admin_user_id ?? '',
                admin_name: workshop.admin_name ?? '',
                admin_email: workshop.admin_email ?? '',
            };
            this.openAddModal = false;
            this.openEditModal = true;
        },
        openEditKey(key) {
            this.activeKey = { ...key };
            this.openEditKeyModal = true;
        },
        openAdd() {
            this.openEditModal = false;
            this.openAddModal = true;
        },
    }));
});
</script>
@endpush
@endsection
