@extends('layouts.app')
@section('title', 'Services')
@section('page-title', 'Services')
@section('page-subtitle', 'Manage workshop services')
@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <form method="GET" class="flex items-center gap-2 w-full sm:w-auto">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search services..." class="form-input sm:w-72">
        <select name="limit" onchange="this.form.submit()" class="form-input sm:w-24" title="Rows per page">
            <option value="15" {{ request('limit') == 15 ? 'selected' : '' }}>15 / page</option>
            <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25 / page</option>
            <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50 / page</option>
            <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100 / page</option>
        </select>
        <button type="submit" class="btn-secondary">Search</button>
    </form>
    <a href="{{ route('services.create') }}" class="btn-primary"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Add Service</a>
</div>
<div class="glass-card !p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Sl No</th><th>Name</th><th>Category</th><th>Price</th><th>Duration</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($services as $s)
                <tr>
                    <td data-label="Sl No" class="text-slate-400 font-semibold">{{ ($services->currentPage() - 1) * $services->perPage() + $loop->iteration }}</td>
                    <td data-label="Name" class="font-bold text-slate-800">{{ $s->name }}</td>
                    <td data-label="Category" class="font-medium text-slate-600">{{ $s->category ?? '—' }}</td>
                    <td data-label="Price" class="text-emerald-600 font-bold">₹{{ number_format($s->price, 2) }}</td>
                    <td data-label="Duration" class="font-medium text-slate-600">{{ $s->duration_minutes ? $s->duration_minutes.' min' : '—' }}</td>
                    <td data-label="">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('services.edit', $s) }}" class="text-amber-600 hover:text-amber-700" title="Edit"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                            <form action="{{ route('services.destroy', $s) }}" method="POST" onsubmit="return confirm('Delete service {{ $s->name }}?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-600" title="Delete"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-8 text-slate-400 font-medium font-semibold">No services found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($services->hasPages())<div class="px-5 py-4 border-t border-slate-200/60">{{ $services->appends(request()->query())->links() }}</div>@endif
</div>
@endsection
