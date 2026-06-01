@extends('layouts.app')
@section('title', 'Customers')
@section('page-title', 'Customers')
@section('page-subtitle', 'Manage your customer database')

@section('content')
<div x-data="{ 
    showModal: false, 
    modalTitle: '', 
    actionUrl: '',
    methodOverride: '',
    customer: { id: null, name: '', phone: '', email: '', address: '' },
    openCreate() {
        this.modalTitle = 'Add Customer';
        this.actionUrl = '{{ route('customers.store') }}';
        this.methodOverride = '';
        this.customer = { id: null, name: '', phone: '', email: '', address: '' };
        this.showModal = true;
    },
    openEdit(cust) {
        this.modalTitle = 'Edit Customer';
        this.actionUrl = '/customers/' + cust.id;
        this.methodOverride = 'PUT';
        this.customer = { ...cust };
        this.showModal = true;
    },
    openView(cust) {
        this.modalTitle = 'Customer Profile';
        this.customer = { ...cust };
        this.showModal = true;
        this.actionUrl = '';
    }
}" class="relative">

    <!-- Search and Add Customer Button -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <form method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customers..." class="form-input sm:w-72" id="search-customers">
            <button type="submit" class="btn-secondary">Search</button>
        </form>
        <button @click="openCreate()" class="btn-primary animate-pulse-glow" id="btn-add-customer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Customer
        </button>
    </div>

    <!-- Customers Table -->
    <div class="glass-card !p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table" id="customers-table">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Vehicles</th>
                        <th>Bills</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td data-label="Sl No" class="text-slate-400 font-semibold">{{ $customer->id }}</td>
                        <td data-label="Name" class="font-bold text-slate-800">{{ $customer->name }}</td>
                        <td data-label="Phone" class="font-medium text-slate-600">{{ $customer->phone ?? '—' }}</td>
                        <td data-label="Email" class="font-medium text-slate-600">{{ $customer->email ?? '—' }}</td>
                        <td data-label="Vehicles"><span class="badge badge-info">{{ $customer->vehicles_count }}</span></td>
                        <td data-label="Bills"><span class="badge badge-purple">{{ $customer->bills_count }}</span></td>
                        <td data-label="">
                            <div class="flex items-center gap-2">
                                <button @click="openView({ 
                                    id: {{ $customer->id }}, 
                                    name: '{{ addslashes($customer->name) }}', 
                                    phone: '{{ addslashes($customer->phone ?? '') }}', 
                                    email: '{{ addslashes($customer->email ?? '') }}', 
                                    address: '{{ addslashes($customer->address ?? '') }}',
                                    vehicles_count: {{ $customer->vehicles_count }},
                                    bills_count: {{ $customer->bills_count }}
                                })" class="text-sky-600 hover:text-sky-700 transition-colors" title="View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <button @click="openEdit({ 
                                    id: {{ $customer->id }}, 
                                    name: '{{ addslashes($customer->name) }}', 
                                    phone: '{{ addslashes($customer->phone ?? '') }}', 
                                    email: '{{ addslashes($customer->email ?? '') }}', 
                                    address: '{{ addslashes($customer->address ?? '') }}'
                                })" class="text-amber-600 hover:text-amber-700 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Delete customer {{ $customer->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-600 transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-8 text-slate-400 font-medium font-semibold">No customers found. <button @click="openCreate()" class="text-primary-600 hover:underline">Add your first customer</button></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())
        <div class="px-5 py-4 border-t border-slate-200/60">{{ $customers->links() }}</div>
        @endif
    </div>

    <!-- Small Modal Popup Overlay -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="showModal = false" class="bg-white rounded-3xl p-8 max-w-md w-full relative border border-slate-100 shadow-2xl animate-fade-in-up" style="animation-duration: 0.2s;">
            
            <!-- Close (X) Icon Button -->
            <button @click="showModal = false" class="absolute top-5 right-5 p-2 rounded-xl text-slate-400 hover:text-slate-900 hover:bg-slate-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Modal Heading -->
            <h3 class="text-xl font-bold text-slate-950 mb-6 flex items-center gap-2" x-text="modalTitle"></h3>

            <!-- View Customer Content -->
            <div x-show="actionUrl === ''" class="space-y-4">
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 space-y-3">
                    <div>
                        <span class="text-xs font-bold text-slate-450 uppercase tracking-wider block">Full Name</span>
                        <p class="text-base font-bold text-slate-900" x-text="customer.name"></p>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-450 uppercase tracking-wider block">Phone Number</span>
                        <p class="text-sm font-semibold text-slate-800" x-text="customer.phone || '—'"></p>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-450 uppercase tracking-wider block">Email Address</span>
                        <p class="text-sm font-semibold text-slate-800" x-text="customer.email || '—'"></p>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-450 uppercase tracking-wider block">Residential Address</span>
                        <p class="text-sm font-medium text-slate-700 leading-relaxed" x-text="customer.address || '—'"></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-sky-50 border border-sky-100 rounded-2xl text-center">
                        <span class="text-[10px] font-bold text-sky-600 uppercase tracking-wider block">Vehicles Registered</span>
                        <span class="text-2xl font-extrabold text-sky-700 block mt-1" x-text="customer.vehicles_count"></span>
                    </div>
                    <div class="p-4 bg-violet-50 border border-violet-100 rounded-2xl text-center">
                        <span class="text-[10px] font-bold text-violet-600 uppercase tracking-wider block">Total Invoices</span>
                        <span class="text-2xl font-extrabold text-violet-700 block mt-1" x-text="customer.bills_count"></span>
                    </div>
                </div>
            </div>

            <!-- Create/Edit Customer Form -->
            <form x-show="actionUrl !== ''" :action="actionUrl" method="POST" class="space-y-4">
                @csrf
                <template x-if="methodOverride === 'PUT'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" x-model="customer.name" required placeholder="Enter customer name" class="form-input">
                </div>

                <div>
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" x-model="customer.phone" placeholder="Enter phone number" class="form-input">
                </div>

                <div>
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" x-model="customer.email" placeholder="Enter email address" class="form-input">
                </div>

                <div>
                    <label class="form-label">Address</label>
                    <textarea name="address" x-model="customer.address" placeholder="Enter postal address" class="form-input h-24 resize-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 mt-6">
                    <button type="button" @click="showModal = false" class="btn-secondary !py-2">Cancel</button>
                    <button type="submit" class="btn-primary !py-2" x-text="methodOverride === 'PUT' ? 'Save Changes' : 'Add Customer'"></button>
                </div>
            </form>

        </div>
    </div>

</div>

<!-- Alpine Cloak Styling to prevent flickering -->
<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
