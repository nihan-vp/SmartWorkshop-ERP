<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Service;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $query = Bill::with('customer', 'vehicle');
        if ($request->search) {
            $query->where('bill_number', 'like', "%{$request->search}%")
                  ->orWhereHas('customer', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }
        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }
        $bills = $query->latest()->paginate(15);
        return view('bills.index', compact('bills'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $vehicles = Vehicle::with('customer')->orderBy('plate_number')->get();
        $products = Product::where('stock_qty', '>', 0)->orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        $billNumber = Bill::generateBillNumber();
        $productsList = Product::select('id', 'name', 'price', 'barcode')->whereNotNull('barcode')->where('barcode', '!=', '')->get();
        return view('bills.create', compact('customers', 'vehicles', 'products', 'services', 'billNumber', 'productsList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'payment_method' => 'required|in:cash,upi',
            'amount_paid' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:product,service',
            'items.*.id' => 'required|integer',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'bill_number' => 'nullable|string|unique:bills,bill_number',
            'bill_date' => 'nullable|date',
            'should_round' => 'nullable|boolean',
            'payment_status' => 'required|in:paid,partial,pending',
        ]);

        DB::transaction(function () use ($validated) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $lineTotal = $item['quantity'] * $item['price'];
                $subtotal += $lineTotal;
                $itemsData[] = [
                    'item_type' => $item['type'],
                    'item_id' => $item['id'],
                    'item_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total' => $lineTotal,
                ];

                // Deduct stock for products
                if ($item['type'] === 'product') {
                    $product = Product::findOrFail($item['id']);
                    $product->deductStock($item['quantity']);
                }
            }

            $discount = $validated['discount'] ?? 0;
            $tax = $validated['tax'] ?? 0;
            $total = $subtotal - $discount + $tax;
            if (!empty($validated['should_round'])) {
                $total = round($total);
            }

            $amountPaid = $validated['amount_paid'] ?? 0;
            $paymentStatus = $validated['payment_status'];

            $bill = Bill::create([
                'bill_number' => ($validated['bill_number'] ?? null) ?: Bill::generateBillNumber(),
                'customer_id' => $validated['customer_id'],
                'vehicle_id' => $validated['vehicle_id'] ?? null,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'amount_paid' => $amountPaid,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $paymentStatus,
                'notes' => $validated['notes'] ?? null,
                'bill_date' => $validated['bill_date'] ?? now()->toDateString(),
            ]);

            foreach ($itemsData as $itemData) {
                $bill->items()->create($itemData);
            }
        });

        return redirect()->route('bills.index')->with('success', 'Bill created successfully!');
    }

    public function show(Bill $bill)
    {
        return redirect()->route('bills.edit', $bill);
    }

    public function edit(Bill $bill)
    {
        $bill->load('customer', 'vehicle', 'items');
        $customers = Customer::orderBy('name')->get();
        $vehicles = Vehicle::with('customer')->orderBy('plate_number')->get();
        $products = Product::orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        $productsList = Product::select('id', 'name', 'price', 'barcode')->whereNotNull('barcode')->where('barcode', '!=', '')->get();
        return view('bills.edit', compact('bill', 'customers', 'vehicles', 'products', 'services', 'productsList'));
    }

    public function update(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'payment_method' => 'required|in:cash,upi',
            'amount_paid' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:product,service',
            'items.*.id' => 'required|integer',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'bill_number' => 'nullable|string|unique:bills,bill_number,' . $bill->id,
            'bill_date' => 'nullable|date',
            'should_round' => 'nullable|boolean',
            'payment_status' => 'required|in:paid,partial,pending',
        ]);

        DB::transaction(function () use ($validated, $bill) {
            // Revert product stocks for old bill items
            foreach ($bill->items as $item) {
                if ($item->item_type === 'product') {
                    $product = Product::find($item->item_id);
                    if ($product) {
                        $product->addStock($item->quantity);
                    }
                }
            }

            // Delete old items
            $bill->items()->delete();

            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $lineTotal = $item['quantity'] * $item['price'];
                $subtotal += $lineTotal;
                $itemsData[] = [
                    'item_type' => $item['type'],
                    'item_id' => $item['id'],
                    'item_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total' => $lineTotal,
                ];

                // Deduct stock for products
                if ($item['type'] === 'product') {
                    $product = Product::findOrFail($item['id']);
                    $product->deductStock($item['quantity']);
                }
            }

            $discount = $validated['discount'] ?? 0;
            $tax = $validated['tax'] ?? 0;
            $total = $subtotal - $discount + $tax;
            if (!empty($validated['should_round'])) {
                $total = round($total);
            }

            $amountPaid = $validated['amount_paid'] ?? 0;
            $paymentStatus = $validated['payment_status'];

            $bill->update([
                'customer_id' => $validated['customer_id'],
                'vehicle_id' => $validated['vehicle_id'] ?? null,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'amount_paid' => $amountPaid,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $paymentStatus,
                'notes' => $validated['notes'] ?? null,
                'bill_date' => $validated['bill_date'] ?? $bill->bill_date->toDateString(),
            ]);

            foreach ($itemsData as $itemData) {
                $bill->items()->create($itemData);
            }
        });

        return redirect()->route('bills.index')->with('success', 'Invoice updated successfully!');
    }

    public function recordPayment(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0',
        ]);

        $amountPaid = $validated['amount_paid'];
        $total = $bill->total;

        $paymentStatus = 'pending';
        if ($amountPaid >= $total) {
            $paymentStatus = 'paid';
        } elseif ($amountPaid > 0) {
            $paymentStatus = 'partial';
        }

        $bill->update([
            'amount_paid' => $amountPaid,
            'payment_status' => $paymentStatus,
        ]);

        return redirect()->back()->with('success', 'Payment recorded successfully!');
    }

    public function downloadPDF(Request $request, Bill $bill)
    {
        $bill->load('customer', 'vehicle.customer', 'items', 'workshop');

        $size = strtoupper($request->query('size', 'A4'));
        $allowedSizes = ['A4', 'A5', 'LETTER', 'LEGAL', 'A3', '80MM', '58MM'];
        if (!in_array($size, $allowedSizes)) {
            $size = 'A4';
        }

        $pdfFormat = $size;
        $margins = [15, 15, 15];
        $bottomMargin = 15;
        
        if ($size === '80MM') {
            $pdfFormat = [80, 297];
            $margins = [5, 5, 5];
            $bottomMargin = 5;
        } elseif ($size === '58MM') {
            $pdfFormat = [58, 297];
            $margins = [3, 5, 3];
            $bottomMargin = 5;
        } elseif ($size === 'A5') {
            $margins = [10, 10, 10];
            $bottomMargin = 10;
        } elseif ($size === 'A3') {
            $margins = [20, 20, 20];
            $bottomMargin = 20;
        }

        // Create new PDF document
        $pdf = new \TCPDF('P', 'mm', $pdfFormat, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('Suhaim Soft Workshop');
        $pdf->SetAuthor($bill->workshop->name ?? 'Suhaim Soft');
        $pdf->SetTitle('Invoice - ' . $bill->bill_number);
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
        // Set margins
        $pdf->SetMargins($margins[0], $margins[1], $margins[2]);
        
        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, $bottomMargin);
        
        // Set font
        $pdf->SetFont('helvetica', '', 10);
        
        // Add a page
        $pdf->AddPage();

        // Render HTML content for the PDF
        $html = view('bills.pdf', compact('bill', 'size'))->render();

        // Write HTML
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF as a string (S) and return as a proper Laravel response
        $pdfContent = $pdf->Output($bill->bill_number . '.pdf', 'S');

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $bill->bill_number . '.pdf"');
    }

    public function destroy(Bill $bill)
    {
        // Restore stock for product items
        foreach ($bill->items as $item) {
            if ($item->item_type === 'product') {
                $product = Product::find($item->item_id);
                if ($product) {
                    $product->addStock($item->quantity);
                }
            }
        }
        $bill->delete();
        return redirect()->route('bills.index')->with('success', 'Invoice deleted successfully!');
    }
}


