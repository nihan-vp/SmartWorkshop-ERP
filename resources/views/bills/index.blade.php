@extends('layouts.app')
@section('title', 'Invoices')
@section('page-title', 'Invoices')
@section('page-subtitle', 'Manage customer invoices')
@section('content')
<div class="w-full" x-data="{ 
    paymentModalOpen: false,
    paymentBillId: null,
    paymentBillNo: '',
    paymentBillTotal: 0,
    paymentBillPaid: 0,
    paymentRoute: ''
}">
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <form method="GET" class="flex flex-row items-center gap-2 w-full sm:w-auto">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search invoice # or customer..." class="form-input flex-1 min-w-[120px] sm:w-72 sm:flex-none">
        <select name="payment_method" class="form-select w-28 sm:w-32 shrink-0">
            <option value="">All Methods</option>
            <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="upi" {{ request('payment_method') === 'upi' ? 'selected' : '' }}>UPI</option>
        </select>
        <button type="submit" class="btn-secondary shrink-0">Filter</button>
    </form>
    <a href="{{ route('bills.create') }}" class="btn-primary w-full sm:w-auto justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Create Invoice</a>
</div>
<div class="glass-card !p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Sl No</th><th>Invoice No</th><th>Date</th><th>Customer</th><th>Vehicle</th><th>Total Amount</th><th>Payment Method</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($bills as $index => $bill)
                <tr>
                    <td data-label="Sl No" class="font-semibold text-slate-500">{{ ($bills->currentPage() - 1) * $bills->perPage() + $loop->iteration }}</td>
                    <td data-label="Invoice No" class="font-mono text-primary-600 font-bold">{{ $bill->bill_number }}</td>
                    <td data-label="Date" class="font-medium text-slate-600">{{ $bill->bill_date->format('d M Y') }}</td>
                    <td data-label="Customer" class="font-bold text-slate-800">{{ $bill->customer->name }}</td>
                    <td data-label="Vehicle" class="font-medium text-slate-600">{{ $bill->vehicle ? $bill->vehicle->plate_number : '—' }}</td>
                    <td data-label="Total" class="py-3 px-4">
                        <div class="font-bold text-slate-800">₹{{ number_format($bill->total, 2) }}</div>
                        @if($bill->payment_status !== 'paid')
                            <div class="text-[11px] font-semibold text-rose-500 mt-0.5" title="Amount Paid: ₹{{ number_format($bill->amount_paid, 2) }}">Due: ₹{{ number_format($bill->total - $bill->amount_paid, 2) }}</div>
                        @else
                            <div class="text-[11px] font-semibold text-emerald-500 mt-0.5">Paid: ₹{{ number_format($bill->amount_paid, 2) }}</div>
                        @endif
                    </td>
                    <td data-label="Payment"><span class="badge {{ $bill->payment_method === 'upi' ? 'badge-info' : 'badge-warning' }}">{{ strtoupper($bill->payment_method) }}</span></td>
                    <td data-label="Status">
                        <span class="badge {{ $bill->payment_status === 'paid' ? 'badge-success' : ($bill->payment_status === 'partial' ? 'badge-warning' : 'badge-danger') }}">
                             {{ ucfirst($bill->payment_status) }}
                        </span>
                    </td>
                    <td data-label="">
                        <div class="flex items-center gap-2 sm:gap-1 flex-wrap sm:flex-nowrap">
                            {{-- Direct Download PDF Button --}}
                            <a href="{{ route('bills.pdf', $bill) }}" 
                               download 
                               class="text-primary-600 hover:text-primary-850 hover:bg-primary-50 p-1.5 rounded-lg transition-all hover:scale-110 flex items-center justify-center" 
                               title="Download PDF">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
 
                             {{-- Direct Share Button (Standard Share Icon, Smart Recipient & Custom Message) --}}
                            @php
                                $whatsappPhone = preg_replace('/[^0-9]/', '', $bill->customer->phone ?? '');
                                if (strlen($whatsappPhone) === 10) {
                                    $whatsappPhone = '91' . $whatsappPhone;
                                }
                                $whatsappMessage = "Hello " . ($bill->customer->name ?? 'Customer') . ", here is your invoice from " . ($bill->workshop->name ?? 'Suhaim Soft Work Shop') . ": " . route('bills.pdf', $bill);
                            @endphp
                            <a href="https://wa.me/{{ $whatsappPhone }}?text={{ urlencode($whatsappMessage) }}" 
                               target="_blank" 
                               class="text-primary-600 hover:text-primary-850 hover:bg-primary-50 p-1.5 rounded-lg transition-all hover:scale-110 flex items-center justify-center" 
                               title="Share Invoice">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 10.742l5.263-2.63m0 3.776l-5.263-2.63m-5.263.263a2.5 2.5 0 115 0 2.5 2.5 0 01-5 0zm10-5a2.5 2.5 0 115 0 2.5 2.5 0 01-5 0zm0 10a2.5 2.5 0 115 0 2.5 2.5 0 01-5 0z"/>
                                </svg>
                            </a>
 
                            <a href="{{ route('bills.edit', $bill) }}" class="text-primary-600 hover:text-primary-850 hover:bg-primary-50 p-1.5 rounded-lg transition-all hover:scale-110 flex items-center justify-center" title="Edit Invoice">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            @if($bill->payment_status !== 'paid')
                            <button type="button" @click="paymentBillId = '{{ $bill->id }}'; paymentBillNo = '{{ $bill->bill_number }}'; paymentBillTotal = {{ $bill->total }}; paymentBillPaid = {{ $bill->amount_paid }}; paymentRoute = '{{ route('bills.record-payment', $bill) }}'; paymentModalOpen = true;" class="text-amber-600 hover:text-amber-850 hover:bg-amber-50 p-1.5 rounded-lg transition-all hover:scale-110 flex items-center justify-center" title="Record Payment">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </button>
                            @endif
                            <form action="{{ route('bills.destroy', $bill) }}" method="POST" class="inline" onsubmit="return confirm('Delete invoice {{ $bill->bill_number }}? This will restock any products sold in this invoice.')">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 p-1.5 rounded-lg transition-all hover:scale-110 flex items-center justify-center" title="Delete Invoice">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center py-8 text-slate-400 font-medium font-semibold">No bills found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bills->hasPages())<div class="px-5 py-4 border-t border-slate-200/60">{{ $bills->appends(request()->query())->links() }}</div>@endif
