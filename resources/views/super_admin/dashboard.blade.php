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

<style>
@keyframes floatOrb {
    0%, 100% { transform: translateY(0px) scale(1); }
    50% { transform: translateY(-20px) scale(1.05); }
}
@keyframes shimmer {
    0% { background-position: -200% center; }
    100% { background-position: 200% center; }
}
.stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 16px 40px -8px rgba(0,0,0,0.15); }
.tab-btn { transition: all 0.2s ease; }
.action-card { transition: all 0.2s ease; cursor: pointer; }
.action-card:hover { transform: translateY(-2px); }
.action-card:active { transform: scale(0.98); }
.modal-enter { animation: slideUp 0.3s ease forwards; }
@keyframes slideUp { from { opacity: 0; transform: translateY(20px) scale(0.97); } to { opacity: 1; transform: translateY(0) scale(1); } }
</style>

<div x-data="workshopAdminPanel()">
<div class="max-w-7xl mx-auto space-y-5">

{{-- ══ HERO BANNER ══ --}}
<div class="relative rounded-3xl overflow-hidden shadow-xl" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 45%, #2563eb 100%);">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute w-96 h-96 rounded-full -top-24 -right-24 opacity-20" style="background: radial-gradient(circle, #818cf8 0%, transparent 70%); animation: floatOrb 8s ease-in-out infinite;"></div>
        <div class="absolute w-64 h-64 rounded-full -bottom-16 -left-16 opacity-10" style="background: radial-gradient(circle, #38bdf8 0%, transparent 70%); animation: floatOrb 12s ease-in-out infinite reverse;"></div>
        <svg class="absolute inset-0 w-full h-full opacity-[0.04]" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="sgrid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.8"/></pattern></defs><rect width="100%" height="100%" fill="url(#sgrid)"/></svg>
    </div>
    <div class="relative z-10 p-6 sm:p-8 lg:p-10">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <p class="text-indigo-300 text-xs font-extrabold uppercase tracking-widest">Super Admin</p>
                        <h2 class="text-white text-2xl sm:text-3xl font-extrabold leading-tight">System Control Panel</h2>
                    </div>
                </div>
                <p class="text-indigo-200 text-sm font-medium mb-4 max-w-lg">Manage all garage workshops, license keys, accounts, and global settings from one command center.</p>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2);">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        {{ $totalWorkshops }} Garages
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2);">
                        <span class="w-2 h-2 rounded-full bg-sky-400"></span>
                        {{ $totalUsers }} Users
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2);">
                        <span class="w-2 h-2 rounded-full bg-violet-400"></span>
                        {{ $unusedProductKeys }} Keys Available
                    </span>
                </div>
            </div>
            <div class="flex flex-wrap gap-3 shrink-0">
                <button type="button" x-show="activeTab === 'workshops'" @click="openAdd()"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold transition-all hover:scale-105 active:scale-95"
                    style="background: white; color: #1e1b4b; box-shadow: 0 8px 24px rgba(0,0,0,0.25);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Add Workshop
                </button>
                <button type="button" x-show="activeTab === 'keys'" @click="openGenerateKeysModal = true"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-bold transition-all hover:scale-105 active:scale-95"
                    style="background: white; color: #1e1b4b; box-shadow: 0 8px 24px rgba(0,0,0,0.25);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Generate Keys
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Trial Warnings --}}
@php $expiringWorkshops = $workshops->filter(fn($w) => in_array($w->subscription_status, ['trial', 'training'])); @endphp
@if($expiringWorkshops->count() > 0)
<div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-2xl px-5 py-4">
    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    <div>
        <p class="text-sm font-bold text-amber-800 mb-1">Active Training / Trial Periods</p>
        @foreach($expiringWorkshops as $ew)
            @php $ewTrialEnds = $ew->trial_ends_at ? \Carbon\Carbon::parse($ew->trial_ends_at) : null; @endphp
            @if($ewTrialEnds)
            <p class="text-xs text-amber-700"><span class="font-bold">{{ $ew->name }}</span> — {{ $ew->subscription_status === 'training' ? 'Training' : 'Trial' }} ends {{ $ewTrialEnds->format('M d, Y') }} ({{ $ewTrialEnds->diffForHumans() }})</p>
            @endif
        @endforeach
    </div>
</div>
@endif

