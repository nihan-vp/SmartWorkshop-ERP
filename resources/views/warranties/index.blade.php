@extends('layouts.app')
@section('title', 'Warranties')
@section('page-title', 'Warranties')
@section('page-subtitle', 'Manage product and service warranties')
@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <form method="GET" class="flex items-center gap-2 w-full sm:w-auto">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer..." class="form-input sm:w-72">
        <select name="status" class="form-select w-32">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
            <option value="claimed" {{ request('status') === 'claimed' ? 'selected' : '' }}>Claimed</option>
        </select>
        <button type="submit" class="btn-secondary">Filter</button>
    </form>
    <a href="{{ route('warranties.create') }}" class="btn-primary"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Add Warranty</a>
</div>
<div class="glass-card !p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Sl No</th><th>Customer</th><th>Vehicle</th><th>Invoice No</th><th>Valid From</th><th>Valid Until</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($warranties as $w)
                <tr>
                    <td data-label="Sl No" class="text-slate-400 font-semibold">{{ $w->id }}</td>
                    <td data-label="Customer" class="font-bold text-slate-800">{{ $w->customer->name }}</td>
                    <td data-label="Vehicle" class="font-medium text-slate-600">{{ $w->vehicle ? $w->vehicle->plate_number : '—' }}</td>
                    <td data-label="Invoice No">
                        @if($w->bill)
                        <span class="text-slate-800 font-semibold">{{ $w->bill->bill_number }}</span>
                        @else
                        —
                        @endif
                    </td>
                    <td data-label="Valid From" class="font-medium text-slate-600">{{ $w->start_date->format('d M Y') }}</td>
                    <td data-label="Valid Until" class="font-semibold {{ $w->isExpired() ? 'text-red-600' : 'text-slate-600' }}">{{ $w->end_date->format('d M Y') }}</td>
                    <td data-label="Status">
                        @if($w->status === 'active' && $w->isExpired())
                        <span class="badge badge-danger">Expired</span>
                        @else
                        <span class="badge {{ $w->status === 'active' ? 'badge-success' : ($w->status === 'claimed' ? 'badge-info' : 'badge-danger') }}">{{ ucfirst($w->status) }}</span>
                        @endif
                    </td>
                    <td data-label="">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('warranties.edit', $w) }}" class="text-amber-600 hover:text-amber-700" title="Edit"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                            <form action="{{ route('warranties.destroy', $w) }}" method="POST" onsubmit="return confirm('Delete warranty for {{ $w->customer->name }}?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-600" title="Delete"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-8 text-slate-400 font-medium">No warranties found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($warranties->hasPages())<div class="px-5 py-4 border-t border-slate-200/60">{{ $warranties->links() }}</div>@endif
</div>
@endsection
