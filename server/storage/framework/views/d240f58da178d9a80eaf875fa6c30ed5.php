
<?php $__env->startSection('title', 'Add Customer'); ?>
<?php $__env->startSection('page-title', 'Add New Customer'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <div class="glass-card">
        <form action="<?php echo e(route('customers.store')); ?>" method="POST" id="create-customer-form">
            <?php echo csrf_field(); ?>
            <div class="space-y-5">
                <div>
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="form-input" required id="input-name" placeholder="Enter name">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" class="form-input" id="input-phone" placeholder="Enter phone">
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-input" id="input-email" placeholder="Enter email">
                    </div>
                </div>
                <div>
                    <label class="form-label">Address</label>
                    <textarea name="address" rows="3" class="form-input" id="input-address" placeholder="Enter address"><?php echo e(old('address')); ?></textarea>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
                <button type="submit" class="btn-primary">Save Customer</button>
                <a href="<?php echo e(route('customers.index')); ?>" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\customers\create.blade.php ENDPATH**/ ?>