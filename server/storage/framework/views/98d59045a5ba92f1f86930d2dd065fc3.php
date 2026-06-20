

<?php $__env->startSection('title', 'System Settings & Info'); ?>
<?php $__env->startSection('page-title', 'System Settings'); ?>
<?php $__env->startSection('page-subtitle', 'Manage workshop profile settings and view technical configuration details'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto space-y-8 animate-fade-in-up">

    
    <?php if(session('success')): ?>
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="text-sm font-semibold"><?php echo e(session('success')); ?></span>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="text-sm font-semibold"><?php echo e(session('error')); ?></span>
    </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 shadow-sm">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span class="text-sm font-bold">Please correct the following errors:</span>
        </div>
        <ul class="list-disc pl-9 mt-2 text-xs font-semibold space-y-1">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        
        <div class="lg:col-span-2 space-y-8">
            <div class="glass-card shadow-sm border border-slate-200/80">
                <div class="border-b border-slate-100 pb-5 mb-6">
                    <h3 class="text-lg font-bold text-slate-900 font-outfit">Workshop Profile Configuration</h3>
                    <p class="text-xs text-slate-500 mt-1">Configure your official business name, contact information, GSTIN, and invoice header logo.</p>
                </div>

                <form action="<?php echo e(route('system.update')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <?php echo csrf_field(); ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="form-label font-bold text-slate-700">Workshop / Business Name *</label>
                            <input type="text" name="name" id="name" value="<?php echo e(old("name", $workshop->name)); ?>" placeholder="Enter name" required 
                                   class="form-input mt-1 w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="phone" class="form-label font-bold text-slate-700">Contact Number *</label>
                            <input type="text" name="phone" id="phone" value="<?php echo e(old("phone", $workshop->phone)); ?>" placeholder="Enter phone" required 
                                   class="form-input mt-1 w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="form-label font-bold text-slate-700">Business Email Address</label>
                            <input type="email" name="email" id="email" value="<?php echo e(old("email", $workshop->email)); ?>" placeholder="Enter email" 
                                   class="form-input mt-1 w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="gstin" class="form-label font-bold text-slate-700">GSTIN / Tax Registration Number</label>
                            <input type="text" name="gstin" id="gstin" value="<?php echo e(old("gstin", $workshop->gstin)); ?>" placeholder="Enter gstin" placeholder="e.g. 22AAAAA0000A1Z5" 
                                   class="form-input mt-1 w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 uppercase">
                        </div>
                    </div>

                    <div>
                        <label for="address" class="form-label font-bold text-slate-700">Workshop Physical Address</label>
                        <textarea name="address" id="address" rows="3" 
                                  class="form-input mt-1 w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Enter address"><?php echo e(old('address', $workshop->address)); ?></textarea>
                    </div>



                    <div class="border-t border-slate-100 pt-6 flex justify-end">
                        <button type="submit" class="btn-primary !py-2.5 !px-6 text-sm shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Save Profile Settings
                        </button>
                    </div>
                </form>

            </div>

            
            <div class="glass-card shadow-sm border border-slate-200/80 mt-8">
                <div class="border-b border-slate-100 pb-5 mb-6">
                    <h3 class="text-lg font-bold text-slate-900 font-outfit">Change Account Password</h3>
                    <p class="text-xs text-slate-500 mt-1">Update your login credentials securely. Passwords must be at least 8 characters long.</p>
                </div>

                <form action="<?php echo e(route('system.change_password')); ?>" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="current_password" class="form-label font-bold text-slate-700">Current Password *</label>
                            <input type="password" name="current_password" id="current_password" required 
                                   class="form-input mt-1 w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Enter current password">
                        </div>

                        <div>
                            <label for="new_password" class="form-label font-bold text-slate-700">New Password *</label>
                            <input type="password" name="new_password" id="new_password" required 
                                   class="form-input mt-1 w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Enter new password">
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="form-label font-bold text-slate-700">Confirm New Password *</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" required 
                                   class="form-input mt-1 w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Enter new password confirmation">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="submit" class="btn-primary !py-2.5 !px-6 text-sm shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            
            <div class="glass-card shadow-sm border border-slate-200/80 border-t-4 border-t-rose-500 mt-8">
                <div class="border-b border-slate-100 pb-5 mb-6">
                    <h3 class="text-lg font-bold text-slate-900 font-outfit flex items-center gap-2">
                        Danger Zone: Reset Database Data
                        <span class="badge badge-rose text-[9px] uppercase">Reset Data</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Permanently empty all business tables for this workshop to start with a clean slate.</p>
                </div>

                <div class="space-y-4">
                    <div class="bg-rose-50 border border-rose-100 text-rose-900 rounded-2xl p-4 text-xs font-semibold leading-relaxed flex items-start gap-3">
                        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div>
                            <span class="block font-bold mb-1">WARNING: THIS IS IRREVERSIBLE!</span>
                            Wiping workshop records will delete all your customers, vehicles, products, services, employees, work orders, estimates, invoices, expenses, and salaries. Your user account, workshop profile info, and settings will remain safe.
                        </div>
                    </div>

                    <form action="<?php echo e(route('system.clear_data')); ?>" method="POST" onsubmit="if(!confirm('CRITICAL WARNING: Are you absolutely sure you want to permanently clear all business data for this workshop? This will delete all customers, vehicles, invoices, work orders, and other records. This action CANNOT be undone!')) { event.preventDefault(); return false; }">
                        <?php echo csrf_field(); ?>
                        <div class="flex justify-end">
                            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors shadow-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Clear Workshop Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="space-y-8 lg:col-span-1">
            
            
            <div class="glass-card shadow-sm border border-slate-200/80">
                <div class="border-b border-slate-100 pb-4 mb-4">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest font-outfit">System Specifications</h3>
                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Underlying software & environment metrics</p>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100/50">
                        <span class="text-xs text-slate-500 font-medium">Laravel Framework</span>
                        <span class="text-xs font-bold text-slate-900 font-mono">v<?php echo e($systemInfo['laravel_version']); ?></span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100/50">
                        <span class="text-xs text-slate-500 font-medium">PHP Version</span>
                        <span class="text-xs font-bold text-slate-900 font-mono">v<?php echo e($systemInfo['php_version']); ?></span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100/50">
                        <span class="text-xs text-slate-500 font-medium">Server Operating System</span>
                        <span class="text-xs font-bold text-slate-900 font-mono capitalize"><?php echo e($systemInfo['server_os']); ?></span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100/50">
                        <span class="text-xs text-slate-500 font-medium">Database Connection</span>
                        <span class="text-xs font-bold text-slate-900 font-mono capitalize"><?php echo e($systemInfo['database_driver']); ?></span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100/50">
                        <span class="text-xs text-slate-500 font-medium">Database Schema Name</span>
                        <span class="text-xs font-bold text-slate-900 font-mono truncate max-w-[150px]" title="<?php echo e($systemInfo['database_name']); ?>"><?php echo e($systemInfo['database_name']); ?></span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100/50">
                        <span class="text-xs text-slate-500 font-medium">Environment Mode</span>
                        <span class="badge <?php echo e($systemInfo['app_env'] === 'production' ? 'badge-info' : 'badge-purple'); ?> uppercase text-[9px]"><?php echo e($systemInfo['app_env']); ?></span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100/50">
                        <span class="text-xs text-slate-500 font-medium">Debug Mode Status</span>
                        <span class="text-xs font-bold <?php echo e($systemInfo['app_debug'] === 'Enabled' ? 'text-rose-500' : 'text-slate-600'); ?>"><?php echo e($systemInfo['app_debug']); ?></span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100/50">
                        <span class="text-xs text-slate-500 font-medium">System Timezone</span>
                        <span class="text-xs font-bold text-slate-900 font-mono"><?php echo e($systemInfo['timezone']); ?></span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100/50">
                        <span class="text-xs text-slate-500 font-medium">Live Database Size</span>
                        <span class="text-xs font-bold text-slate-900 font-mono"><?php echo e($systemInfo['database_size']); ?></span>
                    </div>
                    <div class="flex items-center justify-between py-1.5">
                        <span class="text-xs text-slate-500 font-medium">Application Logs Size</span>
                        <span class="text-xs font-bold text-slate-900 font-mono"><?php echo e($systemInfo['log_size']); ?></span>
                    </div>
                </div>
            </div>

            
            <div class="glass-card shadow-sm border border-slate-200/80 border-l-4 border-l-blue-600">
                <div class="border-b border-slate-100 pb-4 mb-4">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest font-outfit">Subscription Status</h3>
                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Workshop membership & licensing detail</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Membership Type</span>
                        <span class="badge badge-info uppercase mt-1 inline-block"><?php echo e($workshop->subscription_status); ?></span>
                    </div>
                    
                    <?php if($workshop->isTrial()): ?>
                        <?php $isTraining = $workshop->subscription_status === 'training'; ?>
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block"><?php echo e($isTraining ? 'Training' : 'Trial'); ?> Period Time Remaining</span>
                            <span class="text-base font-bold text-slate-900 font-outfit mt-1 block">
                                <?php echo e($workshop->getTrialDaysRemaining()); ?> Days Left
                            </span>
                            <span class="text-[10px] text-slate-400 mt-0.5 block"><?php echo e($isTraining ? 'Training' : 'Trial'); ?> ends on: <?php echo e($workshop->trial_ends_at ? $workshop->trial_ends_at->format('d M Y') : 'N/A'); ?></span>
                        </div>
                    <?php endif; ?>

                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Created / Registered Date</span>
                        <span class="text-xs font-bold text-slate-900 mt-1 block"><?php echo e($workshop->created_at->format('d M Y h:i A')); ?></span>
                        <span class="text-[10px] text-slate-400 mt-0.5 block">Operating for <?php echo e($workshop->getSubscriptionDay()); ?> days</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewImg = document.getElementById('logo-preview-image');
                const placeholder = document.getElementById('logo-placeholder');
                
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\system\index.blade.php ENDPATH**/ ?>