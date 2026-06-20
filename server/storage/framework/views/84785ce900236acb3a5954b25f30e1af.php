
<?php $__env->startSection('title', 'Edit Employee'); ?>
<?php $__env->startSection('page-title', 'Edit Employee: ' . $employee->name); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <div class="glass-card">
        <form action="<?php echo e(route('employees.update', $employee)); ?>" method="POST" id="edit-employee-form">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" value="<?php echo e(old("name", $employee->name)); ?>" placeholder="Enter name" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="<?php echo e(old("phone", $employee->phone)); ?>" placeholder="Enter phone" class="form-input">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="<?php echo e(old("email", $employee->email)); ?>" placeholder="Enter email" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Role *</label>
                        <input type="text" name="role" value="<?php echo e(old("role", $employee->role)); ?>" placeholder="Enter role" class="form-input" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="form-label">Salary *</label>
                        <input type="number" step="0.01" name="salary" value="<?php echo e(old("salary", $employee->salary)); ?>" placeholder="Enter salary" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Join Date</label>
                        <input type="date" name="join_date" value="<?php echo e(old('join_date', $employee->join_date ? \Carbon\Carbon::parse($employee->join_date)->format('Y-m-d') : '')); ?>" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-input" required>
                            <option value="active" <?php echo e(old('status', $employee->status) == 'active' ? 'selected' : ''); ?>>Active</option>
                            <option value="inactive" <?php echo e(old('status', $employee->status) == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
                <button type="submit" class="btn-primary">Update Employee</button>
                <a href="<?php echo e(route('employees.index')); ?>" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\employees\edit.blade.php ENDPATH**/ ?>