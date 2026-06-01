<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::withCount('workOrders');
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('role', 'like', "%{$request->search}%");
        }
        $employees = $query->latest()->paginate(15);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        if (Employee::count() >= 1) {
            return redirect()->route('employees.index')->with('error', 'Each workshop is limited to a maximum of 1 employee.');
        }
        return view('employees.create');
    }

    public function store(Request $request)
    {
        if (Employee::count() >= 1) {
            return redirect()->route('employees.index')->with('error', 'Each workshop is limited to a maximum of 1 employee.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'role' => 'required|string|max:100',
            'salary' => 'required|numeric|min:0',
            'join_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);
        Employee::create($validated);
        return redirect()->route('employees.index')->with('success', 'Employee added successfully!');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'role' => 'required|string|max:100',
            'salary' => 'required|numeric|min:0',
            'join_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);
        $employee->update($validated);
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
    }
}
