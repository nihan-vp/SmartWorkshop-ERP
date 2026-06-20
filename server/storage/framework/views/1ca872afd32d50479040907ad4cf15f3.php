
<?php $__env->startSection('title', 'Edit Work Order'); ?>
<?php $__env->startSection('page-title', 'Edit Work Order'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto"><div class="glass-card">
    <form action="<?php echo e(route('work-orders.update', $workOrder)); ?>" method="POST">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <div class="space-y-6">
            
            <div>
                <h3 class="text-sm font-semibold text-primary-400 uppercase tracking-wider mb-4">Client Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="customer_id" class="form-label">Customer *</label>
                        <select id="customer_id" name="customer_id" class="form-select" required>
                            <option value="">Select Customer</option>
                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($c->id); ?>" <?php echo e(old('customer_id', $workOrder->customer_id ?? '') == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label for="vehicle_id" class="form-label">Vehicle</label>
                        <select id="vehicle_id" name="vehicle_id" class="form-select">
                            <option value="">Select Vehicle</option>
                            <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($v->id); ?>" <?php echo e(old('vehicle_id', $workOrder->vehicle_id ?? '') == $v->id ? 'selected' : ''); ?>><?php echo e($v->plate_number); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>
            
            
            <div class="pt-6 border-t border-white/10">
                <h3 class="text-sm font-semibold text-primary-400 uppercase tracking-wider mb-4">Job Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                    <div>
                        <label for="employee_id" class="form-label">Assigned Technician</label>
                        <select id="employee_id" name="employee_id" class="form-select">
                            <option value="">Unassigned</option>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($e->id); ?>" <?php echo e(old('employee_id', $workOrder->employee_id ?? '') == $e->id ? 'selected' : ''); ?>><?php echo e($e->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="form-label">Status *</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="pending" <?php echo e(old('status', $workOrder->status ?? '') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="in_progress" <?php echo e(old('status', $workOrder->status ?? '') === 'in_progress' ? 'selected' : ''); ?>>In Progress</option>
                            <option value="completed" <?php echo e(old('status', $workOrder->status ?? '') === 'completed' ? 'selected' : ''); ?>>Completed</option>
                        </select>
                    </div>
                    <div>
                        <label for="priority" class="form-label">Priority *</label>
                        <select id="priority" name="priority" class="form-select" required>
                            <option value="normal" <?php echo e(old('priority', $workOrder->priority ?? 'normal') === 'normal' ? 'selected' : ''); ?>>Normal</option>
                            <option value="low" <?php echo e(old('priority', $workOrder->priority ?? '') === 'low' ? 'selected' : ''); ?>>Low</option>
                            <option value="high" <?php echo e(old('priority', $workOrder->priority ?? '') === 'high' ? 'selected' : ''); ?>>High</option>
                            <option value="urgent" <?php echo e(old('priority', $workOrder->priority ?? '') === 'urgent' ? 'selected' : ''); ?>>Urgent</option>
                        </select>
                    </div>
                </div>
                <div><label for="description" class="form-label">Job Description / Instructions</label><textarea id="description" name="description" rows="4" class="form-input" placeholder="Enter description"><?php echo e(old('description', $workOrder->description)); ?></textarea></div>
            </div>

            
            <div class="pt-6 border-t border-white/10">
                <h3 class="text-sm font-semibold text-primary-400 uppercase tracking-wider mb-4">Estimates & Dates</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div><label for="estimated_cost" class="form-label">Est. Cost (₹)</label><input id="estimated_cost" type="number" step="0.01" name="estimated_cost" value="<?php echo e(old("estimated_cost", $workOrder->estimated_cost)); ?>" placeholder="Enter estimated cost" class="form-input"></div>
                    <div><label for="actual_cost" class="form-label">Actual Cost (₹)</label><input id="actual_cost" type="number" step="0.01" name="actual_cost" value="<?php echo e(old("actual_cost", $workOrder->actual_cost)); ?>" placeholder="Enter actual cost" class="form-input"></div>
                    <div><label for="start_date" class="form-label">Start Date</label><input id="start_date" type="date" name="start_date" value="<?php echo e(old('start_date', $workOrder->start_date ? $workOrder->start_date->format('Y-m-d') : '')); ?>" class="form-input"></div>
                    <div><label for="end_date" class="form-label">End Date</label><input id="end_date" type="date" name="end_date" value="<?php echo e(old('end_date', $workOrder->end_date ? $workOrder->end_date->format('Y-m-d') : '')); ?>" class="form-input"></div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-between mt-8 pt-6 border-t border-white/10">
            <p class="text-sm text-gray-500">Order #: <span class="font-mono text-white"><?php echo e($workOrder->order_number); ?></span></p>
            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('work-orders.index')); ?>" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Update Work Order</button>
            </div>
        </div>
    </form>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\work_orders\edit.blade.php ENDPATH**/ ?>