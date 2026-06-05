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
            background-color: #fff;
            margin: 0;
            padding: 0;
        }
        /* Common POS styles */
        .pos-container { font-family: helvetica, sans-serif; text-align: center; width: 100%; color: #000; }
        
        /* A4 styles */
        .invoice-container { 
            width: 100%; 
            max-width: 800px; 
            margin: auto; 
            padding: 30px; 
            box-sizing: border-box;
            font-size: 14px; 
            line-height: 1.5; 
            color: #334155; 
        }
        table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: top; }
        .logo-placeholder {
            width: 180px; height: 70px; background: #f8fafc; border: 1.5px dashed #cbd5e1; 
            text-align: center; line-height: 70px; font-weight: bold; color: #94a3b8; 
            border-radius: 6px; font-size: 12px;
        }
        .company-details { text-align: right; }
        .company-name { margin: 0 0 5px 0; color: #0f172a; font-size: 24px; font-weight: 800; }
        .company-info { color: #64748b; font-size: 13px; line-height: 1.6; }
        .invoice-title { font-size: 36px; font-weight: 800; color: #1e40af; text-align: center; margin: 30px 0; text-transform: uppercase; letter-spacing: 3px; }
        .info-cards { width: 100%; margin-bottom: 25px; }
        .info-cards td { width: 50%; vertical-align: top; }
        .card { background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; }
        .card-title { color: #0f172a; font-size: 15px; font-weight: bold; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .items-table { width: 100%; margin-bottom: 25px; }
        .items-table th, .items-table td { border: 1px solid #cbd5e1; padding: 12px 10px; text-align: left; }
        .items-table th { background: #f1f5f9; color: #1e293b; font-weight: bold; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; }
        .items-table th.center, .items-table td.center { text-align: center; }
        .items-table th.right, .items-table td.right { text-align: right; }
        .totals-wrapper { width: 100%; }
        .totals-wrapper td.empty { width: 50%; }
        .totals-wrapper td.totals-container { width: 50%; vertical-align: top; }
        .totals-table { width: 100%; }
        .totals-table td { padding: 8px 10px; text-align: right; }
        .totals-table td.label { font-weight: 600; color: #475569; text-align: left; }
        .grand-total { font-size: 18px; color: #1e40af; font-weight: bold; border-top: 2px solid #cbd5e1; border-bottom: 2px solid #cbd5e1; background: #f8fafc; }
        .badge { padding: 4px 10px; border-radius: 999px; font-weight: bold; font-size: 11px; text-transform: uppercase; display: inline-block; }
        .badge-paid { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-partial { background-color: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }
        .badge-pending { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .footer { margin-top: 50px; text-align: center; color: #64748b; font-size: 12px; border-top: 1px solid #e2e8f0; padding-top: 20px; }
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
                        <img src="{{ public_path('storage/' . $bill->workshop->logo) }}" alt="Logo" style="height: 70px; max-width: 180px; margin-bottom: 8px;">
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
                <td style="padding-right: 10px;">
                    <div class="card" style="height: 100%;">
                        <div class="card-title">Customer Details</div>
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 30%; color: #64748b; padding-bottom: 4px;">Name:</td>
                                <td style="font-weight: 600; color: #0f172a; padding-bottom: 4px;">{{ $bill->customer->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; padding-bottom: 4px;">Phone:</td>
                                <td style="color: #334155; padding-bottom: 4px;">{{ $bill->customer->phone ?? 'N/A' }}</td>
                            </tr>
                            @if($bill->customer->email)
                            <tr>
                                <td style="color: #64748b; padding-bottom: 4px;">Email:</td>
                                <td style="color: #334155; padding-bottom: 4px;">{{ $bill->customer->email }}</td>
                            </tr>
                            @endif
                            @if($bill->customer->address)
                            <tr>
                                <td style="color: #64748b; padding-bottom: 4px; vertical-align: top;">Address:</td>
                                <td style="color: #334155; padding-bottom: 4px;">{{ $bill->customer->address }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </td>
                <td style="padding-left: 10px;">
                    <div class="card" style="height: 100%;">
                        <div class="card-title">Invoice & Vehicle Details</div>
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 40%; color: #64748b; padding-bottom: 4px;">Invoice No:</td>
                                <td style="font-weight: bold; color: #1e40af; padding-bottom: 4px;">#{{ $bill->bill_number }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; padding-bottom: 4px;">Date:</td>
                                <td style="font-weight: 600; color: #334155; padding-bottom: 4px;">{{ $bill->bill_date ? (is_string($bill->bill_date) ? \Carbon\Carbon::parse($bill->bill_date)->format('d M Y') : $bill->bill_date->format('d M Y')) : date('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; padding-bottom: 4px;">Vehicle Reg:</td>
                                <td style="font-weight: 600; color: #334155; padding-bottom: 4px;">{{ $bill->vehicle ? $bill->vehicle->plate_number : 'N/A' }}</td>
                            </tr>
                            @if($bill->vehicle && $bill->vehicle->make)
                            <tr>
                                <td style="color: #64748b; padding-bottom: 4px;">Model:</td>
                                <td style="color: #334155; padding-bottom: 4px;">{{ $bill->vehicle->make }} {{ $bill->vehicle->model }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td style="color: #64748b; padding-top: 8px;">Status:</td>
                                <td style="padding-top: 8px;">
                                    <span class="badge @if($bill->payment_status == 'paid') badge-paid @elseif($bill->payment_status == 'partial') badge-partial @else badge-pending @endif">
                                        {{ ucfirst($bill->payment_status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
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
                    <th class="center" style="width: 15%;">Quantity</th>
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
                            <strong>{{ $item->item_name }}</strong><br>
                            <span style="font-size: 11px; color: #64748b;">{{ ucfirst($item->item_type) }}</span>
                        </td>
                        <td class="center">{{ floatval($item->quantity) }}</td>
                        <td class="right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="right" style="font-weight: 600; color: #0f172a;">{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="center" style="color: #94a3b8; font-style: italic; padding: 20px;">No items found in this invoice.</td>
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
                            <td style="font-weight: 600; color: #1e293b;">₹{{ number_format($bill->total, 2) }}</td>
                        </tr>
                        
                        @if($bill->discount && $bill->discount > 0)
                        <tr>
                            <td class="label">Discount:</td>
                            <td style="color: #ef4444; font-weight: 600;">- ₹{{ number_format($bill->discount, 2) }}</td>
                        </tr>
                        @endif

                        <tr>
                            <td class="label grand-total" style="padding: 12px 10px;">Grand Total:</td>
                            <td class="grand-total" style="padding: 12px 10px;">₹{{ number_format(($bill->total ?? 0) - ($bill->discount ?? 0), 2) }}</td>
                        </tr>
                        
                        <tr>
                            <td class="label" style="padding-top: 15px;">Amount Paid:</td>
                            <td style="color: #166534; font-weight: bold; padding-top: 15px;">₹{{ number_format($bill->amount_paid ?? 0, 2) }}</td>
                        </tr>
                        
                        @php
                            $balanceDue = ($bill->total ?? 0) - ($bill->discount ?? 0) - ($bill->amount_paid ?? 0);
                        @endphp
                        <tr>
                            <td class="label">Balance Due:</td>
                            <td style="color: #991b1b; font-weight: bold;">₹{{ number_format($balanceDue > 0 ? $balanceDue : 0, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            <strong style="color: #0f172a; font-size: 15px; display: block; margin-bottom: 8px;">Thank you for your business!</strong>
            Computer generated invoice - no signature required.<br>
            For any queries regarding this invoice, please contact us at {{ $email ?: 'info@suhaimsoft.com' }} or {{ $phone ?: '+91 98765 43210' }}.
        </div>
    </div>
@endif

</body>
</html>
