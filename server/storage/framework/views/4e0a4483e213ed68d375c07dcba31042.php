<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?php echo e($bill->bill_number); ?></title>
    <!-- html2pdf.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 24px; height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; overflow: hidden; background: #f8fafc; font-family: 'Inter', sans-serif; }
        
        /* Stunning Modal Box */
        .modal-box {
            background: #ffffff; width: 100%; max-width: 1000px; height: 100%; max-height: 95vh;
            border-radius: 16px; box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1); display: flex; flex-direction: column;
            overflow: hidden; border: 1px solid #e2e8f0; animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes slideUp { from { transform: translateY(30px) scale(0.98); opacity: 0; } to { transform: translateY(0) scale(1); opacity: 1; } }

        /* Sleek Modal Header */
        .modal-header {
            background: #ffffff; color: #1e293b; padding: 20px 24px;
            display: flex; justify-content: space-between; align-items: center; flex-shrink: 0; border-bottom: 1px solid #e2e8f0;
        }
        .modal-header h1 { margin: 0; font-size: 18px; font-weight: 700; letter-spacing: 0.5px; display: flex; align-items: center; gap: 10px; }
        .close-btn {
            background: #f1f5f9; border: none; color: #64748b; width: 36px; height: 36px;
            border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: all 0.2s; text-decoration: none;
        }
        .close-btn:hover { background: #fee2e2; color: #ef4444; transform: rotate(90deg); }

        /* Modal Body (PDF Viewer) */
        .modal-body { flex-grow: 1; position: relative; background: #e2e8f0; }
        iframe#pdf-viewer { width: 100%; height: 100%; border: none; display: block; }

        /* Modal Footer */
        .modal-footer {
            background: #ffffff; padding: 16px 24px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 12px; flex-shrink: 0;
        }
        .btn {
            padding: 10px 24px; border-radius: 99px; font-weight: 600; font-size: 14px; cursor: pointer; border: none;
            display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); text-decoration: none;
        }
        .btn-primary { background: #0f172a; color: #ffffff; box-shadow: 0 4px 10px rgba(15, 23, 42, 0.15); }
        .btn-primary:hover { background: #1e293b; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(15, 23, 42, 0.25); }
        
        .btn-success { background: #25D366; color: #ffffff; box-shadow: 0 4px 10px rgba(37, 211, 102, 0.2); }
        .btn-success:hover { background: #20ba56; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(37, 211, 102, 0.3); }

        /* Premium Loading Overlay */
        #loading {
            position: absolute; inset: 0; background: rgba(255,255,255,0.85); backdrop-filter: blur(4px);
            display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 10;
        }
        .spinner {
            width: 50px; height: 50px; border: 4px solid #e2e8f0; border-top-color: #3b82f6; border-radius: 50%;
            animation: spin 1s cubic-bezier(0.5, 0, 0.5, 1) infinite; margin-bottom: 20px; box-shadow: 0 0 20px rgba(59,130,246,0.2);
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .loading-text { font-weight: 600; color: #334155; letter-spacing: 0.5px; font-size: 15px; }

        /* Mobile Responsiveness for Modal UI */
        @media (max-width: 768px) {
            body { padding: 0; background: #ffffff; }
            .modal-box { max-height: 100vh; border-radius: 0; border: none; box-shadow: none; animation: slideUpMobile 0.3s ease-out; }
            @keyframes slideUpMobile { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
            .modal-header { padding: 12px 16px; }
            .modal-header h1 { font-size: 16px; }
            .close-btn { width: 32px; height: 32px; }
            .close-btn svg { width: 18px; height: 18px; }
        }

        /* Hidden Render Container for PDF */
        .render-container {
            position: absolute; z-index: -1000; opacity: 0.01; pointer-events: none; width: 800px; background: #ffffff; left: 0; top: 0; font-family: 'Inter', sans-serif; color: #111827;
        }
        
        /* Invoice Styles (Simple Minimalist Layout for PDF) */
        .invoice-wrapper { width: 100%; max-width: 800px; margin: 0 auto; background: #fff; padding: 40px; font-family: 'Inter', sans-serif; color: #111; }
        .invoice-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; }
        .company-details h2 { margin: 0 0 4px 0; font-size: 24px; color: #000; text-transform: uppercase; font-weight: 700; }
        .company-details p { margin: 2px 0; font-size: 13px; color: #333; }
        .invoice-title { text-align: right; }
        .invoice-title h1 { margin: 0 0 8px 0; font-size: 32px; color: #000; letter-spacing: 1px; font-weight: 700; }
        .invoice-title p { margin: 2px 0; font-size: 13px; color: #333; }
        .invoice-title p strong { display: inline-block; width: 70px; color: #000; font-weight: 600; }

        .info-section { display: flex; justify-content: space-between; margin-bottom: 40px; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 15px 0; }
        .info-box { width: 48%; }
        .info-box h3 { margin: 0 0 8px 0; font-size: 13px; color: #000; text-transform: uppercase; font-weight: 700; }
        .info-box p { margin: 4px 0; font-size: 13px; color: #333; }
        .info-box p strong { color: #000; width: 90px; display: inline-block; font-weight: 600; }

        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .items-table th { padding: 10px 5px; text-align: left; font-size: 12px; text-transform: uppercase; font-weight: 700; border-bottom: 2px solid #000; color: #000; }
        .items-table td { padding: 10px 5px; font-size: 13px; color: #111; border-bottom: 1px solid #eee; }
        .items-table th.center, .items-table td.center { text-align: center; }
        .items-table th.right, .items-table td.right { text-align: right; }

        .summary-section { display: flex; justify-content: flex-end; }
        .summary-table { width: 300px; border-collapse: collapse; }
        .summary-table td { padding: 8px 5px; font-size: 13px; color: #111; }
        .summary-table td:last-child { text-align: right; }
        .summary-table tr.total td { font-size: 16px; font-weight: 700; border-top: 2px solid #000; border-bottom: 2px solid #000; }
        .summary-table tr.paid td { padding-top: 15px; }

        .footer-note { text-align: center; margin-top: 60px; font-size: 12px; color: #666; font-style: italic; }
    </style>
</head>
<body>

    <!-- Stunning Modal Box -->
    <div class="modal-box">
        
        <!-- Modal Header -->
        <div class="modal-header">
            <h1>
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Invoice Details
            </h1>
            <a href="<?php echo e(route('bills.index')); ?>" class="close-btn" title="Close">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        </div>

        <!-- Modal Body / PDF Viewer -->
        <div class="modal-body">
            <div id="loading">
                <div class="spinner"></div>
                <div class="loading-text" id="loading-text">Generating PDF securely...</div>
            </div>
            <iframe id="pdf-viewer" src=""></iframe>
        </div>
    </div>

    <!-- Hidden Render Container for PDF Generation -->
    <div class="render-container" id="render-container">
        <div class="invoice-wrapper" id="invoice-content">
            
            <!-- Header Section -->
            <div class="invoice-header">
                <div class="company-details">
                    <h2><?php echo e($bill->workshop->name ?? 'Suhaim Soft Work Shop'); ?></h2>
                    <p><?php echo e($bill->workshop->address ?? '123 Workshop Road, City'); ?></p>
                    <p>M: <?php echo e($bill->workshop->phone ?? 'N/A'); ?> | E: <?php echo e($bill->workshop->email ?? 'N/A'); ?></p>
                    <p>GSTIN: <?php echo e($bill->workshop->gstin ?? 'N/A'); ?></p>
                </div>
                <div class="invoice-title">
                    <h1>INVOICE</h1>
                    <p><strong>Invoice #:</strong> <?php echo e($bill->bill_number); ?></p>
                    <p><strong>Date:</strong> <?php echo e($bill->bill_date ? $bill->bill_date->format('d/m/Y') : $bill->created_at->format('d/m/Y')); ?></p>
                </div>
            </div>

            <!-- Customer & Vehicle Info Section -->
            <div class="info-section">
                <div class="info-box">
                    <h3>Bill To</h3>
                    <p><strong>Name:</strong> <?php echo e($bill->customer->name); ?></p>
                    <p><strong>Phone:</strong> <?php echo e($bill->customer->phone); ?></p>
                    <?php if($bill->customer->address): ?>
                    <p><strong>Address:</strong> <?php echo e($bill->customer->address); ?></p>
                    <?php endif; ?>
                </div>
                <div class="info-box">
                    <h3>Vehicle Details</h3>
                    <p><strong>Make/Model:</strong> <?php echo e($bill->vehicle->make ?? 'N/A'); ?> <?php echo e($bill->vehicle->model ?? ''); ?></p>
                    <p><strong>Plate No:</strong> <span style="text-transform: uppercase;"><?php echo e($bill->vehicle->plate_number ?? 'N/A'); ?></span></p>
                    <p><strong>Year:</strong> <?php echo e($bill->vehicle->year ?? 'N/A'); ?></p>
                </div>
            </div>

            <!-- Items Table -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%">S1</th>
                        <th style="width: 50%">Item Description</th>
                        <th class="center" style="width: 15%">Qty</th>
                        <th class="right" style="width: 15%">Rate</th>
                        <th class="right" style="width: 15%">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $bill->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td>
                            <?php echo e($item->item_name); ?>

                            <?php if($item->item_type === 'service'): ?>
                                <small style="color: #666; font-style: italic; margin-left: 6px;">(Service)</small>
                            <?php endif; ?>
                        </td>
                        <td class="center"><?php echo e($item->quantity); ?></td>
                        <td class="right">₹<?php echo e(number_format($item->unit_price, 2)); ?></td>
                        <td class="right">₹<?php echo e(number_format($item->total, 2)); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <!-- Summary Section -->
            <div class="summary-section">
                <table class="summary-table">
                    <tr>
                        <td>Subtotal</td>
                        <td>₹<?php echo e(number_format($bill->subtotal, 2)); ?></td>
                    </tr>
                    <?php if($bill->tax > 0): ?>
                    <tr>
                        <td>Tax</td>
                        <td>₹<?php echo e(number_format($bill->tax, 2)); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if($bill->discount > 0): ?>
                    <tr>
                        <td>Discount</td>
                        <td>-₹<?php echo e(number_format($bill->discount, 2)); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr class="total">
                        <td>Grand Total</td>
                        <td>₹<?php echo e(number_format($bill->total, 2)); ?></td>
                    </tr>
                    <tr class="paid">
                        <td>Amount Paid</td>
                        <td>₹<?php echo e(number_format($bill->amount_paid, 2)); ?></td>
                    </tr>
                    <tr>
                        <td>Balance Due</td>
                        <td>₹<?php echo e(number_format($bill->total - $bill->amount_paid, 2)); ?></td>
                    </tr>
                </table>
            </div>

            <div class="footer-note">
                Thank you for your business. For any queries regarding this invoice, please contact us.
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const element = document.getElementById('invoice-content');
            
            const opt = {
                margin:       [10, 10, 10, 10], // mm
                filename:     'Invoice_<?php echo e($bill->bill_number); ?>.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            // Generate PDF on load and put it in the iframe
            html2pdf().set(opt).from(element).output('datauristring').then(function(pdfUrl) {
                const iframe = document.getElementById('pdf-viewer');
                iframe.src = pdfUrl;
                document.getElementById('loading').style.display = 'none';
            }).catch(function(error) {
                console.error("PDF Generation Error:", error);
                document.getElementById('loading-text').innerHTML = '<span style="color: #dc2626;">Failed to load PDF</span>';
            });
        });
    </script>
</body>
</html>
<?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\bills\invoice.blade.php ENDPATH**/ ?>