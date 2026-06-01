<?php

namespace App\Http\Controllers;

use App\Models\BillTemplate;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillTemplateController extends Controller
{
    public function index()
    {
        $templates = BillTemplate::withCount('items')->latest()->paginate(15);
        return view('bill_templates.index', compact('templates'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        return view('bill_templates.create', compact('products', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:product,service',
            'items.*.id' => 'required|integer',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $template = BillTemplate::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'discount' => $validated['discount'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
            ]);

            foreach ($validated['items'] as $item) {
                $template->items()->create([
                    'item_type' => $item['type'],
                    'item_id' => $item['id'],
                    'item_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                ]);
            }
        });

        return redirect()->route('bill-templates.index')->with('success', 'Template created successfully!');
    }

    public function edit(BillTemplate $billTemplate)
    {
        $billTemplate->load('items');
        $products = Product::orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        return view('bill_templates.edit', compact('billTemplate', 'products', 'services'));
    }

    public function update(Request $request, BillTemplate $billTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:product,service',
            'items.*.id' => 'required|integer',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $billTemplate) {
            $billTemplate->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'discount' => $validated['discount'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
            ]);

            $billTemplate->items()->delete();

            foreach ($validated['items'] as $item) {
                $billTemplate->items()->create([
                    'item_type' => $item['type'],
                    'item_id' => $item['id'],
                    'item_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                ]);
            }
        });

        return redirect()->route('bill-templates.index')->with('success', 'Template updated successfully!');
    }

    public function destroy(BillTemplate $billTemplate)
    {
        $billTemplate->delete();
        return redirect()->route('bill-templates.index')->with('success', 'Template deleted successfully!');
    }

    public function apiShow(BillTemplate $billTemplate)
    {
        return response()->json($billTemplate->load('items'));
    }
}
