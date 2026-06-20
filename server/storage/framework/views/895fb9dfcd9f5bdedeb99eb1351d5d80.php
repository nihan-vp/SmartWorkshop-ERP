
<?php $__env->startSection('title', 'Add Expense'); ?>
<?php $__env->startSection('page-title', 'Add New Expense'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <div class="glass-card">
        <form action="<?php echo e(route('expenses.store')); ?>" method="POST" id="create-expense-form">
            <?php echo csrf_field(); ?>
            <div class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Category *</label>
                        <input type="text" name="category" value="<?php echo e(old('category')); ?>" class="form-input" required placeholder="Enter category">
                    </div>
                    <div>
                        <label class="form-label">Amount *</label>
                        <input type="number" step="0.01" name="amount" value="<?php echo e(old('amount')); ?>" class="form-input" required placeholder="Enter amount">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Expense Date *</label>
                        <input type="date" name="expense_date" value="<?php echo e(old('expense_date', date('Y-m-d'))); ?>" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Payment Method *</label>
                        <select name="payment_method" class="form-input" required>
                            <option value="cash" <?php echo e(old('payment_method') == 'cash' ? 'selected' : ''); ?>>Cash</option>
                            <option value="upi" <?php echo e(old('payment_method') == 'upi' ? 'selected' : ''); ?>>UPI</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-input" placeholder="Enter description"><?php echo e(old('description')); ?></textarea>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
                <button type="submit" class="btn-primary">Save Expense</button>
                <a href="<?php echo e(route('expenses.index')); ?>" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\expenses\create.blade.php ENDPATH**/ ?>