
<?php $__env->startSection('title', 'Edit Borrow Record'); ?>
<?php $__env->startSection('page-title', 'Edit Borrow Record'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <div class="glass-card">
        <form action="<?php echo e(route('salary-advances.update', $salaryAdvance)); ?>" method="POST" id="edit-borrow-form">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="space-y-5">
                <div>
                    <label class="form-label">Employee *</label>
                    <select name="employee_id" class="form-input" required>
                        <option value="">Select Employee</option>
                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($employee->id); ?>" <?php echo e(old('employee_id', $salaryAdvance->employee_id) == $employee->id ? 'selected' : ''); ?>><?php echo e($employee->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Amount *</label>
                        <input type="number" step="0.01" name="amount" value="<?php echo e(old("amount", $salaryAdvance->amount)); ?>" placeholder="Enter amount" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Date *</label>
                        <input type="date" name="date" value="<?php echo e(old('date', $salaryAdvance->date ? \Carbon\Carbon::parse($salaryAdvance->date)->format('Y-m-d') : '')); ?>" class="form-input" required>
                    </div>
                </div>
                <div>
                    <label class="form-label">Reason</label>
                    <textarea name="reason" rows="3" class="form-input" placeholder="Enter reason"><?php echo e(old('reason', $salaryAdvance->reason)); ?></textarea>
                </div>
                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-input" required>
                        <option value="pending" <?php echo e(old('status', $salaryAdvance->status) == 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="approved" <?php echo e(old('status', $salaryAdvance->status) == 'approved' ? 'selected' : ''); ?>>Approved</option>
                        <option value="deducted" <?php echo e(old('status', $salaryAdvance->status) == 'deducted' ? 'selected' : ''); ?>>Deducted</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
                <button type="submit" class="btn-primary">Update Borrow Record</button>
                <a href="<?php echo e(route('salaries.index')); ?>" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\salary_advances\edit.blade.php ENDPATH**/ ?>