<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SalaryAdvance;
use Illuminate\Http\Request;

class SalaryAdvanceController extends Controller
{
    public function create()
    {
        $employees = Employee::where('status', 'active')->orderBy('name')->get();
        return view('salary_advances.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string',
            'status' => 'required|in:pending,approved,deducted',
        ]);
        SalaryAdvance::create($validated);
        return redirect()->route('salaries.index')->with('success', 'Salary advance record added successfully!');
    }

    public function edit(SalaryAdvance $salaryAdvance)
    {
        $employees = Employee::where('status', 'active')->orderBy('name')->get();
        return view('salary_advances.edit', compact('salaryAdvance', 'employees'));
    }

    public function update(Request $request, SalaryAdvance $salaryAdvance)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string',
            'status' => 'required|in:pending,approved,deducted',
        ]);
        $salaryAdvance->update($validated);
        return redirect()->route('salaries.index')->with('success', 'Salary advance record updated successfully!');
    }

    public function destroy(SalaryAdvance $salaryAdvance)
    {
        $salaryAdvance->delete();
        return redirect()->route('salaries.index')->with('success', 'Salary advance record deleted successfully!');
    }
}
