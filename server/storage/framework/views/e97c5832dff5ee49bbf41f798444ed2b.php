
<?php $__env->startSection('title', 'Edit Warranty'); ?>
<?php $__env->startSection('page-title', 'Edit Warranty'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto"><div class="glass-card">
    <form action="<?php echo e(route('warranties.update', $warranty)); ?>" method="POST">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <div class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Customer *</label>
                    <select name="customer_id" class="form-select" required>
                        <option value="">Select Customer</option>
                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c->id); ?>" <?php echo e(old('customer_id', $warranty->customer_id) == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="form-label">Vehicle</label>
                    <select name="vehicle_id" class="form-select">
                        <option value="">Select Vehicle (Optional)</option>
                        <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($v->id); ?>" <?php echo e(old('vehicle_id', $warranty->vehicle_id) == $v->id ? 'selected' : ''); ?>><?php echo e($v->plate_number); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div>
                <label class="form-label">Linked Bill</label>
                <select name="bill_id" class="form-select">
                    <option value="">Select Bill (Optional)</option>
                    <?php $__currentLoopData = $bills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($b->id); ?>" <?php echo e(old('bill_id', $warranty->bill_id) == $b->id ? 'selected' : ''); ?>><?php echo e($b->bill_number); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div><label class="form-label">Description / Terms</label><textarea name="description" rows="3" class="form-input" placeholder="Enter description"><?php echo e(old('description', $warranty->description)); ?></textarea></div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div><label class="form-label">Start Date *</label><input type="date" name="start_date" value="<?php echo e(old('start_date', $warranty->start_date->format('Y-m-d'))); ?>" class="form-input" required></div>
                <div><label class="form-label">End Date *</label><input type="date" name="end_date" value="<?php echo e(old('end_date', $warranty->end_date->format('Y-m-d'))); ?>" class="form-input" required></div>
                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="active" <?php echo e(old('status', $warranty->status) === 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="expired" <?php echo e(old('status', $warranty->status) === 'expired' ? 'selected' : ''); ?>>Expired</option>
                        <option value="claimed" <?php echo e(old('status', $warranty->status) === 'claimed' ? 'selected' : ''); ?>>Claimed</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
            <button type="submit" class="btn-primary">Update Warranty</button>
            <a href="<?php echo e(route('warranties.index')); ?>" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\warranties\edit.blade.php ENDPATH**/ ?>