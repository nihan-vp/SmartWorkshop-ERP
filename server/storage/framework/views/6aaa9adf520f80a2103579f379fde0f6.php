
<?php $__env->startSection('title', $customer->name); ?>
<?php $__env->startSection('page-title', $customer->name); ?>
<?php $__env->startSection('page-subtitle', 'Customer Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-6">
    <div class="glass-card">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-slate-900"><?php echo e($customer->name); ?></h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Customer #<?php echo e($customer->id); ?></p>
            </div>
            <a href="<?php echo e(route('customers.edit', $customer)); ?>" class="btn-secondary">Edit</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div><p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Phone</p><p class="text-slate-800 font-semibold"><?php echo e($customer->phone ?? '—'); ?></p></div>
            <div><p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Email</p><p class="text-slate-800 font-semibold"><?php echo e($customer->email ?? '—'); ?></p></div>
            <div><p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Address</p><p class="text-slate-800 font-semibold"><?php echo e($customer->address ?? '—'); ?></p></div>
        </div>
    </div>

    
    <div class="glass-card">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Vehicles (<?php echo e($customer->vehicles->count()); ?>)</h3>
        <?php if($customer->vehicles->count()): ?>
        <div class="space-y-3">
            <?php $__currentLoopData = $customer->vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200/60">
                <div>
                    <p class="text-sm font-semibold text-slate-800"><?php echo e($v->make); ?> <?php echo e($v->model); ?></p>
                    <p class="text-xs text-slate-500 font-medium"><?php echo e($v->plate_number); ?> <?php echo e($v->color ? '• '.$v->color : ''); ?></p>
                </div>
                <span class="badge badge-info"><?php echo e($v->year ?? '—'); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <p class="text-sm text-slate-500 font-medium">No vehicles registered</p>
        <?php endif; ?>
    </div>

    
    <div class="glass-card">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Bills (<?php echo e($customer->bills->count()); ?>)</h3>
        <?php if($customer->bills->count()): ?>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead><tr><th>Sl No</th><th>Invoice No</th><th>Date</th><th>Total</th><th>Status</th></tr></thead>
                <tbody>
                    <?php $__currentLoopData = $customer->bills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $bill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="font-semibold text-slate-500"><?php echo e($loop->iteration); ?></td>
                        <td class="text-slate-800 font-semibold"><?php echo e($bill->bill_number); ?></td>
                        <td class="font-medium text-slate-600"><?php echo e($bill->bill_date->format('d M Y')); ?></td>
                        <td class="font-bold text-slate-900">₹<?php echo e(number_format($bill->total, 2)); ?></td>
                        <td><span class="badge <?php echo e($bill->payment_status === 'paid' ? 'badge-success' : 'badge-warning'); ?>"><?php echo e(ucfirst($bill->payment_status)); ?></span></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p class="text-sm text-slate-500 font-medium">No bills yet</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\customers\show.blade.php ENDPATH**/ ?>