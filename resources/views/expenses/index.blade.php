@extends('layouts.app')
@section('title', 'Expenses')
@section('page-title', 'Expenses')
@section('page-subtitle', 'Manage workshop expenses')
@section('content')
<div class="glass-card !p-0 overflow-hidden mt-6">

    {{-- ── Advanced Filter Bar ── --}}
    <div class="bg-white border-b border-slate-200/60">

        {{-- Top Row: Title + Add Button --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div>
                    <h2 class="text-sm font-bold text-slate-800">All Expenses</h2>
                    <p class="text-xs text-slate-400 font-medium">Filter and manage records</p>
                </div>
            </div>
            <a href="{{ route('expenses.create') }}" class="btn-primary px-4 py-2 text-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Expense
            </a>
        </div>

        {{-- Filter Row --}}
        <form method="GET" id="expenseFilter" class="px-5 py-3.5 flex flex-wrap items-end gap-3">

            {{-- Search --}}
            <div class="flex-1 min-w-[160px]">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Search</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search description..." class="form-input pl-9 py-2 text-sm w-full">
                </div>
            </div>

            {{-- Category --}}
            <div class="min-w-[130px]">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Category</label>
                <select name="category" class="form-select py-2 text-sm w-full">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Payment Method --}}
            <div class="min-w-[120px]">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Method</label>
                <select name="payment_method" class="form-select py-2 text-sm w-full">
                    <option value="">All Methods</option>
                    <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="upi" {{ request('payment_method') === 'upi' ? 'selected' : '' }}>UPI</option>
                </select>
            </div>

            {{-- Date Range --}}
            <div class="min-w-[120px]">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">From</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-input py-2 text-sm w-full">
            </div>
            <div class="min-w-[120px]">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">To</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input py-2 text-sm w-full">
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-2 shrink-0">
                <button type="submit" class="btn-primary py-2 px-4 text-sm">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                    Filter
                </button>
                <a href="{{ route('expenses.index') }}" class="btn-secondary py-2 px-4 text-sm">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Clear
                </a>
            </div>

        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Sl No</th><th>Date</th><th>Category</th><th>Description</th><th>Amount</th><th>Payment Method</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($expenses as $expense)
                <tr>
                    <td data-label="Sl No" class="text-slate-400 font-semibold">{{ $expense->id }}</td>
                    <td data-label="Date" class="font-medium text-slate-600">{{ $expense->expense_date->format('d M Y') }}</td>
                    <td data-label="Category" class="font-bold text-slate-800">{{ $expense->category }}</td>
                    <td data-label="Description" class="text-slate-600 font-medium">{{ Str::limit($expense->description ?? '—', 30) }}</td>
                    <td data-label="Amount" class="text-rose-600 font-bold">-₹{{ number_format($expense->amount, 2) }}</td>
                    <td data-label="Payment"><span class="badge {{ $expense->payment_method === 'upi' ? 'badge-info' : 'badge-warning' }}">{{ strtoupper($expense->payment_method) }}</span></td>
                    <td data-label="">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('expenses.edit', $expense) }}" class="text-amber-600 hover:text-amber-700" title="Edit"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Delete expense record for {{ $expense->category }}?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-600" title="Delete"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-12 text-slate-400 font-medium">No expenses found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($expenses->hasPages())<div class="px-5 py-4 border-t border-slate-100">{{ $expenses->appends(request()->query())->links() }}</div>@endif
</div>
@endsection
