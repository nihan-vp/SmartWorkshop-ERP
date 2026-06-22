@extends('layouts.app')

@section('title', 'Super Admin Dashboard')
@section('page-title', 'Control Panel')
@section('page-subtitle', 'System management')

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
    <div class="max-w-7xl mx-auto space-y-6">
        
        {{-- Hero Header --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">System Control Panel</h1>
                <p class="text-slate-500 text-sm mt-1">Manage garages, users, licenses, and global settings.</p>
            </div>
            <div class="flex gap-3">
                <button x-show="activeTab === 'workshops'" @click="openAdd()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                    + Add Workshop
                </button>
                <button x-show="activeTab === 'keys'" @click="openGenerateKeysModal = true" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                    + Add Key
                </button>
            </div>
        </div>

        {{-- Navigation Tabs --}}
        <div class="flex flex-wrap gap-2 border-b border-slate-200">
            @php
                $tabs = [
                    ['id' => 'dashboard', 'label' => 'Dashboard'],
                    ['id' => 'workshops', 'label' => 'Garages'],
                    ['id' => 'keys', 'label' => 'License Keys'],
                    ['id' => 'settings', 'label' => 'Settings'],
                    ['id' => 'logs', 'label' => 'Activity Logs'],
                ];
            @endphp
            @foreach($tabs as $tab)
            <button @click="activeTab = '{{ $tab['id'] }}'; window.history.replaceState(null, null, '?tab={{ $tab['id'] }}')"
                :class="activeTab === '{{ $tab['id'] }}' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                class="px-4 py-2 text-sm font-semibold border-b-2 transition-colors">
                {{ $tab['label'] }}
            </button>
            @endforeach
        </div>

        {{-- DASHBOARD TAB --}}
        <div x-show="activeTab === 'dashboard'" x-cloak class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col items-center justify-center">
                    <p class="text-slate-500 text-xs font-semibold uppercase tracking-wide">Garages</p>
                    <p class="text-3xl font-bold text-slate-800 mt-2">{{ $totalWorkshops }}</p>
                </div>
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col items-center justify-center">
                    <p class="text-slate-500 text-xs font-semibold uppercase tracking-wide">Users</p>
                    <p class="text-3xl font-bold text-slate-800 mt-2">{{ $totalUsers }}</p>
                </div>
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col items-center justify-center">
                    <p class="text-slate-500 text-xs font-semibold uppercase tracking-wide">Super Admins</p>
                    <p class="text-3xl font-bold text-slate-800 mt-2">{{ $totalSuperAdmins }}</p>
                </div>
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col items-center justify-center">
                    <p class="text-slate-500 text-xs font-semibold uppercase tracking-wide">Available Keys</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-2">{{ $unusedProductKeys }}</p>
                </div>
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col items-center justify-center">
                    <p class="text-slate-500 text-xs font-semibold uppercase tracking-wide">Keys Redeemed</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $usedProductKeys }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Recent Garages --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
                    <div class="p-4 border-b border-slate-200 flex justify-between items-center">
                        <h3 class="font-bold text-slate-800">Recent Garages</h3>
                        <button @click="activeTab='workshops'" class="text-sm text-blue-600 hover:underline">View All</button>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($workshops as $w)
                        <div class="p-4 flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-slate-800">{{ $w->name }}</p>
                                <p class="text-xs text-slate-500">{{ $w->phone }} | {{ $w->email }}</p>
                                @if(in_array($w->subscription_status, ['trial', 'training']) && $w->trial_ends_at)
                                <div class="mt-1.5 text-xs font-medium text-slate-600 bg-slate-50 border border-slate-100 rounded-lg p-2 shadow-sm max-w-xs">
                                    <span class="font-bold text-amber-800">
                                        {{ $w->subscription_status === 'training' ? 'Training' : 'Trial' }} Status:
                                    </span>
                                    <span data-countdown-id="{{ $w->id }}" class="font-semibold text-slate-700">Loading...</span>
                                    <span class="text-[10px] text-slate-400 block mt-0.5">
                                        (Expires: <span data-expiry-id="{{ $w->id }}"></span>)
                                    </span>
                                </div>
                                @endif
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $w->subscription_status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-800' }}">
                                {{ ucfirst($w->subscription_status) }}
                            </span>
                        </div>
                        @empty
                        <p class="p-4 text-sm text-slate-500">No garages found.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Recent Activity --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
                    <div class="p-4 border-b border-slate-200 flex justify-between items-center">
                        <h3 class="font-bold text-slate-800">Recent Activity</h3>
                        <button @click="activeTab='logs'" class="text-sm text-blue-600 hover:underline">View All</button>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($activityLogs as $log)
                        <div class="p-4">
                            <p class="text-sm text-slate-800">{{ $log->description }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $log->created_at->diffForHumans() }}</p>
                        </div>
                        @empty
                        <p class="p-4 text-sm text-slate-500">No activity yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- WORKSHOPS TAB --}}
        <div x-show="activeTab === 'workshops'" x-cloak>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <form method="GET" class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                        <input type="hidden" name="tab" value="workshops">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search garages..." class="w-full sm:w-64 px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-lg text-sm font-semibold transition-colors">Search</button>
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3">Garage Name</th>
                                <th class="px-4 py-3">Contact</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Admin</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($workshops as $workshop)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $workshop->name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $workshop->phone }}<br><span class="text-xs text-slate-400">{{ $workshop->email }}</span></td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $workshop->subscription_status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-800' }}">
                                        {{ ucfirst($workshop->subscription_status) }}
                                    </span>
                                    @if(in_array($workshop->subscription_status, ['trial', 'training']) && $workshop->trial_ends_at)
                                    <div class="mt-1.5 text-xs font-medium text-slate-600 bg-slate-50 border border-slate-100 rounded-lg p-2 max-w-xs shadow-sm">
                                        <p class="font-bold text-amber-800">
                                            {{ $workshop->subscription_status === 'training' ? 'Training' : 'Trial' }} Status:
                                        </p>
                                        <p data-countdown-id="{{ $workshop->id }}" class="font-semibold text-slate-700 mt-0.5">Loading...</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">
                                            (Expires: <span data-expiry-id="{{ $workshop->id }}"></span>)
                                        </p>
                                    </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    @if($workshop->users->first())
                                        {{ $workshop->users->first()->name }}<br>
                                        <span class="text-xs text-slate-400">{{ $workshop->users->first()->email }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <button @click="openEdit(@js(['id'=>$workshop->id,'name'=>$workshop->name,'phone'=>$workshop->phone,'email'=>$workshop->email,'gstin'=>$workshop->gstin,'address'=>$workshop->address,'subscription_status'=>$workshop->subscription_status,'trial_ends_at'=>$workshop->trial_ends_at?$workshop->trial_ends_at->format('Y-m-d\TH:i'):'','alert_message'=>$workshop->alert_message,'alert_expires_at'=>$workshop->alert_expires_at?$workshop->alert_expires_at->format('Y-m-d\TH:i'):'','admin_user_id'=>$workshop->users->first()?->id,'admin_name'=>$workshop->users->first()?->name,'admin_email'=>$workshop->users->first()?->email]))" class="text-slate-600 hover:underline text-xs font-semibold">Edit</button>
                                    <form action="{{ route('super_admin.destroy_workshop', $workshop) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this garage and all its associated data?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:underline text-xs font-semibold">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-slate-500">
                                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <p class="font-medium text-slate-600">No garages found.</p>
                                    <p class="text-sm mt-1">Click "+ Add Workshop" to create one.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-200">
                    {{ $workshops->links() }}
                </div>
            </div>
        </div>

        {{-- KEYS TAB --}}
        <div x-show="activeTab === 'keys'" x-cloak>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">License Keys</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3">Key</th>
                                <th class="px-4 py-3">Duration (Days)</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Used By</th>
                                <th class="px-4 py-3">Generated</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($productKeys as $key)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-mono font-medium text-slate-800">{{ $key->key }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $key->duration_days }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $key->status === 'unused' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-800' }}">
                                        {{ ucfirst($key->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ $key->workshop ? $key->workshop->name : '-' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $key->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <button @click="openEditKeyModalFn(@js(['id'=>$key->id,'duration_days'=>$key->duration_days,'key'=>$key->key]))" class="text-blue-600 hover:underline text-xs font-semibold">Edit</button>
                                    <form action="{{ route('super_admin.destroy_product_key', $key) }}" method="POST" class="inline" onsubmit="return confirm('Delete this license key?');">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:underline text-xs font-semibold">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-slate-500">
                                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                    <p class="font-medium text-slate-600">No license keys found.</p>
                                    <p class="text-sm mt-1">Generate new keys to see them here.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-200">
                    {{ $productKeys->links() }}
                </div>
            </div>
        </div>

        {{-- SETTINGS TAB --}}
        <div x-show="activeTab === 'settings'" x-cloak>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-bold text-slate-800 mb-4">System Settings</h3>
                <form action="{{ route('super_admin.update_settings') }}" method="POST" class="max-w-md space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Default Trial Duration (Days)</label>
                        <input type="number" name="default_trial_duration" value="{{ $defaultTrialDuration }}" min="0" class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">Save Settings</button>
                </form>
            </div>
        </div>

        {{-- LOGS TAB --}}
        <div x-show="activeTab === 'logs'" x-cloak>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Activity Logs</h3>
                    <form action="{{ route('super_admin.clear_logs') }}" method="POST" onsubmit="return confirm('Clear all logs?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:underline">Clear Logs</button>
                    </form>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($activityLogs as $log)
                    <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <p class="text-sm font-medium text-slate-800">{{ $log->description }}</p>
                            <p class="text-xs text-slate-500">{{ $log->action }} | By: {{ $log->user ? $log->user->name : 'System' }}</p>
                        </div>
                        <span class="text-xs text-slate-400 whitespace-nowrap">{{ $log->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    @empty
                    <p class="p-4 text-sm text-slate-500">No activity logs found.</p>
                    @endforelse
                </div>
                <div class="p-4 border-t border-slate-200">
                    {{ $activityLogs->links() }}
                </div>
            </div>
        </div>

    </div>

    {{-- MODALS --}}

    {{-- Add Workshop Modal --}}
    <div x-show="openAddModal" x-cloak
         class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-slate-900/50 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click.self="openAddModal = false">
        <div class="bg-white w-full h-full sm:h-auto sm:rounded-2xl sm:max-w-xl sm:max-h-[92vh] flex flex-col shadow-2xl"
             x-transition:enter="transition ease-out duration-200 transform" x-transition:enter-start="translate-y-full sm:translate-y-4 sm:scale-95" x-transition:enter-end="translate-y-0 sm:scale-100">

            {{-- Sticky Header --}}
            <div class="flex-shrink-0 flex items-center justify-between px-5 py-4 border-b border-slate-100 bg-white">
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Add New Garage</h3>
                        <p class="text-xs text-slate-400">Register workshop &amp; admin account</p>
                    </div>
                </div>
                <button @click="openAddModal = false"
                        class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors text-xl leading-none flex-shrink-0">&times;</button>
            </div>

            {{-- Scrollable Body --}}
            <form action="{{ route('super_admin.store_workshop') }}" method="POST" class="flex-1 flex flex-col min-h-0">
                @csrf
                <div class="flex-1 overflow-y-auto">

                    {{-- Garage Details --}}
                    <div class="px-5 pt-5 pb-5">
                        <div class="flex items-center gap-2 mb-4">
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-widest">Garage Details</p>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Garage Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" required
                                       class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white transition-all placeholder-slate-400"
                                       placeholder="e.g. Suhaim Auto Garage">
                                @error('name') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Phone <span class="text-rose-500">*</span></label>
                                    <input type="text" name="phone" required
                                           class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white transition-all placeholder-slate-400"
                                           placeholder="9876543210">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email</label>
                                    <input type="email" name="email"
                                           class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white transition-all placeholder-slate-400"
                                           placeholder="info@garage.com">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status <span class="text-rose-500">*</span></label>
                                    <select name="subscription_status" x-model="newWorkshop.subscription_status" @change="handleStatusChange($event, 'Add')"
                                            class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white transition-all cursor-pointer">
                                        <option value="training">Training</option>
                                        <option value="trial">Trial</option>
                                        <option value="active">Active</option>
                                        <option value="suspended">Suspended</option>
                                        <option value="fix">Fix</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ends At</label>
                                    <input type="datetime-local" name="trial_ends_at" x-model="newWorkshop.trial_ends_at" @input="handleDateChange($event, 'Add')"
                                           class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Warning Banner --}}
                    <div class="px-5 py-5 border-t border-slate-100 bg-orange-50/40">
                        <div class="flex items-center gap-2 mb-4">
                            <p class="text-xs font-bold text-orange-600 uppercase tracking-widest">Warning Notification</p>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Warning Message</label>
                                <input type="text" name="alert_message" x-model="newWorkshop.alert_message"
                                       class="w-full border border-orange-200 bg-white rounded-xl px-4 py-3 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/20 focus:border-orange-400 transition-all placeholder-slate-400"
                                       placeholder="e.g. Your subscription is expiring soon!">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Warning Expires At</label>
                                <input type="datetime-local" name="alert_expires_at" x-model="newWorkshop.alert_expires_at"
                                       class="w-full border border-orange-200 bg-white rounded-xl px-4 py-3 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/20 focus:border-orange-400 transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- Administrator Account --}}
                    <div class="px-5 py-5 border-t border-slate-100 bg-emerald-50/30">
                        <div class="flex items-center gap-2 mb-4">
                            <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Administrator Account</p>
                        </div>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Admin Name</label>
                                    <input type="text" name="admin_name"
                                           class="w-full border border-slate-200 bg-white rounded-xl px-4 py-3 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400 transition-all placeholder-slate-400"
                                           placeholder="Full name">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Admin Email</label>
                                    <input type="email" name="admin_email"
                                           class="w-full border border-slate-200 bg-white rounded-xl px-4 py-3 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400 transition-all placeholder-slate-400"
                                           placeholder="admin@garage.com">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Password <span class="text-slate-400 font-normal">(min. 8 characters)</span></label>
                                <input type="password" name="admin_password"
                                       class="w-full border border-slate-200 bg-white rounded-xl px-4 py-3 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400 transition-all placeholder-slate-400"
                                       placeholder="Set a secure password">
                            </div>
                        </div>
                    </div>

                </div>{{-- end overflow body --}}

                {{-- Sticky Footer --}}
                <div class="flex-shrink-0 bg-white border-t border-slate-100 flex items-center justify-between gap-3 px-5 py-4">
                    <p class="text-xs text-slate-400"><span class="text-rose-500 font-bold">*</span> required fields</p>
                    <div class="flex gap-2">
                        <button type="button" @click="openAddModal = false"
                                class="px-4 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 active:scale-95 rounded-xl transition-all shadow-sm shadow-blue-500/20">
                            + Save Garage
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Workshop Modal --}}
    <div x-show="openEditModal" x-cloak
         class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-900/50 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click.self="openEditModal = false">
        <div class="bg-white w-full sm:rounded-2xl sm:max-w-lg max-h-[95vh] flex flex-col rounded-t-2xl shadow-2xl"
             x-transition:enter="transition ease-out duration-200 transform" x-transition:enter-start="translate-y-4 sm:scale-95" x-transition:enter-end="translate-y-0 sm:scale-100">

            {{-- Header --}}
            <div class="flex-shrink-0 flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900" x-text="'Edit: ' + (activeWorkshop.name || 'Garage')">Edit Garage</h3>
                        <p class="text-xs text-slate-400">Update details &amp; admin access</p>
                    </div>
                </div>
                <button @click="openEditModal = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors text-lg leading-none">&times;</button>
            </div>

            {{-- Scrollable Body — form always visible, no x-show --}}
            <form :action="`/super-admin/workshops/${activeWorkshop.id}`" method="POST" class="flex-1 overflow-y-auto min-h-0">
                @csrf @method('PUT')
                <input type="hidden" name="admin_user_id" x-model="activeWorkshop.admin_user_id">

                {{-- Garage Info --}}
                <div class="px-5 pt-5 pb-4">
                    <p class="text-xs font-bold text-amber-500 uppercase tracking-widest mb-3">Garage Details</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Garage Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" x-model="activeWorkshop.name" required
                                   class="w-full border border-slate-200 bg-slate-50 rounded-lg px-3 py-2.5 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/25 focus:border-amber-400 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Phone <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" x-model="activeWorkshop.phone" required
                                   class="w-full border border-slate-200 bg-slate-50 rounded-lg px-3 py-2.5 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/25 focus:border-amber-400 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Email</label>
                            <input type="email" name="email" x-model="activeWorkshop.email"
                                   class="w-full border border-slate-200 bg-slate-50 rounded-lg px-3 py-2.5 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/25 focus:border-amber-400 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Status <span class="text-rose-500">*</span></label>
                            <select name="subscription_status" x-model="activeWorkshop.subscription_status" @change="handleStatusChange($event, 'Edit')"
                                    class="w-full border border-slate-200 bg-slate-50 rounded-lg px-3 py-2.5 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/25 focus:border-amber-400 focus:bg-white transition-all cursor-pointer">
                                <option value="training">Training</option>
                                <option value="trial">Trial</option>
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                                <option value="fix">Fix</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Trial / Training Ends At</label>
                            <input type="datetime-local" name="trial_ends_at" x-model="activeWorkshop.trial_ends_at" @input="handleDateChange($event, 'Edit')"
                                   class="w-full border border-slate-200 bg-slate-50 rounded-lg px-3 py-2.5 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/25 focus:border-amber-400 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Warning Message</label>
                            <input type="text" name="alert_message" x-model="activeWorkshop.alert_message"
                                   class="w-full border border-slate-200 bg-slate-50 rounded-lg px-3 py-2.5 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/25 focus:border-amber-400 focus:bg-white transition-all"
                                   placeholder="Subscription expiring soon...">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Warning Expires At</label>
                            <input type="datetime-local" name="alert_expires_at" x-model="activeWorkshop.alert_expires_at"
                                   class="w-full border border-slate-200 bg-slate-50 rounded-lg px-3 py-2.5 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/25 focus:border-amber-400 focus:bg-white transition-all">
                        </div>
                    </div>
                </div>

                {{-- Admin Account --}}
                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/60">
                    <p class="text-xs font-bold text-violet-600 uppercase tracking-widest mb-3">Administrator Access</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Admin Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="admin_name" x-model="activeWorkshop.admin_name" required
                                   class="w-full border border-slate-200 bg-white rounded-lg px-3 py-2.5 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400/25 focus:border-violet-400 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Admin Email <span class="text-rose-500">*</span></label>
                            <input type="email" name="admin_email" x-model="activeWorkshop.admin_email" required
                                   class="w-full border border-slate-200 bg-white rounded-lg px-3 py-2.5 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400/25 focus:border-violet-400 transition-all">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">New Password <span class="text-slate-400 font-normal">(leave blank to keep current)</span></label>
                            <input type="password" name="admin_password"
                                   class="w-full border border-slate-200 bg-white rounded-lg px-3 py-2.5 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400/25 focus:border-violet-400 transition-all"
                                   placeholder="Enter new password">
                        </div>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="flex-shrink-0 flex items-center justify-end gap-2 px-5 py-4 border-t border-slate-100 bg-white">
                    <button type="button" @click="openEditModal = false"
                            class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Cancel</button>
                    <button type="submit"
                            class="px-5 py-2 text-sm font-semibold text-white bg-amber-500 hover:bg-amber-600 rounded-lg transition-colors shadow-sm">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- Generate Keys Modal --}}

    <div x-show="openGenerateKeysModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden border border-slate-100"
             x-show="openGenerateKeysModal" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="scale-95 translate-y-4" x-transition:enter-end="scale-100 translate-y-0">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 font-outfit">Add / Generate License Key</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Create a custom key or auto-generate a secure key.</p>
                </div>
                <button @click="openGenerateKeysModal = false" class="text-slate-400 hover:text-slate-600 bg-white hover:bg-slate-100 p-1.5 rounded-lg border border-slate-200 transition-colors">&times;</button>
            </div>
            <form action="{{ route('super_admin.store_product_key') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Key Value <span class="text-slate-400 font-normal">(Optional)</span></label>
                    <input type="text" name="key" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-mono uppercase transition-all" placeholder="Leave blank to auto-generate">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Duration (Days) <span class="text-rose-500">*</span></label>
                    <input type="number" name="duration_days" min="1" value="30" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all" required>
                </div>
                <!-- Quantity fixed to 1 -->
                <input type="hidden" name="quantity" value="1">
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openGenerateKeysModal = false" class="px-4 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-sm font-semibold transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm shadow-emerald-500/10">Add / Generate Key</button>
                </div>
            </form>
        </div>
    </div>



    {{-- Edit License Key Modal --}}
    <div x-show="openEditKeyModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden border border-slate-100"
             x-show="openEditKeyModal" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="scale-95 translate-y-4" x-transition:enter-end="scale-100 translate-y-0">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 font-outfit">Edit License Key</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Modify key value and duration settings.</p>
                </div>
                <button @click="openEditKeyModal = false" class="text-slate-400 hover:text-slate-600 bg-white hover:bg-slate-100 p-1.5 rounded-lg border border-slate-200 transition-colors">&times;</button>
            </div>
            <form :action="`/super-admin/product-keys/${activeKey.id}`" method="POST" class="p-6 space-y-4" x-show="activeKey.id">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Key Value <span class="text-rose-500">*</span></label>
                    <input type="text" name="key" x-model="activeKey.key" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-mono transition-all" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Duration (Days) <span class="text-rose-500">*</span></label>
                    <input type="number" name="duration_days" x-model="activeKey.duration_days" min="1" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all" required>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openEditKeyModal = false" class="px-4 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-sm font-semibold transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm shadow-blue-500/10">Update Key</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
