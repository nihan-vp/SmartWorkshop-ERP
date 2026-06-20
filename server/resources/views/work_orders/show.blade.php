@extends('layouts.app')
@section('title', 'Work Order: ' . $workOrder->order_number)
@section('page-title', 'Work Order Details')
@section('page-subtitle', $workOrder->order_number)
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="badge {{ $workOrder->status === 'completed' ? 'badge-success' : ($workOrder->status === 'in_progress' ? 'badge-info' : 'badge-warning') }} text-sm px-3 py-1.5">
                {{ ucfirst(str_replace('_', ' ', $workOrder->status)) }}
            </span>
            <span class="badge {{ $workOrder->priority === 'urgent' ? 'badge-danger' : ($workOrder->priority === 'high' ? 'badge-warning' : 'badge-info') }} text-sm px-3 py-1.5">
                {{ ucfirst($workOrder->priority) }} Priority
            </span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('work-orders.edit', $workOrder) }}" class="btn-secondary">Edit Order</a>
            @if($workOrder->status === 'completed')
            <a href="{{ route('bills.create', ['work_order' => $workOrder->id]) }}" class="btn-primary">Generate Bill</a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="glass-card">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Customer & Vehicle</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Customer</p>
                    <p class="text-lg font-bold text-slate-900">{{ $workOrder->customer->name }}</p>
                    <p class="text-sm font-medium text-slate-500">{{ $workOrder->customer->phone ?? 'No phone' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Vehicle</p>
                    @if($workOrder->vehicle)
                    <p class="text-slate-800 font-semibold">{{ $workOrder->vehicle->make }} {{ $workOrder->vehicle->model }}</p>
                    <p class="text-sm font-mono font-bold text-primary-600">{{ $workOrder->vehicle->plate_number }}</p>
                    @else
                    <p class="text-slate-400 italic">No vehicle assigned</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="glass-card">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Job Details</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Assigned To</p>
                    <p class="text-slate-800 font-semibold">{{ $workOrder->employee ? $workOrder->employee->name : 'Unassigned' }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Start Date</p>
                        <p class="text-slate-800 font-semibold">{{ $workOrder->start_date ? $workOrder->start_date->format('d M Y') : '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">End Date</p>
                        <p class="text-slate-800 font-semibold">{{ $workOrder->end_date ? $workOrder->end_date->format('d M Y') : '—' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Estimated Cost</p>
                        <p class="text-slate-800 font-bold">₹{{ number_format($workOrder->estimated_cost, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Actual Cost</p>
                        <p class="text-slate-800 font-bold">₹{{ number_format($workOrder->actual_cost, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="glass-card">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Description / Instructions</h3>
        @if($workOrder->description)
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60 text-slate-700 whitespace-pre-wrap font-medium">{{ $workOrder->description }}</div>
        @else
        <p class="text-slate-400 italic">No description provided.</p>
        @endif
    </div>
</div>
@endsection
