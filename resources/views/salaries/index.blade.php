@extends('layouts.app')
@section('title', 'Salaries')
@section('page-title', 'Salaries')
@section('page-subtitle', 'Manage employee salaries')
@section('content')
<div class="flex flex-col xl:flex-row items-start xl:items-end justify-between gap-4 mb-6">
    <form method="GET" class="flex flex-col sm:flex-row flex-wrap items-start sm:items-end gap-3 w-full xl:w-auto">
        <div class="w-full sm:w-auto">
            <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search employee..." class="form-input w-full sm:w-48">
        </div>
        <div class="w-full sm:w-auto">
            <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Salary Month</label>
            <select name="month_filter" class="form-input w-full sm:w-40">
                <option value="">All Months</option>
                @for($i = 0; $i < 24; $i++)
                    @php $d = now()->subMonths($i); @endphp
                    <option value="{{ $d->format('Y-m') }}" {{ request('month_filter') == $d->format('Y-m') ? 'selected' : '' }}>
                        {{ $d->format('F Y') }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="flex items-end gap-2 w-full sm:w-auto">
            <div class="flex-1 sm:flex-none">
                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-input w-full sm:w-36">
            </div>
            <span class="text-slate-400 font-medium mb-2.5">to</span>
            <div class="flex-1 sm:flex-none">
                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input w-full sm:w-36">
            </div>
        </div>
        <div class="flex sm:items-center gap-2 w-full sm:w-auto mt-2 sm:mt-0">
            <button type="submit" class="btn-secondary flex-1 sm:flex-none justify-center">Search</button>
            <a href="{{ route('salaries.index') }}" class="btn-danger flex-1 sm:flex-none justify-center text-center">Reset</a>
        </div>
    </form>
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto mt-4 sm:mt-0">
        <button type="button" onclick="document.getElementById('paymentModal').style.display='block'" class="btn-secondary w-full sm:w-auto justify-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Add Payment History</button>
        <a href="{{ route('salary-advances.create') }}" class="btn-secondary w-full sm:w-auto justify-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Add Borrow Record</a>
        <a href="{{ route('salaries.create') }}" class="btn-primary w-full sm:w-auto justify-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Add Salary Record</a>
    </div>
</div>
<div class="glass-card !p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Sl No</th><th>Employee</th><th>Period</th><th>Amount</th><th>Advance Ded.</th><th>Net Payment</th><th>Payment Date</th><th>Method</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($salaries as $salary)
                <tr>
                    <td data-label="Sl No" class="text-slate-400 font-semibold">{{ $salary->id }}</td>
                    <td data-label="Employee" class="font-bold text-slate-800">{{ $salary->employee->name }}</td>
                    <td data-label="Period" class="text-slate-600">{{ $salary->month }} {{ $salary->year }}</td>
                    <td data-label="Amount" class="font-bold text-slate-800">₹{{ number_format($salary->amount, 2) }}</td>
                    <td data-label="Advance Ded." class="text-red-500 font-semibold">-₹{{ number_format($salary->advance_deduction ?? 0, 2) }}</td>
                    <td data-label="Net Payment" class="text-green-600 font-bold">₹{{ number_format($salary->amount - ($salary->advance_deduction ?? 0), 2) }}</td>
                    <td data-label="Payment Date" class="font-medium text-slate-600">{{ $salary->payment_date ? \Carbon\Carbon::parse($salary->payment_date)->format('d M Y') : '—' }}</td>
                    <td data-label="Method" class="text-slate-500 uppercase text-sm font-semibold tracking-wide">{{ $salary->payment_method }}</td>
                    <td data-label="Status"><span class="badge {{ $salary->status === 'paid' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($salary->status) }}</span></td>
                    <td data-label="">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('salaries.edit', $salary) }}" class="text-amber-600 hover:text-amber-700" title="Edit"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                            <form action="{{ route('salaries.destroy', $salary) }}" method="POST" onsubmit="return confirm('Delete salary record for {{ $salary->employee->name }}?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-600" title="Delete"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="text-center py-8 text-slate-400 font-medium font-semibold">No salary records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($salaries->hasPages())<div class="px-5 py-4 border-t border-slate-200/60">{{ $salaries->appends(request()->query())->links() }}</div>@endif
</div>

<div class="mt-12 mb-4">
    <h3 class="text-lg font-bold text-slate-800">Borrow Salary History</h3>
</div>
<div class="glass-card !p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Sl No</th><th>Employee</th><th>Date</th><th>Amount</th><th>Reason</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($salaryAdvances as $advance)
                <tr>
                    <td data-label="Sl No" class="text-slate-400 font-semibold">{{ $advance->id }}</td>
                    <td data-label="Employee" class="font-bold text-slate-800">{{ $advance->employee->name }}</td>
                    <td data-label="Date" class="font-medium text-slate-600">{{ $advance->date->format('d M Y') }}</td>
                    <td data-label="Amount" class="font-bold text-slate-800">₹{{ number_format($advance->amount, 2) }}</td>
                    <td data-label="Reason" class="text-slate-600">{{ $advance->reason ?? '—' }}</td>
                    <td data-label="Status">
                        @if($advance->status === 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($advance->status === 'approved')
                            <span class="badge badge-success">Approved</span>
                        @else
                            <span class="badge badge-info">Deducted</span>
                        @endif
                    </td>
                    <td data-label="">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('salary-advances.edit', $advance) }}" class="text-amber-600 hover:text-amber-700" title="Edit"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                            <form action="{{ route('salary-advances.destroy', $advance) }}" method="POST" onsubmit="return confirm('Delete borrow record for {{ $advance->employee->name }}?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-600" title="Delete"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-8 text-slate-400 font-medium font-semibold">No borrow records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-12 mb-4">
    <h3 class="text-lg font-bold text-slate-800">Payment History</h3>
</div>
<div class="glass-card !p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Sl No</th><th>Employee</th><th>Date</th><th>Amount</th><th>Method</th><th>Notes</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($employeePayments as $payment)
                <tr>
                    <td data-label="Sl No" class="text-slate-400 font-semibold">{{ $payment->id }}</td>
                    <td data-label="Employee" class="font-bold text-slate-800">{{ $payment->employee->name }}</td>
                    <td data-label="Date" class="font-medium text-slate-600">{{ $payment->date->format('d M Y') }}</td>
                    <td data-label="Amount" class="font-bold text-green-600">₹{{ number_format($payment->amount, 2) }}</td>
                    <td data-label="Method" class="text-slate-500 uppercase text-sm font-semibold tracking-wide">{{ $payment->payment_method }}</td>
                    <td data-label="Notes" class="text-slate-600">{{ $payment->notes ?? '—' }}</td>
                    <td data-label="">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('employee-payments.edit', $payment) }}" class="text-amber-600 hover:text-amber-700" title="Edit"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                            <form action="{{ route('employee-payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('Delete payment record for {{ $payment->employee->name }}?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-600" title="Delete"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-8 text-slate-400 font-medium font-semibold">No payment records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Add Payment History Modal --}}
<div id="paymentModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('paymentModal').style.display='none'"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title">Add Payment History</h3>
                <button type="button" onclick="document.getElementById('paymentModal').style.display='none'" class="text-slate-400 hover:text-slate-500 focus:outline-none"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form action="{{ route('employee-payments.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Employee *</label>
                        <select name="employee_id" class="form-input" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Amount *</label>
                            <input type="number" step="0.01" name="amount" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">Date *</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}" class="form-input" required>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Payment Method *</label>
                        <select name="payment_method" class="form-input" required>
                            <option value="cash">Cash</option>
                            <option value="upi">UPI</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="2" class="form-input"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('paymentModal').style.display='none'" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Save Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
