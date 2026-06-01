<?php

namespace App\Http\Controllers;

use App\Models\EmployeePayment;
use Illuminate\Http\Request;

class EmployeePaymentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        EmployeePayment::create($validated);
        return redirect()->route('salaries.index')->with('success', 'Payment history record added successfully!');
    }

    public function edit(EmployeePayment $employeePayment)
    {
        $employees = \App\Models\Employee::where('status', 'active')->orderBy('name')->get();
        return view('employee-payments.edit', compact('employeePayment', 'employees'));
    }

    public function update(Request $request, EmployeePayment $employeePayment)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        $employeePayment->update($validated);
        return redirect()->route('salaries.index')->with('success', 'Payment history record updated successfully!');
    }

    public function destroy(EmployeePayment $employeePayment)
    {
        $employeePayment->delete();
        return redirect()->route('salaries.index')->with('success', 'Payment history record deleted successfully!');
    }
}
