
<?php $__env->startSection('title', 'Salaries'); ?>
<?php $__env->startSection('page-title', 'Salaries'); ?>
<?php $__env->startSection('page-subtitle', 'Manage employee salaries'); ?>
<?php $__env->startSection('content'); ?>
<div x-data="{ tab: 'salaries' }" class="glass-card !p-0 overflow-hidden">

    
    <div class="bg-white border-b border-slate-200/60">

        
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Salary Records</h2>
                    <p class="text-xs text-slate-400 font-medium">Filter and manage employee salaries</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" onclick="document.getElementById('paymentModal').style.display='block'" class="btn-secondary px-4 py-2 text-sm">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Payment
                </button>
                <a href="<?php echo e(route('salary-advances.create')); ?>" class="btn-secondary px-4 py-2 text-sm">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Borrow
                </a>
                <a href="<?php echo e(route('salaries.create')); ?>" class="btn-primary px-4 py-2 text-sm">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Add Salary
                </a>
            </div>
        </div>

        
        <form method="GET" id="salaryFilter" class="px-5 py-3.5 flex flex-wrap items-end gap-3">

            
            <div class="flex-1 min-w-[160px]">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Search Employee</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search employee..." class="form-input pl-9 py-2 text-sm w-full">
                </div>
            </div>

            
            <div class="min-w-[140px]">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Salary Month</label>
                <input type="month" name="month_filter" value="<?php echo e(request('month_filter')); ?>" class="form-input py-2 text-sm w-full">
            </div>

            
            <div class="min-w-[130px]">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">From</label>
                <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>" class="form-input py-2 text-sm w-full">
            </div>

            
            <div class="min-w-[130px]">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">To</label>
                <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>" class="form-input py-2 text-sm w-full">
            </div>

            
            <div class="flex items-center gap-2 shrink-0">
                <button type="submit" class="btn-primary py-2 px-4 text-sm">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                    Filter
                </button>
                <a href="<?php echo e(route('salaries.index')); ?>" class="btn-secondary py-2 px-4 text-sm">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Clear
                </a>
            </div>

        </form>
    </div>

    <div class="flex overflow-x-auto border-b border-blue-100 bg-blue-50/30">
        <button @click="tab = 'salaries'" :class="tab === 'salaries' ? 'border-primary-500 text-primary-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-6 py-3.5 border-b-2 font-bold text-sm whitespace-nowrap transition-colors">Salary Records</button>
        <button @click="tab = 'payments'" :class="tab === 'payments' ? 'border-primary-500 text-primary-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-6 py-3.5 border-b-2 font-bold text-sm whitespace-nowrap transition-colors">Payment History</button>
        <button @click="tab = 'borrows'" :class="tab === 'borrows' ? 'border-primary-500 text-primary-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-6 py-3.5 border-b-2 font-bold text-sm whitespace-nowrap transition-colors">Borrow History</button>
    </div>

    <div x-show="tab === 'salaries'" class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Sl No</th><th>Employee</th><th>Period</th><th>Amount</th><th>Advance Ded.</th><th>Net Payment</th><th>Payment Date</th><th>Method</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $salaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $salary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td data-label="Sl No" class="text-slate-400 font-semibold"><?php echo e(($salaries->currentPage() - 1) * $salaries->perPage() + $loop->iteration); ?></td>
                    <td data-label="Employee" class="font-bold text-slate-800"><?php echo e($salary->employee->name); ?></td>
                    <td data-label="Period" class="text-slate-600"><?php echo e($salary->month); ?> <?php echo e($salary->year); ?></td>
                    <td data-label="Amount" class="font-bold text-slate-800">₹<?php echo e(number_format($salary->amount, 2)); ?></td>
                    <td data-label="Advance Ded." class="text-red-500 font-semibold">-₹<?php echo e(number_format($salary->advance_deduction ?? 0, 2)); ?></td>
                    <td data-label="Net Payment" class="text-green-600 font-bold">₹<?php echo e(number_format($salary->amount - ($salary->advance_deduction ?? 0), 2)); ?></td>
                    <td data-label="Payment Date" class="font-medium text-slate-600"><?php echo e($salary->payment_date ? \Carbon\Carbon::parse($salary->payment_date)->format('d M Y') : '—'); ?></td>
                    <td data-label="Method" class="text-slate-500 uppercase text-sm font-semibold tracking-wide"><?php echo e($salary->payment_method); ?></td>
                    <td data-label="Status"><span class="badge <?php echo e($salary->status === 'paid' ? 'badge-success' : 'badge-danger'); ?>"><?php echo e(ucfirst($salary->status)); ?></span></td>
                    <td data-label="">
                        <div class="flex items-center gap-2">
                            <a href="<?php echo e(route('salaries.edit', $salary)); ?>" class="text-amber-600 hover:text-amber-700" title="Edit"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                            <form action="<?php echo e(route('salaries.destroy', $salary)); ?>" method="POST" onsubmit="return confirm('Delete salary record for <?php echo e($salary->employee->name); ?>?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="text-red-500 hover:text-red-600" title="Delete"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="10" class="text-center py-8 text-slate-400 font-medium font-semibold">No salary records found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($salaries->hasPages()): ?><div class="px-5 py-4 border-t border-slate-200/60"><?php echo e($salaries->appends(request()->query())->links()); ?></div><?php endif; ?>

    
    <div x-show="tab === 'payments'" x-cloak class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Employee</th><th>Date</th><th>Method</th><th>Notes</th><th>Amount</th><th>Actions</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $employeePayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td data-label="Employee" class="font-bold text-slate-800"><?php echo e($payment->employee->name); ?></td>
                    <td data-label="Date" class="text-slate-600"><?php echo e($payment->date->format('d M Y')); ?></td>
                    <td data-label="Method" class="text-slate-500 uppercase font-semibold"><?php echo e($payment->payment_method); ?></td>
                    <td data-label="Notes" class="text-slate-500"><?php echo e($payment->notes ?? '—'); ?></td>
                    <td data-label="Amount" class="font-bold text-green-600">₹<?php echo e(number_format($payment->amount, 2)); ?></td>
                    <td data-label="Actions">
                        <form action="<?php echo e(route('employee-payments.destroy', $payment)); ?>" method="POST" onsubmit="return confirm('Delete payment?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="text-red-500 hover:text-red-600 font-semibold text-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center py-8 text-slate-400 font-medium">No payment records found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <div x-show="tab === 'borrows'" x-cloak class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Employee</th><th>Date</th><th>Status</th><th>Amount</th><th>Actions</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $salaryAdvances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $advance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td data-label="Employee" class="font-bold text-slate-800"><?php echo e($advance->employee->name); ?></td>
                    <td data-label="Date" class="text-slate-600"><?php echo e($advance->date->format('d M Y')); ?></td>
                    <td data-label="Status" class="capitalize text-slate-500"><?php echo e($advance->status); ?></td>
                    <td data-label="Amount" class="font-bold text-amber-600">₹<?php echo e(number_format($advance->amount, 2)); ?></td>
                    <td data-label="Actions">
                        <form action="<?php echo e(route('salary-advances.destroy', $advance)); ?>" method="POST" onsubmit="return confirm('Delete record?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="text-red-500 hover:text-red-600 font-semibold text-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-center py-8 text-slate-400 font-medium">No borrow records found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>




<div id="paymentModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('paymentModal').style.display='none'"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title">Add Payment History</h3>
                <button type="button" onclick="document.getElementById('paymentModal').style.display='none'" class="text-slate-400 hover:text-slate-500 focus:outline-none"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form action="<?php echo e(route('employee-payments.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Employee *</label>
                        <select name="employee_id" class="form-input" required>
                            <option value="">Select Employee</option>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($employee->id); ?>"><?php echo e($employee->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Amount *</label>
                            <input type="number" step="0.01" name="amount" class="form-input" required placeholder="Enter amount">
                        </div>
                        <div>
                            <label class="form-label">Date *</label>
                            <input type="date" name="date" value="<?php echo e(date('Y-m-d')); ?>" class="form-input" required>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Payment Method *</label>
                        <select name="payment_method" class="form-input" required>
                            <option value="cash">Cash</option>
                            <option value="upi">UPI</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="2" class="form-input" placeholder="Enter notes"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('paymentModal').style.display='none'" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Save Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\salaries\index.blade.php ENDPATH**/ ?>