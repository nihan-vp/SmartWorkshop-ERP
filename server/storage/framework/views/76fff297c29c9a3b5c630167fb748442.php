
<?php $__env->startSection('title', 'Bill Templates'); ?>
<?php $__env->startSection('page-title', 'Bill Templates'); ?>
<?php $__env->startSection('page-subtitle', 'Manage presets for quick billing'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-2">
        <p class="text-sm text-slate-500 font-semibold">Predefine frequent services/packages to generate bills with 1-click.</p>
    </div>
    <a href="<?php echo e(route('bill-templates.create')); ?>" class="btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Bill Template
    </a>
</div>

<div class="glass-card !p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Template Name</th>
                    <th>Description</th>
                    <th>Items Count</th>
                    <th>Default Discount</th>
                    <th>Default Tax</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $temp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td data-label="Name" class="font-bold text-slate-800"><?php echo e($temp->name); ?></td>
                    <td data-label="Description" class="text-slate-500 max-w-xs truncate font-medium"><?php echo e($temp->description ?? '—'); ?></td>
                    <td data-label="Items"><span class="badge badge-info"><?php echo e($temp->items_count); ?></span></td>
                    <td data-label="Discount" class="font-semibold text-rose-600">₹<?php echo e(number_format($temp->discount, 2)); ?></td>
                    <td data-label="Tax" class="font-semibold text-slate-700">₹<?php echo e(number_format($temp->tax, 2)); ?></td>
                    <td data-label="">
                        <div class="flex items-center gap-2">
                            <a href="<?php echo e(route('bill-templates.edit', $temp)); ?>" class="text-amber-600 hover:text-amber-700 transition-colors" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="<?php echo e(route('bill-templates.destroy', $temp)); ?>" method="POST" onsubmit="return confirm('Delete template <?php echo e($temp->name); ?>?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-500 hover:text-red-600 transition-colors" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center py-8 text-slate-400 font-medium">
                        No bill templates found. <a href="<?php echo e(route('bill-templates.create')); ?>" class="text-primary-600 hover:underline">Create your first template</a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($templates->hasPages()): ?>
    <div class="px-5 py-4 border-t border-slate-200/60"><?php echo e($templates->appends(request()->query())->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\bill_templates\index.blade.php ENDPATH**/ ?>