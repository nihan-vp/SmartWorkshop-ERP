<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = WorkOrder::with('customer', 'vehicle', 'employee');
        if ($request->search) {
            $query->where('order_number', 'like', "%{$request->search}%")
                  ->orWhereHas('customer', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $workOrders = $query->latest()->paginate((int) request('limit', 15));
        return view('work_orders.index', compact('workOrders'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $vehicles = Vehicle::orderBy('plate_number')->get();
        $employees = Employee::where('status', 'active')->orderBy('name')->get();
        $orderNumber = WorkOrder::generateOrderNumber();
        return view('work_orders.create', compact('customers', 'vehicles', 'employees', 'orderNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'employee_id' => 'nullable|exists:employees,id',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,normal,high,urgent',
            'estimated_cost' => 'required|numeric|min:0',
            'actual_cost' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);
        $validated['order_number'] = WorkOrder::generateOrderNumber();
        $validated['estimated_cost'] = $validated['estimated_cost'] ?? 0;
        WorkOrder::create($validated);
        return redirect()->route('work-orders.index')->with('success', 'Work Order created successfully!');
    }

    public function show(WorkOrder $workOrder)
    {
        $workOrder->load('customer', 'vehicle', 'employee');
        return view('work_orders.show', compact('workOrder'));
    }

    public function edit(WorkOrder $workOrder)
    {
        $customers = Customer::orderBy('name')->get();
        $vehicles = Vehicle::orderBy('plate_number')->get();
        $employees = Employee::where('status', 'active')->orderBy('name')->get();
        return view('work_orders.edit', compact('workOrder', 'customers', 'vehicles', 'employees'));
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'employee_id' => 'nullable|exists:employees,id',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,normal,high,urgent',
            'estimated_cost' => 'required|numeric|min:0',
            'actual_cost' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);
        $workOrder->update($validated);
        return redirect()->route('work-orders.index')->with('success', 'Work Order updated successfully!');
    }

    public function destroy(WorkOrder $workOrder)
    {
        $workOrder->delete();
        return redirect()->route('work-orders.index')->with('success', 'Work Order deleted successfully!');
    }
}
                         
