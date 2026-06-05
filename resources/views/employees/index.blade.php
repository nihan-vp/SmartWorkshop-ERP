@extends('layouts.app')
@section('title', 'Employees')
@section('page-title', 'Employees')
@section('page-subtitle', 'Manage your team')
@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <form method="GET" class="flex items-center gap-2 w-full sm:w-auto">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search team members..." class="form-input sm:w-72">
        <button type="submit" class="btn-secondary">Search</button>
    </form>
    @if(\App\Models\Employee::count() >= 1)
        <button type="button" disabled class="btn-primary !bg-slate-400 !border-slate-400 opacity-60 cursor-not-allowed flex items-center gap-2" title="Limit of 1 employee reached">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Staff (Limit Reached)
        </button>
    @else
        <a href="{{ route('employees.create') }}" class="btn-primary"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Add Staff</a>
    @endif
</div>
<div class="glass-card !p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Sl No</th><th>Name</th><th>Phone</th><th>Role</th><th>Salary</th><th>Join Date</th><th>Status</th><th>Work Orders</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($employees as $e)
                <tr>
                    <td data-label="Sl No" class="text-slate-400 font-semibold">{{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration }}</td>
                    <td data-label="Name" class="font-bold text-slate-800">{{ $e->name }}</td>
                    <td data-label="Phone" class="font-medium text-slate-600">{{ $e->phone ?? '—' }}</td>
                    <td data-label="Role"><span class="badge badge-purple">{{ $e->role }}</span></td>
                    <td data-label="Salary" class="text-emerald-600 font-bold">₹{{ number_format($e->salary, 2) }}</td>
                    <td data-label="Join Date" class="font-medium text-slate-600">{{ $e->join_date ? $e->join_date->format('d M Y') : '—' }}</td>
                    <td data-label="Status"><span class="badge {{ $e->status === 'active' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($e->status) }}</span></td>
                    <td data-label="Work Orders" class="font-semibold text-slate-700 text-center">{{ $e->work_orders_count }}</td>
                    <td data-label="">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('employees.edit', $e) }}" class="text-amber-600 hover:text-amber-700" title="Edit"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                            <form action="{{ route('employees.destroy', $e) }}" method="POST" onsubmit="return confirm('Delete employee record for {{ $e->name }}?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-600" title="Delete"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center py-8 text-slate-400 font-medium font-semibold">No team members found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($employees->hasPages())<div class="px-5 py-4 border-t border-slate-200/60">{{ $employees->appends(request()->query())->links() }}</div>@endif
</div>
@endsection
