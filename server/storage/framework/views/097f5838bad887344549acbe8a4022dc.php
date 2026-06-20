
<?php $__env->startSection('title', 'Edit Salary Record'); ?>
<?php $__env->startSection('page-title', 'Edit Salary Record'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <div class="glass-card">
        <form action="<?php echo e(route('salaries.update', $salary)); ?>" method="POST" id="edit-salary-form">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="space-y-5">
                <div>
                    <label class="form-label">Employee *</label>
                    <select name="employee_id" class="form-input" required>
                        <option value="">Select Employee</option>
                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($employee->id); ?>" <?php echo e(old('employee_id', $salary->employee_id) == $employee->id ? 'selected' : ''); ?>><?php echo e($employee->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Amount *</label>
                        <input type="number" step="0.01" name="amount" value="<?php echo e(old("amount", $salary->amount)); ?>" placeholder="Enter amount" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Advance Deduction</label>
                        <input type="number" step="0.01" name="advance_deduction" value="<?php echo e(old("advance_deduction", $salary->advance_deduction ?? 0)); ?>" placeholder="Enter advance deduction" class="form-input">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Month *</label>
                        <input type="text" name="month" value="<?php echo e(old("month", $salary->month)); ?>" placeholder="Enter month" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Year *</label>
                        <input type="number" name="year" value="<?php echo e(old("year", $salary->year)); ?>" placeholder="Enter year" class="form-input" required>
                    </div>
                </div>
                    <div>
                        <label class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" value="<?php echo e(old('payment_date', $salary->payment_date ? \Carbon\Carbon::parse($salary->payment_date)->format('Y-m-d') : '')); ?>" class="form-input">
                    </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Payment Method *</label>
                        <select name="payment_method" class="form-input" required>
                            <option value="cash" <?php echo e(old('payment_method', $salary->payment_method) == 'cash' ? 'selected' : ''); ?>>Cash</option>
                            <option value="upi" <?php echo e(old('payment_method', $salary->payment_method) == 'upi' ? 'selected' : ''); ?>>UPI</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-input" required>
                            <option value="pending" <?php echo e(old('status', $salary->status) == 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="paid" <?php echo e(old('status', $salary->status) == 'paid' ? 'selected' : ''); ?>>Paid</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
                <button type="submit" class="btn-primary">Update Salary</button>
                <a href="<?php echo e(route('salaries.index')); ?>" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\salaries\edit.blade.php ENDPATH**/ ?>