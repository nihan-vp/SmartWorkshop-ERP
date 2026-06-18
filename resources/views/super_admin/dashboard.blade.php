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
                            @foreach($workshops as $workshop)
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
                                    <form action="{{ route('super_admin.impersonate', $workshop) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="text-blue-600 hover:underline text-xs font-semibold">Login</button>
                                    </form>
                                    <button @click="openActivateModal(@js(['id'=>$workshop->id,'name'=>$workshop->name]))" class="text-emerald-600 hover:underline text-xs font-semibold">License</button>
                                    <button @click="openEdit(@js(['id'=>$workshop->id,'name'=>$workshop->name,'phone'=>$workshop->phone,'email'=>$workshop->email,'gstin'=>$workshop->gstin,'address'=>$workshop->address,'subscription_status'=>$workshop->subscription_status,'trial_ends_at'=>$workshop->trial_ends_at?$workshop->trial_ends_at->format('Y-m-d\TH:i'):'','admin_user_id'=>$workshop->users->first()?->id,'admin_name'=>$workshop->users->first()?->name,'admin_email'=>$workshop->users->first()?->email]))" class="text-slate-600 hover:underline text-xs font-semibold">Edit</button>
                                </td>
                            </tr>
                            @endforeach
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
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-slate-500">
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
    <div x-show="openAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Add Workshop</h3>
                <button @click="openAddModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            <form action="{{ route('super_admin.store_workshop') }}" method="POST" class="flex-1 overflow-y-auto p-4 space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2 font-semibold text-slate-700 border-b pb-1">Garage Details</div>
                    <div><label class="block text-xs font-semibold mb-1">Name *</label><input type="text" name="name" required class="w-full border rounded p-2 text-sm"></div>
                    <div><label class="block text-xs font-semibold mb-1">Phone *</label><input type="text" name="phone" required class="w-full border rounded p-2 text-sm"></div>
                    <div><label class="block text-xs font-semibold mb-1">Email</label><input type="email" name="email" class="w-full border rounded p-2 text-sm"></div>
                    <div>
                        <label class="block text-xs font-semibold mb-1">Status *</label>
                        <select name="subscription_status" class="w-full border rounded p-2 text-sm">
                            <option value="training">Training</option>
                            <option value="active">Active</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2 font-semibold text-slate-700 border-b pb-1 mt-2">Admin Account</div>
                    <div><label class="block text-xs font-semibold mb-1">Admin Name</label><input type="text" name="admin_name" class="w-full border rounded p-2 text-sm"></div>
                    <div><label class="block text-xs font-semibold mb-1">Admin Email</label><input type="email" name="admin_email" class="w-full border rounded p-2 text-sm"></div>
                    <div class="sm:col-span-2"><label class="block text-xs font-semibold mb-1">Admin Password</label><input type="password" name="admin_password" class="w-full border rounded p-2 text-sm"></div>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="openAddModal = false" class="px-4 py-2 border rounded text-sm font-semibold">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm font-semibold">Save</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Workshop Modal --}}
    <div x-show="openEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Edit Workshop</h3>
                <button @click="openEditModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            <form :action="`/super-admin/workshops/${activeWorkshop.id}`" method="POST" class="flex-1 overflow-y-auto p-4 space-y-4" x-show="activeWorkshop.id">
                @csrf @method('PUT')
                <input type="hidden" name="admin_user_id" x-model="activeWorkshop.admin_user_id">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2 font-semibold text-slate-700 border-b pb-1">Garage Details</div>
                    <div><label class="block text-xs font-semibold mb-1">Name *</label><input type="text" name="name" x-model="activeWorkshop.name" required class="w-full border rounded p-2 text-sm"></div>
                    <div><label class="block text-xs font-semibold mb-1">Phone *</label><input type="text" name="phone" x-model="activeWorkshop.phone" required class="w-full border rounded p-2 text-sm"></div>
                    <div><label class="block text-xs font-semibold mb-1">Email</label><input type="email" name="email" x-model="activeWorkshop.email" class="w-full border rounded p-2 text-sm"></div>
                    <div>
                        <label class="block text-xs font-semibold mb-1">Status *</label>
                        <select name="subscription_status" x-model="activeWorkshop.subscription_status" class="w-full border rounded p-2 text-sm">
                            <option value="training">Training</option>
                            <option value="active">Active</option>
                            <option value="suspended">Suspended</option>
                            <option value="fix">Fix</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2 font-semibold text-slate-700 border-b pb-1 mt-2">Admin Account</div>
                    <div><label class="block text-xs font-semibold mb-1">Admin Name</label><input type="text" name="admin_name" x-model="activeWorkshop.admin_name" class="w-full border rounded p-2 text-sm"></div>
                    <div><label class="block text-xs font-semibold mb-1">Admin Email</label><input type="email" name="admin_email" x-model="activeWorkshop.admin_email" class="w-full border rounded p-2 text-sm"></div>
                    <div class="sm:col-span-2"><label class="block text-xs font-semibold mb-1">New Password (leave blank to keep)</label><input type="password" name="admin_password" class="w-full border rounded p-2 text-sm"></div>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="openEditModal = false" class="px-4 py-2 border rounded text-sm font-semibold">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm font-semibold">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Generate Keys Modal --}}
    <div x-show="openGenerateKeysModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
            <div class="p-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Generate Keys</h3>
                <button @click="openGenerateKeysModal = false" class="text-slate-400">&times;</button>
            </div>
            <form action="{{ route('super_admin.store_product_key') }}" method="POST" class="p-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold mb-1">Duration (Days)</label>
                    <select name="duration_days" class="w-full border rounded p-2 text-sm">
                        <option value="30">30 Days</option>
                        <option value="90">90 Days</option>
                        <option value="180">180 Days</option>
                        <option value="365">365 Days</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1">Quantity</label>
                    <input type="number" name="quantity" value="5" min="1" max="100" class="w-full border rounded p-2 text-sm">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openGenerateKeysModal = false" class="px-4 py-2 border rounded text-sm font-semibold">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded text-sm font-semibold">Generate</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Activate License Modal --}}
    <div x-show="openActivateLicenseModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
            <div class="p-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Activate License for <span x-text="activeWorkshopToActivate.name"></span></h3>
                <button @click="openActivateLicenseModal = false" class="text-slate-400">&times;</button>
            </div>
            <form :action="`/super-admin/workshops/${activeWorkshopToActivate.id}/activate-license`" method="POST" class="p-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold mb-1">License Duration (Auto-Generate)</label>
                    <select name="duration_days" class="w-full border rounded p-2 text-sm">
                        <option value="30">30 Days</option>
                        <option value="90">90 Days</option>
                        <option value="180">180 Days</option>
                        <option value="365">365 Days</option>
                    </select>
                </div>
                <p class="text-center text-xs text-slate-500">OR</p>
                <div>
                    <label class="block text-xs font-semibold mb-1">Enter Existing Key</label>
                    <input type="text" name="product_key" class="w-full border rounded p-2 text-sm font-mono uppercase" placeholder="XXXX-XXXX-XXXX-XXXX">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openActivateLicenseModal = false" class="px-4 py-2 border rounded text-sm font-semibold">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm font-semibold">Activate</button>
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
        openActivateLicenseModal: false,
        activeWorkshop: @json($initialActiveWorkshop ?: ['id'=>'']),
        activeWorkshopToActivate: { id: '', name: '' },

        openAdd() {
            this.openAddModal = true;
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
