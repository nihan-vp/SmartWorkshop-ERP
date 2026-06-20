<?php $__env->startSection('title', 'Edit Invoice: ' . $bill->bill_number); ?>
<?php $__env->startSection('page-title', 'Edit Invoice'); ?>
<?php $__env->startSection('page-subtitle', $bill->bill_number); ?>
<?php $__env->startSection('content'); ?>
<div class="w-full" x-data="billingApp()">
    <div class="flex items-center justify-between gap-4 mb-6">
        <a href="<?php echo e(route('bills.index')); ?>" class="btn-secondary !py-2 !px-4 flex items-center gap-2 shadow-sm text-xs font-bold rounded-xl">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Invoices
        </a>
        <a href="<?php echo e(route('bills.invoice', $bill)); ?>" class="btn-primary !py-2 !px-4 flex items-center gap-2 shadow-sm text-xs font-bold rounded-xl">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            View Invoice
        </a>
    </div>

    <form action="<?php echo e(route('bills.update', $bill)); ?>" method="POST" id="bill-form">
        
        <?php if(session('success')): ?>
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-sm" x-data="{ show: true }" x-show="show">
                <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <div class="flex-1 text-sm font-medium"><?php echo e(session('success')); ?></div>
                <button type="button" @click="show = false" class="text-emerald-500 hover:text-emerald-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-3 shadow-sm" x-data="{ show: true }" x-show="show">
                <svg class="w-5 h-5 text-rose-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div class="flex-1 text-sm font-medium"><?php echo e(session('error')); ?></div>
                <button type="button" @click="show = false" class="text-rose-500 hover:text-rose-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
        <?php endif; ?>

        <?php if(session('warning')): ?>
            <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 flex items-start gap-3 shadow-sm" x-data="{ show: true }" x-show="show">
                <svg class="w-5 h-5 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div class="flex-1 text-sm font-medium"><?php echo e(session('warning')); ?></div>
                <button type="button" @click="show = false" class="text-amber-500 hover:text-amber-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
        <?php endif; ?>

        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-6">
                <div class="glass-card">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-100 pb-3">Customer & Vehicle</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label">Customer *</label>
                            <select name="customer_id" class="form-select" required x-model="customerId" @change="fetchVehicles()">
                                <option value="">Select Customer</option>
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?> (<?php echo e($c->phone); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Vehicle</label>
                            <select name="vehicle_id" class="form-select" x-model="vehicleId">
                                <option value="">Select Vehicle (Optional)</option>
                                <template x-for="v in vehicles" :key="v.id">
                                    <option :value="v.id" :selected="v.id == vehicleId" x-text="(v.make && v.make !== 'Unknown' ? v.make + ' ' : '') + v.model + ' (' + v.plate_number + ')' + (v.customer ? ' - Owner: ' + v.customer.name : '')"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="glass-card">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Bill Items</h3>
                        <div class="flex flex-wrap items-center gap-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                </div>
                                <input type="text" x-model="scannedBarcode" @keydown.enter.prevent="handleScanBarcode" placeholder="Scan Barcode..." class="form-input !py-1.5 !pl-9 !text-xs w-48 bg-slate-50 border-slate-200">
                            </div>
                            <select class="form-select !py-1.5 !text-xs !w-44" @change="loadTemplate($event.target.value); $event.target.value = '';">
                                <option value="">Load Preset Package...</option>
                                <?php $__currentLoopData = App\Models\BillTemplate::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($t->id); ?>"><?php echo e($t->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <button type="button" @click="addItem('product')" class="btn-secondary !py-1.5 !text-xs">+ Product</button>
                            <button type="button" @click="addItem('service')" class="btn-secondary !py-1.5 !text-xs">+ Service</button>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="flex flex-wrap lg:flex-nowrap items-start gap-3 p-3 bg-slate-50 border border-slate-200/60 rounded-xl">
                                <input type="hidden" :name="`items[${index}][type]`" :value="item.type">
                                
                                <div class="flex-shrink-0 mt-8 text-slate-400 font-bold text-sm w-5 text-center" x-text="(index + 1) + '.'"></div>
                                
                                <div class="flex-1 min-w-[200px]">
                                    <label class="text-xs font-semibold text-slate-500 mb-1 block capitalize" x-text="item.type"></label>
                                    
                                    <select x-show="item.type === 'product'" :name="item.type === 'product' ? `items[${index}][id]` : ''" class="form-select !py-2" x-model="item.id" @change="updateItemDetails(index)" :required="item.type === 'product'">
                                        <option value="">Select Product</option>
                                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($p->id); ?>" data-price="<?php echo e($p->price); ?>" data-name="<?php echo e($p->name); ?>"><?php echo e($p->name); ?> (₹<?php echo e($p->price); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    
                                    <select x-show="item.type === 'service'" :name="item.type === 'service' ? `items[${index}][id]` : ''" class="form-select !py-2" x-model="item.id" @change="updateItemDetails(index)" :required="item.type === 'service'">
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
                                    <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-600 p-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </div>
                        </template>
                        <p x-show="items.length === 0" class="text-sm text-slate-400 text-center py-4 font-medium italic">No items added yet. Click above to add products or services.</p>
                    </div>
                </div>

                <div class="glass-card">
                    <label class="form-label">Notes / Terms</label>
                    <textarea name="notes" rows="3" class="form-input" placeholder="Additional notes for the invoice..."><?php echo e($bill->notes); ?></textarea>
                </div>
            </div>

            <div class="space-y-6">
                <div class="glass-card lg:sticky lg:top-24">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Payment Summary</h3>
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-slate-600 font-medium">
                            <span>Subtotal</span>
                            <span class="font-bold text-slate-800" x-text="'₹' + subtotal.toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600 font-medium">
                                <span>Discount Amount (₹)</span>
                                <input type="number" step="0.01" name="discount" x-model.number="discount" class="form-input !py-1 !px-2 w-24 text-right" placeholder="Enter discount">
                            </div>
                        <div class="flex justify-between items-center text-slate-600 font-medium">
                            <span>Tax (₹)</span>
                            <input type="number" step="0.01" name="tax" x-model.number="tax" class="form-input !py-1 !px-2 w-24 text-right" placeholder="Enter tax">
                        </div>
                        <div class="pt-4 border-t border-slate-200 flex justify-between text-slate-900 text-lg font-extrabold">
                            <span>Total</span>
                            <span x-text="'₹' + total.toFixed(2)"></span>
                        </div>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="form-label !text-xs">Payment Method</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center justify-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-all duration-200" :class="{'bg-primary-50 border-primary-500 text-primary-600 font-semibold shadow-sm': paymentMethod === 'cash'}">
                                    <input type="radio" name="payment_method" value="cash" x-model="paymentMethod" class="sr-only">
                                    <span class="text-sm font-medium">Cash</span>
                                </label>
                                <label class="flex items-center justify-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-all duration-200" :class="{'bg-primary-50 border-primary-500 text-primary-600 font-semibold shadow-sm': paymentMethod === 'upi'}">
                                    <input type="radio" name="payment_method" value="upi" x-model="paymentMethod" class="sr-only">
                                    <span class="text-sm font-medium">UPI</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="form-label !text-xs">Amount Paid (₹)</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="amount_paid" x-model.number="amountPaid" class="form-input pr-16" placeholder="0.00">
                                <button type="button" @click="amountPaid = total" class="absolute right-2 top-2 px-2 py-1 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded text-[10px] font-bold text-slate-600 transition-colors">Pay Full</button>
                            </div>
                        </div>
                        <div>
                            <label class="form-label !text-xs">Payment Status</label>
                            <select name="payment_status" x-model="paymentStatus" class="form-input text-sm font-semibold !py-2.5">
                                <option value="pending">Pending</option>
                                <option value="partial">Partial</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full justify-center !py-3 !text-base shadow-lg shadow-primary-500/10" :disabled="items.length === 0">
                        Update Invoice
                    </button>
                    <p class="text-xs text-center text-slate-400 font-semibold mt-3">Invoice #: <?php echo e($bill->bill_number); ?></p>
                </div>
            </div>
        </div>
    </form>
</div>

<?php
    $itemsJson = $bill->items->map(function($item) {
        return [
            'type' => $item->item_type,
            'id' => $item->item_id,
            'name' => $item->item_name,
            'price' => floatval($item->unit_price),
            'qty' => intval($item->quantity)
        ];
    });
?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('billingApp', () => ({
        customerId: '<?php echo e($bill->customer_id); ?>',
        vehicleId: '<?php echo e($bill->vehicle_id ?? ''); ?>',
        vehicles: [],
        items: <?php echo json_encode($itemsJson, 15, 512) ?>,
        discount: <?php echo e(floatval($bill->discount)); ?>,
        tax: <?php echo e(floatval($bill->tax)); ?>,
        paymentMethod: '<?php echo e($bill->payment_method); ?>',
        amountPaid: <?php echo e(floatval($bill->amount_paid)); ?>,
        paymentStatus: '<?php echo e($bill->payment_status); ?>',
        scannedBarcode: '',
        productsList: <?php echo json_encode($productsList, 15, 512) ?>,

        async init() {
            if(this.customerId) {
                try {
                    this.vehicles = await window.wsClient.getVehicles(this.customerId);
                    this.vehicleId = '<?php echo e($bill->vehicle_id ?? ''); ?>';
                } catch(e) { console.error(e); }
            }
        },

        async fetchVehicles() {
            if(!this.customerId) { this.vehicles = []; this.vehicleId = ''; return; }
            try {
                this.vehicles = await window.wsClient.getVehicles(this.customerId);
                if(this.vehicles.length === 1) this.vehicleId = this.vehicles[0].id;
                else this.vehicleId = '';
            } catch(e) { console.error(e); }
        },

        async loadTemplate(templateId) {
            if(!templateId) return;
            try {
                let template = await window.wsClient.getBillTemplate(templateId);
                this.items = template.items.map(item => ({
                    type: item.item_type,
                    id: item.item_id,
                    name: item.item_name,
                    price: parseFloat(item.unit_price) || 0,
                    qty: item.quantity
                }));
                this.discount = parseFloat(template.discount) || 0;
                this.tax = parseFloat(template.tax) || 0;
            } catch(e) { console.error(e); }
        },

        addItem(type) {
            this.items.push({ type: type, id: '', name: '', price: 0, qty: 1 });
        },

        handleScanBarcode() {
            if(!this.scannedBarcode.trim()) return;
            let product = this.productsList.find(p => p.barcode === this.scannedBarcode.trim());
            if(product) {
                this.items.push({
                    type: 'product',
                    id: product.id,
                    name: product.name,
                    price: parseFloat(product.price) || 0,
                    qty: 1
                });
                this.scannedBarcode = '';
            } else {
                alert('Product with this barcode not found!');
                this.scannedBarcode = '';
            }
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\bills\edit.blade.php ENDPATH**/ ?>