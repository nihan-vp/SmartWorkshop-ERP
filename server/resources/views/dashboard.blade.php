@extends('layouts.app')

@php
    $user = auth()->user();
    $workshop = null;
    if ($user) {
        if ($user->isSuperAdmin() && session()->has('active_workshop_id')) {
            $workshop = \App\Models\Workshop::find(session('active_workshop_id'));
        } else {
            $workshop = $user->workshop;
        }
    }
@endphp

@section('title', 'Dashboard')
@section('page-title', 'Overview')
@section('page-subtitle', 'Workshop Performance & Operations')

@section('content')
@if($workshop)
<div x-data="subscriptionManager({
    status: '{{ $workshop ? $workshop->subscription_status : 'N/A' }}',
    expiry: '{{ $workshop && $workshop->trial_ends_at ? ($workshop->trial_ends_at->isToday() ? "Today, " . $workshop->trial_ends_at->format("h:i A") : ($workshop->trial_ends_at->isTomorrow() ? "Tomorrow, " . $workshop->trial_ends_at->format("h:i A") : $workshop->trial_ends_at->format("d M Y, h:i A"))) : "Never (Lifetime)" }}',
    daysRemaining: {{ $workshop && $workshop->trial_ends_at ? $workshop->getTrialDaysRemaining() : 0 }},
    totalDuration: {{ $workshop && $workshop->trial_ends_at ? $workshop->getTotalDurationDays() : 0 }},
    subscriptionDay: {{ $workshop ? $workshop->getSubscriptionDay() : 0 }},
    isExpired: {{ $workshop && $workshop->trial_ends_at && $workshop->isTrialExpired() ? 'true' : 'false' }},
    hasExpiry: {{ $workshop && $workshop->trial_ends_at ? 'true' : 'false' }},
    keys: [
        @if($workshop)
            @foreach($workshop->productKeys()->orderBy('used_at', 'desc')->get() as $key)
                @php
                    $parts = explode('-', $key->key);
                    $masked = (count($parts) >= 3) ? ($parts[0] . '-XXXX-XXXX-' . end($parts)) : (substr($key->key, 0, 8) . '...');
                @endphp
                {
                    key: '{{ $masked }}',
                    duration: {{ $key->duration_days }},
                    used_at: '{{ $key->used_at ? $key->used_at->format('d M Y, h:i A') : $key->updated_at->format('d M Y, h:i A') }}'
                },
            @endforeach
        @endif
    ]
})">
@endif
<div class="max-w-7xl mx-auto space-y-5 animate-fade-in-up">

    <!-- Removed redundant banner -->

    <!-- Welcome & Overview Banner with Filter -->
    <div class="relative bg-white border border-slate-200/80 rounded-3xl p-6 lg:p-8 shadow-sm overflow-hidden flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <!-- Background decorative blur -->
        <div class="absolute -right-16 -bottom-16 w-64 h-64 rounded-full bg-primary-100/20 filter blur-[30px] pointer-events-none"></div>
        
        <div class="space-y-1.5 z-10 flex-1">
            <h3 class="text-xl font-bold font-outfit text-slate-900 flex items-center gap-2">
                Welcome back, Workshop Manager!
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
            </h3>
            <p class="text-sm text-slate-500 font-medium">Here is a summary of your shop's operations and finances.</p>
        </div>
        
        <div class="w-full xl:w-auto flex flex-col xl:flex-row items-start xl:items-center z-10 gap-3 mt-4 md:mt-0">
            <form method="GET" action="{{ route('dashboard') }}" class="w-full flex flex-col xl:flex-row items-start xl:items-center gap-3">
                <div class="relative w-full xl:w-48">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <select name="filter" onchange="if(this.value === 'custom') { document.getElementById('custom-date-container').classList.remove('hidden'); document.getElementById('custom-date-container').classList.add('flex'); } else { this.form.submit(); }" class="block w-full appearance-none bg-slate-50 border border-slate-200 text-slate-700 py-2.5 pl-10 pr-8 rounded-xl text-sm font-semibold shadow-sm hover:bg-slate-100 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 cursor-pointer">
                        <option value="today" @if(($filter ?? 'today') === 'today') selected @endif>Today</option>
                        <option value="yesterday" @if(($filter ?? 'today') === 'yesterday') selected @endif>Yesterday</option>
                        <option value="week" @if(($filter ?? 'today') === 'week') selected @endif>Last 7 Days</option>
                        <option value="month" @if(($filter ?? 'today') === 'month') selected @endif>This Month</option>
                        <option value="all" @if(($filter ?? 'today') === 'all') selected @endif>All Time</option>
                        <option value="custom" @if(($filter ?? 'today') === 'custom') selected @endif>Custom Range</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                
                <div id="custom-date-container" class="{{ ($filter ?? '') === 'custom' ? 'flex' : 'hidden' }} flex-col sm:flex-row items-center gap-2 w-full xl:w-auto">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="block w-full sm:w-auto appearance-none bg-slate-50 border border-slate-200 text-slate-700 py-2.5 px-3 rounded-xl text-sm font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <span class="text-slate-500 text-sm font-medium hidden sm:block">to</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="block w-full sm:w-auto appearance-none bg-slate-50 border border-slate-200 text-slate-700 py-2.5 px-3 rounded-xl text-sm font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button type="submit" class="btn-primary !py-2.5 !px-4 text-sm whitespace-nowrap flex-1 sm:flex-none justify-center">Apply</button>
                        @if(($filter ?? '') === 'custom')
                            <a href="{{ route('dashboard') }}" class="btn-secondary !py-2.5 !px-4 text-sm whitespace-nowrap flex-1 sm:flex-none justify-center text-center">Clear</a>
                        @else
                            <button type="button" onclick="document.getElementById('custom-date-container').classList.add('hidden'); document.getElementById('custom-date-container').classList.remove('flex'); document.querySelector('select[name=\'filter\']').value='today';" class="btn-secondary !py-2.5 !px-4 text-sm whitespace-nowrap flex-1 sm:flex-none justify-center">Cancel</button>
                        @endif
                    </div>
                </div>
            </form>
            <a href="{{ route('bills.create') }}" class="btn-primary shadow-sm !py-2.5 !px-5 text-sm w-full xl:w-auto justify-center text-center whitespace-nowrap">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Invoice
            </a>
        </div>
    </div>

    <!-- Core Metrics (Spacious 4-Card Grid) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Metric 1: Total Revenue -->
        <div class="glass-card flex flex-col justify-between border-l-4 border-l-emerald-500 relative overflow-hidden group hover:scale-[1.01] transition-transform duration-300">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Income</span>
                    <span class="badge badge-success">Paid</span>
                </div>
                <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-outfit truncate animate-count-up" title="₹{{ number_format($totalIncome, 2) }}">₹{{ number_format($totalIncome, 2) }}</p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs text-slate-500 font-medium">
                <span>UPI: ₹{{ number_format($upiPayments, 0) }}</span>
                <span class="hidden xs:inline text-slate-300">•</span>
                <span>Cash: ₹{{ number_format($cashPayments, 0) }}</span>
            </div>
        </div>

        <!-- Metric 2: Total Expenses -->
        <div class="glass-card flex flex-col justify-between border-l-4 border-l-rose-500 relative overflow-hidden group hover:scale-[1.01] transition-transform duration-300">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Outflow</span>
                    <span class="badge badge-danger">Expenses</span>
                </div>
                <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-outfit truncate animate-count-up" title="₹{{ number_format($totalExpensesAll, 2) }}">₹{{ number_format($totalExpensesAll, 2) }}</p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs text-slate-500 font-medium">
                <span>Shop: ₹{{ number_format($totalExpenses, 0) }}</span>
                <span class="hidden xs:inline text-slate-300">•</span>
                <span>Salaries: ₹{{ number_format($totalSalaries, 0) }}</span>
            </div>
        </div>

        <!-- Metric 3: Net Margin -->
        <div class="glass-card flex flex-col justify-between border-l-4 border-l-violet-500 relative overflow-hidden group hover:scale-[1.01] transition-transform duration-300">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Net Profit</span>
                    <span class="badge badge-purple">Margin</span>
                </div>
                <p class="text-2xl sm:text-3xl font-extrabold font-outfit truncate animate-count-up {{ $totalProfit < 0 ? 'text-rose-600' : 'text-slate-900' }}" title="₹{{ number_format($totalProfit, 2) }}">
                    ₹{{ number_format($totalProfit, 2) }}
                </p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-500 font-medium">
                <span>Total Shop Balance Margin</span>
            </div>
        </div>

        <!-- Metric 4: Work Orders -->
        <div class="glass-card flex flex-col justify-between border-l-4 border-l-sky-500 relative overflow-hidden group hover:scale-[1.01] transition-transform duration-300">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Job Queue</span>
                    <span class="badge badge-info">Operations</span>
                </div>
                <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-outfit truncate animate-count-up">{{ $totalWorkOrders }}</p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs text-slate-500 font-medium">
                <span class="text-amber-600 font-semibold">{{ $pendingWorkOrders }} Pending</span>
                <span class="hidden xs:inline text-slate-300">•</span>
                <span>{{ $totalServices }} Services Offered</span>
            </div>
        </div>

    </div>

    <!-- Top Statistics Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
        <div class="glass-card flex items-center gap-4 p-5">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Active Warranties</p>
                <p class="text-2xl font-black text-slate-800">{{ $activeWarranties }}</p>
            </div>
        </div>
        <div class="glass-card flex items-center gap-4 p-5">
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Customers</p>
                <p class="text-2xl font-black text-slate-800">{{ $totalCustomers }}</p>
            </div>
        </div>
        <div class="glass-card flex items-center gap-4 p-5">
            <div class="w-12 h-12 rounded-full bg-violet-50 text-violet-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Registered Vehicles</p>
                <p class="text-2xl font-black text-slate-800">{{ $totalVehicles }}</p>
            </div>
        </div>
        <div class="glass-card flex items-center gap-4 p-5">
            <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Active Staff</p>
                <p class="text-2xl font-black text-slate-800">{{ $totalEmployees }}</p>
            </div>
        </div>
    </div>

    <!-- Main Content Layout Split (2:1 Column Split) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        <!-- Left Side: Core Lists & Activity (Col-Span-8) -->
        <div class="lg:col-span-8 flex flex-col gap-5">
            
            <!-- Recent Invoices / Bills Card -->
            <div class="glass-card !p-0 overflow-hidden shrink-0">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white flex-wrap gap-4">
                    <h3 class="text-base font-bold text-slate-900 font-outfit">Recent Invoices</h3>
                    <a href="{{ route('bills.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">View All Bills →</a>
                </div>
                @if($recentBills->count())
                <div class="overflow-x-auto table-scroll-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>Invoice No</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Method</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBills as $index => $bill)
                            <tr>
                                <td data-label="Sl No" class="font-semibold text-slate-500">{{ $loop->iteration }}</td>
                                <td data-label="Invoice No" class="font-mono font-bold text-primary-600">
                                    {{ $bill->bill_number }}
                                </td>
                                <td data-label="Customer" class="font-bold text-slate-800">{{ $bill->customer->name }}</td>
                                <td data-label="Total" class="font-bold text-slate-900">₹{{ number_format($bill->total, 2) }}</td>
                                <td data-label="Method">
                                    <span class="badge {{ $bill->payment_method === 'upi' ? 'badge-info' : 'badge-warning' }}">
                                        {{ strtoupper($bill->payment_method) }}
                                    </span>
                                </td>
                                <td data-label="Status">
                                    <span class="badge {{ $bill->payment_status === 'paid' ? 'badge-success' : ($bill->payment_status === 'partial' ? 'badge-warning' : 'badge-danger') }}">
                                        {{ ucfirst($bill->payment_status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8 px-6">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    <p class="text-slate-500 font-semibold">No invoices recorded yet. <a href="{{ route('bills.create') }}" class="text-primary-600 hover:underline">Generate a bill</a></p>
                </div>
                @endif
            </div>

            <!-- Active Work Orders Card -->
            <div class="glass-card !p-0 overflow-hidden flex flex-col flex-1">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white flex-wrap gap-4 shrink-0">
                    <h3 class="text-base font-bold text-slate-900 font-outfit">Active Shop Jobs</h3>
                    <a href="{{ route('work-orders.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">View All Jobs →</a>
                </div>
                @if($pendingOrders->count())
                <div class="overflow-x-auto table-scroll-wrapper flex-1">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Order No</th>
                                <th>Customer & Vehicle</th>
                                <th>Assigned Mechanic</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingOrders as $order)
                            <tr>
                                <td data-label="Order No" class="font-mono font-bold text-primary-600">
                                    <a href="{{ route('work-orders.show', $order) }}" class="hover:underline">{{ $order->order_number }}</a>
                                </td>
                                <td data-label="Customer & Vehicle">
                                    <p class="font-bold text-slate-800">{{ $order->customer->name }}</p>
                                    <p class="text-xs text-slate-400 font-semibold">{{ $order->vehicle ? $order->vehicle->plate_number : '—' }}</p>
                                </td>
                                <td data-label="Assigned Mechanic" class="font-semibold text-slate-600">{{ $order->employee ? $order->employee->name : 'Unassigned' }}</td>
                                <td data-label="Status">
                                    <span class="badge {{ $order->status === 'pending' ? 'badge-warning' : 'badge-info' }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8 px-6 flex flex-col items-center justify-center flex-1 h-full min-h-[200px]">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                    <p class="text-slate-500 font-semibold">No active jobs in the queue.</p>
                </div>
                @endif
            </div>

        </div>

        <!-- Right Side: Sidebar & Fast Actions (Col-Span-4) -->
        <div class="lg:col-span-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-5 lg:space-y-5 lg:gap-0">
            
            <!-- Quick Actions Panel -->
            <div class="glass-card space-y-4 md:col-span-2 lg:col-span-1">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Quick Actions</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-1 gap-3">
                    <a href="{{ route('bills.create') }}" class="btn-primary !justify-start !py-3 w-full shadow-sm text-sm">
                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span class="truncate">Create New Invoice</span>
                    </a>
                    <a href="{{ route('work-orders.create') }}" class="btn-secondary !justify-start !py-3 w-full text-sm">
                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                        <span class="truncate">Log Repair Order</span>
                    </a>
                    <a href="{{ route('customers.create') }}" class="btn-secondary !justify-start !py-3 w-full text-sm">
                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        <span class="truncate">New Customer</span>
                    </a>
                </div>
            </div>

            <!-- Low Stock Alerts -->
            <div class="glass-card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Low Stock Alerts</h3>
                    <span class="badge badge-danger">{{ $lowStockProducts->count() }}</span>
                </div>
                @if($lowStockProducts->count())
                <div class="space-y-3">
                    @foreach($lowStockProducts as $product)
                    <div class="flex items-center justify-between p-3 bg-red-50 border border-red-100 rounded-xl">
                        <div class="min-w-0 flex-1 pr-2">
                            <p class="text-sm font-semibold text-slate-800 truncate" title="{{ $product->name }}">{{ $product->name }}</p>
                            <p class="text-xs text-slate-500 font-medium">Min: {{ $product->min_stock }} {{ $product->unit }}</p>
                        </div>
                        <span class="text-base font-extrabold text-red-600 shrink-0">{{ $product->stock_qty }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-slate-500 text-center py-4 font-semibold">All inventory levels healthy ✓</p>
                @endif
            </div>

            <!-- Subscription & Trial (Moved back to Right Sidebar) -->
            @if($workshop)
            <div>
                <div class="glass-card">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center justify-between">
                    <span>{{ $workshop && $workshop->subscription_status === 'training' ? 'Training Status' : 'Subscription & Trial' }}</span>
                    <span class="badge uppercase text-[10px]" :class="status === 'active' || status === 'fix' || status === 'fixed' ? 'badge-success' : 'badge-danger'" x-text="status"></span>
                </h3>
                <div class="space-y-3">
                    @if($workshop)
                        <div class="flex justify-between items-center text-xs pb-1.5 border-b border-slate-100">
                            <span class="text-slate-500 font-semibold">Account Status</span>
                            <span class="font-bold uppercase tracking-wider text-xs" :class="status === 'active' || status === 'fix' || status === 'fixed' ? 'text-emerald-600' : 'text-rose-600'" x-text="status"></span>
                        </div>

                        <div class="flex justify-between items-center text-xs pb-1.5 border-b border-slate-100">
                            <span class="text-slate-500 font-semibold">Expiration Date</span>
                            <span class="font-bold text-slate-700" x-text="expiry"></span>
                        </div>

                        <div class="flex justify-between items-center text-xs pb-1.5 border-b border-slate-100">
                            <span class="text-slate-500 font-semibold">Time Remaining</span>
                            <span x-show="isExpired" class="text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded border border-rose-100 animate-pulse">Expired</span>
                            <span x-show="!isExpired" class="text-slate-700 font-bold bg-blue-50 text-blue-700 px-2 py-0.5 rounded border border-blue-100">
                                <span x-text="hasExpiry ? daysRemaining + ' Days Left' : 'Active &amp; Unlimited'"></span>
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-xs pb-1.5 border-b border-slate-100">
                            <span class="text-slate-500 font-semibold">Total Duration</span>
                            <span class="font-bold text-slate-700" x-text="totalDuration + ' Days'"></span>
                        </div>

                        <div class="flex justify-between items-center text-xs pb-1.5 border-b border-slate-100">
                            <span class="text-slate-500 font-semibold">Operating Time</span>
                            <span class="font-bold text-slate-700" x-text="'Day ' + subscriptionDay"></span>
                        </div>

                        @php
                            $unusedKey = \App\Models\ProductKey::where('status', 'unused')->first();
                        @endphp
                        <div class="mt-4 p-3 rounded-xl text-center text-xs {{ $unusedKey ? 'bg-emerald-50 border border-emerald-100' : 'bg-red-50 border border-red-100' }}">
                            @if($unusedKey)
                                <p class="font-bold text-emerald-800">
                                    Your activation key is ready: <br>
                                    <span class="font-mono bg-white px-2 py-0.5 rounded border border-emerald-200 select-all font-extrabold tracking-wider text-sm block my-1.5 break-all">{{ $unusedKey->key }}</span>

                                </p>
                            @else
                                <p class="font-bold text-red-700">Please contact Suhaim Soft at 8891479505 for an active key.</p>
                            @endif
                        </div>
                        
                    @endif
                </div>
                </div> <!-- End of glass-card -->
            </div>
            @endif
        </div>
    </div>
</div>


    </div>
</div>

@endsection

@push('scripts')
<script>
    function subscriptionManager(initialData) {
        return {
            status: initialData.status,
            expiry: initialData.expiry,
            daysRemaining: initialData.daysRemaining,
            totalDuration: initialData.totalDuration,
            subscriptionDay: initialData.subscriptionDay,
            isExpired: initialData.isExpired,
            hasExpiry: initialData.hasExpiry,
            keys: initialData.keys || []
        }
    }
</script>
@endpush
