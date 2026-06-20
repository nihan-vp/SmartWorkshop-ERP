

<?php $__env->startSection('title', 'Edit Purchase'); ?>
<?php $__env->startSection('page-title', 'Edit Purchase'); ?>
<?php $__env->startSection('page-subtitle', 'Update purchase record details'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl animate-fade-in-up">
    <div class="mb-6">
        <a href="<?php echo e(route('purchases.index')); ?>" class="text-sm font-bold text-slate-500 hover:text-slate-700 flex items-center gap-1.5 transition-colors w-max">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Purchases
        </a>
    </div>

    <form action="<?php echo e(route('purchases.update', $purchase)); ?>" method="POST" class="glass-card shadow-sm p-6 sm:p-8 relative overflow-hidden">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary-50 rounded-full blur-3xl -z-10 opacity-60"></div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
            <div class="space-y-1 md:col-span-2">
                <label for="supplier_name" class="form-label">Supplier Name <span class="text-rose-500">*</span></label>
                <input type="text" name="supplier_name" id="supplier_name" value="<?php echo e(old("supplier_name", $purchase->supplier_name)); ?>" placeholder="Enter supplier name" required class="form-input">
                <?php $__errorArgs = ['supplier_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="space-y-1">
                <label for="invoice_number" class="form-label">Invoice Number</label>
                <input type="text" name="invoice_number" id="invoice_number" value="<?php echo e(old("invoice_number", $purchase->invoice_number)); ?>" placeholder="Enter invoice number" class="form-input">
                <?php $__errorArgs = ['invoice_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="space-y-1">
                <label for="purchase_date" class="form-label">Purchase Date <span class="text-rose-500">*</span></label>
                <input type="date" name="purchase_date" id="purchase_date" value="<?php echo e(old('purchase_date', $purchase->purchase_date->format('Y-m-d'))); ?>" required class="form-input">
                <?php $__errorArgs = ['purchase_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="space-y-1">
                <label for="total_amount" class="form-label">Total Amount (₹) <span class="text-rose-500">*</span></label>
                <input type="number" step="0.01" min="0" name="total_amount" id="total_amount" value="<?php echo e(old("total_amount", $purchase->total_amount)); ?>" placeholder="Enter total amount" required class="form-input font-mono">
                <?php $__errorArgs = ['total_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="space-y-1">
                <label for="payment_method" class="form-label">Payment Method <span class="text-rose-500">*</span></label>
                <select name="payment_method" id="payment_method" required class="form-input">
                    <option value="cash" <?php echo e(old('payment_method', $purchase->payment_method) == 'cash' ? 'selected' : ''); ?>>Cash</option>
                    <option value="upi" <?php echo e(old('payment_method', $purchase->payment_method) == 'upi' ? 'selected' : ''); ?>>UPI</option>
                    <option value="card" <?php echo e(old('payment_method', $purchase->payment_method) == 'card' ? 'selected' : ''); ?>>Card</option>
                    <option value="bank" <?php echo e(old('payment_method', $purchase->payment_method) == 'bank' ? 'selected' : ''); ?>>Bank Transfer</option>
                </select>
                <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <div class="space-y-1">
                <label for="payment_status" class="form-label">Payment Status <span class="text-rose-500">*</span></label>
                <select name="payment_status" id="payment_status" required class="form-input">
                    <option value="paid" <?php echo e(old('payment_status', $purchase->payment_status) == 'paid' ? 'selected' : ''); ?>>Paid</option>
                    <option value="partial" <?php echo e(old('payment_status', $purchase->payment_status) == 'partial' ? 'selected' : ''); ?>>Partial</option>
                    <option value="unpaid" <?php echo e(old('payment_status', $purchase->payment_status) == 'unpaid' ? 'selected' : ''); ?>>Unpaid</option>
                </select>
                <?php $__errorArgs = ['payment_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="space-y-1 md:col-span-2">
                <label for="items_description" class="form-label">Items / Description</label>
                <textarea name="items_description" id="items_description" rows="3" class="form-input" placeholder="Enter items description"><?php echo e(old('items_description', $purchase->items_description)); ?></textarea>
                <?php $__errorArgs = ['items_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3 relative z-10">
            <a href="<?php echo e(route('purchases.index')); ?>" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">
                Update Purchase
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\purchases\edit.blade.php ENDPATH**/ ?>