@extends('layouts.app')
@section('title', 'Add Employee')
@section('page-title', 'Add New Employee')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-card">
        <form action="{{ route('employees.store') }}" method="POST" id="create-employee-form">
            @csrf
            <div class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input" required placeholder="Enter name">
                    </div>
                    <div>
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="Enter phone">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="Enter email">
                    </div>
                    <div>
                        <label class="form-label">Role *</label>
                        <input type="text" name="role" value="{{ old('role') }}" class="form-input" required placeholder="Enter role">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="form-label">Salary *</label>
                        <input type="number" step="0.01" name="salary" value="{{ old('salary') }}" class="form-input" required placeholder="Enter salary">
                    </div>
                    <div>
                        <label class="form-label">Join Date</label>
                        <input type="date" name="join_date" value="{{ old('join_date', date('Y-m-d')) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-input" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
                <button type="submit" class="btn-primary">Save Employee</button>
                <a href="{{ route('employees.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
