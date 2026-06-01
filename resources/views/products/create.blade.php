@extends('layouts.app')
@section('title', isset($product) ? 'Edit Product' : 'Add Product')
@section('page-title', isset($product) ? 'Edit Product' : 'Add New Product')
@section('content')
<div class="max-w-2xl mx-auto"><div class="glass-card">
    <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST">
        @csrf @if(isset($product)) @method('PUT') @endif
        <div class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div><label class="form-label">Name *</label><input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="form-input" required></div>
                <div><label class="form-label">SKU</label><input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" class="form-input"></div>
            </div>
            <div><label class="form-label">Category</label><input type="text" name="category" value="{{ old('category', $product->category ?? '') }}" class="form-input" placeholder="e.g. Engine Parts, Oil, Tyres"></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div><label class="form-label">Selling Price (₹) *</label><input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" class="form-input" required></div>
                <div><label class="form-label">Cost Price (₹) *</label><input type="number" step="0.01" name="cost_price" value="{{ old('cost_price', $product->cost_price ?? '') }}" class="form-input" required></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div><label class="form-label">Stock Qty *</label><input type="number" name="stock_qty" value="{{ old('stock_qty', $product->stock_qty ?? 0) }}" class="form-input" required></div>
                <div><label class="form-label">Min Stock *</label><input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock ?? 5) }}" class="form-input" required></div>
                <div><label class="form-label">Unit *</label><input type="text" name="unit" value="{{ old('unit', $product->unit ?? 'pcs') }}" class="form-input" required></div>
            </div>
        </div>
        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
            <button type="submit" class="btn-primary">{{ isset($product) ? 'Update' : 'Save' }} Product</button>
            <a href="{{ route('products.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div></div>
@endsection