// ── Helper: Get current local datetime in 'YYYY-MM-DDTHH:mm' format for datetime-local inputs
function localNow() {
    const d = new Date();
    const pad = n => String(n).padStart(2, '0');
    return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
}

// ── Helper: Calculate future local datetime in 'YYYY-MM-DDTHH:mm' format by adding days
function localFuture(days) {
    const d = new Date();
    d.setDate(d.getDate() + parseInt(days || 0));
    const pad = n => String(n).padStart(2, '0');
    return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
}

// ── Helper: Format a datetime-local value ('YYYY-MM-DDTHH:mm') into a friendly string '22 Jul 2026, 04:40 AM'
function formatFriendlyDatetime(val) {
    if (!val) return '';
    const parts = val.split('T');
    if (parts.length !== 2) return '';
    const datePart = parts[0]; // '2026-07-22'
    const timePart = parts[1]; // '04:40'
    
    const dateElements = datePart.split('-');
    if (dateElements.length !== 3) return '';
    const year = parseInt(dateElements[0]);
    const monthIndex = parseInt(dateElements[1]) - 1;
    const day = parseInt(dateElements[2]);
    
    const timeElements = timePart.split(':');
    if (timeElements.length < 2) return '';
    let hours = parseInt(timeElements[0]);
    const minutes = timeElements[1].substring(0, 2).padStart(2, '0');
    
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const monthName = months[monthIndex] || '';
    
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    const formattedHours = String(hours).padStart(2, '0');
    
    return `${day} ${monthName} ${year}, ${formattedHours}:${minutes} ${ampm}`;
}

