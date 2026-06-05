@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Overview')
@section('page-subtitle', 'Workshop Performance & Operations')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 sm:space-y-8 animate-fade-in-up">

    <!-- 1. Welcome & Overview Banner -->
    <div class="bg-white rounded-3xl p-6 lg:p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-24 -top-24 w-64 h-64 bg-emerald-50 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="space-y-2 z-10">
            <h3 class="text-2xl font-bold font-outfit text-slate-800 flex items-center gap-2 tracking-tight">
                Welcome back, Workshop Manager
                <span class="relative flex h-3 w-3 ml-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
            </h3>
            <p class="text-slate-500 text-sm">Here is a top-level summary of your shop's operations and finances.</p>
        </div>

        <div class="w-full md:w-auto flex flex-col sm:flex-row items-center z-10 gap-4">
            <form method="GET" action="{{ route('dashboard') }}" class="w-full sm:w-auto relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" type="button" class="w-full sm:w-48 bg-slate-50 border border-slate-200 text-slate-700 py-3 px-4 rounded-xl text-sm font-semibold flex items-center justify-between hover:bg-slate-100 transition-colors">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ match($filter ?? 'today') {
                            'today' => 'Today',
                            'yesterday' => 'Yesterday',
                            'week' => 'Last 7 Days',
                            'month' => 'This Month',
                            'all' => 'All Time',
                            default => 'Today'
                        } }}
                    </span>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-transition class="absolute top-full mt-2 w-full sm:w-48 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden z-50" style="display: none;">
                    <a href="?filter=today" class="block px-4 py-3 text-sm font-medium text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 {{ ($filter ?? 'today') === 'today' ? 'bg-emerald-50 text-emerald-700' : '' }}">Today</a>
                    <a href="?filter=yesterday" class="block px-4 py-3 text-sm font-medium text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 {{ ($filter ?? 'today') === 'yesterday' ? 'bg-emerald-50 text-emerald-700' : '' }}">Yesterday</a>
                    <a href="?filter=week" class="block px-4 py-3 text-sm font-medium text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 {{ ($filter ?? 'today') === 'week' ? 'bg-emerald-50 text-emerald-700' : '' }}">Last 7 Days</a>
                    <a href="?filter=month" class="block px-4 py-3 text-sm font-medium text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 {{ ($filter ?? 'today') === 'month' ? 'bg-emerald-50 text-emerald-700' : '' }}">This Month</a>
                    <a href="?filter=all" class="block px-4 py-3 text-sm font-medium text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 {{ ($filter ?? 'today') === 'all' ? 'bg-emerald-50 text-emerald-700' : '' }}">All Time</a>
                </div>
            </form>
            <a href="{{ route('bills.create') }}" class="btn-primary shadow-lg shadow-emerald-500/20 !py-3 !px-6 w-full sm:w-auto justify-center text-sm">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Invoice
            </a>
        </div>
    </div>

    <!-- 2. The Vital Core (Bento Metrics) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Income -->
        <div class="bg-white rounded-3xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 hover:-translate-y-1 hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.1)] transition-all duration-300 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-50 rounded-bl-full opacity-50 -z-10 group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between mb-6">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase tracking-wider rounded-lg">Income</span>
            </div>
            <p class="text-3xl font-black text-slate-800 font-outfit tracking-tight">₹{{ number_format($totalIncome, 2) }}</p>
            <div class="mt-4 flex items-center gap-4 text-xs font-medium text-slate-500">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-400"></span>UPI: ₹{{ number_format($upiPayments, 0) }}</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-slate-300"></span>Cash: ₹{{ number_format($cashPayments, 0) }}</span>
            </div>
        </div>

        <!-- Total Expenses -->
        <div class="bg-white rounded-3xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 hover:-translate-y-1 hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.1)] transition-all duration-300 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-rose-50 rounded-bl-full opacity-50 -z-10 group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between mb-6">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                </div>
                <span class="px-3 py-1 bg-rose-100 text-rose-700 text-[10px] font-bold uppercase tracking-wider rounded-lg">Outflow</span>
            </div>
            <p class="text-3xl font-black text-slate-800 font-outfit tracking-tight">₹{{ number_format($totalExpensesAll, 2) }}</p>
            <div class="mt-4 flex items-center gap-4 text-xs font-medium text-slate-500">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-rose-400"></span>Shop: ₹{{ number_format($totalExpenses, 0) }}</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-slate-300"></span>Salaries: ₹{{ number_format($totalSalaries, 0) }}</span>
            </div>
        </div>

        <!-- Net Margin -->
        <div class="bg-white rounded-3xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 hover:-translate-y-1 hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.1)] transition-all duration-300 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-violet-50 rounded-bl-full opacity-50 -z-10 group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between mb-6">
                <div class="w-12 h-12 rounded-2xl bg-violet-50 flex items-center justify-center text-violet-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                </div>
                <span class="px-3 py-1 bg-violet-100 text-violet-700 text-[10px] font-bold uppercase tracking-wider rounded-lg">Net Profit</span>
            </div>
            <p class="text-3xl font-black font-outfit tracking-tight {{ $totalProfit < 0 ? 'text-rose-600' : 'text-slate-800' }}">₹{{ number_format($totalProfit, 2) }}</p>
            <div class="mt-4 flex items-center gap-2 text-xs font-medium text-slate-500">
                Total Shop Balance Margin
            </div>
        </div>

        <!-- Job Queue -->
        <div class="bg-white rounded-3xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 hover:-translate-y-1 hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.1)] transition-all duration-300 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-sky-50 rounded-bl-full opacity-50 -z-10 group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between mb-6">
                <div class="w-12 h-12 rounded-2xl bg-sky-50 flex items-center justify-center text-sky-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                </div>
                <span class="px-3 py-1 bg-sky-100 text-sky-700 text-[10px] font-bold uppercase tracking-wider rounded-lg">Jobs</span>
            </div>
            <p class="text-3xl font-black text-slate-800 font-outfit tracking-tight">{{ $totalWorkOrders }}</p>
            <div class="mt-4 flex items-center gap-4 text-xs font-medium text-slate-500">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-400"></span>{{ $pendingWorkOrders }} Pending</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-slate-300"></span>{{ $totalServices }} Services</span>
            </div>
        </div>

    </div>

    <!-- 3. The Split: Operations vs Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Side: Tabbed Data Area (Col-Span-8) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white border border-slate-100 rounded-3xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden" x-data="{ tab: 'invoices' }">
                
                <!-- Custom Tabs Header -->
                <div class="flex flex-wrap items-center border-b border-slate-100 bg-slate-50/50 p-2 gap-2">
                    <button @click="tab = 'invoices'" :class="tab === 'invoices' ? 'bg-white shadow-sm text-slate-900 border-slate-200' : 'text-slate-500 hover:text-slate-700 border-transparent'" class="px-5 py-3 rounded-2xl text-sm font-bold border transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Recent Invoices
                    </button>
                    <button @click="tab = 'jobs'" :class="tab === 'jobs' ? 'bg-white shadow-sm text-slate-900 border-slate-200' : 'text-slate-500 hover:text-slate-700 border-transparent'" class="px-5 py-3 rounded-2xl text-sm font-bold border transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                        Active Shop Jobs
                    </button>
                    <div class="ml-auto hidden sm:block pr-4">
                        <a href="{{ route('bills.index') }}" x-show="tab === 'invoices'" class="text-xs font-bold text-primary-600 hover:text-primary-700">View All →</a>
                        <a href="{{ route('work-orders.index') }}" x-show="tab === 'jobs'" class="text-xs font-bold text-primary-600 hover:text-primary-700" style="display: none;">View All →</a>
                    </div>
                </div>

                <!-- Tab 1: Invoices -->
                <div x-show="tab === 'invoices'">
                    @if($recentBills->count())
                    <div class="overflow-x-auto table-scroll-wrapper p-2">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">Invoice No</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">Customer</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">Total</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($recentBills as $bill)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-mono font-bold text-slate-700 text-sm">{{ $bill->bill_number }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-800">{{ $bill->customer->name }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-900">₹{{ number_format($bill->total, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider
                                            {{ $bill->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-700' : ($bill->payment_status === 'partial' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                            {{ ucfirst($bill->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-16">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                        </div>
                        <p class="text-slate-500 font-medium">No invoices generated yet.</p>
                    </div>
                    @endif
                </div>

                <!-- Tab 2: Jobs -->
                <div x-show="tab === 'jobs'" style="display: none;">
                    @if($pendingOrders->count())
                    <div class="overflow-x-auto table-scroll-wrapper p-2">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">Order No</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">Vehicle</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">Mechanic</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($pendingOrders as $order)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-mono font-bold text-primary-600 text-sm">
                                        <a href="{{ route('work-orders.show', $order) }}" class="hover:underline">{{ $order->order_number }}</a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-800 text-sm">{{ $order->customer->name }}</p>
                                        <p class="text-xs text-slate-400 font-semibold">{{ $order->vehicle ? $order->vehicle->plate_number : '—' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-600">{{ $order->employee ? $order->employee->name : 'Unassigned' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-sky-100 text-sky-700' }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-16">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                        </div>
                        <p class="text-slate-500 font-medium">No active jobs in the queue.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Side: Unified Action Center (Col-Span-4) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Quick Actions -->
            <div class="bg-slate-900 rounded-3xl p-6 shadow-xl relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-primary-500 rounded-full blur-3xl opacity-30 pointer-events-none"></div>
                <h3 class="text-sm font-bold text-slate-100 uppercase tracking-widest mb-4">Fast Actions</h3>
                <div class="space-y-3 relative z-10">
                    <a href="{{ route('work-orders.create') }}" class="w-full bg-white/10 hover:bg-white/20 border border-white/5 text-white py-3 px-4 rounded-2xl text-sm font-bold flex items-center gap-3 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                        </div>
                        Log Repair Order
                    </a>
                    <a href="{{ route('customers.create') }}" class="w-full bg-white/10 hover:bg-white/20 border border-white/5 text-white py-3 px-4 rounded-2xl text-sm font-bold flex items-center gap-3 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        </div>
                        New Customer Record
                    </a>
                </div>
            </div>

            <!-- Low Stock Mini Card -->
            <div class="bg-white rounded-3xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest">Inventory Alerts</h3>
                    @if($lowStockProducts->count())
                        <span class="w-6 h-6 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xs font-black">{{ $lowStockProducts->count() }}</span>
                    @else
                        <span class="text-emerald-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span>
                    @endif
                </div>
                
                @if($lowStockProducts->count())
                <div class="space-y-3">
                    @foreach($lowStockProducts->take(3) as $product)
                    <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                        <p class="text-sm font-semibold text-slate-700 truncate pr-3">{{ $product->name }}</p>
                        <span class="text-sm font-black text-rose-500">{{ $product->stock_qty }} left</span>
                    </div>
                    @endforeach
                    @if($lowStockProducts->count() > 3)
                        <a href="#" class="block text-center text-xs font-bold text-primary-600 pt-2 hover:underline">View {{ $lowStockProducts->count() - 3 }} more</a>
                    @endif
                </div>
                @else
                <p class="text-sm text-slate-500 font-medium">All parts and inventory are sufficiently stocked.</p>
                @endif
            </div>

        </div>

    </div>

    <!-- 4. Mini Stats (Pill Bar) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-white border border-slate-100 rounded-3xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.02)] p-4 sm:p-6">
        <div class="flex items-center gap-4 p-2 sm:p-4 border-r border-slate-100 last:border-0 md:last:border-r-0">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Warranties</p>
                <p class="text-xl font-black text-slate-800">{{ $activeWarranties }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4 p-2 sm:p-4 border-r-0 md:border-r border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Customers</p>
                <p class="text-xl font-black text-slate-800">{{ $totalCustomers }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4 p-2 sm:p-4 border-r border-slate-100 last:border-0 md:last:border-r-0">
            <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Vehicles</p>
                <p class="text-xl font-black text-slate-800">{{ $totalVehicles }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4 p-2 sm:p-4 border-r-0 border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Staff</p>
                <p class="text-xl font-black text-slate-800">{{ $totalEmployees }}</p>
            </div>
        </div>
    </div>

</div>
@endsection
