
<?php $__env->startSection('title', 'Vehicles'); ?>
<?php $__env->startSection('page-title', 'Vehicles'); ?>
<?php $__env->startSection('page-subtitle', 'Manage registered vehicles'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <form method="GET" class="flex items-center gap-2 w-full sm:w-auto">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search plate, brand, model..." class="form-input sm:w-72">
        <button type="submit" class="btn-secondary">Search</button>
    </form>
    <a href="<?php echo e(route('vehicles.create')); ?>" class="btn-primary"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Add Vehicle</a>
</div>
<div class="glass-card !p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Sl No</th><th>Plate Number</th><th>Model</th><th>Year</th><th>Color</th><th>Owner</th><th>Actions</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td data-label="Sl No" class="text-slate-400 font-semibold"><?php echo e(($vehicles->currentPage() - 1) * $vehicles->perPage() + $loop->iteration); ?></td>
                    <td data-label="Plate No" class="font-mono font-bold text-primary-600 bg-primary-50 px-2.5 py-1 rounded-lg inline-block my-2"><?php echo e($v->plate_number); ?></td>

                    <td data-label="Model" class="font-medium text-slate-600"><?php echo e($v->model); ?></td>
                    <td data-label="Year" class="font-medium text-slate-600"><?php echo e($v->year ?? '—'); ?></td>
                    <td data-label="Color" class="font-medium text-slate-600"><?php echo e($v->color ?? '—'); ?></td>
                    <td data-label="Owner" class="font-semibold text-slate-700"><?php echo e($v->customer->name); ?></td>
                    <td data-label="">
                        <div class="flex items-center gap-2">
                            <a href="<?php echo e(route('vehicles.edit', $v)); ?>" class="text-amber-600 hover:text-amber-700" title="Edit"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                            <form action="<?php echo e(route('vehicles.destroy', $v)); ?>" method="POST" onsubmit="return confirm('Delete vehicle <?php echo e($v->plate_number); ?>?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="text-red-500 hover:text-red-600" title="Delete"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="text-center py-8 text-slate-400 font-medium">No vehicles found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($vehicles->hasPages()): ?><div class="px-5 py-4 border-t border-slate-200/60"><?php echo e($vehicles->appends(request()->query())->links()); ?></div><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\vehicles\index.blade.php ENDPATH**/ ?>