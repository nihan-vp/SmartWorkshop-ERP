<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('category', 'like', "%{$request->search}%");
        }
        $products = $query->latest()->paginate((int) request('limit', 15));
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:100|unique:products',
            'category' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'stock_qty' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
        ]);
        
        $product = Product::create($validated);

        // Manage branch-specific stock if a branch context is available
        $branchId = null;
        if (auth()->user()->branch_id) {
            $branchId = auth()->user()->branch_id;
        } elseif (session('active_branch_id')) {
            $branchId = session('active_branch_id');
        }

        if ($branchId) {
            \App\Models\ProductStock::create([
                'product_id' => $product->id,
                'branch_id' => $branchId,
                'stock_qty' => $validated['stock_qty'],
                'min_stock' => $validated['min_stock'],
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $product->id,
            'category' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'stock_qty' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
        ]);
        
        $product->update($validated);

        // Update branch-specific stock if a branch context is available
        $branchId = null;
        if (auth()->user()->branch_id) {
            $branchId = auth()->user()->branch_id;
        } elseif (session('active_branch_id')) {
            $branchId = session('active_branch_id');
        }

        if ($branchId) {
            $stock = \App\Models\ProductStock::firstOrNew([
                'product_id' => $product->id,
                'branch_id' => $branchId
            ]);
            $stock->stock_qty = $validated['stock_qty'];
            $stock->min_stock = $validated['min_stock'];
            $stock->save();
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
