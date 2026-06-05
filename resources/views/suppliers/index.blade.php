@extends('layouts.app')
@section('title', 'Suppliers')
@section('page-title', 'Suppliers')
@section('page-subtitle', 'Manage your supplier & vendor contacts')

@section('content')
<div x-data="{
    showModal: false,
    modalTitle: '',
    actionUrl: '',
    methodOverride: '',
    viewMode: false,
    supplier: { id: null, name: '', contact_person: '', phone: '', email: '', address: '' },

    openCreate() {
        this.modalTitle = 'Add New Supplier';
        this.actionUrl = '{{ route('suppliers.store') }}';
        this.methodOverride = '';
        this.viewMode = false;
        this.supplier = { id: null, name: '', contact_person: '', phone: '', email: '', address: '' };
        this.showModal = true;
    },
    openEdit(s) {
        this.modalTitle = 'Edit Supplier';
        this.actionUrl = '/suppliers/' + s.id;
        this.methodOverride = 'PUT';
        this.viewMode = false;
        this.supplier = { ...s };
        this.showModal = true;
    },
    openView(s) {
        this.modalTitle = 'Supplier Details';
        this.viewMode = true;
        this.supplier = { ...s };
        this.showModal = true;
        this.actionUrl = '';
    }
}" class="relative">

    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <form method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search suppliers..." class="form-input sm:w-72" id="search-suppliers">
            <button type="submit" class="btn-secondary">Search</button>
        </form>
        <button @click="openCreate()" class="btn-primary animate-pulse-glow" id="btn-add-supplier">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Supplier
        </button>
    </div>

    {{-- Table Card --}}
    <div class="glass-card !p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table" id="suppliers-table">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Company Name</th>
                        <th>Contact Person</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                    <tr>
                        <td data-label="Sl No" class="text-slate-400 font-semibold">{{ ($suppliers->currentPage() - 1) * $suppliers->perPage() + $loop->iteration }}</td>
                        <td data-label="Company Name" class="font-bold text-slate-800">{{ $supplier->name }}</td>
                        <td data-label="Contact Person" class="font-medium text-slate-600">{{ $supplier->contact_person ?? '—' }}</td>
                        <td data-label="Phone" class="font-medium text-slate-600">{{ $supplier->phone ?? '—' }}</td>
                        <td data-label="Email" class="font-medium text-slate-600 text-sm">{{ $supplier->email ?? '—' }}</td>
                        <td data-label="">
                            <div class="flex items-center gap-2">
                                {{-- View --}}
                                <button @click="openView({
                                    id: {{ $supplier->id }},
                                    name: '{{ addslashes($supplier->name) }}',
                                    contact_person: '{{ addslashes($supplier->contact_person ?? '') }}',
                                    phone: '{{ addslashes($supplier->phone ?? '') }}',
                                    email: '{{ addslashes($supplier->email ?? '') }}',
                                    address: '{{ addslashes($supplier->address ?? '') }}'
                                })" class="text-sky-600 hover:text-sky-700 transition-colors" title="View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                {{-- Edit --}}
                                <button @click="openEdit({
                                    id: {{ $supplier->id }},
                                    name: '{{ addslashes($supplier->name) }}',
                                    contact_person: '{{ addslashes($supplier->contact_person ?? '') }}',
                                    phone: '{{ addslashes($supplier->phone ?? '') }}',
                                    email: '{{ addslashes($supplier->email ?? '') }}',
                                    address: '{{ addslashes($supplier->address ?? '') }}'
                                })" class="text-amber-600 hover:text-amber-700 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                {{-- Delete --}}
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Delete supplier {{ $supplier->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-600 transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-16">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <p class="text-slate-400 font-semibold">No suppliers found.</p>
                                <button @click="openCreate()" class="text-primary-600 hover:underline text-sm font-medium">Add your first supplier</button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($suppliers->hasPages())
        <div class="px-5 py-4 border-t border-slate-200/60">{{ $suppliers->appends(request()->query())->links() }}</div>
        @endif
    </div>

    {{-- Modal --}}
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="showModal = false" class="bg-white rounded-3xl p-8 max-w-lg w-full relative border border-slate-100 shadow-2xl animate-fade-in-up" style="animation-duration: 0.2s;">

            {{-- Close --}}
            <button @click="showModal = false" class="absolute top-5 right-5 p-2 rounded-xl text-slate-400 hover:text-slate-900 hover:bg-slate-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            {{-- Title --}}
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-950" x-text="modalTitle"></h3>
            </div>

            {{-- View Mode --}}
            <div x-show="viewMode" class="space-y-4">
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Company Name</span>
                            <p class="text-base font-bold text-slate-900" x-text="supplier.name"></p>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Contact Person</span>
                            <p class="text-sm font-semibold text-slate-800" x-text="supplier.contact_person || '—'"></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Phone</span>
                            <p class="text-sm font-semibold text-slate-800" x-text="supplier.phone || '—'"></p>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Email</span>
                            <p class="text-sm font-semibold text-slate-800 break-all" x-text="supplier.email || '—'"></p>
                        </div>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Address</span>
                        <p class="text-sm font-medium text-slate-700 leading-relaxed" x-text="supplier.address || '—'"></p>
                    </div>
                </div>
                <div class="flex justify-end pt-2">
                    <button @click="showModal = false" class="btn-secondary !py-2">Close</button>
                </div>
            </div>

            {{-- Create / Edit Form --}}
            <form x-show="!viewMode" :action="actionUrl" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="PUT" x-bind:disabled="methodOverride !== 'PUT'">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="form-label">Company Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" x-model="supplier.name" required placeholder="e.g. Acme Auto Parts" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" x-model="supplier.contact_person" placeholder="Full name" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" x-model="supplier.phone" placeholder="Phone number" class="form-input">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" x-model="supplier.email" placeholder="Email address" class="form-input">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="form-label">Address</label>
                        <textarea name="address" x-model="supplier.address" placeholder="Company address" class="form-input h-24 resize-none"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 mt-2">
                    <button type="button" @click="showModal = false" class="btn-secondary !py-2">Cancel</button>
                    <button type="submit" class="btn-primary !py-2" x-text="methodOverride === 'PUT' ? 'Save Changes' : 'Add Supplier'"></button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>[x-cloak] { display: none !important; }</style>
@endsection
