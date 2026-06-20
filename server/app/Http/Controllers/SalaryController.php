<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $query = Salary::with('employee');
        $advancesQuery = \App\Models\SalaryAdvance::with('employee');
        $paymentsQuery = \App\Models\EmployeePayment::with('employee');
        if ($request->search) {
            $query->whereHas('employee', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
            $advancesQuery->whereHas('employee', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
            $paymentsQuery->whereHas('employee', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }
        if ($request->month_filter) {
            $year = substr($request->month_filter, 0, 4);
            $monthNum = substr($request->month_filter, 5, 2);
            $monthName = \Carbon\Carbon::createFromFormat('m', $monthNum)->format('F');
            
            $query->where('year', $year)->where('month', $monthName);
            $advancesQuery->whereYear('date', $year)->whereMonth('date', $monthNum);
            $paymentsQuery->whereYear('date', $year)->whereMonth('date', $monthNum);
        }
        if ($request->start_date) {
            $query->whereDate('payment_date', '>=', $request->start_date);
            $advancesQuery->whereDate('date', '>=', $request->start_date);
            $paymentsQuery->whereDate('date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('payment_date', '<=', $request->end_date);
            $advancesQuery->whereDate('date', '<=', $request->end_date);
            $paymentsQuery->whereDate('date', '<=', $request->end_date);
        }
        $salaries = $query->latest()->paginate((int) request('limit', 15));
        $salaryAdvances = $advancesQuery->latest()->get();
        $employeePayments = $paymentsQuery->latest()->get();
        $employees = Employee::where('status', 'active')->orderBy('name')->get();
        return view('salaries.index', compact('salaries', 'salaryAdvances', 'employeePayments', 'employees'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')->orderBy('name')->get();
        return view('salaries.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'advance_deduction' => 'nullable|numeric|min:0',
            'month' => 'required|string',
            'year' => 'required|integer|min:2020',
            'payment_date' => 'nullable|date',
            'payment_method' => 'required|in:cash,upi',
            'status' => 'required|in:pending,paid',
        ]);
        Salary::create($validated);
        return redirect()->route('salaries.index')->with('success', 'Salary record added successfully!');
    }

    public function edit(Salary $salary)
    {
        $employees = Employee::where('status', 'active')->orderBy('name')->get();
        return view('salaries.edit', compact('salary', 'employees'));
    }

    public function update(Request $request, Salary $salary)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'advance_deduction' => 'nullable|numeric|min:0',
            'month' => 'required|string',
            'year' => 'required|integer|min:2020',
            'payment_date' => 'nullable|date',
            'payment_method' => 'required|in:cash,upi',
            'status' => 'required|in:pending,paid',
        ]);
        $salary->update($validated);
        return redirect()->route('salaries.index')->with('success', 'Salary record updated successfully!');
    }

    public function destroy(Salary $salary)
    {
        $salary->delete();
        return redirect()->route('salaries.index')->with('success', 'Salary record deleted successfully!');
    }
}
