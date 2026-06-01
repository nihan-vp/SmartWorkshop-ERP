@extends('layouts.app')
@section('title', $customer->name)
@section('page-title', $customer->name)
@section('page-subtitle', 'Customer Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="glass-card">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-slate-900">{{ $customer->name }}</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Customer #{{ $customer->id }}</p>
            </div>
            <a href="{{ route('customers.edit', $customer) }}" class="btn-secondary">Edit</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div><p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Phone</p><p class="text-slate-800 font-semibold">{{ $customer->phone ?? '—' }}</p></div>
            <div><p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Email</p><p class="text-slate-800 font-semibold">{{ $customer->email ?? '—' }}</p></div>
            <div><p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Address</p><p class="text-slate-800 font-semibold">{{ $customer->address ?? '—' }}</p></div>
        </div>
    </div>

    {{-- Vehicles --}}
    <div class="glass-card">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Vehicles ({{ $customer->vehicles->count() }})</h3>
        @if($customer->vehicles->count())
        <div class="space-y-3">
            @foreach($customer->vehicles as $v)
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200/60">
                <div>
                    <p class="text-sm font-semibold text-slate-800">{{ $v->make }} {{ $v->model }}</p>
                    <p class="text-xs text-slate-500 font-medium">{{ $v->plate_number }} {{ $v->color ? '• '.$v->color : '' }}</p>
                </div>
                <span class="badge badge-info">{{ $v->year ?? '—' }}</span>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-slate-500 font-medium">No vehicles registered</p>
        @endif
    </div>

    {{-- Recent Bills --}}
    <div class="glass-card">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Bills ({{ $customer->bills->count() }})</h3>
        @if($customer->bills->count())
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead><tr><th>Sl No</th><th>Invoice No</th><th>Date</th><th>Total</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($customer->bills->take(10) as $index => $bill)
                    <tr>
                        <td class="font-semibold text-slate-500">{{ $loop->iteration }}</td>
                        <td class="text-slate-800 font-semibold">{{ $bill->bill_number }}</td>
                        <td class="font-medium text-slate-600">{{ $bill->bill_date->format('d M Y') }}</td>
                        <td class="font-bold text-slate-900">₹{{ number_format($bill->total, 2) }}</td>
                        <td><span class="badge {{ $bill->payment_status === 'paid' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($bill->payment_status) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-sm text-slate-500 font-medium">No bills yet</p>
        @endif
    </div>
</div>
@endsection