</div>


{{-- Record Payment Modal --}}
<div x-show="paymentModalOpen" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
     style="display: none;"
     x-cloak>
    <div class="bg-white w-full max-w-md rounded-2xl overflow-hidden shadow-2xl border border-slate-200 transition-all duration-300 transform"
         @click.away="paymentModalOpen = false"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
         
        <div class="px-6 py-4 border-b border-slate-200 bg-white flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </span>
                <h3 class="text-lg font-bold text-slate-800">Record Payment</h3>
            </div>
            <button type="button" @click="paymentModalOpen = false" class="text-slate-400 hover:text-slate-650 hover:bg-slate-50 transition-all p-1.5 rounded-lg border border-slate-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <form :action="paymentRoute" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Invoice Details</span>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-1.5">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 font-semibold">Invoice No:</span>
                        <span class="font-mono font-bold text-primary-600" x-text="paymentBillNo"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 font-semibold">Total Amount:</span>
                        <span class="font-bold text-slate-800" x-text="'₹' + Number(paymentBillTotal).toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 font-semibold">Previously Paid:</span>
                        <span class="font-bold text-emerald-600" x-text="'₹' + Number(paymentBillPaid).toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between text-sm pt-1.5 border-t border-slate-200/60">
                        <span class="text-slate-700 font-bold">Outstanding Balance:</span>
                        <span class="font-extrabold text-rose-600" x-text="'₹' + (Number(paymentBillTotal) - Number(paymentBillPaid)).toFixed(2)"></span>
                    </div>
                </div>
            </div>

            <div>
                <label class="form-label font-bold text-slate-700 mb-1">New Total Paid Amount (₹) *</label>
                <div class="relative">
                    <input type="number" step="0.01" name="amount_paid" :min="paymentBillPaid" :max="paymentBillTotal" x-model.number="paymentBillPaid" class="form-input text-base font-bold pr-16" required placeholder="Enter amount paid">
                    <button type="button" @click="paymentBillPaid = paymentBillTotal" class="absolute right-2 top-2 px-2 py-1 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded text-[10px] font-bold text-slate-600 transition-colors">Pay Full</button>
                </div>
                <p class="text-[10px] text-slate-400 mt-1.5 font-semibold">Enter the cumulative total amount paid by the customer for this invoice.</p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" @click="paymentModalOpen = false" class="btn-secondary !py-2 !px-4">Cancel</button>
                <button type="submit" class="btn-primary !py-2 !px-4 justify-center">Update Payment</button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
