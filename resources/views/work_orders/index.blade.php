@extends('layouts.app')
@section('title', 'Work Orders')
@section('page-title', 'Work Orders')
@section('page-subtitle', 'Manage ongoing workshop jobs')
@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <form method="GET" class="flex items-center gap-2 w-full sm:w-auto">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search orders..." class="form-input sm:w-72">
        <select name="status" class="form-select w-36">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
        <button type="submit" class="btn-secondary">Filter</button>
    </form>
    <a href="{{ route('work-orders.create') }}" class="btn-primary"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>New Work Order</a>
</div>
<div class="glass-card !p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Order No</th><th>Customer / Vehicle</th><th>Assigned To</th><th>Status</th><th>Priority</th><th>Est. Cost</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($workOrders as $wo)
                <tr>
                    <td data-label="Order No" class="font-mono text-primary-600 font-semibold"><a href="{{ route('work-orders.show', $wo) }}" class="hover:underline">{{ $wo->order_number }}</a></td>
                    <td data-label="Customer / Vehicle">
                        <p class="font-semibold text-slate-800">{{ $wo->customer->name }}</p>
                        <p class="text-xs text-slate-400 font-medium">{{ $wo->vehicle ? $wo->vehicle->plate_number : '—' }}</p>
                    </td>
                    <td data-label="Assigned To" class="font-medium text-slate-600">{{ $wo->employee ? $wo->employee->name : 'Unassigned' }}</td>
                    <td data-label="Status">
                        <span class="badge {{ $wo->status === 'completed' ? 'badge-success' : ($wo->status === 'in_progress' ? 'badge-info' : 'badge-warning') }}">
                            {{ ucfirst(str_replace('_', ' ', $wo->status)) }}
                        </span>
                    </td>
                    <td data-label="Priority">
                        <span class="badge {{ $wo->priority === 'urgent' ? 'badge-danger' : ($wo->priority === 'high' ? 'badge-warning' : 'badge-info') }}">
                            {{ ucfirst($wo->priority) }}
                        </span>
                    </td>
                    <td data-label="Est. Cost" class="font-bold text-slate-700">₹{{ number_format($wo->estimated_cost, 2) }}</td>
                    <td data-label="">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('work-orders.show', $wo) }}" class="text-sky-600 hover:text-sky-700" title="View"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                            <a href="{{ route('work-orders.edit', $wo) }}" class="text-amber-600 hover:text-amber-700" title="Edit"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                            <form action="{{ route('work-orders.destroy', $wo) }}" method="POST" onsubmit="return confirm('Delete work order {{ $wo->order_number }}?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-600" title="Delete"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-8 text-slate-400 font-medium">No work orders found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($workOrders->hasPages())<div class="px-5 py-4 border-t border-slate-200/60">{{ $workOrders->links() }}</div>@endif
</div>
@endsection
