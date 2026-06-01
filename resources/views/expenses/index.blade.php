@extends('layouts.app')
@section('title', 'Expenses')
@section('page-title', 'Expenses')
@section('page-subtitle', 'Manage workshop expenses')
@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <form method="GET" class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search description..." class="form-input sm:w-60">
        
        <select name="category" class="form-select w-40">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        
        <select name="payment_method" class="form-select w-36">
            <option value="">All Methods</option>
            <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="upi" {{ request('payment_method') === 'upi' ? 'selected' : '' }}>UPI</option>
        </select>
        
        <button type="submit" class="btn-secondary">Filter</button>
    </form>
    <a href="{{ route('expenses.create') }}" class="btn-primary"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Add Expense</a>
</div>
<div class="glass-card !p-0 overflow-hidden">
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
                <tr><td colspan="7" class="text-center py-8 text-slate-400 font-medium font-semibold">No expenses found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($expenses->hasPages())<div class="px-5 py-4 border-t border-slate-200/60">{{ $expenses->links() }}</div>@endif
</div>
@endsection
