@extends('layouts.app')
@section('title', 'Add Borrow Record')
@section('page-title', 'Add Borrow Record')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-card">
        <form action="{{ route('salary-advances.store') }}" method="POST" id="create-borrow-form">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="form-label">Employee *</label>
                    <select name="employee_id" class="form-input" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Amount *</label>
                        <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" class="form-input" required placeholder="Enter amount">
                    </div>
                    <div>
                        <label class="form-label">Date *</label>
                        <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" class="form-input" required>
                    </div>
                </div>
                <div>
                    <label class="form-label">Reason</label>
                    <textarea name="reason" rows="3" class="form-input" placeholder="Enter reason">{{ old('reason') }}</textarea>
                </div>
                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-input" required>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="deducted" {{ old('status') == 'deducted' ? 'selected' : '' }}>Deducted</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
                <button type="submit" class="btn-primary">Save Borrow Record</button>
                <a href="{{ route('salaries.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
