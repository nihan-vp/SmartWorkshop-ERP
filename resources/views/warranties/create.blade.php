@extends('layouts.app')
@section('title', isset($warranty) ? 'Edit Warranty' : 'Add Warranty')
@section('page-title', isset($warranty) ? 'Edit Warranty' : 'Add New Warranty')
@section('content')
<div class="max-w-2xl mx-auto"><div class="glass-card">
    <form action="{{ isset($warranty) ? route('warranties.update', $warranty) : route('warranties.store') }}" method="POST">
        @csrf @if(isset($warranty)) @method('PUT') @endif
        <div class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Customer *</label>
                    <select name="customer_id" class="form-select" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ old('customer_id', $warranty->customer_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Vehicle</label>
                    <select name="vehicle_id" class="form-select">
                        <option value="">Select Vehicle (Optional)</option>
                        @foreach($vehicles as $v)
                        <option value="{{ $v->id }}" {{ old('vehicle_id', $warranty->vehicle_id ?? '') == $v->id ? 'selected' : '' }}>{{ $v->plate_number }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="form-label">Linked Bill</label>
                <select name="bill_id" class="form-select">
                    <option value="">Select Bill (Optional)</option>
                    @foreach($bills as $b)
                    <option value="{{ $b->id }}" {{ old('bill_id', $warranty->bill_id ?? '') == $b->id ? 'selected' : '' }}>{{ $b->bill_number }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="form-label">Description / Terms</label><textarea name="description" rows="3" class="form-input" placeholder="Enter description">{{ old('description', $warranty->description ?? '') }}</textarea></div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div><label class="form-label">Start Date *</label><input type="date" name="start_date" value="{{ old('start_date', isset($warranty) ? $warranty->start_date->format('Y-m-d') : date('Y-m-d')) }}" class="form-input" required></div>
                <div><label class="form-label">End Date *</label><input type="date" name="end_date" value="{{ old('end_date', isset($warranty) ? $warranty->end_date->format('Y-m-d') : '') }}" class="form-input" required></div>
                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status', $warranty->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ old('status', $warranty->status ?? '') === 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="claimed" {{ old('status', $warranty->status ?? '') === 'claimed' ? 'selected' : '' }}>Claimed</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
            <button type="submit" class="btn-primary">{{ isset($warranty) ? 'Update' : 'Save' }} Warranty</button>
            <a href="{{ route('warranties.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div></div>
@endsection
