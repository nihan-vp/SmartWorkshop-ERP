@extends('layouts.app')
@section('title', 'Edit Expense')
@section('page-title', 'Edit Expense')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-card">
        <form action="{{ route('expenses.update', $expense) }}" method="POST" id="edit-expense-form">
            @csrf
            @method('PUT')
            <div class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Category *</label>
                        <input type="text" name="category" value="{{ old('category', $expense- placeholder="Enter category">category) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Amount *</label>
                        <input type="number" step="0.01" name="amount" value="{{ old('amount', $expense- placeholder="Enter amount">amount) }}" class="form-input" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Expense Date *</label>
                        <input type="date" name="expense_date" value="{{ old('expense_date', \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d')) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Payment Method *</label>
                        <select name="payment_method" class="form-input" required>
                            <option value="cash" {{ old('payment_method', $expense->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="upi" {{ old('payment_method', $expense->payment_method) == 'upi' ? 'selected' : '' }}>UPI</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-input" placeholder="Enter description">{{ old('description', $expense->description) }}</textarea>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
                <button type="submit" class="btn-primary">Update Expense</button>
                <a href="{{ route('expenses.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
