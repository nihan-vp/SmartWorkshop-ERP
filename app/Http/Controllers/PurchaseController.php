<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('supplier_name', 'like', "%{$request->search}%")
                  ->orWhere('invoice_number', 'like', "%{$request->search}%")
                  ->orWhere('items_description', 'like', "%{$request->search}%");
            });
        }

        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        $purchases = $query->latest('purchase_date')->paginate(15);
        
        $totalPurchases = Purchase::sum('total_amount');
        $unpaidPurchases = Purchase::where('payment_status', 'unpaid')->sum('total_amount');

        return view('purchases.index', compact('purchases', 'totalPurchases', 'unpaidPurchases'));
    }

    public function create()
    {
        return view('purchases.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'invoice_number' => 'nullable|string|max:100',
            'purchase_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,upi,card,bank',
            'payment_status' => 'required|in:paid,unpaid,partial',
            'items_description' => 'nullable|string',
        ]);

        Purchase::create($validated);

        return redirect()->route('purchases.index')->with('success', 'Purchase record created successfully!');
    }

    public function edit(Purchase $purchase)
    {
        return view('purchases.edit', compact('purchase'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'invoice_number' => 'nullable|string|max:100',
            'purchase_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,upi,card,bank',
            'payment_status' => 'required|in:paid,unpaid,partial',
            'items_description' => 'nullable|string',
        ]);

        $purchase->update($validated);

        return redirect()->route('purchases.index')->with('success', 'Purchase record updated successfully!');
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return redirect()->route('purchases.index')->with('success', 'Purchase record deleted successfully!');
    }
}
