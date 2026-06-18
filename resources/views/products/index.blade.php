@extends('layouts.app')
@section('title', 'Products & Stock')
@section('page-title', 'Products & Stock')
@section('page-subtitle', 'Manage your inventory')

@section('content')
<div x-data="{ 
    showModal: false, 
    modalTitle: '', 
    actionUrl: '',
    methodOverride: '',
    product: { id: null, name: '', barcode: '', category: '', price: '', cost_price: '', stock_qty: '', min_stock: '', unit: 'pcs' },
    openCreate() {
        this.modalTitle = 'Add Product';
        this.actionUrl = '{{ route('products.store') }}';
        this.methodOverride = '';
        this.product = { id: null, name: '', barcode: '', category: '', price: '', cost_price: '', stock_qty: '', min_stock: '', unit: 'pcs' };
        this.showModal = true;
    },
    openEdit(prod) {
        this.modalTitle = 'Edit Product';
        this.actionUrl = '/products/' + prod.id;
        this.methodOverride = 'PUT';
        this.product = { ...prod };
        this.showModal = true;
    }
}" class="relative">

    <!-- Search and Add Product Button -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <form method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="form-input sm:w-72">
        <select name="limit" onchange="this.form.submit()" class="form-input sm:w-24" title="Rows per page">
            <option value="15" {{ request('limit') == 15 ? 'selected' : '' }}>15 / page</option>
            <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25 / page</option>
            <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50 / page</option>
            <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100 / page</option>
        </select>
            <button type="submit" class="btn-secondary">Search</button>
        </form>
        <button @click="openCreate()" class="btn-primary animate-pulse-glow">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Product
        </button>
    </div>

    <!-- Products Table -->
    <div class="glass-card !p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Name</th>
                        <th>Barcode</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Cost</th>
                        <th>Stock</th>
                        <th>Min</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr>
                        <td data-label="Sl No" class="text-slate-400 font-semibold">{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                        <td data-label="Name" class="font-bold text-slate-800">{{ $p->name }}</td>
                        <td data-label="Barcode" class="font-mono text-xs font-semibold text-slate-500">{{ $p->barcode ?? '—' }}</td>
                        <td data-label="Category" class="font-medium text-slate-600">{{ $p->category ?? '—' }}</td>
                        <td data-label="Price" class="text-emerald-600 font-bold">₹{{ number_format($p->price, 2) }}</td>
                        <td data-label="Cost" class="font-medium text-slate-600">₹{{ number_format($p->cost_price, 2) }}</td>
                        <td data-label="Stock" class="font-bold {{ $p->isLowStock() ? 'text-red-600' : 'text-slate-800' }}">{{ $p->stock_qty }} {{ $p->unit }}</td>
                        <td data-label="Min" class="font-medium text-slate-600">{{ $p->min_stock }}</td>
                        <td data-label="Status">
                            @if($p->isLowStock())
                            <span class="badge badge-danger">Low Stock</span>
                            @else
                            <span class="badge badge-success">In Stock</span>
                            @endif
                        </td>
                        <td data-label="">
                            <div class="flex items-center gap-2">
                                <button @click="openEdit({ 
                                    id: {{ $p->id }}, 
                                    name: '{{ addslashes($p->name) }}', 
                                    barcode: '{{ addslashes($p->barcode ?? '') }}', 
                                    category: '{{ addslashes($p->category ?? '') }}', 
                                    price: '{{ $p->price }}', 
                                    cost_price: '{{ $p->cost_price }}', 
                                    stock_qty: '{{ $p->stock_qty }}', 
                                    min_stock: '{{ $p->min_stock }}', 
                                    unit: '{{ addslashes($p->unit) }}'
                                })" class="text-amber-600 hover:text-amber-700 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('products.destroy', $p) }}" method="POST" onsubmit="return confirm('Delete product {{ $p->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-600 transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center py-8 text-slate-400 font-medium font-semibold">No products found. <button @click="openCreate()" class="text-primary-600 hover:underline">Add your first product</button></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div class="px-5 py-4 border-t border-slate-200/60">{{ $products->appends(request()->query())->links() }}</div>
        @endif
    </div>

    <!-- Small Modal Popup Overlay -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="showModal = false" class="bg-white rounded-3xl p-8 max-w-lg w-full relative border border-slate-100 shadow-2xl animate-fade-in-up" style="animation-duration: 0.2s;">
            
            <!-- Close (X) Icon Button -->
            <button @click="showModal = false" class="absolute top-5 right-5 p-2 rounded-xl text-slate-400 hover:text-slate-900 hover:bg-slate-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Modal Heading -->
            <h3 class="text-xl font-bold text-slate-950 mb-6 flex items-center gap-2" x-text="modalTitle"></h3>

            <!-- Create/Edit Product Form -->
            <form :action="actionUrl" method="POST" class="space-y-4">
                @csrf
                <template x-if="methodOverride === 'PUT'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" x-model="product.name" required placeholder="Enter product name" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Barcode</label>
                        <input type="text" name="barcode" x-model="product.barcode" placeholder="Scan or enter barcode" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Category</label>
                        <input type="text" name="category" x-model="product.category" placeholder="e.g. Engine oil" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Selling Price (₹)</label>
                        <input type="number" step="0.01" name="price" x-model="product.price" required placeholder="0.00" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Cost Price (₹)</label>
                        <input type="number" step="0.01" name="cost_price" x-model="product.cost_price" required placeholder="0.00" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" name="stock_qty" x-model="product.stock_qty" required placeholder="0" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Min Stock (Alert)</label>
                        <input type="number" name="min_stock" x-model="product.min_stock" required placeholder="0" class="form-input">
                    </div>

                    <div class="col-span-2">
                        <label class="form-label">Stock Unit</label>
                        <input type="text" name="unit" x-model="product.unit" placeholder="e.g. pcs, ltrs" class="form-input">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 mt-6">
                    <button type="button" @click="showModal = false" class="btn-secondary !py-2">Cancel</button>
                    <button type="submit" class="btn-primary !py-2" x-text="methodOverride === 'PUT' ? 'Save Changes' : 'Add Product'"></button>
                </div>
            </form>

        </div>
    </div>

</div>

<!-- Alpine Cloak Styling to prevent flickering -->
<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
