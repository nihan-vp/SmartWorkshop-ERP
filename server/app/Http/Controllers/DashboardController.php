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
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'today');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $today = today();

        $queryDate = function ($query, $dateColumn = 'created_at') use ($filter, $today, $startDate, $endDate) {
            if ($filter === 'today') {
                return $query->whereDate($dateColumn, $today);
            } elseif ($filter === 'yesterday') {
                return $query->whereDate($dateColumn, $today->copy()->subDay());
            } elseif ($filter === 'week') {
                return $query->whereBetween($dateColumn, [$today->copy()->subDays(6), $today]);
            } elseif ($filter === 'month') {
                return $query->whereMonth($dateColumn, $today->month)->whereYear($dateColumn, $today->year);
            } elseif ($filter === 'custom' && $startDate && $endDate) {
                return $query->whereBetween($dateColumn, [$startDate, $endDate]);
            }
            return $query;
        };

        $totalIncome = $queryDate(Bill::where('payment_status', 'paid'), 'bill_date')->sum('total');
        $totalExpenses = $queryDate(Expense::query(), 'expense_date')->sum('amount');
        $totalSalaries = $queryDate(Salary::where('status', 'paid'), 'payment_date')->sum('amount');
        $totalExpensesAll = $totalExpenses + $totalSalaries;
        $totalProfit = $totalIncome - $totalExpensesAll;
        
        $totalServices = Service::count(); // Master Data
        $totalRecords = $queryDate(Bill::query(), 'bill_date')->count();
        $totalCustomers = $queryDate(Customer::query())->count();
        $totalVehicles = $queryDate(Vehicle::query())->count();
        $totalEmployees = Employee::where('status', 'active')->count(); // Master Data

        $upiPayments = $queryDate(Bill::where('payment_method', 'upi')->where('payment_status', 'paid'), 'bill_date')->sum('total');
        $cashPayments = $queryDate(Bill::where('payment_method', 'cash')->where('payment_status', 'paid'), 'bill_date')->sum('total');

        $stockValue = Product::selectRaw('SUM(cost_price * stock_qty) as value')->value('value') ?? 0;
        $totalWorkOrders = $queryDate(WorkOrder::query())->count();
        $pendingWorkOrders = $queryDate(WorkOrder::where('status', 'pending'))->count();

        $recentBills = Bill::with('customer')->latest()->get();
        $recentExpenses = Expense::latest()->get();
        $lowStockProducts = Product::whereColumn('stock_qty', '<=', 'min_stock')->get();
        $pendingOrders = WorkOrder::with('customer', 'vehicle')->where('status', '!=', 'completed')->latest()->get();
        
        $activeWarranties = $queryDate(Warranty::where('status', 'active'))->count();
        $totalProducts = Product::count();

        return view('dashboard', compact(
            'filter',
            'totalIncome', 'totalExpenses', 'totalSalaries', 'totalExpensesAll', 'totalProfit',
            'totalServices', 'totalRecords', 'totalCustomers', 'totalVehicles', 'totalEmployees',
            'upiPayments', 'cashPayments', 'stockValue', 'totalWorkOrders', 'pendingWorkOrders',
            'recentBills', 'recentExpenses', 'lowStockProducts', 'pendingOrders',
            'activeWarranties', 'totalProducts'
        ));
    }

    public function dismissAlert(Request $request)
    {
        $user = auth()->user();
        if ($user && $user->workshop) {
            $user->workshop->update(['alert_dismissed' => true]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 403);
    }
}
