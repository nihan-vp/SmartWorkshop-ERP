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
                    + Generate Keys
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
                        @forelse($workshops->take(5) as $w)
                        <div class="p-4 flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-slate-800">{{ $w->name }}</p>
                                <p class="text-xs text-slate-500">{{ $w->phone }} | {{ $w->email }}</p>
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
                        @forelse($activityLogs->take(5) as $log)
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
                        <select name="limit" onchange="this.form.submit()" class="px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 sm:w-24">
                            <option value="15" {{ request('limit') == 15 ? 'selected' : '' }}>15 / page</option>
                            <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25 / page</option>
                            <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50 / page</option>
                            <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100 / page</option>
                        </select>
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
                                    <button @click="openActivateModal(@js(['id'=>$workshop->id,'name'=>$workshop->name]))" class="text-emerald-600 hover:underline text-xs font-semibold">License</button>
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
                                    @if($key->status === 'unused')
                                        <button @click="openEditKeyModalFn(@js(['id'=>$key->id,'duration_days'=>$key->duration_days,'key'=>$key->key]))" class="text-blue-600 hover:underline text-xs font-semibold">Edit</button>
                                        <form action="{{ route('super_admin.destroy_product_key', $key) }}" method="POST" class="inline" onsubmit="return confirm('Delete this license key?');">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:underline text-xs font-semibold">Delete</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400">N/A</span>
                                    @endif
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
                        <input type="number" name="default_trial_duration" value="{{ $defaultTrialDuration }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
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
    <div x-show="openAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col border border-slate-100"
             x-show="openAddModal" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="scale-95 translate-y-4" x-transition:enter-end="scale-100 translate-y-0">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 font-outfit">Add New Garage</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Register a new workshop and set up its administrator account.</p>
                </div>
                <button @click="openAddModal = false" class="text-slate-400 hover:text-slate-600 bg-white hover:bg-slate-100 p-1.5 rounded-lg border border-slate-200 transition-colors">&times;</button>
            </div>
            <form action="{{ route('super_admin.store_workshop') }}" method="POST" class="flex-1 overflow-y-auto p-6 space-y-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2 font-bold text-slate-900 text-sm border-b border-slate-100 pb-2">Garage Details</div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Garage Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all" placeholder="e.g. Suhaim Soft Garage">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number <span class="text-rose-500">*</span></label>
                        <input type="text" name="phone" required class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all" placeholder="e.g. 08891479505">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
                        <input type="email" name="email" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all" placeholder="e.g. info@garage.com">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Initial Status <span class="text-rose-500">*</span></label>
                        <select name="subscription_status" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all bg-white cursor-pointer">
                            <option value="training">Training</option>
                            <option value="trial">Trial</option>
                            <option value="active">Active</option>
                            <option value="suspended">Suspended</option>
                            <option value="fix">Fix</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Training / Trial Ends At</label>
                        <input type="datetime-local" name="trial_ends_at" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all">
                    </div>

                    <div class="sm:col-span-2 font-bold text-slate-900 text-sm border-b border-slate-100 pb-2 mt-2">Warning Notification Banner</div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Warning Message</label>
                        <input type="text" name="alert_message" placeholder="e.g. Your subscription is expiring soon!" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Warning Expires At</label>
                        <input type="datetime-local" name="alert_expires_at" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all">
                    </div>
                    
                    <div class="sm:col-span-2 font-bold text-slate-900 text-sm border-b border-slate-100 pb-2 mt-2">Administrator Account</div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Admin Name</label>
                        <input type="text" name="admin_name" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all" placeholder="e.g. John Doe">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Admin Email</label>
                        <input type="email" name="admin_email" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all" placeholder="e.g. admin@garage.com">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Admin Password</label>
                        <input type="password" name="admin_password" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all" placeholder="Enter password (minimum 8 characters)">
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <button type="button" @click="openAddModal = false" class="px-4 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-sm font-semibold transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm shadow-blue-500/10">Save Garage</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Workshop Modal --}}
    <div x-show="openEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col border border-slate-100"
             x-show="openEditModal" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="scale-95 translate-y-4" x-transition:enter-end="scale-100 translate-y-0">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 font-outfit">Edit Garage Settings</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Update workspace details, warnings, and administrative access.</p>
                </div>
                <button @click="openEditModal = false" class="text-slate-400 hover:text-slate-600 bg-white hover:bg-slate-100 p-1.5 rounded-lg border border-slate-200 transition-colors">&times;</button>
            </div>
            <form :action="`/super-admin/workshops/${activeWorkshop.id}`" method="POST" class="flex-1 overflow-y-auto p-6 space-y-6" x-show="activeWorkshop.id">
                @csrf @method('PUT')
                <input type="hidden" name="admin_user_id" x-model="activeWorkshop.admin_user_id">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    
                    <div class="sm:col-span-2 font-bold text-slate-900 text-sm border-b border-slate-100 pb-2">Garage Details</div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Garage Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="activeWorkshop.name" required class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number <span class="text-rose-500">*</span></label>
                        <input type="text" name="phone" x-model="activeWorkshop.phone" required class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
                        <input type="email" name="email" x-model="activeWorkshop.email" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Subscription Status <span class="text-rose-500">*</span></label>
                        <select name="subscription_status" x-model="activeWorkshop.subscription_status" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all bg-white cursor-pointer">
                            <option value="training">Training</option>
                            <option value="trial">Trial</option>
                            <option value="active">Active</option>
                            <option value="suspended">Suspended</option>
                            <option value="fix">Fix</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Training / Trial Ends At</label>
                        <input type="datetime-local" name="trial_ends_at" x-model="activeWorkshop.trial_ends_at" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all">
                    </div>

                    <div class="sm:col-span-2 font-bold text-slate-900 text-sm border-b border-slate-100 pb-2 mt-2">Warning Notification Banner</div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Warning Message</label>
                        <input type="text" name="alert_message" x-model="activeWorkshop.alert_message" placeholder="e.g. Your subscription is expiring soon!" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Warning Expires At</label>
                        <input type="datetime-local" name="alert_expires_at" x-model="activeWorkshop.alert_expires_at" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all">
                    </div>

                    <div class="sm:col-span-2 font-bold text-slate-900 text-sm border-b border-slate-100 pb-2 mt-2">Administrator Access</div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Admin Name</label>
                        <input type="text" name="admin_name" x-model="activeWorkshop.admin_name" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Admin Email</label>
                        <input type="email" name="admin_email" x-model="activeWorkshop.admin_email" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Reset Password <span class="text-slate-400 font-normal lowercase">(leave blank to keep current)</span></label>
                        <input type="password" name="admin_password" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all" placeholder="Enter new password">
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <button type="button" @click="openEditModal = false" class="px-4 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-sm font-semibold transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm shadow-blue-500/10">Save Changes</button>
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
                    <h3 class="text-lg font-bold text-slate-900 font-outfit">Generate License Key</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Create a new key to sell or activate subscription.</p>
                </div>
                <button @click="openGenerateKeysModal = false" class="text-slate-400 hover:text-slate-600 bg-white hover:bg-slate-100 p-1.5 rounded-lg border border-slate-200 transition-colors">&times;</button>
            </div>
            <form action="{{ route('super_admin.store_product_key') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Key Duration <span class="text-rose-500">*</span></label>
                    <select name="duration_days" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all bg-white cursor-pointer">
                        <option value="30">30 Days</option>
                        <option value="90">90 Days</option>
                        <option value="180">180 Days</option>
                        <option value="365">365 Days</option>
                    </select>
                </div>
                <!-- Quantity fixed to 1 -->
                <input type="hidden" name="quantity" value="1">
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openGenerateKeysModal = false" class="px-4 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-sm font-semibold transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm shadow-emerald-500/10">Generate Key</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Activate License Modal --}}
    <div x-show="openActivateLicenseModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden border border-slate-100"
             x-show="openActivateLicenseModal" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="scale-95 translate-y-4" x-transition:enter-end="scale-100 translate-y-0">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 font-outfit truncate">Activate Garage License</h3>
                    <p class="text-xs text-slate-500 mt-0.5" x-text="'Activating for ' + activeWorkshopToActivate.name"></p>
                </div>
                <button @click="openActivateLicenseModal = false" class="text-slate-400 hover:text-slate-600 bg-white hover:bg-slate-100 p-1.5 rounded-lg border border-slate-200 transition-colors">&times;</button>
            </div>
            <form :action="`/super-admin/workshops/${activeWorkshopToActivate.id}/activate-license`" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Auto-Generate &amp; Activate License</label>
                    <select name="duration_days" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all bg-white cursor-pointer">
                        <option value="30">30 Days</option>
                        <option value="90">90 Days</option>
                        <option value="180">180 Days</option>
                        <option value="365">365 Days</option>
                    </select>
                </div>
                <div class="relative py-2">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-white px-2 text-slate-400 font-bold">OR</span>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Redeem Existing License Key</label>
                    <input type="text" name="product_key" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-mono uppercase transition-all" placeholder="XXXX-XXXX-XXXX-XXXX">
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openActivateLicenseModal = false" class="px-4 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-sm font-semibold transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm shadow-blue-500/10">Activate License</button>
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
document.addEventListener('alpine:init', () => {
    Alpine.data('workshopAdminPanel', () => ({
        activeTab: new URLSearchParams(window.location.search).get('tab') || 'dashboard',
        searchWorkshopQuery: '',
        openAddModal: {{ $showAddWorkshopModal ? 'true' : 'false' }},
        openEditModal: {{ $showEditWorkshopModal ? 'true' : 'false' }},
        openGenerateKeysModal: false,
        openEditKeyModal: false,
        openActivateLicenseModal: false,
        activeWorkshop: @json($initialActiveWorkshop ?: ['id'=>'']),
        activeWorkshopToActivate: { id: '', name: '' },
        activeKey: { id: '', duration_days: '', key: '' },

        openAdd() {
            this.openAddModal = true;
        },
        openEditKeyModalFn(keyObj) {
            this.activeKey = { ...keyObj };
            this.openEditKeyModal = true;
        },
        openEdit(workshop) {
            this.activeWorkshop = { ...workshop };
            this.openEditModal = true;
        },
        openActivateModal(workshop) {
            this.activeWorkshopToActivate = { ...workshop };
            this.openActivateLicenseModal = true;
        },
        matchesWorkshopSearch(name, phone, email, adminName, adminEmail) {
            if (!this.searchWorkshopQuery) return true;
            let q = this.searchWorkshopQuery.toLowerCase();
            return name.includes(q) || phone.includes(q) || email.includes(q) || adminName.includes(q) || adminEmail.includes(q);
        }
    }));
});
</script>
@endsection
