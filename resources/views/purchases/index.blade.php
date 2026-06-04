@extends('layouts.app')

@section('title', 'Purchases')
@section('page-title', 'Purchases')
@section('page-subtitle', 'Manage supply and parts purchases')

@section('content')
<div x-data="{
    search: '{{ request('search') }}',
    payment_status: '{{ request('payment_status') }}',
    payment_method: '{{ request('payment_method') }}',
}">
    <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4 animate-fade-in-up">
        <div class="flex-1 max-w-3xl">
            <form action="{{ route('purchases.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <input type="text" name="search" x-model="search" placeholder="Search supplier, invoice..." class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200/80 rounded-xl text-sm focus:border-primary-400 focus:ring focus:ring-primary-100 transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <div class="w-full sm:w-40 relative">
                    <select name="payment_method" x-model="payment_method" class="w-full pl-9 pr-8 py-2 bg-white border border-slate-200/80 rounded-xl text-sm appearance-none focus:border-primary-400 focus:ring focus:ring-primary-100 transition-colors shadow-sm text-slate-700">
                        <option value="">Any Method</option>
                        <option value="cash">Cash</option>
                        <option value="upi">UPI</option>
                    </select>
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <div class="w-full sm:w-40 relative">
                    <select name="payment_status" x-model="payment_status" class="w-full pl-9 pr-8 py-2 bg-white border border-slate-200/80 rounded-xl text-sm appearance-none focus:border-primary-400 focus:ring focus:ring-primary-100 transition-colors shadow-sm text-slate-700">
                        <option value="">Any Status</option>
                        <option value="paid">Paid</option>
                        <option value="partial">Partial</option>
                        <option value="unpaid">Unpaid</option>
                    </select>
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <button type="submit" class="btn-primary py-2 shadow-sm shrink-0">Filter</button>
                @if(request()->anyFilled(['search', 'payment_status', 'payment_method']))
                    <a href="{{ route('purchases.index') }}" class="btn-secondary py-2 text-slate-500 hover:text-slate-700 shadow-sm shrink-0" title="Clear Filters">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </form>
        </div>
        <div class="shrink-0">
            <a href="{{ route('purchases.create') }}" class="btn-primary shadow-sm group">
                <svg class="w-4 h-4 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Purchase
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 animate-fade-in-up" style="animation-delay: 50ms;">
        <div class="glass-card border-l-4 border-l-blue-500 shadow-sm p-4">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Purchases</span>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">₹{{ number_format($totalPurchases, 2) }}</p>
        </div>
        <div class="glass-card border-l-4 border-l-rose-500 shadow-sm p-4">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Unpaid Amount</span>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">₹{{ number_format($unpaidPurchases, 2) }}</p>
        </div>
    </div>

    <div class="glass-card !p-0 overflow-hidden animate-fade-in-up shadow-sm" style="animation-delay: 100ms;">
        @if($purchases->isEmpty())
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">No purchases found</h3>
            <p class="text-sm text-slate-500 mt-1">Record your first purchase or supply expense.</p>
            <a href="{{ route('purchases.create') }}" class="btn-primary mt-4 py-2 inline-flex shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Purchase
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="data-table min-w-full">
                <thead>
                    <tr>
                        <th class="w-24">Date</th>
                        <th>Supplier</th>
                        <th>Invoice</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th class="text-right w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($purchases as $purchase)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td data-label="Date" class="font-medium text-slate-700 whitespace-nowrap">{{ $purchase->purchase_date->format('M d, Y') }}</td>
                        <td data-label="Supplier">
                            <div class="font-bold text-slate-900 text-sm">{{ $purchase->supplier_name }}</div>
                            @if($purchase->items_description)
                            <div class="text-[11px] text-slate-500 font-medium truncate max-w-[200px]" title="{{ $purchase->items_description }}">{{ Str::limit($purchase->items_description, 40) }}</div>
                            @endif
                        </td>
                        <td data-label="Invoice">
                            @if($purchase->invoice_number)
                            <span class="badge badge-info bg-slate-100 text-slate-600 border-slate-200">{{ $purchase->invoice_number }}</span>
                            @else
                            <span class="text-slate-400 text-xs">—</span>
                            @endif
                        </td>
                        <td data-label="Amount" class="font-bold text-slate-800 whitespace-nowrap">₹{{ number_format($purchase->total_amount, 2) }}</td>
                        <td data-label="Method" class="whitespace-nowrap">
                            <span class="badge {{ $purchase->payment_method === 'cash' ? 'badge-info' : 'badge-warning' }} capitalize">{{ $purchase->payment_method }}</span>
                        </td>
                        <td data-label="Status" class="whitespace-nowrap">
                            <span class="badge {{ $purchase->payment_status === 'paid' ? 'badge-success' : ($purchase->payment_status === 'partial' ? 'badge-warning' : 'badge-danger') }} capitalize">
                                {{ $purchase->payment_status }}
                            </span>
                        </td>
                        <td data-label="Actions" class="text-right whitespace-nowrap">
                            <div class="flex justify-end gap-1 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('purchases.edit', $purchase) }}" class="p-1.5 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" onsubmit="return confirm('Delete this purchase record?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete">
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
        @if($purchases->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $purchases->appends(request()->query())->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
