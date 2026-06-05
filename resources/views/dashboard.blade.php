@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Overview')
@section('page-subtitle', 'Workshop Performance & Operations')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-fade-in-up">

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
        
        <div class="w-full md:w-auto flex flex-col sm:flex-row items-center z-10 gap-3">
            <form method="GET" action="{{ route('dashboard') }}" class="w-full sm:w-auto">
                <div class="relative w-full sm:w-48">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <select name="filter" onchange="this.form.submit()" class="block w-full appearance-none bg-slate-50 border border-slate-200 text-slate-700 py-2.5 pl-10 pr-8 rounded-xl text-sm font-semibold shadow-sm hover:bg-slate-100 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 cursor-pointer">
                        <option value="today" @if(($filter ?? 'today') === 'today') selected @endif>Today</option>
                        <option value="yesterday" @if(($filter ?? 'today') === 'yesterday') selected @endif>Yesterday</option>
                        <option value="week" @if(($filter ?? 'today') === 'week') selected @endif>Last 7 Days</option>
                        <option value="month" @if(($filter ?? 'today') === 'month') selected @endif>This Month</option>
                        <option value="all" @if(($filter ?? 'today') === 'all') selected @endif>All Time</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </form>
            <a href="{{ route('bills.create') }}" class="btn-primary shadow-sm !py-2.5 !px-5 text-sm w-full sm:w-auto justify-center text-center">
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
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Side: Core Lists & Activity (Col-Span-8) -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- Recent Invoices / Bills Card -->
            <div class="glass-card !p-0 overflow-hidden">
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
                <div class="text-center py-12 px-6">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    <p class="text-slate-500 font-semibold">No invoices recorded yet. <a href="{{ route('bills.create') }}" class="text-primary-600 hover:underline">Generate a bill</a></p>
                </div>
                @endif
            </div>

            <!-- Active Work Orders Card -->
            <div class="glass-card !p-0 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white flex-wrap gap-4">
                    <h3 class="text-base font-bold text-slate-900 font-outfit">Active Shop Jobs</h3>
                    <a href="{{ route('work-orders.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">View All Jobs →</a>
                </div>
                @if($pendingOrders->count())
                <div class="overflow-x-auto table-scroll-wrapper">
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
                <div class="text-center py-12 px-6">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                    <p class="text-slate-500 font-semibold">No active jobs in the queue.</p>
                </div>
                @endif
            </div>

        </div>

        <!-- Right Side: Sidebar & Fast Actions (Col-Span-4) -->
        <div class="lg:col-span-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-8 lg:space-y-8 lg:gap-0">
            
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
                    @foreach($lowStockProducts->take(5) as $product)
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

            <!-- Statistics moved to top -->

            <div class="glass-card">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Subscription & Trial</h3>
                <div class="space-y-3">
                    @if(auth()->user()->workshop)
                        @php
                            $workshop = auth()->user()->workshop;
                        @endphp
                        <div class="flex justify-between items-center text-xs pb-2 border-b border-slate-100">
                            <span class="text-slate-500 font-semibold">Account Type</span>
                            <span class="font-bold uppercase tracking-wider {{ in_array($workshop->subscription_status, ['active', 'fix', 'fixed']) ? 'text-emerald-600' : 'text-amber-600' }}">{{ $workshop->subscription_status }}</span>
                        </div>
                        @if(in_array($workshop->subscription_status, ['trial', 'training']) && $workshop->trial_ends_at)
                        <div class="flex justify-between items-center text-xs pb-2 border-b border-slate-100">
                            <span class="text-slate-500 font-semibold">Expiration Date & Time</span>
                            <span class="font-bold text-slate-700">{{ $workshop->trial_ends_at->format('d M Y, h:i A') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs pb-2 border-b border-slate-100">
                            <span class="text-slate-500 font-semibold">Status Details</span>
                            @if($workshop->isTrialExpired())
                                <span class="text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded border border-rose-100">Expired</span>
                            @else
                                <span class="text-slate-700 font-bold">{{ $workshop->getTrialDaysRemaining() }} days left</span>
                            @endif
                        </div>
                        @else
                        <div class="flex justify-between items-center text-xs pb-2 border-b border-slate-100">
                            <span class="text-slate-500 font-semibold">Expiration Date</span>
                            <span class="font-bold text-emerald-600">Never (Lifetime)</span>
                        </div>
                        <div class="flex justify-between items-center text-xs pb-2 border-b border-slate-100">
                            <span class="text-slate-500 font-semibold">Status Details</span>
                            <span class="text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">Active & Unlimited</span>
                        </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- AI Coming Soon Card --}}
            <div class="glass-card relative overflow-hidden border border-violet-200/60 bg-gradient-to-br from-violet-50 to-indigo-50">
                {{-- Decorative glow --}}
                <div class="absolute -top-8 -right-8 w-32 h-32 rounded-full bg-violet-300/30 blur-2xl pointer-events-none"></div>
                <div class="absolute -bottom-6 -left-6 w-24 h-24 rounded-full bg-indigo-300/20 blur-xl pointer-events-none"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center shadow-md shadow-violet-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-violet-900">AI Workshop Assistant</h3>
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-widest text-violet-500 bg-violet-100 border border-violet-200 px-2 py-0.5 rounded-full">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-violet-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-violet-500"></span>
                                </span>
                                Coming Soon
                            </span>
                        </div>
                    </div>
                    
                    <p class="text-xs text-violet-700/80 font-medium leading-relaxed mb-4">
                        Your AI-powered assistant will help you generate invoices, answer customer queries, get business insights, and much more — right from your dashboard.
                    </p>
                    
                    {{-- Feature previews --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-xs text-violet-700/70 font-medium"><span>💬</span><span>Smart invoice generation</span></div>
                        <div class="flex items-center gap-2 text-xs text-violet-700/70 font-medium"><span>📊</span><span>Business insights &amp; reports</span></div>
                        <div class="flex items-center gap-2 text-xs text-violet-700/70 font-medium"><span>🔧</span><span>Auto work order suggestions</span></div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-violet-200/60">
                        <button disabled class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-gradient-to-r from-violet-500 to-indigo-600 text-white text-xs font-bold opacity-60 cursor-not-allowed select-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Launch AI Assistant
                        </button>
                    </div>
                </div>
            </div>

        </div>

</div>
@endsection
