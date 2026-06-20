@extends('layouts.app')
@section('title', 'Edit Work Order')
@section('page-title', 'Edit Work Order')
@section('content')
<div class="max-w-4xl mx-auto"><div class="glass-card">
    <form action="{{ route('work-orders.update', $workOrder) }}" method="POST">
        @csrf @method('PUT')
        <div class="space-y-6">
            {{-- Client & Vehicle --}}
            <div>
                <h3 class="text-sm font-semibold text-primary-400 uppercase tracking-wider mb-4">Client Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="customer_id" class="form-label">Customer *</label>
                        <select id="customer_id" name="customer_id" class="form-select" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id', $workOrder->customer_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="vehicle_id" class="form-label">Vehicle</label>
                        <select id="vehicle_id" name="vehicle_id" class="form-select">
                            <option value="">Select Vehicle</option>
                            @foreach($vehicles as $v)
                            <option value="{{ $v->id }}" {{ old('vehicle_id', $workOrder->vehicle_id ?? '') == $v->id ? 'selected' : '' }}>{{ $v->plate_number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            {{-- Job Details --}}
            <div class="pt-6 border-t border-white/10">
                <h3 class="text-sm font-semibold text-primary-400 uppercase tracking-wider mb-4">Job Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                    <div>
                        <label for="employee_id" class="form-label">Assigned Technician</label>
                        <select id="employee_id" name="employee_id" class="form-select">
                            <option value="">Unassigned</option>
                            @foreach($employees as $e)
                            <option value="{{ $e->id }}" {{ old('employee_id', $workOrder->employee_id ?? '') == $e->id ? 'selected' : '' }}>{{ $e->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status" class="form-label">Status *</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="pending" {{ old('status', $workOrder->status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ old('status', $workOrder->status ?? '') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status', $workOrder->status ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div>
                        <label for="priority" class="form-label">Priority *</label>
                        <select id="priority" name="priority" class="form-select" required>
                            <option value="normal" {{ old('priority', $workOrder->priority ?? 'normal') === 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="low" {{ old('priority', $workOrder->priority ?? '') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="high" {{ old('priority', $workOrder->priority ?? '') === 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority', $workOrder->priority ?? '') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                </div>
                <div><label for="description" class="form-label">Job Description / Instructions</label><textarea id="description" name="description" rows="4" class="form-input" placeholder="Enter description">{{ old('description', $workOrder->description) }}</textarea></div>
            </div>

            {{-- Estimates & Dates --}}
            <div class="pt-6 border-t border-white/10">
                <h3 class="text-sm font-semibold text-primary-400 uppercase tracking-wider mb-4">Estimates & Dates</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div><label for="estimated_cost" class="form-label">Est. Cost (₹)</label><input id="estimated_cost" type="number" step="0.01" name="estimated_cost" value="{{ old("estimated_cost", $workOrder->estimated_cost) }}" placeholder="Enter estimated cost" class="form-input"></div>
                    <div><label for="actual_cost" class="form-label">Actual Cost (₹)</label><input id="actual_cost" type="number" step="0.01" name="actual_cost" value="{{ old("actual_cost", $workOrder->actual_cost) }}" placeholder="Enter actual cost" class="form-input"></div>
                    <div><label for="start_date" class="form-label">Start Date</label><input id="start_date" type="date" name="start_date" value="{{ old('start_date', $workOrder->start_date ? $workOrder->start_date->format('Y-m-d') : '') }}" class="form-input"></div>
                    <div><label for="end_date" class="form-label">End Date</label><input id="end_date" type="date" name="end_date" value="{{ old('end_date', $workOrder->end_date ? $workOrder->end_date->format('Y-m-d') : '') }}" class="form-input"></div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-between mt-8 pt-6 border-t border-white/10">
            <p class="text-sm text-gray-500">Order #: <span class="font-mono text-white">{{ $workOrder->order_number }}</span></p>
            <div class="flex items-center gap-3">
                <a href="{{ route('work-orders.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Update Work Order</button>
            </div>
        </div>
    </form>
</div></div>
@endsection