{{-- ══ TAB NAVIGATOR ══ --}}
<div class="flex overflow-x-auto gap-1.5 p-1.5 bg-white rounded-2xl border border-slate-200 shadow-sm w-full sm:w-max" style="-ms-overflow-style: none; scrollbar-width: none;">
    <style>.flex.overflow-x-auto::-webkit-scrollbar { display: none; }</style>
    @php
        $tabs = [
            ['id' => 'dashboard', 'label' => 'Dashboard', 'color' => '#1e1b4b,#3730a3', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['id' => 'workshops', 'label' => 'Garages', 'color' => '#0c4a6e,#0369a1', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z'],
            ['id' => 'keys', 'label' => 'Keys', 'color' => '#064e3b,#059669', 'icon' => 'M15 7a2 2 0 012 2m-9 8a2 2 0 012-2m7-3a6 6 0 11-12 0 6 6 0 0112 0z'],
            ['id' => 'settings', 'label' => 'Settings', 'color' => '#78350f,#b45309', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
            ['id' => 'logs', 'label' => 'Logs', 'color' => '#1e293b,#475569', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2'],
        ];
    @endphp
    @foreach($tabs as $tab)
    <button type="button"
        @click="activeTab = '{{ $tab['id'] }}'; window.history.replaceState(null, null, '?tab={{ $tab['id'] }}')"
        :class="activeTab === '{{ $tab['id'] }}' ? 'text-white shadow-md' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
        :style="activeTab === '{{ $tab['id'] }}' ? 'background: linear-gradient(135deg, {{ $tab['color'] }});' : ''"
        class="tab-btn flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold shrink-0 sm:flex-initial justify-center whitespace-nowrap transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"/></svg>
        {{ $tab['label'] }}
    </button>
    @endforeach
</div>

{{-- ══════════════════════════════════════════ --}}
{{-- DASHBOARD TAB --}}
{{-- ══════════════════════════════════════════ --}}
<div x-show="activeTab === 'dashboard'" class="space-y-5 animate-fade-in-up" x-cloak>

    {{-- 5 KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">
        <div class="stat-card relative rounded-2xl p-5 overflow-hidden" style="background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 1px solid #bfdbfe;">
            <div class="absolute -top-4 -right-4 w-16 h-16 rounded-full opacity-25" style="background: #3b82f6;"></div>
            <div class="w-10 h-10 rounded-xl bg-blue-500 flex items-center justify-center mb-3 shadow-lg shadow-blue-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
            </div>
            <p class="text-3xl font-extrabold text-blue-900">{{ $totalWorkshops }}</p>
            <p class="text-xs font-bold text-blue-600 mt-1 uppercase tracking-wider">Garages</p>
        </div>
        <div class="stat-card relative rounded-2xl p-5 overflow-hidden" style="background: linear-gradient(135deg, #f5f3ff, #ede9fe); border: 1px solid #c4b5fd;">
            <div class="absolute -top-4 -right-4 w-16 h-16 rounded-full opacity-25" style="background: #7c3aed;"></div>
            <div class="w-10 h-10 rounded-xl bg-violet-500 flex items-center justify-center mb-3 shadow-lg shadow-violet-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <p class="text-3xl font-extrabold text-violet-900">{{ $totalUsers }}</p>
            <p class="text-xs font-bold text-violet-600 mt-1 uppercase tracking-wider">Total Users</p>
        </div>
        <div class="stat-card relative rounded-2xl p-5 overflow-hidden" style="background: linear-gradient(135deg, #fdf4ff, #fae8ff); border: 1px solid #f5d0fe;">
            <div class="absolute -top-4 -right-4 w-16 h-16 rounded-full opacity-25" style="background: #d946ef;"></div>
            <div class="w-10 h-10 rounded-xl bg-fuchsia-500 flex items-center justify-center mb-3 shadow-lg shadow-fuchsia-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <p class="text-3xl font-extrabold text-fuchsia-900">{{ $totalSuperAdmins }}</p>
            <p class="text-xs font-bold text-fuchsia-600 mt-1 uppercase tracking-wider">Super Admins</p>
        </div>
        <div class="stat-card relative rounded-2xl p-5 overflow-hidden" style="background: linear-gradient(135deg, #ecfdf5, #d1fae5); border: 1px solid #6ee7b7;">
            <div class="absolute -top-4 -right-4 w-16 h-16 rounded-full opacity-25" style="background: #059669;"></div>
            <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center mb-3 shadow-lg shadow-emerald-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-9 8a2 2 0 012-2m7-3a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
            </div>
            <p class="text-3xl font-extrabold text-emerald-900">{{ $unusedProductKeys }}</p>
            <p class="text-xs font-bold text-emerald-600 mt-1 uppercase tracking-wider">Available Keys</p>
        </div>
        <div class="stat-card relative rounded-2xl p-5 overflow-hidden sm:col-span-2 xl:col-span-1" style="background: linear-gradient(135deg, #fff7ed, #ffedd5); border: 1px solid #fdba74;">
            <div class="absolute -top-4 -right-4 w-16 h-16 rounded-full opacity-25" style="background: #ea580c;"></div>
            <div class="w-10 h-10 rounded-xl bg-orange-500 flex items-center justify-center mb-3 shadow-lg shadow-orange-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-3xl font-extrabold text-orange-900">{{ $usedProductKeys }}</p>
            <p class="text-xs font-bold text-orange-600 mt-1 uppercase tracking-wider">Keys Redeemed</p>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        {{-- Left --}}
        <div class="lg:col-span-7 space-y-5">
            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <h3 class="text-sm font-bold text-slate-900">Quick Actions</h3>
                </div>
                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @if($totalWorkshops >= 2)
                    <div class="action-card flex items-center gap-3 p-4 rounded-xl opacity-50 cursor-not-allowed" style="background:#f8fafc; border: 1px dashed #cbd5e1;">
                        <div class="w-9 h-9 rounded-xl bg-slate-200 flex items-center justify-center shrink-0"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div>
                        <div><p class="text-xs font-bold text-slate-500">Register Workshop</p><p class="text-[10px] text-slate-400">Limit of 2 reached</p></div>
                    </div>
                    @else
                    <div class="action-card flex items-center gap-3 p-4 rounded-xl" style="background: #eef2ff; border: 1px solid #c7d2fe;" @click="openAdd()">
                        <div class="w-9 h-9 rounded-xl bg-indigo-500 flex items-center justify-center shrink-0 shadow-md shadow-indigo-500/30"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg></div>
                        <div><p class="text-xs font-bold text-indigo-800">Register Workshop</p><p class="text-[10px] text-indigo-500">Add a new garage</p></div>
                    </div>
                    @endif
                    <a href="{{ route('backup.index') }}" class="action-card flex items-center gap-3 p-4 rounded-xl" style="background: #ecfeff; border: 1px solid #a5f3fc;">
                        <div class="w-9 h-9 rounded-xl bg-cyan-500 flex items-center justify-center shrink-0 shadow-md shadow-cyan-500/30"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg></div>
                        <div><p class="text-xs font-bold text-cyan-800">Database Backup</p><p class="text-[10px] text-cyan-500">Backup & Restore</p></div>
                    </a>
                    <div class="action-card flex items-center gap-3 p-4 rounded-xl" style="background: #ecfdf5; border: 1px solid #a7f3d0;" @click="activeTab='keys'; window.history.replaceState(null,null,'?tab=keys')">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500 flex items-center justify-center shrink-0 shadow-md shadow-emerald-500/30"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-9 8a2 2 0 012-2m7-3a6 6 0 11-12 0 6 6 0 0112 0z"/></svg></div>
                        <div><p class="text-xs font-bold text-emerald-800">Product Keys</p><p class="text-[10px] text-emerald-500">Manage license keys</p></div>
                    </div>
                    <div class="action-card flex items-center gap-3 p-4 rounded-xl" style="background: #fffbeb; border: 1px solid #fde68a;" @click="activeTab='settings'; window.history.replaceState(null,null,'?tab=settings')">
                        <div class="w-9 h-9 rounded-xl bg-amber-500 flex items-center justify-center shrink-0 shadow-md shadow-amber-500/30"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                        <div><p class="text-xs font-bold text-amber-800">Platform Settings</p><p class="text-[10px] text-amber-500">Configure system</p></div>
                    </div>
                </div>
            </div>

            {{-- Recent Garages --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                        <h3 class="text-sm font-bold text-slate-900">Recent Garages</h3>
                    </div>
                    <button type="button" @click="activeTab='workshops'; window.history.replaceState(null,null,'?tab=workshops')" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                        View All <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
                @if($workshops->count())
                <div class="divide-y divide-slate-50">
                    @foreach($workshops->take(4) as $w)
                    <div class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50/60 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-sm shrink-0" style="background: linear-gradient(135deg, #3730a3, #2563eb);">{{ strtoupper(substr($w->name,0,1)) }}</div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $w->name }}</p>
                                <p class="text-xs text-slate-400 truncate">{{ $w->phone }}</p>
                            </div>
                        </div>
                        <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold
                            {{ $w->subscription_status==='active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($w->subscription_status==='suspended' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-blue-50 text-blue-700 border border-blue-200') }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $w->subscription_status==='active' ? 'bg-emerald-500' : ($w->subscription_status==='suspended' ? 'bg-rose-500' : 'bg-blue-500 animate-pulse') }}"></span>
                            {{ ucfirst($w->subscription_status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="w-10 h-10 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    <p class="text-slate-400 text-sm font-semibold">No workshops yet.</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Right --}}
        <div class="lg:col-span-5 space-y-5">
            {{-- License Key Panel --}}
            <div class="rounded-2xl overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); border: 1px solid rgba(255,255,255,0.08);">
                <div class="px-5 py-4" style="border-bottom: 1px solid rgba(255,255,255,0.08);">
                    <p class="text-xs text-indigo-400 font-bold uppercase tracking-widest">License Keys</p>
                    <p class="text-white font-bold text-sm mt-0.5">Subscription Summary</p>
                </div>
                <div class="p-5 space-y-3">
                    @foreach([['label'=>'Available / Unused','val'=>$unusedProductKeys,'color'=>'#34d399','bg'=>'rgba(52,211,153,0.1)'],['label'=>'Redeemed Keys','val'=>$usedProductKeys,'color'=>'#fb923c','bg'=>'rgba(251,146,60,0.1)'],['label'=>'Total Generated','val'=>$totalProductKeys,'color'=>'#818cf8','bg'=>'rgba(129,140,248,0.1)']] as $k)
                    <div class="flex items-center justify-between p-3.5 rounded-xl" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.06);">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full" style="background: {{ $k['color'] }};"></span>
                            <span class="text-xs font-semibold text-slate-300">{{ $k['label'] }}</span>
                        </div>
                        <span class="text-sm font-extrabold px-3 py-1 rounded-lg" style="color: {{ $k['color'] }}; background: {{ $k['bg'] }};">{{ $k['val'] }}</span>
                    </div>
                    @endforeach
                    @if($totalProductKeys > 0)
                    <div class="pt-1">
                        <div class="flex justify-between text-[10px] font-semibold text-slate-500 mb-1.5">
                            <span>Key Usage</span>
                            <span>{{ round(($usedProductKeys / $totalProductKeys) * 100) }}% redeemed</span>
                        </div>
                        <div class="w-full h-2 rounded-full" style="background: rgba(255,255,255,0.07);">
                            <div class="h-2 rounded-full" style="width: {{ round(($usedProductKeys / $totalProductKeys) * 100) }}%; background: linear-gradient(90deg, #818cf8, #34d399); transition: width 1s ease;"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Recent Unused Keys List in Dashboard --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-9 8a2 2 0 012-2m7-3a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                        <h3 class="text-sm font-bold text-slate-900">Recent Unused Keys</h3>
                    </div>
                    <button type="button" @click="activeTab='keys'; window.history.replaceState(null,null,'?tab=keys')" class="text-xs font-bold text-violet-600 hover:text-violet-800 flex items-center gap-1">
                        View All <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
                @if($productKeys->where('status', 'unused')->count())
                <div class="divide-y divide-slate-50">
                    @foreach($productKeys->where('status', 'unused')->take(5) as $k)
                    <div class="px-5 py-3 hover:bg-slate-50/60 transition-colors flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-mono text-xs font-bold text-slate-800 select-all">{{ $k->key }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5 font-medium">{{ $k->duration_days }} days duration · Created {{ $k->created_at->diffForHumans() }}</p>
                        </div>
                        <button type="button" 
                                onclick="navigator.clipboard.writeText('{{ $k->key }}'); alert('Key copied to clipboard!')"
                                class="p-1.5 text-slate-400 hover:text-violet-600 hover:bg-violet-50 rounded-lg transition-all"
                                title="Copy Key">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                        </button>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <p class="text-xs text-slate-400 font-semibold">No unused keys available.</p>
                </div>
                @endif
            </div>

            {{-- Activity Logs --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                        <h3 class="text-sm font-bold text-slate-900">Recent Activity</h3>
                    </div>
                    <button type="button" @click="activeTab='logs'; window.history.replaceState(null,null,'?tab=logs')" class="text-xs font-bold text-slate-400 hover:text-slate-700 flex items-center gap-1">
                        All Logs <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
                @if($activityLogs->count())
                <div class="divide-y divide-slate-50">
                    @foreach($activityLogs->take(5) as $log)
                    @php $at = str_contains($log->action,'update') ? 'update' : (str_contains($log->action,'delete') ? 'delete' : 'create'); @endphp
                    <div class="px-5 py-3.5 hover:bg-slate-50/50 transition-colors flex items-start gap-3">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0 mt-0.5 {{ $at==='create' ? 'bg-emerald-50' : ($at==='delete' ? 'bg-rose-50' : 'bg-blue-50') }}">
                            <span class="w-2 h-2 rounded-full {{ $at==='create' ? 'bg-emerald-500' : ($at==='delete' ? 'bg-rose-500' : 'bg-blue-500') }}"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-slate-700 leading-snug">{{ $log->description }}</p>
                            <div class="flex items-center justify-between mt-0.5">
                                <p class="text-[10px] font-bold text-indigo-500 uppercase">{{ str_replace('_',' ',$log->action) }}</p>
                                <span class="text-[10px] text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-10">
                    <p class="text-xs text-slate-400 font-semibold">No logs yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════ --}}
{{-- WORKSHOPS TAB --}}
{{-- ══════════════════════════════════════════ --}}
<div x-show="activeTab === 'workshops'" class="space-y-5 animate-fade-in-up" x-cloak>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-bold text-slate-900">Garages / Workshops</h3>
                <p class="text-xs text-slate-500 mt-0.5">Manage workshop profiles and admin logins</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="relative">
                    <input id="searchWorkshopQuery" type="text" x-model="searchWorkshopQuery" placeholder="Search garages..." autocomplete="off"
                        class="pl-8 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-400 focus:bg-white transition-colors w-48 sm:w-56">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-2 rounded-xl whitespace-nowrap">{{ $workshops->count() }} total</span>
            </div>
        </div>

        @if($workshops->isEmpty())
        <div class="text-center py-16 px-6">
            <svg class="w-14 h-14 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
            <p class="text-slate-600 font-bold text-base">No workshops yet</p>
            <p class="text-sm text-slate-400 mt-1 mb-5">Register your first garage to get started.</p>
            <button type="button" @click="openAdd()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white" style="background: linear-gradient(135deg, #1e1b4b, #2563eb);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add First Workshop
            </button>
        </div>
        @else
        {{-- Desktop Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead style="background: #f8fafc;">
                    <tr>
                        <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3">Workshop</th>
                        <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3">Phone</th>
                        <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3">Status</th>
                        <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3">Admin Login</th>
                        <th class="text-right text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($workshops as $workshop)
                    <tr x-show="matchesWorkshopSearch('{{ strtolower($workshop->name) }}','{{ strtolower($workshop->phone) }}','{{ strtolower($workshop->email) }}','{{ strtolower($workshop->users->first()?->name ?? '') }}','{{ strtolower($workshop->users->first()?->email ?? '') }}')" class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-sm shrink-0" style="background: linear-gradient(135deg, #3730a3, #2563eb);">{{ strtoupper(substr($workshop->name,0,1)) }}</div>
                                <div>
                                    <p class="font-bold text-slate-900 text-sm">{{ $workshop->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $workshop->email ?: 'No email' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 font-semibold text-slate-700 whitespace-nowrap">{{ $workshop->phone }}</td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if($workshop->isSuspended())
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200"><span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>Suspended</span>
                            @elseif($workshop->subscription_status === 'fix' || $workshop->subscription_status === 'fixed')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-200"><span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>Fix</span>
                            @elseif($workshop->isTrial())
                                @if($workshop->isTrialExpired())
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Training Expired</span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200"><span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>Training · {{ $workshop->getTrialDaysRemaining() }}d</span>
                                @endif
                            @else
                                @if($workshop->isTrialExpired())
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Expired</span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Active</span>
                                @endif
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            @forelse($workshop->users as $adminUser)
                            <p class="text-xs font-bold text-slate-800">{{ $adminUser->name }}</p>
                            <p class="text-[11px] text-indigo-500">{{ $adminUser->email }}</p>
                            @empty<span class="text-slate-400 text-xs">No admin</span>@endforelse
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-1">
                                <form action="{{ route('super_admin.impersonate', $workshop) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 text-indigo-400 hover:text-indigo-700 hover:bg-indigo-50 rounded-xl transition-all" title="Inspect Workshop">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </form>
                                <button type="button" @click="openActivateModal(@js(['id'=>$workshop->id,'name'=>$workshop->name,'phone'=>$workshop->phone,'email'=>$workshop->email,'gstin'=>$workshop->gstin,'address'=>$workshop->address,'subscription_status'=>$workshop->subscription_status,'trial_ends_at'=>$workshop->trial_ends_at?$workshop->trial_ends_at->format('Y-m-d\TH:i'):'','trial_days'=>'','alert_message'=>$workshop->alert_message,'alert_expires_at'=>$workshop->alert_expires_at?$workshop->alert_expires_at->format('Y-m-d\TH:i'):'','admin_user_id'=>$workshop->users->first()?->id,'admin_name'=>$workshop->users->first()?->name,'admin_email'=>$workshop->users->first()?->email]))"
                                    class="p-2 text-emerald-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-xl transition-all" title="Activate License">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-9 8a2 2 0 012-2m7-3a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                                </button>
                                <button type="button" @click="openEdit(@js(['id'=>$workshop->id,'name'=>$workshop->name,'phone'=>$workshop->phone,'email'=>$workshop->email,'gstin'=>$workshop->gstin,'address'=>$workshop->address,'subscription_status'=>$workshop->subscription_status,'trial_ends_at'=>$workshop->trial_ends_at?$workshop->trial_ends_at->format('Y-m-d\TH:i'):'','trial_days'=>'','alert_message'=>$workshop->alert_message,'alert_expires_at'=>$workshop->alert_expires_at?$workshop->alert_expires_at->format('Y-m-d\TH:i'):'','admin_user_id'=>$workshop->users->first()?->id,'admin_name'=>$workshop->users->first()?->name,'admin_email'=>$workshop->users->first()?->email]))"
                                    class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('super_admin.destroy_workshop', $workshop) }}" method="POST" onsubmit="return confirm('Delete this workshop and ALL its data permanently?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-400 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition-all" title="Delete">
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

        {{-- Mobile Cards --}}
        <div class="block md:hidden divide-y divide-slate-100">
            @foreach($workshops as $workshop)
            <div class="p-4 space-y-3" x-show="matchesWorkshopSearch('{{ strtolower($workshop->name) }}','{{ strtolower($workshop->phone) }}','{{ strtolower($workshop->email) }}','{{ strtolower($workshop->users->first()?->name ?? '') }}','{{ strtolower($workshop->users->first()?->email ?? '') }}')">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-sm shrink-0" style="background: linear-gradient(135deg, #3730a3, #2563eb);">{{ strtoupper(substr($workshop->name,0,1)) }}</div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">{{ $workshop->name }}</h4>
                            <p class="text-xs text-slate-400">{{ $workshop->phone }}</p>
                        </div>
                    </div>
                    @if($workshop->isSuspended())
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 shrink-0">Suspended</span>
                    @elseif($workshop->subscription_status === 'fix' || $workshop->subscription_status === 'fixed')
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200 shrink-0">Fix</span>
                    @elseif($workshop->isTrial() && !$workshop->isTrialExpired())
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 shrink-0">Training</span>
                    @elseif($workshop->isTrialExpired())
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 shrink-0">Expired</span>
                    @else
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">Active</span>
                    @endif
                </div>
                <div class="grid grid-cols-2 gap-2 bg-slate-50 rounded-xl p-3 text-xs">
                    <div class="col-span-2"><p class="text-slate-400 font-semibold uppercase text-[9px] mb-0.5">Email</p><p class="font-bold text-slate-700 break-all">{{ $workshop->email ?: '—' }}</p></div>
                    <div class="col-span-2"><p class="text-slate-400 font-semibold uppercase text-[9px] mb-0.5">Admin</p><p class="font-bold text-slate-700">{{ $workshop->users->first()?->name ?? 'No admin' }}</p><p class="text-[10px] text-indigo-500">{{ $workshop->users->first()?->email }}</p></div>
                </div>
                <div class="flex gap-2 flex-wrap sm:flex-nowrap">
                    <form action="{{ route('super_admin.impersonate', $workshop) }}" method="POST" class="flex-1 min-w-[70px]">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold border border-indigo-200 bg-indigo-50 text-indigo-700">
                            Inspect
                        </button>
                    </form>
                    <button type="button" @click="openActivateModal(@js(['id'=>$workshop->id,'name'=>$workshop->name,'phone'=>$workshop->phone,'email'=>$workshop->email,'gstin'=>$workshop->gstin,'address'=>$workshop->address,'subscription_status'=>$workshop->subscription_status,'trial_ends_at'=>$workshop->trial_ends_at?$workshop->trial_ends_at->format('Y-m-d\TH:i'):'','trial_days'=>'','alert_message'=>$workshop->alert_message,'alert_expires_at'=>$workshop->alert_expires_at?$workshop->alert_expires_at->format('Y-m-d\TH:i'):'','admin_user_id'=>$workshop->users->first()?->id,'admin_name'=>$workshop->users->first()?->name,'admin_email'=>$workshop->users->first()?->email]))"
                        class="flex-1 min-w-[70px] flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold border border-emerald-200 bg-emerald-50 text-emerald-700">
                        Activate
                    </button>
                    <button type="button" @click="openEdit(@js(['id'=>$workshop->id,'name'=>$workshop->name,'phone'=>$workshop->phone,'email'=>$workshop->email,'gstin'=>$workshop->gstin,'address'=>$workshop->address,'subscription_status'=>$workshop->subscription_status,'trial_ends_at'=>$workshop->trial_ends_at?$workshop->trial_ends_at->format('Y-m-d\TH:i'):'','trial_days'=>'','alert_message'=>$workshop->alert_message,'alert_expires_at'=>$workshop->alert_expires_at?$workshop->alert_expires_at->format('Y-m-d\TH:i'):'','admin_user_id'=>$workshop->users->first()?->id,'admin_name'=>$workshop->users->first()?->name,'admin_email'=>$workshop->users->first()?->email]))"
                        class="flex-1 min-w-[70px] flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold border border-slate-200 bg-slate-50 text-slate-700">
                        Edit
                    </button>
                    <form action="{{ route('super_admin.destroy_workshop', $workshop) }}" method="POST" onsubmit="return confirm('Delete this workshop and ALL its data permanently?')" class="flex-1 min-w-[70px]">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold border border-rose-200 bg-rose-50 text-rose-700">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- ══════════════════════════════════════════ --}}
{{-- PRODUCT KEYS TAB --}}
{{-- ══════════════════════════════════════════ --}}
<div x-show="activeTab === 'keys'" class="space-y-5 animate-fade-in-up" x-cloak>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-bold text-slate-900">Product Keys</h3>
                <p class="text-xs text-slate-500 mt-0.5">Manage license keys for workshop subscriptions</p>
            </div>
            <div class="flex items-center gap-2">
                <select x-model="keyStatusFilter" class="text-xs font-semibold border border-slate-200 rounded-xl px-3 py-2 bg-slate-50 focus:outline-none focus:border-indigo-400">
                    <option value="all">All Keys</option>
                    <option value="unused">Unused</option>
                    <option value="used">Used</option>
                </select>
                <div class="relative">
                    <input type="text" x-model="searchKeyQuery" placeholder="Search key..." class="pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-indigo-400 w-40">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <button type="button" @click="openGenerateKeysModal = true" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white whitespace-nowrap" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Generate
                </button>
                {{-- Delete All Keys --}}
                <form action="{{ route('super_admin.destroy_all_product_keys') }}" method="POST"
                      onsubmit="return confirm('⚠️ Delete ALL product keys (including redeemed)? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white whitespace-nowrap bg-rose-500 hover:bg-rose-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete All
                    </button>
                </form>
            </div>
        </div>
        @if(isset($productKeys) && $productKeys->count())
        {{-- Desktop --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3">Key Code</th>
                        <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3">Duration</th>
                        <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3">Status</th>
                        <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3">Used By</th>
                        <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3">Created</th>
                        <th class="text-right text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($productKeys as $key)
                    <tr x-show="matchesKeySearch('{{ strtolower($key->key) }}','{{ $key->status }}','{{ strtolower($key->workshop?->name ?? '') }}')" class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-xs font-bold text-slate-800 whitespace-nowrap">{{ $key->key }}</td>
                        <td class="px-5 py-3.5 whitespace-nowrap"><span class="font-bold text-slate-700">{{ $key->duration_days }}</span> <span class="text-slate-400 text-xs">days</span></td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($key->status === 'unused')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Available</span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Redeemed</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">
                            {{ $key->workshop?->name ?? '—' }}
                            @if($key->isUsed() && $key->used_at)
                            <p class="text-[10px] text-slate-400 mt-0.5 font-semibold" title="Redemption date">Redeemed: {{ $key->used_at->format('M d, Y h:i A') }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-400 whitespace-nowrap">{{ $key->created_at->format('M d, Y') }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-1">
                                @if($key->status === 'unused')
                                <button type="button" @click="openEditKey({ id: '{{ $key->id }}', key: '{{ $key->key }}', duration_days: {{ $key->duration_days }} })"
                                    class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @endif
                                <form action="{{ route('super_admin.destroy_product_key', $key) }}" method="POST" onsubmit="return confirm('Delete this key permanently?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-400 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition-all" title="Delete">
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
        {{-- Mobile --}}
        <div class="block md:hidden divide-y divide-slate-100">
            @foreach($productKeys as $key)
            <div class="p-4 space-y-2" x-show="matchesKeySearch('{{ strtolower($key->key) }}','{{ $key->status }}','{{ strtolower($key->workshop?->name ?? '') }}')">
                <div class="flex items-center justify-between gap-2">
                    <p class="font-mono text-xs font-bold text-slate-800 break-all">{{ $key->key }}</p>
                    @if($key->status === 'unused')
                    <span class="shrink-0 px-2 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Available</span>
                    @else
                    <span class="shrink-0 px-2 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Used</span>
                    @endif
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-500">
                        {{ $key->duration_days }} days
                        @if($key->isUsed())
                            · Used by {{ $key->workshop?->name }} @if($key->used_at) on {{ $key->used_at->format('M d, Y') }} @endif
                        @else
                            · Unused
                        @endif
                    </span>
                    <div class="flex gap-1">
                        @if($key->status === 'unused')
                        <button type="button" @click="openEditKey({ id: '{{ $key->id }}', key: '{{ $key->key }}', duration_days: {{ $key->duration_days }} })"
                            class="px-2.5 py-1 rounded-lg text-[10px] font-bold border border-indigo-200 bg-indigo-50 text-indigo-700">Edit</button>
                        @endif
                        <form action="{{ route('super_admin.destroy_product_key', $key) }}" method="POST" onsubmit="return confirm('Delete?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-bold border border-rose-200 bg-rose-50 text-rose-700">Del</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-14">
            <svg class="w-12 h-12 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m-9 8a2 2 0 012-2m7-3a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
            <p class="text-slate-600 font-bold text-sm">No product keys yet</p>
            <p class="text-xs text-slate-400 mt-1 mb-5">Generate license keys to distribute to workshops.</p>
            <button type="button" @click="openGenerateKeysModal = true" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                Generate Keys
            </button>
        </div>
        @endif
    </div>
</div>

{{-- ══════════════════════════════════════════ --}}
{{-- SETTINGS TAB --}}
{{-- ══════════════════════════════════════════ --}}
<div x-show="activeTab === 'settings'" class="space-y-5 animate-fade-in-up" x-cloak>
    @if(session('success') && request()->query('tab') === 'settings')
    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-2xl px-5 py-3.5">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-sm font-semibold text-emerald-700">{{ session('success') }}</p>
    </div>
    @endif
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center"><svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <div><h4 class="text-sm font-bold text-slate-900">Trial Period Configuration</h4><p class="text-xs text-slate-500">Set default trial duration for new garages.</p></div>
            </div>
            <form action="{{ route('super_admin.update_settings') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label for="default_trial_duration_main" class="block text-xs font-bold text-slate-700 mb-1.5">Default Trial Duration (Days) *</label>
                    <div class="flex items-center gap-3">
                        <input id="default_trial_duration_main" type="number" name="default_trial_duration" value="{{ $defaultTrialDuration }}" min="1" max="365" autocomplete="off"
                            class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-lg font-bold text-slate-900 focus:outline-none focus:border-amber-400 focus:bg-white transition-colors @error('default_trial_duration') border-red-400 @enderror" placeholder="Enter default trial duration">
                        <span class="text-sm font-semibold text-slate-500 shrink-0">days</span>
                    </div>
                    @error('default_trial_duration')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Quick Presets</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach([7=>'7d',14=>'14d',30=>'30d',60=>'60d',90=>'90d'] as $days=>$label)
                        <button type="button" onclick="this.closest('form').querySelector('input[name=default_trial_duration]').value={{ $days }}"
                            class="px-4 py-2 rounded-xl text-xs font-bold border transition-all {{ $defaultTrialDuration==$days ? 'bg-amber-500 text-white border-amber-500 shadow-sm' : 'bg-slate-50 text-slate-600 border-slate-200 hover:border-amber-300 hover:text-amber-700' }}">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>
                <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
                    <button type="submit" class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white" style="background: linear-gradient(135deg, #d97706, #b45309);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Configuration
                    </button>
                    <p class="text-xs text-slate-400">Applies to new workshops only.</p>
                </div>
            </form>
        </div>
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Platform Summary</h4>
                <div class="space-y-3">
                    @foreach([['Workshops',$totalWorkshops,'text-blue-600'],['Total Users',$totalUsers,'text-violet-600'],['Active Trials',$workshops->filter(fn($w)=>$w->isTrial())->count(),'text-amber-600'],['Product Keys',$totalProductKeys,'text-indigo-600']] as $s)
                    <div class="flex items-center justify-between py-2 border-b border-slate-50">
                        <span class="text-xs font-semibold text-slate-500">{{ $s[0] }}</span>
                        <span class="text-sm font-extrabold {{ $s[2] }}">{{ $s[1] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3">
                <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-[11px] text-amber-700 leading-relaxed font-medium">Changing this setting only affects newly created workshops. Existing workshops keep their current trial expiry dates.</p>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════ --}}
{{-- LOGS TAB --}}
{{-- ══════════════════════════════════════════ --}}
<div x-show="activeTab === 'logs'" class="space-y-5 animate-fade-in-up" x-cloak>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div><h3 class="text-base font-bold text-slate-900">Activity Logs</h3><p class="text-xs text-slate-500 mt-0.5">Complete audit trail of admin actions</p></div>
            @if(!$activityLogs->isEmpty())
            <form action="{{ route('super_admin.clear_logs') }}" method="POST" onsubmit="return confirm('Clear ALL activity logs permanently?')">
                @csrf @method('DELETE')
                <button type="submit" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-rose-700 border border-rose-200 bg-rose-50 hover:bg-rose-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Clear All Logs
                </button>
            </form>
            @endif
        </div>
        @if($activityLogs->isEmpty())
        <div class="text-center py-14"><p class="text-slate-500 font-semibold">No activity logs yet.</p></div>
        @else
        {{-- Desktop --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3">Time</th>
                        <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3">Action</th>
                        <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3">User</th>
                        <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3">Workshop</th>
                        <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3">Details</th>
                        <th class="text-right text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3">Del</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($activityLogs as $log)
                    @php $at = str_contains($log->action,'update')?'update':(str_contains($log->action,'delete')?'delete':'create'); @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-3.5 whitespace-nowrap"><p class="text-xs font-semibold text-slate-700">{{ $log->created_at->format('M d, Y') }}</p><p class="text-[10px] text-slate-400">{{ $log->created_at->format('h:i A') }}</p></td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-bold
                                {{ $at==='create' ? 'bg-emerald-50 text-emerald-700' : ($at==='delete' ? 'bg-rose-50 text-rose-700' : 'bg-blue-50 text-blue-700') }}">
                                {{ strtoupper(str_replace('_',' ',$log->action)) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-xs font-semibold text-slate-700 whitespace-nowrap">{{ $log->user?->name ?? 'System' }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-600 whitespace-nowrap">{{ $log->workshop?->name ?? 'Global' }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-600 max-w-xs">{{ $log->description }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <form action="{{ route('super_admin.destroy_log', $log) }}" method="POST" onsubmit="return confirm('Delete this log?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-rose-400 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- Mobile --}}
        <div class="block md:hidden divide-y divide-slate-100">
            @foreach($activityLogs as $log)
            @php $at = str_contains($log->action,'update')?'update':(str_contains($log->action,'delete')?'delete':'create'); @endphp
            <div class="p-4 flex items-start gap-3">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 mt-0.5 {{ $at==='create' ? 'bg-emerald-50' : ($at==='delete' ? 'bg-rose-50' : 'bg-blue-50') }}">
                    <span class="w-2 h-2 rounded-full {{ $at==='create' ? 'bg-emerald-500' : ($at==='delete' ? 'bg-rose-500' : 'bg-blue-500') }}"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <span class="text-[10px] font-bold {{ $at==='create' ? 'text-emerald-600' : ($at==='delete' ? 'text-rose-600' : 'text-blue-600') }} uppercase">{{ str_replace('_',' ',$log->action) }}</span>
                        <span class="text-[10px] text-slate-400">{{ $log->created_at->format('M d, h:i A') }}</span>
                    </div>
                    <p class="text-xs text-slate-600">{{ $log->description }}</p>
                    @if($log->user)<p class="text-[10px] text-slate-400 mt-1">by {{ $log->user->name }}</p>@endif
                </div>
                <form action="{{ route('super_admin.destroy_log', $log) }}" method="POST" onsubmit="return confirm('Delete?')" class="shrink-0">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-1.5 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

</div>{{-- close max-w-7xl --}}

{{-- ══════════════════════════════════════════ --}}
{{-- MODALS --}}
{{-- ══════════════════════════════════════════ --}}

{{-- Modal: Add Workshop --}}
<div x-show="openAddModal" x-cloak class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4" style="display:none;" role="dialog">
    <div class="absolute inset-0" style="background: rgba(15,23,42,0.7); backdrop-filter: blur(4px);" @click="openAddModal = false"></div>
    <div class="modal-enter relative bg-white w-full sm:rounded-3xl sm:max-w-2xl max-h-[95vh] sm:max-h-[90vh] shadow-2xl flex flex-col overflow-hidden z-10 rounded-t-3xl">
        <div class="shrink-0 flex justify-between items-center px-6 py-4" style="background: linear-gradient(135deg, #0f172a, #1e1b4b);">
            <div>
                <h3 class="text-base font-bold text-white">Register New Workshop</h3>
                <p class="text-xs text-indigo-300 mt-0.5">Garage profile + administrator account</p>
            </div>
            <button type="button" @click="openAddModal = false" class="w-8 h-8 rounded-xl flex items-center justify-center text-white/60 hover:text-white hover:bg-white/10 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('super_admin.store_workshop') }}" method="POST" class="flex flex-col flex-1 min-h-0" autocomplete="off">
            @csrf
            @if($showAddWorkshopModal)
            <div class="shrink-0 mx-5 mt-4 px-4 py-3 bg-rose-50 border border-rose-200 rounded-2xl">
                <p class="text-sm font-semibold text-rose-700">Please fix the errors below and try again.</p>
            </div>
            @endif
            <div class="flex-1 overflow-y-auto px-5 py-4 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <p class="text-xs font-extrabold text-indigo-600 uppercase tracking-widest border-b border-indigo-100 pb-2 mb-3">Workshop Details</p>
                    </div>
                    <div>
                        <label for="add_name" class="block text-xs font-bold text-slate-700 mb-1">Workshop Name *</label>
                        <input id="add_name" type="text" name="name" required placeholder="e.g. Speedsters Garage" value="{{ old('name') }}" autocomplete="organization" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-400 focus:bg-white transition-colors @error('name') border-rose-400 @enderror">
                        @error('name')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="add_phone" class="block text-xs font-bold text-slate-700 mb-1">Phone *</label>
                        <input id="add_phone" type="text" name="phone" required placeholder="+91 9988776655" value="{{ old('phone') }}" autocomplete="tel" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-400 focus:bg-white transition-colors @error('phone') border-rose-400 @enderror">
                        @error('phone')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="add_email" class="block text-xs font-bold text-slate-700 mb-1">Email</label>
                        <input id="add_email" type="email" name="email" placeholder="garage@example.com" value="{{ old('email') }}" autocomplete="email" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-400 focus:bg-white transition-colors @error('email') border-rose-400 @enderror">
                    </div>
                    <div>
                        <label for="add_gstin" class="block text-xs font-bold text-slate-700 mb-1">GSTIN</label>
                        <input id="add_gstin" type="text" name="gstin" placeholder="29XXXXXXXXXX1Z5" value="{{ old('gstin') }}" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-400 focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label for="add_status" class="block text-xs font-bold text-slate-700 mb-1">Subscription Status *</label>
                        <select id="add_status" name="subscription_status" required x-model="newWorkshopStatus" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-400 focus:bg-white transition-colors">
                            <option value="training">Training</option>
                            <option value="active">Active</option>
                            <option value="fix">Fix</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                    <div x-show="newWorkshopStatus === 'trial' || newWorkshopStatus === 'training' || newWorkshopStatus === 'active'" x-cloak>
                        <label for="add_trial" class="block text-xs font-bold text-slate-700 mb-1" x-text="(newWorkshopStatus === 'trial' || newWorkshopStatus === 'training') ? 'Training Expiration Date *' : 'Subscription Expiry (Optional)'"></label>
                        <input id="add_trial" type="datetime-local" name="trial_ends_at" value="{{ old('trial_ends_at', now()->addDays(14)->format('Y-m-d\TH:i')) }}" :required="newWorkshopStatus === 'trial' || newWorkshopStatus === 'training'" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-400 focus:bg-white transition-colors">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="add_address" class="block text-xs font-bold text-slate-700 mb-1">Address</label>
                        <textarea id="add_address" name="address" rows="2" placeholder="Street, city" autocomplete="street-address" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-400 focus:bg-white transition-colors resize-none">{{ old('address') }}</textarea>
                    </div>
                    <div class="sm:col-span-2 flex gap-4 flex-wrap">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="restrict_features_on_expiry" value="1" checked class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-xs font-semibold text-slate-700">Restrict features on expiry</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="admin_extend_allowed" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-xs font-semibold text-slate-700">Allow Admin to extend trial</span>
                        </label>
                    </div>

                    <div class="sm:col-span-2 pt-2">
                        <p class="text-xs font-extrabold text-indigo-600 uppercase tracking-widest border-b border-indigo-100 pb-2 mb-3">Administrator Account</p>
                    </div>
                    <div>
                        <label for="add_admin_name" class="block text-xs font-bold text-slate-700 mb-1">Admin Name *</label>
                        <input id="add_admin_name" type="text" name="admin_name" required placeholder="e.g. Rajesh Kumar" value="{{ old('admin_name') }}" autocomplete="name" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-400 focus:bg-white transition-colors @error('admin_name') border-rose-400 @enderror">
                        @error('admin_name')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="add_admin_email" class="block text-xs font-bold text-slate-700 mb-1">Admin Email *</label>
                        <input id="add_admin_email" type="email" name="admin_email" required placeholder="owner@garage.com" value="{{ old('admin_email') }}" autocomplete="email" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-400 focus:bg-white transition-colors @error('admin_email') border-rose-400 @enderror">
                        @error('admin_email')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="add_admin_password" class="block text-xs font-bold text-slate-700 mb-1">Admin Password *</label>
                        <input id="add_admin_password" type="password" name="admin_password" required placeholder="Min 8 characters" autocomplete="new-password" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-400 focus:bg-white transition-colors @error('admin_password') border-rose-400 @enderror">
                        @error('admin_password')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-2 pt-2">
                        <p class="text-xs font-extrabold text-amber-600 uppercase tracking-widest border-b border-amber-100 pb-2 mb-3">Notification Alert (Optional)</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="add_alert_message" class="block text-xs font-bold text-slate-700 mb-1">Alert Message</label>
                        <textarea id="add_alert_message" name="alert_message" rows="2" placeholder="Message to display to this garage" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-amber-400 focus:bg-white transition-colors resize-none">{{ old('alert_message') }}</textarea>
                    </div>
                    <div>
                        <label for="add_alert_expires_at" class="block text-xs font-bold text-slate-700 mb-1">Alert Expires At</label>
                        <input id="add_alert_expires_at" type="datetime-local" name="alert_expires_at" value="{{ old('alert_expires_at') }}" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-amber-400 focus:bg-white transition-colors">
                    </div>
                </div>
            </div>
            <div class="shrink-0 px-5 py-4 border-t border-slate-100 bg-white flex justify-end gap-3">
                <button type="button" @click="openAddModal = false" class="px-5 py-2.5 rounded-xl text-sm font-bold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">Cancel</button>
                <button type="submit" class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white" style="background: linear-gradient(135deg, #1e1b4b, #2563eb);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Create Workshop & Admin
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Edit Workshop --}}
<div x-show="openEditModal" x-cloak class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4" style="display:none;" role="dialog">
    <div class="absolute inset-0" style="background: rgba(15,23,42,0.7); backdrop-filter: blur(4px);" @click="openEditModal = false"></div>
    <div class="modal-enter relative bg-white w-full sm:rounded-3xl sm:max-w-2xl max-h-[95vh] sm:max-h-[90vh] shadow-2xl flex flex-col overflow-hidden z-10 rounded-t-3xl">
        <div class="shrink-0 flex justify-between items-center px-6 py-4" style="background: linear-gradient(135deg, #0c4a6e, #0369a1);">
            <div>
                <h3 class="text-base font-bold text-white">Edit Workshop</h3>
                <p class="text-xs text-sky-200 mt-0.5">Update garage profile & admin account</p>
            </div>
            <button type="button" @click="openEditModal = false" class="w-8 h-8 rounded-xl flex items-center justify-center text-white/60 hover:text-white hover:bg-white/10 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form :action="`/super-admin/workshops/${activeWorkshop.id}`" method="POST" class="flex flex-col flex-1 min-h-0" x-show="activeWorkshop.id" autocomplete="off">
            @csrf @method('PUT')
            <input type="hidden" name="workshop_id" x-model="activeWorkshop.id">
            <input type="hidden" name="admin_user_id" x-model="activeWorkshop.admin_user_id">
            @if($showEditWorkshopModal)
            <div class="shrink-0 mx-5 mt-4 px-4 py-3 bg-rose-50 border border-rose-200 rounded-2xl">
                <p class="text-sm font-semibold text-rose-700">Please fix the errors below.</p>
            </div>
            @endif
            <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2"><p class="text-xs font-extrabold text-sky-600 uppercase tracking-widest border-b border-sky-100 pb-2 mb-1">Workshop Details</p></div>
                    <div>
                        <label for="edit_name" class="block text-xs font-bold text-slate-700 mb-1">Workshop Name *</label>
                        <input id="edit_name" type="text" name="name" required x-model="activeWorkshop.name" autocomplete="organization" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-sky-400 focus:bg-white transition-colors @error('name') border-rose-400 @enderror" placeholder="Enter name">
                        @error('name')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="edit_phone" class="block text-xs font-bold text-slate-700 mb-1">Phone *</label>
                        <input id="edit_phone" type="text" name="phone" required x-model="activeWorkshop.phone" autocomplete="tel" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-sky-400 focus:bg-white transition-colors @error('phone') border-rose-400 @enderror" placeholder="Enter phone">
                        @error('phone')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="edit_email" class="block text-xs font-bold text-slate-700 mb-1">Email</label>
                        <input id="edit_email" type="email" name="email" x-model="activeWorkshop.email" autocomplete="email" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-sky-400 focus:bg-white transition-colors" placeholder="Enter email">
                    </div>
                    <div>
                        <label for="edit_gstin" class="block text-xs font-bold text-slate-700 mb-1">GSTIN</label>
                        <input id="edit_gstin" type="text" name="gstin" x-model="activeWorkshop.gstin" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-sky-400 focus:bg-white transition-colors" placeholder="Enter gstin">
                    </div>
                    <div>
                        <label for="edit_status" class="block text-xs font-bold text-slate-700 mb-1">Subscription Status *</label>
                        <select id="edit_status" name="subscription_status" required x-model="activeWorkshop.subscription_status" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-sky-400 focus:bg-white transition-colors">
                            <option value="training">Training</option>
                            <option value="active">Active</option>
                            <option value="fix">Fix</option>
                            <option value="suspended">Suspended</option>
                            <option value="trial" style="display:none;">Trial</option>
                        </select>
                    </div>
                    <div x-show="activeWorkshop.subscription_status === 'trial' || activeWorkshop.subscription_status === 'training' || activeWorkshop.subscription_status === 'active'" x-cloak>
                        <label for="edit_trial" class="block text-xs font-bold text-slate-700 mb-1" x-text="(activeWorkshop.subscription_status === 'trial' || activeWorkshop.subscription_status === 'training') ? 'Training Expiration Date *' : 'Subscription Expiry (Optional)'"></label>
                        <input id="edit_trial" type="datetime-local" name="trial_ends_at" x-model="activeWorkshop.trial_ends_at" :required="activeWorkshop.subscription_status === 'trial' || activeWorkshop.subscription_status === 'training'" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-sky-400 focus:bg-white transition-colors">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="edit_address" class="block text-xs font-bold text-slate-700 mb-1">Address</label>
                        <textarea id="edit_address" name="address" rows="2" x-model="activeWorkshop.address" autocomplete="street-address" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-sky-400 focus:bg-white transition-colors resize-none" placeholder="Enter address"></textarea>
                    </div>
                    <div class="sm:col-span-2 flex gap-4 flex-wrap">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="restrict_features_on_expiry" value="1" x-model="activeWorkshop.restrict_features_on_expiry" class="rounded border-slate-300 text-sky-600">
                            <span class="text-xs font-semibold text-slate-700">Restrict features on expiry</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="admin_extend_allowed" value="1" x-model="activeWorkshop.admin_extend_allowed" class="rounded border-slate-300 text-sky-600">
                            <span class="text-xs font-semibold text-slate-700">Allow Admin to extend trial</span>
                        </label>
                    </div>

                    <div class="sm:col-span-2 pt-2"><p class="text-xs font-extrabold text-sky-600 uppercase tracking-widest border-b border-sky-100 pb-2 mb-1">Administrator Account</p></div>
                    <div>
                        <label for="edit_admin_name" class="block text-xs font-bold text-slate-700 mb-1">Admin Name *</label>
                        <input id="edit_admin_name" type="text" name="admin_name" required x-model="activeWorkshop.admin_name" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-sky-400 focus:bg-white transition-colors @error('admin_name') border-rose-400 @enderror" placeholder="Enter admin name">
                        @error('admin_name')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="edit_admin_email" class="block text-xs font-bold text-slate-700 mb-1">Admin Email *</label>
                        <input id="edit_admin_email" type="email" name="admin_email" required x-model="activeWorkshop.admin_email" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-sky-400 focus:bg-white transition-colors @error('admin_email') border-rose-400 @enderror" placeholder="Enter admin email">
                        @error('admin_email')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="edit_admin_password" class="block text-xs font-bold text-slate-700 mb-1">New Password <span class="font-normal text-slate-400">(leave blank to keep current)</span></label>
                        <input id="edit_admin_password" type="password" name="admin_password" placeholder="Min 8 characters" autocomplete="new-password" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-sky-400 focus:bg-white transition-colors">
                    </div>

                    <div class="sm:col-span-2 pt-2"><p class="text-xs font-extrabold text-amber-600 uppercase tracking-widest border-b border-amber-100 pb-2 mb-1">Notification Alert</p></div>
                    <div class="sm:col-span-2">
                        <label for="edit_alert_message" class="block text-xs font-bold text-slate-700 mb-1">Alert Message</label>
                        <textarea id="edit_alert_message" name="alert_message" rows="2" x-model="activeWorkshop.alert_message" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-amber-400 focus:bg-white transition-colors resize-none" placeholder="Enter alert message"></textarea>
                    </div>
                    <div>
                        <label for="edit_alert_expires_at" class="block text-xs font-bold text-slate-700 mb-1">Alert Expires At</label>
                        <input id="edit_alert_expires_at" type="datetime-local" name="alert_expires_at" x-model="activeWorkshop.alert_expires_at" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-amber-400 focus:bg-white transition-colors">
                    </div>
                </div>
            </div>
            <div class="shrink-0 px-5 py-4 border-t border-slate-100 bg-white flex justify-end gap-3">
                <button type="button" @click="openEditModal = false" class="px-5 py-2.5 rounded-xl text-sm font-bold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">Cancel</button>
                <button type="submit" class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white" style="background: linear-gradient(135deg, #0c4a6e, #0369a1);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Workshop & Admin
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Generate Keys --}}
<div x-show="openGenerateKeysModal" x-cloak class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4" style="display:none;" role="dialog">
    <div class="absolute inset-0" style="background: rgba(15,23,42,0.7); backdrop-filter: blur(4px);" @click="openGenerateKeysModal = false"></div>
    <div class="modal-enter relative bg-white w-full sm:rounded-3xl sm:max-w-md shadow-2xl flex flex-col overflow-hidden z-10 rounded-t-3xl">
        <div class="shrink-0 flex justify-between items-center px-6 py-4" style="background: linear-gradient(135deg, #4c1d95, #6d28d9);">
            <div>
                <h3 class="text-base font-bold text-white">Generate Product Keys</h3>
                <p class="text-xs text-violet-300 mt-0.5">Create batch license keys for distribution</p>
            </div>
            <button type="button" @click="openGenerateKeysModal = false" class="w-8 h-8 rounded-xl flex items-center justify-center text-white/60 hover:text-white hover:bg-white/10 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('super_admin.store_product_key') }}" method="POST" class="p-5 space-y-4" x-data="{ durationType: '90', customDays: '45' }">
            @csrf
            <div>
                <label for="gen_duration" class="block text-xs font-bold text-slate-700 mb-1">License Duration *</label>
                <select id="gen_duration" x-model="durationType" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-violet-400">
                    <option value="30">30 Days (1 Month)</option>
                    <option value="90">90 Days (3 Months)</option>
                    <option value="180">180 Days (6 Months)</option>
                    <option value="365">365 Days (1 Year)</option>
                    <option value="custom">Custom Days...</option>
                </select>
            </div>
            <input type="hidden" name="duration_days" :value="durationType === 'custom' ? customDays : durationType">
            <div x-show="durationType === 'custom'" x-cloak>
                <label for="gen_custom" class="block text-xs font-bold text-slate-700 mb-1">Custom Duration (Days) *</label>
                <input id="gen_custom" type="number" x-model="customDays" min="1" max="10000" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-violet-400">
            </div>
            <div>
                <label for="gen_quantity" class="block text-xs font-bold text-slate-700 mb-1">Quantity to Generate *</label>
                <input id="gen_quantity" type="number" name="quantity" required min="1" max="100" value="5" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-violet-400" placeholder="Enter quantity">
                <p class="text-[11px] text-slate-400 mt-1">Maximum 100 per batch.</p>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="openGenerateKeysModal = false" class="px-5 py-2.5 rounded-xl text-sm font-bold border border-slate-200 text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white" style="background: linear-gradient(135deg, #4c1d95, #6d28d9);">Generate Batch</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Edit Product Key --}}
<div x-show="openEditKeyModal" x-cloak class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4" style="display:none;" role="dialog">
    <div class="absolute inset-0" style="background: rgba(15,23,42,0.7); backdrop-filter: blur(4px);" @click="openEditKeyModal = false"></div>
    <div class="modal-enter relative bg-white w-full sm:rounded-3xl sm:max-w-md shadow-2xl flex flex-col overflow-hidden z-10 rounded-t-3xl">
        <div class="shrink-0 flex justify-between items-center px-6 py-4" style="background: linear-gradient(135deg, #064e3b, #059669);">
            <div>
                <h3 class="text-base font-bold text-white">Edit Product Key</h3>
                <p class="font-mono text-xs text-emerald-200 mt-0.5" x-text="activeKey.key"></p>
            </div>
            <button type="button" @click="openEditKeyModal = false" class="w-8 h-8 rounded-xl flex items-center justify-center text-white/60 hover:text-white hover:bg-white/10 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form :action="`/super-admin/product-keys/${activeKey.id}`" method="POST" class="p-5 space-y-4"
              x-data="{ durationType: '90', customDays: '45' }"
              x-init="$watch('activeKey.duration_days', value => { if(['30','90','180','365'].includes(String(value))) { durationType = String(value); } else { durationType = 'custom'; customDays = String(value); } })">
            @csrf @method('PUT')
            <div>
                <label for="edit_key_duration" class="block text-xs font-bold text-slate-700 mb-1">License Duration *</label>
                <select id="edit_key_duration" x-model="durationType" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-emerald-400">
                    <option value="30">30 Days</option>
                    <option value="90">90 Days</option>
                    <option value="180">180 Days</option>
                    <option value="365">365 Days</option>
                    <option value="custom">Custom...</option>
                </select>
            </div>
            <input type="hidden" name="duration_days" :value="durationType === 'custom' ? customDays : durationType">
            <div x-show="durationType === 'custom'" x-cloak>
                <label for="edit_key_custom" class="block text-xs font-bold text-slate-700 mb-1">Custom Days</label>
                <input id="edit_key_custom" type="number" x-model="customDays" min="1" max="10000" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-emerald-400">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="openEditKeyModal = false" class="px-5 py-2.5 rounded-xl text-sm font-bold border border-slate-200 text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white" style="background: linear-gradient(135deg, #064e3b, #059669);">Save Changes</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Activate License (Super Admin Side) --}}
<div x-show="openActivateLicenseModal" x-cloak class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4" style="display:none;" role="dialog">
    <div class="absolute inset-0" style="background: rgba(15,23,42,0.7); backdrop-filter: blur(4px);" @click="openActivateLicenseModal = false"></div>
    <div class="modal-enter relative bg-white w-full sm:rounded-3xl sm:max-w-md shadow-2xl flex flex-col overflow-hidden z-10 rounded-t-3xl">
        <div class="shrink-0 flex justify-between items-center px-6 py-4" style="background: linear-gradient(135deg, #1d4ed8, #2563eb);">
            <div class="flex items-center gap-3">
                <div>
                    <h3 class="text-base font-bold text-white">Activate License</h3>
                    <p class="text-xs text-blue-100 mt-0.5" x-text="`For workshop: ${activeWorkshopToActivate.name}`"></p>
                </div>
            </div>
            <button type="button" @click="openActivateLicenseModal = false" class="w-8 h-8 rounded-xl flex items-center justify-center text-white/60 hover:text-white hover:bg-white/10 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <div x-data="{ mode: 'generate', durationType: '30', customDays: '45', productKeyVal: '' }" 
             x-init="$watch('openActivateLicenseModal', value => { if (value) { mode = 'generate'; durationType = '30'; customDays = '45'; productKeyVal = ''; } })"
             class="flex flex-col flex-1 min-h-0">
            
            <!-- Tabs Navigation -->
            <div class="flex border-b border-slate-100 px-5 pt-3 bg-slate-50 gap-2">
                <button type="button" 
                        @click="mode = 'generate'" 
                        :class="mode === 'generate' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800'"
                        class="px-4 py-2.5 text-xs border-b-2 font-semibold transition-all">
                    Auto-Generate & Activate
                </button>
                <button type="button" 
                        @click="mode = 'manual'" 
                        :class="mode === 'manual' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800'"
                        class="px-4 py-2.5 text-xs border-b-2 font-semibold transition-all">
                    Redeem Manual Key
                </button>
            </div>

            <form :action="`/super-admin/workshops/${activeWorkshopToActivate.id}/activate-license`" method="POST" class="p-5 space-y-4" autocomplete="off">
                @csrf
                
                <!-- Mode 1: Auto-Generate -->
                <div x-show="mode === 'generate'" class="space-y-4">
                    <div>
                        <label for="sa_duration" class="block text-xs font-bold text-slate-700 mb-1">License Duration *</label>
                        <select id="sa_duration" x-model="durationType" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-400 focus:bg-white transition-colors">
                            <option value="30">30 Days (1 Month)</option>
                            <option value="90">90 Days (3 Months)</option>
                            <option value="180">180 Days (6 Months)</option>
                            <option value="365">365 Days (1 Year)</option>
                            <option value="custom">Custom Days...</option>
                            <option value="exact_date">Exact Date & Time...</option>
                        </select>
                    </div>
                    {{-- Hidden field: only has a 'name' when in generate mode so it actually submits --}}
                    <input type="hidden" :name="mode === 'generate' ? 'duration_days' : ''" :value="durationType === 'custom' ? customDays : durationType">
                    
                    <div x-show="durationType === 'custom'" x-cloak>
                        <label for="sa_custom_days" class="block text-xs font-bold text-slate-700 mb-1">Custom Duration (Days) *</label>
                        <input id="sa_custom_days" type="number" x-model="customDays" min="1" max="10000" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-400 focus:bg-white transition-colors" placeholder="e.g. 45">
                    </div>

                    <div x-show="durationType === 'exact_date'" x-cloak>
                        <label for="sa_exact_date" class="block text-xs font-bold text-slate-700 mb-1">Exact Expiration Date & Time *</label>
                        <input id="sa_exact_date" type="datetime-local" :name="mode === 'generate' && durationType === 'exact_date' ? 'exact_date' : ''" autocomplete="off" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-400 focus:bg-white transition-colors">
                    </div>
                    <p class="text-[11px] text-slate-400">Selecting this option will automatically generate a new secure license key, redeem it, and update the workshop's subscription immediately.</p>
                </div>

                <!-- Mode 2: Manual Key -->
                <div x-show="mode === 'manual'" class="space-y-3" x-cloak
                     x-data="{
                        savedKeys: @json($productKeys->where('status','unused')->values()),
                        editingKeyId: null,
                        editingKeyVal: '',
                        selectKey(k) { productKeyVal = k.key; },
                        startEdit(k) { editingKeyId = k.id; editingKeyVal = k.key; },
                        cancelEdit() { editingKeyId = null; editingKeyVal = ''; },
                        saveEdit(k) {
                            fetch(`/super-admin/product-keys/${k.id}`, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-HTTP-Method-Override': 'PUT', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                                body: JSON.stringify({ key: editingKeyVal, duration_days: k.duration_days })
                            }).then(r => {
                                if (r.ok) { k.key = editingKeyVal; if (productKeyVal) productKeyVal = editingKeyVal; editingKeyId = null; }
                                else { alert('Save failed'); }
                            });
                        },
                        deleteKey(k, idx) {
                            if (!confirm('Delete key ' + k.key + '?')) return;
                            fetch(`/super-admin/product-keys/${k.id}`, {
                                method: 'POST',
                                headers: { 'X-HTTP-Method-Override': 'DELETE', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
                            }).then(r => {
                                if (r.ok) { savedKeys.splice(idx, 1); if (productKeyVal === k.key) productKeyVal = ''; }
                                else { alert('Delete failed'); }
                            });
                        }
                     }">

                    {{-- Saved keys list --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Activation Key *
                            <span class="ml-1 text-[10px] font-normal text-slate-400">(pick from saved or type below)</span>
                        </label>

                        <template x-if="savedKeys.length === 0">
                            <p class="text-[11px] text-slate-400 italic py-2">No unused keys available. Generate some first.</p>
                        </template>

                        <div x-show="savedKeys.length > 0" class="border border-slate-200 rounded-xl overflow-hidden max-h-44 overflow-y-auto divide-y divide-slate-100 mb-2">
                            <template x-for="(k, idx) in savedKeys" :key="k.id">
                                <div class="flex items-center gap-2 px-3 py-2 hover:bg-blue-50/50 transition-colors"
                                     :class="productKeyVal === k.key ? 'bg-blue-50 border-l-2 border-blue-500' : 'bg-white'">

                                    {{-- Editable key text or display --}}
                                    <template x-if="editingKeyId !== k.id">
                                        <button type="button" @click="selectKey(k)"
                                                class="flex-1 text-left font-mono font-bold text-xs text-slate-700 uppercase tracking-wider truncate"
                                                x-text="k.key"></button>
                                    </template>
                                    <template x-if="editingKeyId === k.id">
                                        <input type="text" x-model="editingKeyVal" @keydown.enter.prevent="saveEdit(k)" @keydown.escape.prevent="cancelEdit()"
                                               class="flex-1 font-mono font-bold text-xs uppercase tracking-wider px-2 py-1 border border-blue-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                                    </template>

                                    {{-- Duration badge --}}
                                    <span class="shrink-0 text-[9px] font-bold bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded-full" x-text="k.duration_days + 'd'"></span>

                                    {{-- Actions --}}
                                    <template x-if="editingKeyId !== k.id">
                                        <div class="flex items-center gap-1 shrink-0">
                                            {{-- Select --}}
                                            <button type="button" @click="selectKey(k)" title="Use this key"
                                                    class="w-6 h-6 flex items-center justify-center rounded-lg transition-colors"
                                                    :class="productKeyVal === k.key ? 'bg-blue-500 text-white' : 'text-slate-400 hover:text-blue-600 hover:bg-blue-50'">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                            {{-- Edit --}}
                                            <button type="button" @click="startEdit(k)" title="Edit key"
                                                    class="w-6 h-6 flex items-center justify-center rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            {{-- Delete --}}
                                            <button type="button" @click="deleteKey(k, idx)" title="Delete key"
                                                    class="w-6 h-6 flex items-center justify-center rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </template>
                                    <template x-if="editingKeyId === k.id">
                                        <div class="flex items-center gap-1 shrink-0">
                                            {{-- Save --}}
                                            <button type="button" @click="saveEdit(k)" title="Save"
                                                    class="w-6 h-6 flex items-center justify-center rounded-lg bg-emerald-500 text-white hover:bg-emerald-600 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                            {{-- Cancel --}}
                                            <button type="button" @click="cancelEdit()" title="Cancel"
                                                    class="w-6 h-6 flex items-center justify-center rounded-lg bg-slate-200 text-slate-600 hover:bg-slate-300 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    </template>

                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Selected / manual key input --}}
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Selected / Type Key</label>
                        <input id="sa_product_key" type="text" :name="mode === 'manual' ? 'product_key' : ''" x-model="productKeyVal" :required="mode === 'manual'" autocomplete="off"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-extrabold font-mono tracking-widest text-center uppercase placeholder:normal-case placeholder:tracking-normal focus:outline-none focus:border-blue-400 focus:bg-white transition-colors"
                               placeholder="SUHAIM-XXXX-XXXX-XXXX">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="openActivateLicenseModal = false" class="px-5 py-2.5 rounded-xl text-sm font-bold border border-slate-200 text-slate-600 hover:bg-slate-50">Cancel</button>
                    
                    <!-- Submit button for Generate -->
                    <button type="submit" x-show="mode === 'generate'" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:scale-105 active:scale-95 shadow-sm" style="background: linear-gradient(135deg, #1d4ed8, #2563eb);">
                        Generate & Activate
                    </button>
                    
                    <!-- Submit button for Manual Key -->
                    <button type="submit" x-show="mode === 'manual'" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:scale-105 active:scale-95 shadow-sm" style="background: linear-gradient(135deg, #1d4ed8, #2563eb);" x-cloak>
                        Redeem & Activate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>{{-- close x-data --}}

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('workshopAdminPanel', () => ({
        openAddModal: @json($showAddWorkshopModal),
        openEditModal: @json($showEditWorkshopModal),
        openGenerateKeysModal: false,
        openEditKeyModal: false,
        openActivateLicenseModal: false,
        activeTab: (new URLSearchParams(window.location.search)).get('tab') || 'dashboard',
        activeWorkshop: @json($initialActiveWorkshop),
        activeWorkshopToActivate: { id: '', name: '' },
        activeKey: { id: '', key: '', duration_days: 90 },
        newWorkshopStatus: 'trial',
        searchWorkshopQuery: '',
        searchKeyQuery: '',
        keyStatusFilter: 'all',

        matchesWorkshopSearch(name, phone, email, adminName, adminEmail) {
            let q = this.searchWorkshopQuery.toLowerCase().trim();
            if (!q) return true;
            return name.includes(q) || phone.includes(q) || email.includes(q) || adminName.includes(q) || adminEmail.includes(q);
        },
        matchesKeySearch(keyCode, status, workshopName) {
            if (this.keyStatusFilter !== 'all' && status !== this.keyStatusFilter) return false;
            let q = this.searchKeyQuery.toLowerCase().trim();
            if (!q) return true;
            return keyCode.includes(q) || workshopName.includes(q);
        },
        init() {
            this.$watch('openAddModal', () => this.syncBodyScroll());
            this.$watch('openEditModal', () => this.syncBodyScroll());
            this.$watch('openGenerateKeysModal', () => this.syncBodyScroll());
            this.$watch('openEditKeyModal', () => this.syncBodyScroll());
            this.$watch('openActivateLicenseModal', () => this.syncBodyScroll());
            this.syncBodyScroll();
        },
        syncBodyScroll() {
            document.body.classList.toggle('overflow-hidden', this.openAddModal || this.openEditModal || this.openGenerateKeysModal || this.openEditKeyModal || this.openActivateLicenseModal);
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
                alert_message: workshop.alert_message ?? '',
                alert_expires_at: workshop.alert_expires_at ?? '',
            };
            this.openAddModal = false;
            this.openEditModal = true;
        },
        openEditKey(key) {
            this.activeKey = { ...key };
            this.openEditKeyModal = true;
        },
        openActivateModal(workshop) {
            this.activeWorkshopToActivate = { ...workshop };
            this.openActivateLicenseModal = true;
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
