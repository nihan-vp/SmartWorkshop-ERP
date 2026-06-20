@extends('layouts.app')
@section('title', 'Edit Customer')
@section('page-title', 'Edit Customer')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-card">
        <form action="{{ route('customers.update', $customer) }}" method="POST">
            @csrf @method('PUT')
            <div class="space-y-5">
                <div>
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" value="{{ old("name", $customer->name) }}" placeholder="Enter name" class="form-input" required>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old("phone", $customer->phone) }}" placeholder="Enter phone" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old("email", $customer->email) }}" placeholder="Enter email" class="form-input">
                    </div>
                </div>
                <div>
                    <label class="form-label">Address</label>
                    <textarea name="address" rows="3" class="form-input" placeholder="Enter address">{{ old('address', $customer->address) }}</textarea>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
                <button type="submit" class="btn-primary">Update Customer</button>
                <a href="{{ route('customers.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
