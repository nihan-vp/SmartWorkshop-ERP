@extends('layouts.app')
@section('title', 'Add Vehicle')
@section('page-title', 'Add Vehicle')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-card">
        <form action="{{ route('vehicles.store') }}" method="POST" id="create-vehicle-form">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="form-label">Customer *</label>
                    <div class="flex gap-3">
                        <select name="customer_id" class="form-input flex-1" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('customers.create') }}" class="btn-secondary whitespace-nowrap flex items-center">
                            + Add
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="form-label">Model *</label>
                        <input type="text" name="model" value="{{ old('model') }}" class="form-input" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="form-label">Year</label>
                        <input type="number" name="year" value="{{ old('year') }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Plate Number *</label>
                        <input type="text" name="plate_number" value="{{ old('plate_number') }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Color</label>
                        <input type="text" name="color" value="{{ old('color') }}" class="form-input">
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
                <button type="submit" class="btn-primary">Save Vehicle</button>
                <a href="{{ route('vehicles.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
