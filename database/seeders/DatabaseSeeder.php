<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Product;
use App\Models\Service;
use App\Models\Employee;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Expense;
use App\Models\Salary;
use App\Models\Warranty;
use App\Models\WorkOrder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Customers
        $c1 = Customer::create(['name' => 'Rahul Sharma', 'phone' => '9876543210', 'email' => 'rahul@example.com', 'address' => 'Mumbai, MH']);
        $c2 = Customer::create(['name' => 'Priya Patel', 'phone' => '8765432109', 'email' => 'priya@example.com', 'address' => 'Pune, MH']);
        $c3 = Customer::create(['name' => 'Amit Kumar', 'phone' => '7654321098', 'address' => 'Delhi']);

        // Vehicles
        $v1 = Vehicle::create(['customer_id' => $c1->id, 'make' => 'Hyundai', 'model' => 'i20', 'plate_number' => 'MH12AB1234', 'year' => 2019, 'color' => 'White']);
        $v2 = Vehicle::create(['customer_id' => $c2->id, 'make' => 'Honda', 'model' => 'City', 'plate_number' => 'MH14XY9876', 'year' => 2021, 'color' => 'Silver']);
        $v3 = Vehicle::create(['customer_id' => $c3->id, 'make' => 'Maruti', 'model' => 'Swift', 'plate_number' => 'DL01C4567', 'year' => 2018, 'color' => 'Red']);

        // Products
        $p1 = Product::create(['name' => 'Engine Oil 5W-30', 'sku' => 'OIL-001', 'category' => 'Lubricants', 'price' => 1500, 'cost_price' => 1000, 'stock_qty' => 50, 'min_stock' => 10, 'unit' => 'L']);
        $p2 = Product::create(['name' => 'Brake Pads Set', 'sku' => 'BRK-002', 'category' => 'Spares', 'price' => 2500, 'cost_price' => 1800, 'stock_qty' => 20, 'min_stock' => 5, 'unit' => 'Set']);
        $p3 = Product::create(['name' => 'Air Filter', 'sku' => 'FLT-003', 'category' => 'Filters', 'price' => 450, 'cost_price' => 300, 'stock_qty' => 30, 'min_stock' => 10, 'unit' => 'Pcs']);
        $p4 = Product::create(['name' => 'Spark Plug', 'sku' => 'SPK-004', 'category' => 'Spares', 'price' => 200, 'cost_price' => 120, 'stock_qty' => 3, 'min_stock' => 10, 'unit' => 'Pcs']); // Low stock

        // Services
        $s1 = Service::create(['name' => 'General Service', 'description' => 'Full car checkup and wash', 'price' => 1200, 'duration_minutes' => 120, 'category' => 'Maintenance']);
        $s2 = Service::create(['name' => 'Wheel Alignment', 'description' => 'Computerized alignment', 'price' => 800, 'duration_minutes' => 45, 'category' => 'Tires']);
        $s3 = Service::create(['name' => 'AC Servicing', 'description' => 'AC gas topup and cleaning', 'price' => 1500, 'duration_minutes' => 90, 'category' => 'AC']);

        // Employees
        $e1 = Employee::create(['name' => 'Suresh Mechanic', 'phone' => '9988776655', 'role' => 'Senior Technician', 'salary' => 25000, 'join_date' => Carbon::now()->subMonths(12), 'status' => 'active']);
        $e2 = Employee::create(['name' => 'Ramesh Helper', 'phone' => '8877665544', 'role' => 'Helper', 'salary' => 15000, 'join_date' => Carbon::now()->subMonths(6), 'status' => 'active']);

        // Work Orders
        $wo1 = WorkOrder::create(['order_number' => 'WO-1001', 'customer_id' => $c1->id, 'vehicle_id' => $v1->id, 'employee_id' => $e1->id, 'status' => 'completed', 'priority' => 'normal', 'description' => 'Standard servicing required', 'estimated_cost' => 3000, 'actual_cost' => 3150, 'start_date' => Carbon::now()->subDays(2), 'end_date' => Carbon::now()->subDays(1)]);
        $wo2 = WorkOrder::create(['order_number' => 'WO-1002', 'customer_id' => $c2->id, 'vehicle_id' => $v2->id, 'employee_id' => $e1->id, 'status' => 'in_progress', 'priority' => 'high', 'description' => 'Brake noise issue', 'estimated_cost' => 4500, 'start_date' => Carbon::now()]);
        $wo3 = WorkOrder::create(['order_number' => 'WO-1003', 'customer_id' => $c3->id, 'vehicle_id' => $v3->id, 'status' => 'pending', 'priority' => 'normal', 'description' => 'AC not cooling', 'estimated_cost' => 2000]);

        // Bills
        $b1 = Bill::create(['bill_number' => 'INV-2001', 'customer_id' => $c1->id, 'vehicle_id' => $v1->id, 'bill_date' => Carbon::now()->subDays(1), 'subtotal' => 3150, 'tax' => 0, 'discount' => 150, 'total' => 3000, 'payment_method' => 'upi', 'payment_status' => 'paid']);
        
        BillItem::create(['bill_id' => $b1->id, 'item_type' => 'service', 'item_id' => $s1->id, 'item_name' => $s1->name, 'quantity' => 1, 'unit_price' => $s1->price, 'total' => $s1->price]);
        BillItem::create(['bill_id' => $b1->id, 'item_type' => 'product', 'item_id' => $p1->id, 'item_name' => $p1->name, 'quantity' => 1, 'unit_price' => $p1->price, 'total' => $p1->price]);
        BillItem::create(['bill_id' => $b1->id, 'item_type' => 'product', 'item_id' => $p3->id, 'item_name' => $p3->name, 'quantity' => 1, 'unit_price' => $p3->price, 'total' => $p3->price]);

        $b2 = Bill::create(['bill_number' => 'INV-2002', 'customer_id' => $c2->id, 'bill_date' => Carbon::now(), 'subtotal' => 800, 'total' => 800, 'payment_method' => 'cash', 'payment_status' => 'pending']);
        BillItem::create(['bill_id' => $b2->id, 'item_type' => 'service', 'item_id' => $s2->id, 'item_name' => $s2->name, 'quantity' => 1, 'unit_price' => $s2->price, 'total' => $s2->price]);

        // Expenses
        Expense::create(['category' => 'Rent', 'amount' => 10000, 'expense_date' => Carbon::now()->startOfMonth(), 'payment_method' => 'upi', 'description' => 'Shop rent']);
        Expense::create(['category' => 'Electricity', 'amount' => 2500, 'expense_date' => Carbon::now()->subDays(5), 'payment_method' => 'upi']);
        Expense::create(['category' => 'Tools', 'amount' => 1200, 'expense_date' => Carbon::now()->subDays(2), 'payment_method' => 'cash', 'description' => 'New wrenches']);

        // Salaries
        Salary::create(['employee_id' => $e1->id, 'month' => Carbon::now()->subMonth()->format('F'), 'year' => Carbon::now()->year, 'amount' => $e1->salary, 'payment_date' => Carbon::now()->startOfMonth(), 'payment_method' => 'upi', 'status' => 'paid']);
        Salary::create(['employee_id' => $e2->id, 'month' => Carbon::now()->subMonth()->format('F'), 'year' => Carbon::now()->year, 'amount' => $e2->salary, 'payment_date' => Carbon::now()->startOfMonth(), 'payment_method' => 'cash', 'status' => 'paid']);

        // Warranties
        Warranty::create(['customer_id' => $c1->id, 'vehicle_id' => $v1->id, 'bill_id' => $b1->id, 'start_date' => Carbon::now(), 'end_date' => Carbon::now()->addMonths(6), 'status' => 'active', 'description' => '6 months warranty on engine oil leak']);
    }
}
