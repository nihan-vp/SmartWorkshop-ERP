@extends('layouts.app')
@section('title', 'Manage Branches')
@section('page-title', 'Manage Branches')
@section('page-subtitle', 'Manage all physical locations for your enterprise')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div></div>
    <a href="{{ route('branches.create') }}" class="btn-primary !py-2 !px-4 flex items-center gap-2 shadow-sm text-xs font-bold rounded-xl">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add New Branch
    </a>
</div>

<div class="glass-card !p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-1/4">Name</th>
                    <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Phone</th>
                    <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider hidden md:table-cell">Email</th>
                    <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Address</th>
                    <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100/50">
                @forelse($branches as $branch)
                    <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                        <td class="p-4">
                            <span class="font-semibold text-slate-800">{{ $branch->name }}</span>
                        </td>
                        <td class="p-4 text-sm text-slate-600">
                            {{ $branch->phone ?? 'N/A' }}
                        </td>
                        <td class="p-4 text-sm text-slate-600 hidden md:table-cell">
                            {{ $branch->email ?? 'N/A' }}
                        </td>
                        <td class="p-4 text-sm text-slate-600 hidden lg:table-cell">
                            {{ Str::limit($branch->address, 50) ?? 'N/A' }}
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('branches.edit', $branch) }}" class="p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors duration-200" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>
                                <form action="{{ route('branches.destroy', $branch) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this branch? All associated branch-specific data might be affected.');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors duration-200" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <p class="text-sm">No branches found.</p>
                                <a href="{{ route('branches.create') }}" class="text-primary-600 hover:text-primary-700 font-medium mt-2 text-sm">Create your first branch</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
