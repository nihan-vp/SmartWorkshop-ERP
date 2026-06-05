<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $bill->bill_number }}</title>
    <!-- Tailwind CSS (DomPDF has limited support for Tailwind utilities, so we mix it with inline styles for guaranteed rendering) -->
    <style>
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            color: #334155; 
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        /* Common POS styles */
        .pos-container { font-family: helvetica, sans-serif; text-align: center; width: 100%; color: #000; }
        
        /* A4 styles */
        .invoice-container { 
            width: 100%; 
            max-width: 800px; 
            margin: 0 auto; 
            padding: 30px; 
            box-sizing: border-box;
            font-size: 13px; 
            line-height: 1.6; 
            color: #334155; 
        }
        table { width: 100%; border-collapse: collapse; border-spacing: 0; }
        .header-table { margin-bottom: 30px; }
        .header-table td { vertical-align: top; }
        .logo-placeholder {
            width: 160px; height: 65px; background: #f8fafc; border: 1px solid #e2e8f0; 
            text-align: center; line-height: 65px; font-weight: bold; color: #94a3b8; 
            border-radius: 4px; font-size: 11px; letter-spacing: 0.5px; text-transform: uppercase;
        }
        .company-details { text-align: right; }
        .company-name { margin: 0 0 4px 0; color: #0f172a; font-size: 22px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
        .company-info { color: #64748b; font-size: 12px; line-height: 1.5; }
        .invoice-title { font-size: 32px; font-weight: 800; color: #1e40af; text-align: center; margin: 20px 0 30px; text-transform: uppercase; letter-spacing: 4px; }
        .info-cards { width: 100%; margin-bottom: 30px; }
        .info-cards td { width: 50%; vertical-align: top; }
        .card { background: #f8fafc; padding: 16px; border-radius: 6px; border: 1px solid #e2e8f0; }
        .card-title { color: #1e293b; font-size: 13px; font-weight: bold; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px; }
        .info-row { margin-bottom: 4px; }
        .info-label { color: #64748b; font-size: 12px; width: 35%; display: inline-block; }
        .info-value { color: #0f172a; font-weight: 600; font-size: 12px; }
        .items-table { width: 100%; margin-bottom: 30px; border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden; }
        .items-table th, .items-table td { border-bottom: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; padding: 10px 12px; text-align: left; }
        .items-table th:last-child, .items-table td:last-child { border-right: none; }
        .items-table tr:last-child td { border-bottom: none; }
        .items-table th { background: #f1f5f9; color: #1e293b; font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
        .items-table th.center, .items-table td.center { text-align: center; }
        .items-table th.right, .items-table td.right { text-align: right; }
        .item-name { font-weight: 600; color: #0f172a; font-size: 13px; }
        .totals-wrapper { width: 100%; }
        .totals-wrapper td.empty { width: 55%; }
        .totals-wrapper td.totals-container { width: 45%; vertical-align: top; }
        .totals-table { width: 100%; }
        .totals-table td { padding: 6px 10px; text-align: right; font-size: 13px; }
        .totals-table td.label { font-weight: 600; color: #475569; text-align: left; }
        .totals-table td.value { color: #0f172a; font-weight: 600; }
        .grand-total { font-size: 16px; color: #1e40af; font-weight: 800; border-top: 2px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; background: #f8fafc; }
        .badge { padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 10px; text-transform: uppercase; display: inline-block; }
        .badge-paid { background-color: #dcfce7; color: #166534; }
        .badge-partial { background-color: #fef9c3; color: #854d0e; }
        .badge-pending { background-color: #fee2e2; color: #991b1b; }
        .footer { margin-top: 40px; text-align: center; color: #64748b; font-size: 11px; border-top: 1px solid #e2e8f0; padding-top: 15px; }
    </style>
</head>
<body>

@php
    $workshopName = trim($bill->workshop->name ?? 'Suhaim Soft Work Shop');
    $address = trim($bill->workshop->address ?? "123 Auto Hub Industrial Area\nKerala, India - 673001");
    $phone = trim($bill->workshop->phone ?? '+91 98765 43210');
    $email = trim($bill->workshop->email ?? 'info@suhaimsoft.com');

    // Clean up logic
    $address = preg_replace('/^(o)?zon\s+detailing\s*(&|and)?\s*(car\s*wash)?/i', '', $address);
    $address = ltrim($address, " \t\n\r\0\x0B,/-");
    if ($phone) $address = str_ireplace($phone, '', $address);
    if ($email) $address = str_ireplace($email, '', $address);
    $address = preg_replace('/[\r\n]+/', "\n", $address);
    $address = preg_replace('/,\s*,/', ',', $address);
    $address = trim($address, " \t\n\r\0\x0B,/-");

    $size = strtoupper($size ?? 'A4');
    $isPos = in_array($size, ['80MM', '58MM']);
    $is58mm = $size === '58MM';
    $baseFontSize = $is58mm ? '7px' : '9px';
    $titleFontSize = $is58mm ? '10px' : '12px';
@endphp

@if($isPos)
    <div class="pos-container" style="font-size: {{ $baseFontSize }};">
        @if(isset($bill->workshop->logo) && file_exists(public_path('storage/' . $bill->workshop->logo)))
            <img src="{{ public_path('storage/' . $bill->workshop->logo) }}" alt="Logo" style="height: 40px; margin-bottom: 5px;"><br>
        @endif
        <strong style="font-size: {{ $titleFontSize }};">{{ strtoupper($workshopName) }}</strong><br>
        {!! nl2br(e($address)) !!}<br>
        @if($phone) Ph: {{ $phone }}<br> @endif
        <hr style="border-top: 1px dashed #000; margin: 5px 0;">
        <div style="text-align: left;">
            <strong>INVOICE:</strong> {{ $bill->bill_number }}<br>
            <strong>DATE:</strong> {{ $bill->bill_date ? (is_string($bill->bill_date) ? \Carbon\Carbon::parse($bill->bill_date)->format('d-m-Y H:i') : $bill->bill_date->format('d-m-Y H:i')) : date('d-m-Y H:i') }}<br>
            <strong>CUSTOMER:</strong> {{ strtoupper($bill->customer->name ?? 'N/A') }}<br>
            @if($bill->vehicle)
                <strong>VEHICLE OWNER:</strong> {{ strtoupper($bill->vehicle->customer->name ?? $bill->customer->name ?? 'N/A') }}<br>
                <strong>VEHICLE:</strong> {{ $bill->vehicle->plate_number }} ({{ $bill->vehicle->make }} {{ $bill->vehicle->model }})<br>
            @endif
        </div>
        <hr style="border-top: 1px dashed #000; margin: 5px 0;">
        <table width="100%" cellpadding="1" style="font-size: {{ $baseFontSize }}; text-align: left;">
            <tr>
                <td width="50%"><strong>Item</strong></td>
                <td width="15%" align="center"><strong>Qty</strong></td>
                <td width="35%" align="right"><strong>Amt</strong></td>
            </tr>
            @foreach($bill->items as $item)
            <tr>
                <td width="50%">{{ $item->item_name }}</td>
                <td width="15%" align="center">{{ floatval($item->quantity) }}</td>
                <td width="35%" align="right">{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </table>
        <hr style="border-top: 1px dashed #000; margin: 5px 0;">
        <table width="100%" cellpadding="1" style="font-size: {{ $baseFontSize }}; text-align: right;">
            @php
                $servicesTotal = $bill->items->filter(fn($i) => strtolower($i->item_type) == 'service')->sum('total');
                $productsTotal = $bill->items->filter(fn($i) => strtolower($i->item_type) == 'product')->sum('total');
            @endphp
            @if($servicesTotal > 0)
            <tr><td width="60%">Services:</td><td width="40%">{{ number_format($servicesTotal, 2) }}</td></tr>
            @endif
            @if($productsTotal > 0)
            <tr><td width="60%">Products:</td><td width="40%">{{ number_format($productsTotal, 2) }}</td></tr>
            @endif
            @if($bill->discount > 0)
            <tr><td width="60%">Discount:</td><td width="40%">-{{ number_format($bill->discount, 2) }}</td></tr>
            @endif
            @if($bill->tax > 0)
            <tr><td width="60%">Tax:</td><td width="40%">{{ number_format($bill->tax, 2) }}</td></tr>
            @endif
            <tr>
                <td width="60%"><strong>TOTAL:</strong></td>
                <td width="40%"><strong>{{ number_format($bill->total, 2) }}</strong></td>
            </tr>
            <tr><td width="60%">Paid:</td><td width="40%">{{ number_format($bill->amount_paid, 2) }}</td></tr>
            <tr><td width="60%">Status:</td><td width="40%"><strong>{{ strtoupper($bill->payment_status) }}</strong></td></tr>
        </table>
        <hr style="border-top: 1px dashed #000; margin: 5px 0;">
        <div style="text-align: center; margin-top: 5px;">
            Thank you for your business!<br>Please visit again.
        </div>
    </div>
@else
    <div class="invoice-container">
        <!-- Header Section -->
        <table class="header-table">
            <tr>
                <td>
                    @if(isset($bill->workshop->logo) && file_exists(public_path('storage/' . $bill->workshop->logo)))
                        <img src="{{ public_path('storage/' . $bill->workshop->logo) }}" alt="Logo" style="height: 65px; max-width: 160px;">
                    @else
                        <div class="logo-placeholder">
                            [COMPANY LOGO]
                        </div>
                    @endif
                </td>
                <td class="company-details">
                    <h1 class="company-name">{{ $workshopName }}</h1>
                    <div class="company-info">
                        {!! nl2br(e($address)) !!}<br>
                        @if($phone) Phone: {{ $phone }}<br> @endif
                        @if($email) Email: {{ $email }}<br> @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- Invoice Title -->
        <div class="invoice-title">INVOICE</div>

        <!-- Info Cards -->
        <table class="info-cards">
            <tr>
                <td style="padding-right: 8px;">
                    <div class="card" style="height: 100%;">
                        <div class="card-title">Customer Details</div>
                        <div class="info-row">
                            <span class="info-label">Name:</span>
                            <span class="info-value">{{ $bill->customer->name ?? 'N/A' }}</span>
                        </div>
                        @if(!empty($bill->customer->phone))
                        <div class="info-row">
                            <span class="info-label">Phone:</span>
                            <span class="info-value">{{ $bill->customer->phone }}</span>
                        </div>
                        @endif
                        @if(!empty($bill->customer->email))
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value">{{ $bill->customer->email }}</span>
                        </div>
                        @endif
                        @if(!empty($bill->customer->address))
                        <div class="info-row">
                            <span class="info-label">Address:</span>
                            <span class="info-value">{{ $bill->customer->address }}</span>
                        </div>
                        @endif
                    </div>
                </td>
                <td style="padding-left: 8px;">
                    <div class="card" style="height: 100%;">
                        <div class="card-title">Invoice & Vehicle Details</div>
                        <div class="info-row">
                            <span class="info-label">Invoice No:</span>
                            <span class="info-value" style="color: #1e40af;">#{{ $bill->bill_number }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Date:</span>
                            <span class="info-value">{{ $bill->bill_date ? (is_string($bill->bill_date) ? \Carbon\Carbon::parse($bill->bill_date)->format('d M Y') : $bill->bill_date->format('d M Y')) : date('d M Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Vehicle Reg:</span>
                            <span class="info-value">{{ $bill->vehicle->plate_number ?? 'N/A' }}</span>
                        </div>
                        @if(!empty($bill->vehicle->make))
                        <div class="info-row">
                            <span class="info-label">Model:</span>
                            <span class="info-value">{{ $bill->vehicle->make }} {{ $bill->vehicle->model }}</span>
                        </div>
                        @endif
                        <div class="info-row" style="margin-top: 6px;">
                            <span class="info-label">Status:</span>
                            <span class="badge @if($bill->payment_status == 'paid') badge-paid @elseif($bill->payment_status == 'partial') badge-partial @else badge-pending @endif">
                                {{ ucfirst($bill->payment_status) }}
                            </span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="center" style="width: 8%;">Sl No</th>
                    <th style="width: 47%;">Description</th>
                    <th class="center" style="width: 15%;">Qty</th>
                    <th class="right" style="width: 15%;">Rate (₹)</th>
                    <th class="right" style="width: 15%;">Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($bill->items) && $bill->items->count() > 0)
                    @foreach($bill->items as $index => $item)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td>
                            <div class="item-name">{{ $item->item_name }}</div>
                            @if(isset($item->item_type))
                            <div style="font-size: 10px; color: #64748b;">{{ ucfirst($item->item_type) }}</div>
                            @endif
                        </td>
                        <td class="center">{{ floatval($item->quantity) }}</td>
                        <td class="right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="right" style="font-weight: 600; color: #0f172a;">{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="center" style="color: #94a3b8; font-style: italic; padding: 20px;">No items found.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Totals Section -->
        <table class="totals-wrapper">
            <tr>
                <td class="empty"></td>
                <td class="totals-container">
                    <table class="totals-table">
                        <tr>
                            <td class="label">Subtotal:</td>
                            <td class="value">₹{{ number_format($bill->total, 2) }}</td>
                        </tr>
                        
                        @if($bill->discount > 0)
                        <tr>
                            <td class="label">Discount:</td>
                            <td class="value" style="color: #ef4444;">- ₹{{ number_format($bill->discount, 2) }}</td>
                        </tr>
                        @endif

                        <tr>
                            <td class="label grand-total" style="padding: 10px;">Grand Total:</td>
                            <td class="grand-total" style="padding: 10px; text-align: right;">₹{{ number_format(($bill->total ?? 0) - ($bill->discount ?? 0), 2) }}</td>
                        </tr>
                        
                        <tr>
                            <td class="label" style="padding-top: 12px;">Amount Paid:</td>
                            <td class="value" style="color: #166534; padding-top: 12px;">₹{{ number_format($bill->amount_paid ?? 0, 2) }}</td>
                        </tr>
                        
                        @php
                            $balanceDue = ($bill->total ?? 0) - ($bill->discount ?? 0) - ($bill->amount_paid ?? 0);
                        @endphp
                        <tr>
                            <td class="label">Balance Due:</td>
                            <td class="value" style="color: #dc2626;">₹{{ number_format($balanceDue > 0 ? $balanceDue : 0, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            <strong style="color: #0f172a; font-size: 13px; display: block; margin-bottom: 4px;">Thank you for your business!</strong>
            Computer generated invoice - no signature required.<br>
            For any queries regarding this invoice, please contact us at {{ $email ?: 'info@suhaimsoft.com' }} or {{ $phone ?: 'Phone' }}.
        </div>
    </div>
@endif

</body>
</html>
