@extends('layouts.app')
@section('title', 'Edit Product')
@section('page-title', 'Edit Product')
@section('content')
<div class="max-w-2xl mx-auto"><div class="glass-card">
    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf @method('PUT')
        <div class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div><label class="form-label">Name *</label><input type="text" name="name" value="{{ old('name', $product- placeholder="Enter name">name) }}" class="form-input" required></div>
                <div><label class="form-label">Barcode</label><input type="text" name="barcode" value="{{ old('barcode', $product- placeholder="Enter barcode">barcode) }}" class="form-input"></div>
            </div>
            <div><label class="form-label">Category</label><input type="text" name="category" value="{{ old('category', $product- placeholder="Enter category">category) }}" class="form-input"></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div><label class="form-label">Selling Price (₹) *</label><input type="number" step="0.01" name="price" value="{{ old('price', $product- placeholder="Enter price">price) }}" class="form-input" required></div>
                <div><label class="form-label">Cost Price (₹) *</label><input type="number" step="0.01" name="cost_price" value="{{ old('cost_price', $product- placeholder="Enter cost price">cost_price) }}" class="form-input" required></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div><label class="form-label">Stock Qty *</label><input type="number" name="stock_qty" value="{{ old('stock_qty', $product- placeholder="Enter stock qty">stock_qty) }}" class="form-input" required></div>
                <div><label class="form-label">Min Stock *</label><input type="number" name="min_stock" value="{{ old('min_stock', $product- placeholder="Enter min stock">min_stock) }}" class="form-input" required></div>
                <div><label class="form-label">Unit *</label><input type="text" name="unit" value="{{ old('unit', $product- placeholder="Enter unit">unit) }}" class="form-input" required></div>
            </div>
        </div>
        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
            <button type="submit" class="btn-primary">Update Product</button>
            <a href="{{ route('products.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div></div>
@endsection
