
<?php $__env->startSection('title', 'Work Order: ' . $workOrder->order_number); ?>
<?php $__env->startSection('page-title', 'Work Order Details'); ?>
<?php $__env->startSection('page-subtitle', $workOrder->order_number); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="badge <?php echo e($workOrder->status === 'completed' ? 'badge-success' : ($workOrder->status === 'in_progress' ? 'badge-info' : 'badge-warning')); ?> text-sm px-3 py-1.5">
                <?php echo e(ucfirst(str_replace('_', ' ', $workOrder->status))); ?>

            </span>
            <span class="badge <?php echo e($workOrder->priority === 'urgent' ? 'badge-danger' : ($workOrder->priority === 'high' ? 'badge-warning' : 'badge-info')); ?> text-sm px-3 py-1.5">
                <?php echo e(ucfirst($workOrder->priority)); ?> Priority
            </span>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?php echo e(route('work-orders.edit', $workOrder)); ?>" class="btn-secondary">Edit Order</a>
            <?php if($workOrder->status === 'completed'): ?>
            <a href="<?php echo e(route('bills.create', ['work_order' => $workOrder->id])); ?>" class="btn-primary">Generate Bill</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="glass-card">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Customer & Vehicle</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Customer</p>
                    <p class="text-lg font-bold text-slate-900"><?php echo e($workOrder->customer->name); ?></p>
                    <p class="text-sm font-medium text-slate-500"><?php echo e($workOrder->customer->phone ?? 'No phone'); ?></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Vehicle</p>
                    <?php if($workOrder->vehicle): ?>
                    <p class="text-slate-800 font-semibold"><?php echo e($workOrder->vehicle->make); ?> <?php echo e($workOrder->vehicle->model); ?></p>
                    <p class="text-sm font-mono font-bold text-primary-600"><?php echo e($workOrder->vehicle->plate_number); ?></p>
                    <?php else: ?>
                    <p class="text-slate-400 italic">No vehicle assigned</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="glass-card">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Job Details</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Assigned To</p>
                    <p class="text-slate-800 font-semibold"><?php echo e($workOrder->employee ? $workOrder->employee->name : 'Unassigned'); ?></p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Start Date</p>
                        <p class="text-slate-800 font-semibold"><?php echo e($workOrder->start_date ? $workOrder->start_date->format('d M Y') : '—'); ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">End Date</p>
                        <p class="text-slate-800 font-semibold"><?php echo e($workOrder->end_date ? $workOrder->end_date->format('d M Y') : '—'); ?></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Estimated Cost</p>
                        <p class="text-slate-800 font-bold">₹<?php echo e(number_format($workOrder->estimated_cost, 2)); ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Actual Cost</p>
                        <p class="text-slate-800 font-bold">₹<?php echo e(number_format($workOrder->actual_cost, 2)); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="glass-card">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Description / Instructions</h3>
        <?php if($workOrder->description): ?>
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60 text-slate-700 whitespace-pre-wrap font-medium"><?php echo e($workOrder->description); ?></div>
        <?php else: ?>
        <p class="text-slate-400 italic">No description provided.</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\work_orders\show.blade.php ENDPATH**/ ?>