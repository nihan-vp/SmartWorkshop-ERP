
<?php $__env->startSection('title', 'Edit Vehicle'); ?>
<?php $__env->startSection('page-title', 'Edit Vehicle: ' . $vehicle->plate_number); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <div class="glass-card">
        <form action="<?php echo e(route('vehicles.update', $vehicle)); ?>" method="POST" id="edit-vehicle-form">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="space-y-5">
                <div>
                    <label class="form-label">Customer *</label>
                    <div class="flex gap-3">
                        <select name="customer_id" class="form-input flex-1" required>
                            <option value="">Select Customer</option>
                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($customer->id); ?>" <?php echo e(old('customer_id', $vehicle->customer_id) == $customer->id ? 'selected' : ''); ?>><?php echo e($customer->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <a href="<?php echo e(route('customers.create')); ?>" class="btn-secondary whitespace-nowrap flex items-center">
                            + Add
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Make</label>
                        <input type="text" name="make" value="<?php echo e(old('make', $vehicle->make)); ?>" class="form-input" placeholder="e.g. Maruti, Honda">
                    </div>
                    <div>
                        <label class="form-label">Model *</label>
                        <input type="text" name="model" value="<?php echo e(old("model", $vehicle->model)); ?>" placeholder="Enter model" class="form-input" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="form-label">Year</label>
                        <input type="number" name="year" value="<?php echo e(old("year", $vehicle->year)); ?>" placeholder="Enter year" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Plate Number *</label>
                        <input type="text" name="plate_number" value="<?php echo e(old("plate_number", $vehicle->plate_number)); ?>" placeholder="Enter plate number" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Color</label>
                        <input type="text" name="color" value="<?php echo e(old("color", $vehicle->color)); ?>" placeholder="Enter color" class="form-input">
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
                <button type="submit" class="btn-primary">Update Vehicle</button>
                <a href="<?php echo e(route('vehicles.index')); ?>" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\vehicles\edit.blade.php ENDPATH**/ ?>