// ── Helper: Fix a datetime-local value — if time is 00:00 (midnight / 12:00 AM bug), replace with current time
function fixDatetime(val) {
    if (!val) return '';
    // val is like '2026-06-20T00:00' — if time is exactly 00:00 replace it with current time
    if (val.endsWith('T00:00') || val.endsWith('T00:0')) {
        const now = new Date();
        const pad = n => String(n).padStart(2, '0');
        return val.replace(/T\d{2}:\d{2}$/, `T${pad(now.getHours())}:${pad(now.getMinutes())}`);
    }
    return val;
}

document.addEventListener('alpine:init', () => {
    Alpine.data('workshopAdminPanel', () => ({
        activeTab: new URLSearchParams(window.location.search).get('tab') || 'dashboard',
        searchWorkshopQuery: '',
        openAddModal: {{ $showAddWorkshopModal ? 'true' : 'false' }},
        openEditModal: {{ $showEditWorkshopModal ? 'true' : 'false' }},
        openGenerateKeysModal: false,
        openEditKeyModal: false,
        activeWorkshop: @json($initialActiveWorkshop ?: ['id'=>'']),
        activeKey: { id: '', duration_days: '', key: '' },
        newWorkshop: {
            subscription_status: 'training',
            trial_ends_at: '',
            alert_message: '',
            alert_expires_at: ''
        },

        openAdd() {
            this.openAddModal = true;
            const defaultDays = {{ (int) $defaultTrialDuration }};
            const duration = 7; // training default
            const trialEnds = localFuture(duration);
            this.newWorkshop.subscription_status = 'training';
            this.newWorkshop.trial_ends_at = trialEnds;
            this.updateAlertFields(trialEnds, 'Add');
        },

        updateAlertFields(endsAtDate, modalType) {
            if (!endsAtDate) return;
            const formattedDate = formatFriendlyDatetime(endsAtDate);
            const msg = `Your subscription is expiring on ${formattedDate}. Please contact Suhaim Soft at 8891479505 for an active key.`;
            
            if (modalType === 'Add') {
                const messageInput = document.querySelector('[x-show="openAddModal"] input[name="alert_message"]');
                const expiresInput = document.querySelector('[x-show="openAddModal"] input[name="alert_expires_at"]');
                if (messageInput) messageInput.value = msg;
                if (expiresInput) expiresInput.value = endsAtDate;
            } else if (modalType === 'Edit') {
                this.activeWorkshop.alert_message = msg;
                this.activeWorkshop.alert_expires_at = endsAtDate;
            }
        },

        handleDateChange(event, modalType) {
            const endsAtDate = event.target.value;
            this.updateAlertFields(endsAtDate, modalType);
        },

        handleStatusChange(event, modalType) {
            const status = event.target.value;
            const defaultDays = {{ (int) $defaultTrialDuration }};
            let duration = defaultDays;
            if (status === 'training') duration = 7;
            else if (status === 'active') duration = 365;
            else if (status === 'suspended' || status === 'fix') duration = 0;

            const futureDate = localFuture(duration);

            if (modalType === 'Add') {
                const trialInput = document.querySelector('[x-show="openAddModal"] input[name="trial_ends_at"]');
                if (trialInput) trialInput.value = futureDate;
            } else if (modalType === 'Edit') {
                this.activeWorkshop.trial_ends_at = futureDate;
            }

            this.updateAlertFields(futureDate, modalType);
        },

        openEditKeyModalFn(keyObj) {
            this.activeKey = { ...keyObj };
            this.openEditKeyModal = true;
        },

        openEdit(workshop) {
            const w = { ...workshop };
            // ── Fix 12:00 AM (00:00) bug: replace midnight time with current local time
            w.trial_ends_at   = fixDatetime(w.trial_ends_at);
            w.alert_expires_at = fixDatetime(w.alert_expires_at);
            this.activeWorkshop = w;
            this.openEditModal = true;
        },



        matchesWorkshopSearch(name, phone, email, adminName, adminEmail) {
            if (!this.searchWorkshopQuery) return true;
            let q = this.searchWorkshopQuery.toLowerCase();
            return name.includes(q) || phone.includes(q) || email.includes(q) || adminName.includes(q) || adminEmail.includes(q);
        }
    }));
});
</script>

