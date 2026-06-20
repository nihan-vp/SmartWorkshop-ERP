<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Warranty;
use Illuminate\Http\Request;

class WarrantyController extends Controller
{
    public function index(Request $request)
    {
        $query = Warranty::with('customer', 'vehicle', 'bill');
        if ($request->search) {
            $query->whereHas('customer', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $warranties = $query->latest()->paginate((int) request('limit', 15));
        return view('warranties.index', compact('warranties'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $vehicles = Vehicle::orderBy('plate_number')->get();
        $bills = Bill::orderBy('bill_number', 'desc')->get();
        return view('warranties.create', compact('customers', 'vehicles', 'bills'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bill_id' => 'nullable|exists:bills,id',
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,expired,claimed',
        ]);
        Warranty::create($validated);
        return redirect()->route('warranties.index')->with('success', 'Warranty added successfully!');
    }

    public function edit(Warranty $warranty)
    {
        $customers = Customer::orderBy('name')->get();
        $vehicles = Vehicle::orderBy('plate_number')->get();
        $bills = Bill::orderBy('bill_number', 'desc')->get();
        return view('warranties.edit', compact('warranty', 'customers', 'vehicles', 'bills'));
    }

    public function update(Request $request, Warranty $warranty)
    {
        $validated = $request->validate([
            'bill_id' => 'nullable|exists:bills,id',
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,expired,claimed',
        ]);
        $warranty->update($validated);
        return redirect()->route('warranties.index')->with('success', 'Warranty updated successfully!');
    }

    public function destroy(Warranty $warranty)
    {
        $warranty->delete();
        return redirect()->route('warranties.index')->with('success', 'Warranty deleted successfully!');
    }
}
