@extends('layouts.app')
@section('title', 'Invoices')
@section('page-title', 'Invoices')
@section('page-subtitle', 'Manage customer invoices')
@section('content')
<div class="w-full" x-data="{ pdfModalOpen: false, pdfUrl: '', pdfBaseUrl: '', pdfSize: 'A4', iframeLoading: true }">
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
                    <td data-label="Total" class="text-emerald-600 font-bold">₹{{ number_format($bill->total, 2) }}</td>
                    <td data-label="Payment"><span class="badge {{ $bill->payment_method === 'upi' ? 'badge-info' : 'badge-warning' }}">{{ strtoupper($bill->payment_method) }}</span></td>
                    <td data-label="Status">
                        <span class="badge {{ $bill->payment_status === 'paid' ? 'badge-success' : ($bill->payment_status === 'partial' ? 'badge-warning' : 'badge-danger') }}">
                             {{ ucfirst($bill->payment_status) }}
                        </span>
                    </td>
                    <td data-label="">
                        <div class="flex items-center gap-2 sm:gap-1 flex-wrap sm:flex-nowrap">
                            {{-- New Invoice Preview Button (Sleek PDF Document Icon) --}}
                            <button type="button" @click="pdfBaseUrl = '{{ route('bills.pdf', $bill) }}'; pdfUrl = pdfBaseUrl + '?size=' + pdfSize + '#view=FitH'; pdfModalOpen = true; iframeLoading = true;" class="text-primary-600 hover:text-primary-850 hover:bg-primary-50 p-1.5 rounded-lg transition-all hover:scale-110 flex items-center justify-center" title="Preview Invoice">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </button>
                            
                            {{-- Direct Download PDF Button --}}
                            <a :href="'{{ route('bills.pdf', $bill) }}?size=' + pdfSize" 
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

{{-- Responsive PDF Preview Modal --}}
<div x-show="pdfModalOpen" 
     class="fixed inset-0 z-50 flex items-center justify-center sm:p-4 p-0 bg-slate-900/50 backdrop-blur-sm"
     style="display: none;"
     x-cloak>
     
    <div class="bg-white w-full sm:max-w-5xl h-full sm:h-[92vh] flex flex-col sm:rounded-2xl rounded-none overflow-hidden shadow-2xl sm:border border-none border-slate-200 transition-all duration-300 transform"
         @click.away="pdfModalOpen = false"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
         
         {{-- Modal Header --}}
        <div class="sm:px-6 px-4 sm:py-4 py-3 border-b border-slate-200 bg-white flex flex-col sm:flex-row gap-3 sm:gap-0 sm:items-center justify-between shrink-0">
            <div class="flex items-center justify-between w-full sm:w-auto">
                <div class="flex items-center gap-2.5">
                    <span class="p-2 bg-primary-50 text-primary-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </span>
                    <h3 class="text-base sm:text-lg font-bold text-slate-805 tracking-tight">Invoice Preview</h3>
                </div>
                <button type="button" @click="pdfModalOpen = false" class="sm:hidden text-slate-400 hover:text-slate-650 hover:bg-slate-50 transition-all p-2 rounded-xl border border-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <select x-model="pdfSize" @change="pdfUrl = pdfBaseUrl + '?size=' + pdfSize + '#view=FitH'; iframeLoading = true;" class="form-select w-full sm:w-auto py-1.5 pl-3 pr-8 text-sm font-semibold text-slate-700 bg-slate-50 border-slate-200 rounded-lg hover:bg-slate-100 transition-colors focus:ring-2 focus:ring-primary-500/20">
                    <option value="A4">A4 Size</option>
                    <option value="A5">A5 Size</option>
                    <option value="LETTER">Letter</option>
                    <option value="LEGAL">Legal</option>
                </select>
                <button type="button" @click="pdfModalOpen = false" class="hidden sm:block text-slate-400 hover:text-slate-650 hover:bg-slate-50 transition-all p-2 rounded-xl border border-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        
        {{-- Modal Body (IFrame with Shimmering Loader & touch scroll support) --}}
        <div class="flex-1 bg-slate-100 relative min-h-0 overflow-y-auto -webkit-overflow-scrolling-touch">
            
            {{-- Modern Shimmering Loading Placeholder --}}
            <div x-show="iframeLoading" 
                 class="absolute inset-0 flex flex-col items-center justify-center bg-white z-10 transition-all duration-300">
                <div class="relative flex items-center justify-center">
                    <div class="animate-spin rounded-full h-14 w-14 border-4 border-slate-100 border-t-primary-600"></div>
                    <div class="absolute text-primary-600 animate-pulse">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <span class="mt-4 text-xs font-bold text-slate-705 tracking-wide uppercase animate-pulse">Generating Secure PDF...</span>
                <span class="mt-1 text-[10px] font-semibold text-slate-400">Please wait a few moments</span>
            </div>

            <template x-if="pdfModalOpen">
                <iframe :src="pdfUrl" 
                        @load="iframeLoading = false" 
                        class="w-full h-full min-h-[500px] sm:min-h-[80vh] border-none transition-all duration-300"
                        :class="iframeLoading ? 'opacity-0' : 'opacity-100'"
                        allow="autoplay"></iframe>
            </template>
        </div>

    </div>
</div>
</div>
@endsection
