
<?php $__env->startSection('title', isset($product) ? 'Edit Product' : 'Add Product'); ?>
<?php $__env->startSection('page-title', isset($product) ? 'Edit Product' : 'Add New Product'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto"><div class="glass-card">
    <form action="<?php echo e(isset($product) ? route('products.update', $product) : route('products.store')); ?>" method="POST">
        <?php echo csrf_field(); ?> <?php if(isset($product)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
        <div class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div><label class="form-label">Name *</label><input type="text" name="name" value="<?php echo e(old("name", $product->name ?? '')); ?>" placeholder="Enter name" class="form-input" required></div>
                <div><label class="form-label">Barcode</label><input type="text" name="barcode" value="<?php echo e(old("barcode", $product->barcode ?? '')); ?>" placeholder="Enter barcode" class="form-input"></div>
            </div>
            <div><label class="form-label">Category</label><input type="text" name="category" value="<?php echo e(old("category", $product->category ?? '')); ?>" placeholder="Enter category" class="form-input" placeholder="e.g. Engine Parts, Oil, Tyres"></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div><label class="form-label">Selling Price (₹) *</label><input type="number" step="0.01" name="price" value="<?php echo e(old("price", $product->price ?? '')); ?>" placeholder="Enter price" class="form-input" required></div>
                <div><label class="form-label">Cost Price (₹) *</label><input type="number" step="0.01" name="cost_price" value="<?php echo e(old("cost_price", $product->cost_price ?? '')); ?>" placeholder="Enter cost price" class="form-input" required></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div><label class="form-label">Stock Qty *</label><input type="number" name="stock_qty" value="<?php echo e(old("stock_qty", $product->stock_qty ?? 0)); ?>" placeholder="Enter stock qty" class="form-input" required></div>
                <div><label class="form-label">Min Stock *</label><input type="number" name="min_stock" value="<?php echo e(old("min_stock", $product->min_stock ?? 5)); ?>" placeholder="Enter min stock" class="form-input" required></div>
                <div><label class="form-label">Unit *</label><input type="text" name="unit" value="<?php echo e(old("unit", $product->unit ?? 'pcs')); ?>" placeholder="Enter unit" class="form-input" required></div>
            </div>
        </div>
        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
            <button type="submit" class="btn-primary"><?php echo e(isset($product) ? 'Update' : 'Save'); ?> Product</button>
            <a href="<?php echo e(route('products.index')); ?>" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\products\create.blade.php ENDPATH**/ ?>