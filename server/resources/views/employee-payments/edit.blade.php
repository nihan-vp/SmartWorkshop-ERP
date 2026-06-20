@extends('layouts.app')
@section('title', 'Edit Payment History')
@section('page-title', 'Edit Payment History')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-card">
        <form action="{{ route('employee-payments.update', $employeePayment) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-5">
                <div>
                    <label class="form-label">Employee *</label>
                    <select name="employee_id" class="form-input" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id', $employeePayment->employee_id) == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Amount *</label>
                        <input type="number" step="0.01" name="amount" value="{{ old("amount", $employeePayment->amount) }}" placeholder="Enter amount" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Date *</label>
                        <input type="date" name="date" value="{{ old('date', $employeePayment->date->format('Y-m-d')) }}" class="form-input" required>
                    </div>
                </div>
                <div>
                    <label class="form-label">Payment Method *</label>
                    <select name="payment_method" class="form-input" required>
                        <option value="cash" {{ old('payment_method', $employeePayment->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="upi" {{ old('payment_method', $employeePayment->payment_method) == 'upi' ? 'selected' : '' }}>UPI</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Notes</label>
                    <textarea name="notes" rows="3" class="form-input" placeholder="Enter notes">{{ old('notes', $employeePayment->notes) }}</textarea>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
                <button type="submit" class="btn-primary">Update Payment</button>
                <a href="{{ route('salaries.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
