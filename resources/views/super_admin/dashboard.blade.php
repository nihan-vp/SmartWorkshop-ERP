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
        <div class="z-10 flex gap-2 shrink-0">
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
            <button type="button" x-show="activeTab === 'keys'" @click="openGenerateKeysModal = true" class="btn-primary shadow-sm !py-2.5 !px-5 text-sm z-10 !bg-violet-600 hover:!bg-violet-700 !border-violet-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-9 8a2 2 0 012-2m7-3a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                Generate Keys
            </button>
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
    <div class="flex items-center justify-start p-1.5 bg-slate-100/80 backdrop-blur-md rounded-2xl border border-slate-200/50 w-full sm:w-max shadow-sm shrink-0">
        <button type="button" 
                @click="activeTab = 'workshops'; window.history.replaceState(null, null, '?tab=workshops')" 
                :class="activeTab === 'workshops' ? 'bg-white text-primary-700 shadow-sm font-bold border border-slate-200/40' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs transition-all duration-200 flex-1 sm:flex-initial justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
            Garages & Workshops
        </button>
        <button type="button" 
                @click="activeTab = 'keys'; window.history.replaceState(null, null, '?tab=keys')" 
                :class="activeTab === 'keys' ? 'bg-white text-violet-700 shadow-sm font-bold border border-slate-200/40' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs transition-all duration-200 flex-1 sm:flex-initial justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-9 8a2 2 0 012-2m7-3a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
            Product Keys & Licenses
        </button>
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
                        <input type="text" x-model="searchWorkshopQuery" placeholder="Search garages..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200/80 rounded-xl text-xs font-medium focus:outline-none focus:border-primary-400 focus:bg-white transition-colors">
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
                                        Trial · Day {{ $workshop->getSubscriptionDay() }}
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
                                        Active · Day {{ $workshop->getSubscriptionDay() }}
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
                                    Trial · Day {{ $workshop->getSubscriptionDay() }}
                                </span>
                                @endif
                            @else
                                @if($workshop->isTrialExpired())
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    License Expired
                                </span>
                                @elseif($workshop->trial_ends_at)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Active · Day {{ $workshop->getSubscriptionDay() }}
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

    {{-- Product Keys Panel --}}
    <div x-show="activeTab === 'keys'" class="space-y-8 animate-fade-in-up" x-cloak>
        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="glass-card border-l-4 border-l-primary-500">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Product Keys</span>
                <p class="text-3xl font-extrabold text-slate-900 mt-2">{{ $totalProductKeys }}</p>
                <p class="text-xs text-slate-500 mt-3 font-medium">Generated licenses</p>
            </div>
            <div class="glass-card border-l-4 border-l-emerald-500">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Unused Keys</span>
                <p class="text-3xl font-extrabold text-slate-900 mt-2">{{ $unusedProductKeys }}</p>
                <p class="text-xs text-slate-500 mt-3 font-medium">Available for activation</p>
            </div>
            <div class="glass-card border-l-4 border-l-indigo-500">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Redeemed Keys</span>
                <p class="text-3xl font-extrabold text-slate-900 mt-2">{{ $usedProductKeys }}</p>
                <p class="text-xs text-slate-500 mt-3 font-medium">Activated by workshops</p>
            </div>
        </div>

        {{-- Product Keys Table --}}
        <div class="glass-card !p-0 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Product Keys & Licenses</h3>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Distribute secure alphanumeric key strings for subscription activations.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    {{-- Search Input --}}
                    <div class="relative flex-1 min-w-[200px] lg:w-64">
                        <input type="text" x-model="searchKeyQuery" placeholder="Search keys or garages..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200/80 rounded-xl text-xs font-medium focus:outline-none focus:border-primary-400 focus:bg-white transition-colors">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    {{-- Filter Pills --}}
                    <div class="flex bg-slate-100/80 p-1 rounded-xl border border-slate-200/40 text-xs font-semibold shrink-0">
                        <button type="button" @click="keyStatusFilter = 'all'" :class="keyStatusFilter === 'all' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/30' : 'text-slate-500 hover:text-slate-800'" class="px-3 py-1.5 rounded-lg transition-all">All</button>
                        <button type="button" @click="keyStatusFilter = 'unused'" :class="keyStatusFilter === 'unused' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/30' : 'text-slate-500 hover:text-slate-800'" class="px-3 py-1.5 rounded-lg transition-all">Unused</button>
                        <button type="button" @click="keyStatusFilter = 'used'" :class="keyStatusFilter === 'used' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/30' : 'text-slate-500 hover:text-slate-800'" class="px-3 py-1.5 rounded-lg transition-all">Redeemed</button>
                    </div>
                    <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-2.5 rounded-xl whitespace-nowrap shrink-0">{{ $totalProductKeys }} total keys</span>
                </div>
            </div>

            @if($productKeys->isEmpty())
            <div class="text-center py-16 px-6 bg-white">
                <svg class="w-14 h-14 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m-9 8a2 2 0 012-2m7-3a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                <p class="text-slate-600 font-semibold">No product keys generated yet</p>
                <p class="text-sm text-slate-400 mt-1 mb-6">Create keys in bulk with select durations (30, 90, 365 days) or custom durations.</p>
                <button type="button" @click="openGenerateKeysModal = true" class="btn-primary text-sm !bg-violet-600 hover:!bg-violet-700 !border-violet-600">Generate First Batch</button>
            </div>
            @else
            {{-- Desktop View --}}
            <div class="hidden md:block overflow-x-auto relative z-0">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Product Key</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Redeemed By</th>
                            <th>Created At</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productKeys as $key)
                        <tr x-show="matchesKeySearch('{{ strtolower($key->key) }}', '{{ $key->isUsed() ? 'used' : 'unused' }}', '{{ strtolower($key->workshop?->name ?? '') }}')" x-transition>
                            <td class="whitespace-nowrap">
                                <div class="flex items-center gap-2" x-data="{ copied: false }">
                                    <span class="font-bold font-mono text-xs text-slate-800 tracking-wide bg-slate-50 border border-slate-200/60 px-3 py-1.5 rounded-lg select-all">
                                        {{ $key->key }}
                                    </span>
                                    <button type="button" 
                                            @click="navigator.clipboard.writeText('{{ $key->key }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                            class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-all relative" 
                                            title="Copy Key">
                                        <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                        <svg x-show="copied" x-cloak class="w-4 h-4 text-emerald-500 scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span x-show="copied" x-cloak class="absolute bottom-full left-1/2 transform -translate-x-1/2 -translate-y-1 px-2 py-1 bg-slate-900 text-white text-[10px] font-bold rounded shadow-md pointer-events-none whitespace-nowrap z-50 animate-bounce">
                                            Copied!
                                        </span>
                                    </button>
                                </div>
                            </td>
                            <td class="font-bold text-slate-700 whitespace-nowrap">{{ $key->duration_days }} Days</td>
                            <td class="whitespace-nowrap">
                                @if($key->isUsed())
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500">
                                    Redeemed
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Unused
                                </span>
                                @endif
                            </td>
                            <td class="min-w-[200px]">
                                @if($key->isUsed() && $key->workshop)
                                <div class="text-xs font-bold text-slate-800 truncate">{{ $key->workshop->name }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">Redeemed on {{ $key->used_at->format('M d, Y') }}</div>
                                @else
                                <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="text-xs text-slate-400 whitespace-nowrap">{{ $key->created_at->format('M d, Y') }}</td>
                            <td class="whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    @if(!$key->isUsed())
                                    <button type="button" @click="openEditKey(@js([
                                        'id' => $key->id,
                                        'key' => $key->key,
                                        'duration_days' => $key->duration_days
                                    ]))" class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-all" title="Edit Key">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('super_admin.destroy_product_key', $key) }}" method="POST" onsubmit="return confirm('Delete this unused product key permanently?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-all" title="Delete Key">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-xs text-slate-300 font-semibold px-2">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards View --}}
            <div class="block md:hidden divide-y divide-slate-100 bg-white">
                @foreach($productKeys as $key)
                <div class="p-5 space-y-4" x-show="matchesKeySearch('{{ strtolower($key->key) }}', '{{ $key->isUsed() ? 'used' : 'unused' }}', '{{ strtolower($key->workshop?->name ?? '') }}')" x-transition>
                    <!-- Key and Duration Header -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0" x-data="{ copied: false }">
                            <div class="flex items-center gap-2">
                                <span class="font-bold font-mono text-xs text-slate-800 tracking-wide bg-slate-50 border border-slate-200/60 px-3 py-1.5 rounded-lg select-all block w-max">
                                    {{ $key->key }}
                                </span>
                                <button type="button" 
                                        @click="navigator.clipboard.writeText('{{ $key->key }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                        class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-all relative" 
                                        title="Copy Key">
                                    <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                    <svg x-show="copied" x-cloak class="w-3.5 h-3.5 text-emerald-500 scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span x-show="copied" x-cloak class="absolute bottom-full left-1/2 transform -translate-x-1/2 -translate-y-1 px-2 py-1 bg-slate-900 text-white text-[9px] font-bold rounded shadow pointer-events-none whitespace-nowrap z-50">
                                        Copied!
                                    </span>
                                </button>
                            </div>
                            <span class="text-[11px] text-slate-400 block mt-2 font-medium">Created: {{ $key->created_at->format('M d, Y') }}</span>
                        </div>
                        
                        <!-- Status Badge -->
                        <div>
                            @if($key->isUsed())
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">
                                Redeemed
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Unused
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Details Row -->
                    <div class="grid grid-cols-2 gap-4 bg-slate-50/50 border border-slate-100 p-3 rounded-xl text-xs">
                        <div>
                            <span class="text-slate-400 font-semibold block uppercase tracking-wider text-[9px] mb-0.5">Duration</span>
                            <span class="font-bold text-slate-700">{{ $key->duration_days }} Days</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block uppercase tracking-wider text-[9px] mb-0.5">Redeemed By</span>
                            @if($key->isUsed() && $key->workshop)
                            <span class="font-bold text-slate-800 block truncate" title="{{ $key->workshop->name }}">{{ $key->workshop->name }}</span>
                            @else
                            <span class="text-slate-400 font-bold">—</span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Controls -->
                    @if(!$key->isUsed())
                    <div class="flex items-center gap-2 pt-3 border-t border-slate-100 w-full justify-end">
                        <button type="button" @click="openEditKey(@js([
                            'id' => $key->id,
                            'key' => $key->key,
                            'duration_days' => $key->duration_days
                        ]))" class="btn-secondary !py-1.5 !px-3.5 !rounded-lg !text-xs flex-1 sm:flex-initial justify-center shadow-sm" title="Edit Key">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </button>
                        <form action="{{ route('super_admin.destroy_product_key', $key) }}" method="POST" onsubmit="return confirm('Delete this unused product key permanently?')" class="inline flex-1 sm:flex-initial">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger !py-1.5 !px-3.5 !rounded-lg !text-xs !bg-red-50 hover:!bg-red-100 !text-red-600 !border-red-100 w-full justify-center shadow-sm" title="Delete Key">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Delete
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                @endforeach
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
                                <label class="form-label text-xs">Workshop name *</label>
                                <input type="text" name="name" required placeholder="e.g. Speedsters Garage" value="{{ old('name') }}" class="form-input @error('name') border-red-400 @enderror">
                                @error('name')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-xs">Phone *</label>
                                <input type="text" name="phone" required placeholder="e.g. +91 9988776655" value="{{ old('phone') }}" class="form-input @error('phone') border-red-400 @enderror">
                                @error('phone')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-xs">Email</label>
                                <input type="email" name="email" placeholder="garage@example.com" value="{{ old('email') }}" class="form-input @error('email') border-red-400 @enderror">
                                @error('email')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-xs">GSTIN</label>
                                <input type="text" name="gstin" placeholder="29XXXXXXXXXX1Z5" value="{{ old('gstin') }}" class="form-input @error('gstin') border-red-400 @enderror">
                                @error('gstin')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-xs">Subscription Status *</label>
                                <select name="subscription_status" required x-model="newWorkshopStatus" class="form-input @error('subscription_status') border-red-400 @enderror">
                                    <option value="trial">Free Trial</option>
                                    <option value="active">Active</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                                @error('subscription_status')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div x-show="newWorkshopStatus === 'trial' || newWorkshopStatus === 'active'" x-cloak class="animate-fade-in-up">
                                <label class="form-label text-xs" x-text="newWorkshopStatus === 'trial' ? 'Trial Expiration Date *' : 'Subscription Expiration Date (Optional)'"></label>
                                <input type="datetime-local" name="trial_ends_at" value="{{ old('trial_ends_at', now()->addDays(14)->format('Y-m-d\TH:i')) }}" :required="newWorkshopStatus === 'trial'" class="form-input @error('trial_ends_at') border-red-400 @enderror">
                                @error('trial_ends_at')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="space-y-3.5">
                            <p class="text-xs font-bold text-primary-600 uppercase tracking-wider pb-1 border-b border-slate-100">Administrator</p>
                            <div>
                                <label class="form-label text-xs">Admin name *</label>
                                <input type="text" name="admin_name" required placeholder="e.g. Rajesh Kumar" value="{{ old('admin_name') }}" autocomplete="off" class="form-input @error('admin_name') border-red-400 @enderror">
                                @error('admin_name')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-xs">Admin email *</label>
                                <input type="email" name="admin_email" required placeholder="owner@garage.com" value="{{ old('admin_email') }}" autocomplete="off" class="form-input @error('admin_email') border-red-400 @enderror">
                                @error('admin_email')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                                <p class="text-[11px] text-slate-500 mt-1">Unique — not your super admin login email.</p>
                            </div>
                            <div>
                                <label class="form-label text-xs">Admin password *</label>
                                <input type="password" name="admin_password" required placeholder="Min 8 characters" autocomplete="new-password" class="form-input @error('admin_password') border-red-400 @enderror">
                                @error('admin_password')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <label class="form-label text-xs">Address</label>
                            <textarea name="address" rows="2" placeholder="Street, city" class="form-input @error('address') border-red-400 @enderror">{{ old('address') }}</textarea>
                            @error('address')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2 space-y-3.5">
                            <p class="text-xs font-bold text-amber-600 uppercase tracking-wider pb-1 border-b border-amber-100">Notification Alert</p>
                            <div>
                                <label class="form-label text-xs">Alert Message</label>
                                <textarea name="alert_message" rows="2" placeholder="Message to display to this garage" class="form-input @error('alert_message') border-red-400 @enderror">{{ old('alert_message') }}</textarea>
                                @error('alert_message')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-xs">Alert Expires At</label>
                                <input type="datetime-local" name="alert_expires_at" value="{{ old('alert_expires_at') }}" class="form-input @error('alert_expires_at') border-red-400 @enderror">
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
                                <label class="form-label text-xs">Workshop name *</label>
                                <input type="text" name="name" required placeholder="e.g. Speedsters Garage" x-model="activeWorkshop.name" class="form-input @error('name') border-red-400 @enderror">
                                @error('name')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-xs">Phone *</label>
                                <input type="text" name="phone" required placeholder="e.g. +91 9988776655" x-model="activeWorkshop.phone" class="form-input @error('phone') border-red-400 @enderror">
                                @error('phone')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-xs">Email</label>
                                <input type="email" name="email" placeholder="garage@example.com" x-model="activeWorkshop.email" class="form-input @error('email') border-red-400 @enderror">
                                @error('email')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-xs">GSTIN</label>
                                <input type="text" name="gstin" placeholder="29XXXXXXXXXX1Z5" x-model="activeWorkshop.gstin" class="form-input @error('gstin') border-red-400 @enderror">
                                @error('gstin')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-xs">Subscription Status *</label>
                                <select name="subscription_status" required x-model="activeWorkshop.subscription_status" class="form-input @error('subscription_status') border-red-400 @enderror">
                                    <option value="trial">Free Trial</option>
                                    <option value="active">Active</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                                @error('subscription_status')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div x-show="activeWorkshop.subscription_status === 'trial' || activeWorkshop.subscription_status === 'active'" x-cloak class="animate-fade-in-up">
                                <label class="form-label text-xs" x-text="activeWorkshop.subscription_status === 'trial' ? 'Trial Expiration Date *' : 'Subscription Expiration Date (Optional)'"></label>
                                <input type="datetime-local" name="trial_ends_at" x-model="activeWorkshop.trial_ends_at" :required="activeWorkshop.subscription_status === 'trial'" class="form-input @error('trial_ends_at') border-red-400 @enderror">
                                @error('trial_ends_at')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="space-y-3.5">
                            <p class="text-xs font-bold text-primary-600 uppercase tracking-wider pb-1 border-b border-slate-100">Administrator</p>
                            <div>
                                <label class="form-label text-xs">Admin name *</label>
                                <input type="text" name="admin_name" required placeholder="e.g. Rajesh Kumar" x-model="activeWorkshop.admin_name" autocomplete="off" class="form-input @error('admin_name') border-red-400 @enderror">
                                @error('admin_name')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-xs">Admin email *</label>
                                <input type="email" name="admin_email" required placeholder="owner@garage.com" x-model="activeWorkshop.admin_email" autocomplete="off" class="form-input @error('admin_email') border-red-400 @enderror">
                                @error('admin_email')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                                <p class="text-[11px] text-slate-500 mt-1">Unique — not your super admin login email.</p>
                            </div>
                            <div>
                                <label class="form-label text-xs">Admin password</label>
                                <input type="password" name="admin_password" placeholder="Min 8 characters" autocomplete="new-password" class="form-input @error('admin_password') border-red-400 @enderror">
                                @error('admin_password')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                                <p class="text-[11px] text-slate-500 mt-1">Leave blank to keep the current password.</p>
                            </div>
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <label class="form-label text-xs">Address</label>
                            <textarea name="address" rows="2" placeholder="Street, city" x-model="activeWorkshop.address" class="form-input @error('address') border-red-400 @enderror"></textarea>
                            @error('address')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2 space-y-3.5">
                            <p class="text-xs font-bold text-amber-600 uppercase tracking-wider pb-1 border-b border-amber-100">Notification Alert</p>
                            <div>
                                <label class="form-label text-xs">Alert Message</label>
                                <textarea name="alert_message" rows="2" placeholder="Message to display to this garage" x-model="activeWorkshop.alert_message" class="form-input @error('alert_message') border-red-400 @enderror"></textarea>
                                @error('alert_message')<p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label text-xs">Alert Expires At</label>
                                <input type="datetime-local" name="alert_expires_at" x-model="activeWorkshop.alert_expires_at" class="form-input @error('alert_expires_at') border-red-400 @enderror">
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
                    <label class="form-label text-xs">License Duration *</label>
                    <select x-model="durationType" class="form-input">
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
                    <label class="form-label text-xs">Custom Duration (Days) *</label>
                    <input type="number" x-model="customDays" min="1" max="10000" class="form-input">
                    <p class="text-[11px] text-slate-400 mt-1">Specify any exact number of days (e.g. 15, 45, or 730 days).</p>
                </div>

                <div>
                    <label class="form-label text-xs">Quantity to Generate *</label>
                    <input type="number" name="quantity" required min="1" max="100" value="5" class="form-input">
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
                    <label class="form-label text-xs">License Duration *</label>
                    <select x-model="durationType" class="form-input">
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
                    <label class="form-label text-xs">Custom Duration (Days) *</label>
                    <input type="number" x-model="customDays" min="1" max="10000" class="form-input">
                    <p class="text-[11px] text-slate-400 mt-1">Specify any exact number of days (e.g. 15, 45, or 730 days).</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="openEditKeyModal = false" class="btn-secondary text-sm">Cancel</button>
                    <button type="submit" class="btn-primary text-sm !bg-violet-600 hover:!bg-violet-700 !border-violet-600">Save Changes</button>
                </div>
            </form>
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
        activeTab: (new URLSearchParams(window.location.search)).get('tab') || 'workshops',
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
