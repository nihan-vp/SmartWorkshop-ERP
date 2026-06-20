
<?php $__env->startSection('title', 'Edit Bill Template'); ?>
<?php $__env->startSection('page-title', 'Edit Bill Template'); ?>
<?php $__env->startSection('page-subtitle', 'Modify package presets'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto" x-data="templateApp()">
    <div class="flex items-center gap-4 mb-6">
        <a href="<?php echo e(route('bill-templates.index')); ?>" class="btn-secondary !py-2 !px-4 flex items-center gap-2 shadow-sm text-xs font-bold rounded-xl">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Templates
        </a>
    </div>

    <form action="<?php echo e(route('bill-templates.update', $billTemplate)); ?>" method="POST" id="template-form">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 space-y-6">
                <div class="glass-card">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Template Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Template Name *</label>
                            <input type="text" name="name" required value="<?php echo e($billTemplate->name); ?>" placeholder="e.g. Premium General Servicing Package" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="2" placeholder="Brief details about what this package includes..." class="form-input"><?php echo e($billTemplate->description); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="glass-card">
                    <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-2">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Preset Items</h3>
                        <div class="flex gap-2">
                            <button type="button" @click="addItem('product')" class="btn-secondary !py-1.5 !text-xs">+ Product</button>
                            <button type="button" @click="addItem('service')" class="btn-secondary !py-1.5 !text-xs">+ Service</button>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="flex items-start gap-3 p-3 bg-slate-50 border border-slate-200/60 rounded-xl">
                                <input type="hidden" :name="`items[${index}][type]`" :value="item.type">
                                
                                <div class="flex-shrink-0 mt-8 text-slate-400 font-bold text-sm w-5 text-center" x-text="(index + 1) + '.'"></div>
                                
                                <div class="flex-1">
                                    <label class="text-xs font-semibold text-slate-500 mb-1 block capitalize" x-text="item.type"></label>
                                    
                                    
                                    <select x-show="item.type === 'product'" :name="`items[${index}][id]`" class="form-select !py-2" x-model="item.id" @change="updateItemDetails(index)" required>
                                        <option value="">Select Product</option>
                                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($p->id); ?>" data-price="<?php echo e($p->price); ?>" data-name="<?php echo e($p->name); ?>"><?php echo e($p->name); ?> (₹<?php echo e($p->price); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    
                                    
                                    <select x-show="item.type === 'service'" :name="`items[${index}][id]`" class="form-select !py-2" x-model="item.id" @change="updateItemDetails(index)" required>
                                        <option value="">Select Service</option>
                                        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($s->id); ?>" data-price="<?php echo e($s->price); ?>" data-name="<?php echo e($s->name); ?>"><?php echo e($s->name); ?> (₹<?php echo e($s->price); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    
                                    <input type="hidden" :name="`items[${index}][name]`" :value="item.name">
                                </div>
                                
                                <div class="w-24">
                                    <label class="text-xs font-semibold text-slate-500 mb-1 block">Price</label>
                                    <input type="number" step="0.01" :name="`items[${index}][price]`" x-model="item.price" class="form-input !py-2" readonly placeholder="Enter `items[${index}][price]`">
                                </div>
                                
                                <div class="w-20">
                                    <label class="text-xs font-semibold text-slate-500 mb-1 block">Qty</label>
                                    <input type="number" min="1" :name="`items[${index}][quantity]`" x-model="item.qty" class="form-input !py-2" required placeholder="Enter `items[${index}][quantity]`">
                                </div>
                                
                                <div class="w-28">
                                    <label class="text-xs font-semibold text-slate-500 mb-1 block">Total</label>
                                    <input type="text" :value="(item.price * item.qty).toFixed(2)" class="form-input !py-2 bg-slate-100 border-transparent text-slate-800 font-semibold" readonly>
                                </div>
                                
                                <div class="pt-6">
                                    <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-600 p-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <p x-show="items.length === 0" class="text-sm text-slate-400 text-center py-4 font-medium italic">No preset items added yet. Click above to add products or services.</p>
                    </div>
                </div>
            </div>

            
            <div class="space-y-6">
                <div class="glass-card sticky top-24">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Defaults Summary</h3>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-slate-600 font-medium">
                            <span>Subtotal</span>
                            <span class="font-bold text-slate-800" x-text="'₹' + subtotal.toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600 font-medium">
                            <span>Default Discount Amount (₹)</span>
                            <input type="number" step="0.01" name="discount" x-model.number="discount" class="form-input !py-1 !px-2 w-24 text-right" placeholder="Enter discount">
                        </div>
                        <div class="flex justify-between items-center text-slate-600 font-medium">
                            <span>Default Tax (₹)</span>
                            <input type="number" step="0.01" name="tax" x-model.number="tax" class="form-input !py-1 !px-2 w-24 text-right" placeholder="Enter tax">
                        </div>
                        <div class="pt-4 border-t border-slate-200 flex justify-between text-slate-900 text-lg font-extrabold">
                            <span>Preset Total</span>
                            <span x-text="'₹' + total.toFixed(2)"></span>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full justify-center !py-3 shadow-lg shadow-primary-500/10" :disabled="items.length === 0">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('templateApp', () => ({
        items: <?php echo json_encode($billTemplate->items->map(fn($it) => [
            'type' => $it->item_type,
            'id' => $it->item_id,
            'name' => $it->item_name,
            'price' => $it->unit_price,
            'qty' => $it->quantity,
        ])->values()->toArray()); ?>,
        discount: <?php echo e($billTemplate->discount); ?>,
        tax: <?php echo e($billTemplate->tax); ?>,

        addItem(type) {
            this.items.push({ type: type, id: '', name: '', price: 0, qty: 1 });
        },

        removeItem(index) {
            this.items.splice(index, 1);
        },

        updateItemDetails(index) {
            let item = this.items[index];
            if(!item.id) { item.price = 0; item.name = ''; return; }
            
            let select = document.querySelector(`select[name="items[${index}][id]"]`);
            let option = select.options[select.selectedIndex];
            
            item.price = parseFloat(option.dataset.price) || 0;
            item.name = option.dataset.name || '';
        },

        get subtotal() {
            return this.items.reduce((sum, item) => sum + ((parseFloat(item.price)||0) * (parseInt(item.qty)||1)), 0);
        },

        get total() {
            let s = this.subtotal;
            let d = parseFloat(this.discount) || 0;
            let t = parseFloat(this.tax) || 0;
            return Math.max(0, s - d + t);
        }
    }))
})
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\bill_templates\edit.blade.php ENDPATH**/ ?>