<script>
(function() {
    const countdownWorkshops = [
        @foreach($workshops as $w)
            @if(in_array($w->subscription_status, ['trial', 'training']) && $w->trial_ends_at)
            {
                id: {{ $w->id }},
                expiryISO: '{{ $w->trial_ends_at->format("Y-m-d\TH:i:s") }}',
                formattedExpiryStr: '{{ $w->trial_ends_at->isToday() ? "Today, " . $w->trial_ends_at->format("h:i A") : ($w->trial_ends_at->isTomorrow() ? "Tomorrow, " . $w->trial_ends_at->format("h:i A") : $w->trial_ends_at->format("d M Y, h:i A")) }}'
            },
            @endif
        @endforeach
    ];

    function pad(n) { return String(n).padStart(2, '0'); }

    function updateAllCountdowns() {
        const now = new Date();
        countdownWorkshops.forEach(w => {
            const expiryDate = new Date(w.expiryISO);
            const diff = expiryDate - now;

            const countdownEls = document.querySelectorAll(`[data-countdown-id="${w.id}"]`);
            const expiryEls = document.querySelectorAll(`[data-expiry-id="${w.id}"]`);

            expiryEls.forEach(el => {
                if (el.textContent !== w.formattedExpiryStr) {
                    el.textContent = w.formattedExpiryStr;
                }
            });

            countdownEls.forEach(el => {
                if (diff <= 0) {
                    el.textContent = 'Expired';
                    el.style.color = '#be123c';
                    return;
                }

                const totalSecs = Math.floor(diff / 1000);
                const days = Math.floor(totalSecs / 86400);
                const hours = Math.floor((totalSecs % 86400) / 3600);
                const mins = Math.floor((totalSecs % 3600) / 60);
                const secs = totalSecs % 60;

                let text;
                if (days > 1) {
                    text = days + ' Days Left ' + pad(hours) + 'h ' + pad(mins) + 'm';
                } else if (days === 1) {
                    text = '1 Day Left ' + pad(hours) + 'h ' + pad(mins) + 'm';
                } else if (hours > 0) {
                    text = '0 Days Left ' + pad(hours) + 'h ' + pad(mins) + 'm ' + pad(secs) + 's';
                } else {
                    text = '0 Days Left 0h ' + pad(mins) + 'm ' + pad(secs) + 's';
                    el.style.color = '#dc2626'; // urgent red
                }

                el.textContent = text;
            });
        });
    }

    if (countdownWorkshops.length > 0) {
        updateAllCountdowns();
        setInterval(updateAllCountdowns, 1000);
    }
})();
</script>
@endsection
