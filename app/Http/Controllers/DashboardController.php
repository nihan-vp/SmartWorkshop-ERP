<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Salary;
use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Warranty;
use App\Models\WorkOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();
        $totalIncome = Bill::where('payment_status', 'paid')->whereDate('bill_date', $today)->sum('total');
        $totalExpenses = Expense::whereDate('expense_date', $today)->sum('amount');
        $totalSalaries = Salary::where('status', 'paid')->whereDate('payment_date', $today)->sum('amount');
        $totalExpensesAll = $totalExpenses + $totalSalaries;
        $totalProfit = $totalIncome - $totalExpensesAll;
        $totalServices = Service::count();
        $totalRecords = Bill::count();
        $totalCustomers = Customer::count();
        $totalVehicles = Vehicle::count();
        $totalEmployees = Employee::where('status', 'active')->count();

        $upiPayments = Bill::where('payment_method', 'upi')->where('payment_status', 'paid')->whereDate('bill_date', $today)->sum('total');
        $cashPayments = Bill::where('payment_method', 'cash')->where('payment_status', 'paid')->whereDate('bill_date', $today)->sum('total');

        $stockValue = Product::selectRaw('SUM(cost_price * stock_qty) as value')->value('value') ?? 0;
        $totalWorkOrders = WorkOrder::count();
        $pendingWorkOrders = WorkOrder::where('status', 'pending')->count();

        $recentBills = Bill::with('customer')->latest()->take(5)->get();
        $recentExpenses = Expense::latest()->take(5)->get();
        $lowStockProducts = Product::whereColumn('stock_qty', '<=', 'min_stock')->get();
        $pendingOrders = WorkOrder::with('customer', 'vehicle')->where('status', '!=', 'completed')->latest()->take(5)->get();
        $activeWarranties = Warranty::where('status', 'active')->count();
        $totalProducts = Product::count();

        // Chart Data (Last 7 Days Performance)
        $chartDates = [];
        $chartIncome = [];
        $chartExpense = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $chartDates[] = $date->format('M d');
            $chartIncome[] = Bill::where('payment_status', 'paid')->whereDate('bill_date', $date)->sum('total');
            $exp = Expense::whereDate('expense_date', $date)->sum('amount');
            $sal = Salary::where('status', 'paid')->whereDate('payment_date', $date)->sum('amount');
            $chartExpense[] = $exp + $sal;
        }

        return view('dashboard', compact(
            'totalIncome', 'totalExpenses', 'totalSalaries', 'totalExpensesAll', 'totalProfit',
            'totalServices', 'totalRecords', 'totalCustomers', 'totalVehicles', 'totalEmployees',
            'upiPayments', 'cashPayments', 'stockValue', 'totalWorkOrders', 'pendingWorkOrders',
            'recentBills', 'recentExpenses', 'lowStockProducts', 'pendingOrders',
            'activeWarranties', 'totalProducts', 'chartDates', 'chartIncome', 'chartExpense'
        ));
    }
}
