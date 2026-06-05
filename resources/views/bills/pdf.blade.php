@php
    $workshopName = trim($bill->workshop->name ?? 'Suhaim Soft Work Shop');
    $address = trim($bill->workshop->address ?? "123 Workshop Avenue, City");
    $phone = trim($bill->workshop->phone ?? '+91 9876543210');
    $email = trim($bill->workshop->email ?? 'info@suhaimsoft.com');

    // Robustly clean up any duplicate company name from the start of the address
    $address = preg_replace('/^(o)?zon\s+detailing\s*(&|and)?\s*(car\s*wash)?/i', '', $address);
    $address = ltrim($address, " \t\n\r\0\x0B,/-");

    // Clean up duplicate phone number from address
    if ($phone) {
        $address = str_ireplace($phone, '', $address);
    }

    // Clean up duplicate email from address
    if ($email) {
        $address = str_ireplace($email, '', $address);
    }

    // Tidy up any empty lines or duplicate commas left after stripping
    $address = preg_replace('/[\r\n]+/', "\n", $address);
    $address = preg_replace('/,\s*,/', ',', $address);
    $address = trim($address, " \t\n\r\0\x0B,/-");

    $size = strtoupper($size ?? 'A4');
    $isPos = in_array($size, ['80MM', '58MM']);
    $is58mm = $size === '58MM';
    $baseFontSize = $is58mm ? '7px' : '9px';
    $titleFontSize = $is58mm ? '10px' : '12px';

    // Scale fonts and spacing proportionally for A3/A4/A5/Letter/Legal sizes
    $scale = 1.0;
    if ($size === 'A5') {
        $scale = 0.8;
    } elseif ($size === 'A3') {
        $scale = 1.4;
    }

    $f8 = round(8 * $scale) . 'px';
    $f9 = round(9 * $scale) . 'px';
    $f10 = round(10 * $scale) . 'px';
    $f11 = round(11 * $scale) . 'px';
    $f12 = round(12 * $scale) . 'px';
    $f13 = round(13 * $scale) . 'px';
    $f14 = round(14 * $scale) . 'px';
    $f16 = round(16 * $scale) . 'px';
    $f24 = round(24 * $scale) . 'px';
@endphp

@if($isPos)
{{-- ============================================================ --}}
{{-- POS / Thermal Receipt Layout (80MM / 58MM)                   --}}
{{-- ============================================================ --}}
<div style="font-family: helvetica, sans-serif; font-size: {{ $baseFontSize }}; color: #000; text-align: center; width: 100%;">
    @if(isset($bill->workshop->logo) && file_exists(public_path('storage/' . $bill->workshop->logo)))
        <img src="{{ public_path('storage/' . $bill->workshop->logo) }}" alt="Logo" style="height: 40px; margin-bottom: 5px;"><br>
    @endif
    <strong style="font-size: {{ $titleFontSize }};">{{ strtoupper($workshopName) }}</strong><br>
    {{ $address }}<br>
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
{{-- ============================================================ --}}
{{-- Clean & Premium A3 / A4 / A5 / Letter / Legal Layout         --}}
{{-- ============================================================ --}}

<!-- Top Color Accent Bar -->
<table cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <tr>
        <td style="height: {{ round(4 * $scale) }}px; background-color: #3b82f6; line-height: {{ round(4 * $scale) }}px; font-size: 1px;">&nbsp;</td>
    </tr>
</table>
<br>

<!-- Header: Workshop Info & Invoice Title -->
<table cellpadding="0" cellspacing="0" style="width: 100%; font-family: helvetica;">
    <tr>
        <!-- Left Side: Logo & Business Details -->
        <td style="width: 55%; vertical-align: top; text-align: left;">
            @if(isset($bill->workshop->logo) && file_exists(public_path('storage/' . $bill->workshop->logo)))
                <img src="{{ public_path('storage/' . $bill->workshop->logo) }}" alt="Logo" style="height: {{ round(60 * $scale) }}px; max-width: {{ round(140 * $scale) }}px; margin-bottom: 8px;"><br>
            @endif
            <span style="font-size: {{ $f14 }}; font-weight: bold; color: #1e293b; line-height: 1.4;">{{ $workshopName }}</span><br>
            <span style="font-size: {{ $f10 }}; color: #475569; line-height: 1.5;">
                {{ $address }}<br>
                @if($phone)<strong>Phone:</strong> {{ $phone }} &nbsp;|&nbsp; @endif
                @if($email)<strong>Email:</strong> {{ $email }}@endif
            </span>
        </td>

        <!-- Right Side: Invoice Meta Details -->
        <td style="width: 45%; text-align: right; vertical-align: top;">
            <span style="font-size: {{ $f24 }}; font-weight: bold; color: #1e293b; letter-spacing: 0.5px;">INVOICE</span><br>
            <br>
            <table cellpadding="2" cellspacing="0" align="right" style="font-size: {{ $f10 }}; color: #475569;">
                <tr>
                    <td align="right" style="padding-right: 5px;"><strong>Invoice No:</strong></td>
                    <td align="left" style="color: #1e293b; font-weight: bold;">{{ $bill->bill_number }}</td>
                </tr>
                <tr>
                    <td align="right" style="padding-right: 5px;"><strong>Date:</strong></td>
                    <td align="left">{{ $bill->bill_date ? (is_string($bill->bill_date) ? \Carbon\Carbon::parse($bill->bill_date)->format('d M Y') : $bill->bill_date->format('d M Y')) : date('d M Y') }}</td>
                </tr>
                <tr>
                    <td align="right" style="padding-right: 5px;"><strong>Status:</strong></td>
                    <td align="left">
                        @if($bill->payment_status === 'paid')
                            <span style="color: #16a34a; font-weight: bold; background-color: #f0fdf4; padding: 2px 6px; border-radius: 4px;">PAID</span>
                        @elseif($bill->payment_status === 'partial')
                            <span style="color: #d97706; font-weight: bold; background-color: #fef3c7; padding: 2px 6px; border-radius: 4px;">PARTIAL</span>
                        @else
                            <span style="color: #dc2626; font-weight: bold; background-color: #fef2f2; padding: 2px 6px; border-radius: 4px;">DUE</span>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<br><br>
<div style="border-top: 1px solid #e2e8f0; width: 100%;"></div>
<br><br>

<!-- Billed To & Vehicle Info -->
<table cellpadding="0" cellspacing="0" style="width: 100%; font-family: helvetica;">
    <tr>
        <!-- Billed To (Customer Details) -->
        <td style="width: 48%; vertical-align: top; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: {{ round(12 * $scale) }}px;">
            <span style="font-size: {{ $f9 }}; font-weight: bold; color: #64748b; letter-spacing: 0.8px; text-transform: uppercase;">Billed To</span><br>
            <div style="border-top: 1px solid #e2e8f0; margin: 6px 0;"></div>
            <span style="font-size: {{ $f12 }}; font-weight: bold; color: #1e293b;">{{ strtoupper($bill->customer->name ?? 'N/A') }}</span><br>
            <span style="font-size: {{ $f10 }}; color: #475569; line-height: 1.6;">
                @if($bill->customer->address){{ $bill->customer->address }}<br>@endif
                @if($bill->customer->phone)<strong>Phone:</strong> {{ $bill->customer->phone }}<br>@endif
            </span>
        </td>
        
        <!-- Spacer -->
        <td style="width: 4%;"></td>

        <!-- Vehicle & Owner Details -->
        <td style="width: 48%; vertical-align: top; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: {{ round(12 * $scale) }}px;">
            <span style="font-size: {{ $f9 }}; font-weight: bold; color: #64748b; letter-spacing: 0.8px; text-transform: uppercase;">Vehicle & Owner Details</span><br>
            <div style="border-top: 1px solid #e2e8f0; margin: 6px 0;"></div>
            <span style="font-size: {{ $f10 }}; color: #475569; line-height: 1.6;">
                <strong>Vehicle Owner:</strong> <span style="color: #1e293b; font-weight: bold;">{{ strtoupper($bill->vehicle && $bill->vehicle->customer ? $bill->vehicle->customer->name : ($bill->customer->name ?? 'N/A')) }}</span><br>
                <strong>Vehicle:</strong> {{ $bill->vehicle ? $bill->vehicle->make . ' ' . $bill->vehicle->model : 'N/A' }}
                @if($bill->vehicle) ({{ $bill->vehicle->plate_number }})@endif<br>
                @if($bill->vehicle && $bill->vehicle->color)<strong>Color:</strong> {{ ucfirst($bill->vehicle->color) }}<br>@endif
                <strong>Payment Method:</strong> {{ strtoupper($bill->payment_method ?? 'N/A') }}
            </span>
        </td>
    </tr>
</table>

<br><br>

<!-- Items Table -->
<table cellpadding="{{ round(8 * $scale) }}" cellspacing="0" style="width: 100%; font-family: helvetica; border-collapse: collapse; border: 1px solid #cbd5e1; border-radius: 6px;">
    <thead>
        <tr style="background-color: #1e293b; color: #ffffff;">
            <th style="width: 50%; text-align: left; font-size: {{ $f10 }}; font-weight: bold; border-right: 1px solid #475569; color: #ffffff;">Description</th>
            <th style="width: 15%; text-align: center; font-size: {{ $f10 }}; font-weight: bold; border-right: 1px solid #475569; color: #ffffff;">Qty</th>
            <th style="width: 15%; text-align: right; font-size: {{ $f10 }}; font-weight: bold; border-right: 1px solid #475569; color: #ffffff;">Unit Price</th>
            <th style="width: 20%; text-align: right; font-size: {{ $f10 }}; font-weight: bold; padding-right: 8px; color: #ffffff;">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bill->items as $index => $item)
        @php $rowBg = $index % 2 === 0 ? '#ffffff' : '#f8fafc'; @endphp
        <tr style="background-color: {{ $rowBg }};">
            <td style="width: 50%; text-align: left; font-size: {{ $f10 }}; color: #1e293b; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0;">
                <strong>{{ strtoupper($item->item_name) }}</strong>
                <br><span style="font-size: {{ $f8 }}; color: #64748b;">{{ ucfirst($item->item_type) }}</span>
            </td>
            <td style="width: 15%; text-align: center; font-size: {{ $f10 }}; color: #334155; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0;">{{ floatval($item->quantity) }}</td>
            <td style="width: 15%; text-align: right; font-size: {{ $f10 }}; color: #334155; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0;">{{ number_format($item->unit_price, 2) }}</td>
            <td style="width: 20%; text-align: right; font-size: {{ $f10 }}; font-weight: bold; color: #1e293b; border-bottom: 1px solid #e2e8f0; padding-right: 8px;">{{ number_format($item->total, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<br>

<!-- Totals Section -->
<table cellpadding="{{ round(4 * $scale) }}" cellspacing="0" style="width: 100%; font-family: helvetica; font-size: {{ $f10 }};">
    @php
        $servicesTotal = $bill->items->filter(fn($i) => strtolower($i->item_type) == 'service')->sum('total');
        $productsTotal = $bill->items->filter(fn($i) => strtolower($i->item_type) == 'product')->sum('total');
    @endphp
    @if($servicesTotal > 0)
    <tr>
        <td style="width: 50%;"></td>
        <td style="width: 30%; text-align: right; color: #64748b;">Services Subtotal:</td>
        <td style="width: 20%; text-align: right; color: #1e293b; padding-right: 8px;">{{ number_format($servicesTotal, 2) }}</td>
    </tr>
    @endif
    @if($productsTotal > 0)
    <tr>
        <td style="width: 50%;"></td>
        <td style="width: 30%; text-align: right; color: #64748b;">Products Subtotal:</td>
        <td style="width: 20%; text-align: right; color: #1e293b; padding-right: 8px;">{{ number_format($productsTotal, 2) }}</td>
    </tr>
    @endif
    @if($bill->discount > 0)
    <tr>
        <td style="width: 50%;"></td>
        <td style="width: 30%; text-align: right; color: #dc2626;">Discount:</td>
        <td style="width: 20%; text-align: right; color: #dc2626; padding-right: 8px;">-{{ number_format($bill->discount, 2) }}</td>
    </tr>
    @endif
    @if($bill->tax > 0)
    <tr>
        <td style="width: 50%;"></td>
        <td style="width: 30%; text-align: right; color: #64748b;">Tax:</td>
        <td style="width: 20%; text-align: right; color: #1e293b; padding-right: 8px;">{{ number_format($bill->tax, 2) }}</td>
    </tr>
    @endif
    <tr>
        <td style="width: 50%;"></td>
        <td colspan="2" style="border-top: 1px solid #cbd5e1;"></td>
    </tr>
    <tr>
        <td style="width: 50%;"></td>
        <td style="width: 30%; text-align: right; font-weight: bold; font-size: {{ $f12 }}; color: #1e293b; padding-top: 4px;">Total Amount:</td>
        <td style="width: 20%; text-align: right; font-weight: bold; font-size: {{ $f12 }}; color: #1e293b; padding-top: 4px; padding-right: 8px;">{{ number_format($bill->total, 2) }}</td>
    </tr>
    @if($bill->amount_paid > 0)
    <tr>
        <td style="width: 50%;"></td>
        <td style="width: 30%; text-align: right; color: #16a34a; font-size: {{ $f10 }};">Amount Paid:</td>
        <td style="width: 20%; text-align: right; color: #16a34a; font-weight: bold; font-size: {{ $f10 }}; padding-right: 8px;">{{ number_format($bill->amount_paid, 2) }}</td>
    </tr>
    @endif
    @if(($bill->total - $bill->amount_paid) > 0)
    <tr>
        <td style="width: 50%;"></td>
        <td style="width: 30%; text-align: right; color: #dc2626; font-size: {{ $f10 }};">Balance Due:</td>
        <td style="width: 20%; text-align: right; color: #dc2626; font-weight: bold; font-size: {{ $f10 }}; padding-right: 8px;">{{ number_format($bill->total - $bill->amount_paid, 2) }}</td>
    </tr>
    @endif
</table>

<br><br>
<div style="border-top: 1px solid #cbd5e1; width: 100%;"></div>
<br>

<!-- Notes -->
<table cellpadding="0" cellspacing="0" style="width: 100%; font-family: helvetica; font-size: {{ $f10 }};">
    <tr>
        <td>
            <strong style="color: #1e293b;">Notes:</strong><br>
            <span style="color: #475569; line-height: 1.5;">
                {{ $bill->notes ?? 'Thank you for choosing ' . $workshopName . '. We appreciate your business. Please settle the invoice within 7 days.' }}
            </span>
        </td>
    </tr>
</table>

<br><br><br><br>

<!-- Footer Signatures -->
<table cellpadding="0" cellspacing="0" style="width: 100%; font-family: helvetica;">
    <tr>
        <td style="width: 50%; text-align: center;">
            <hr style="width: 55%; border: 0; border-top: 1px solid #94a3b8; margin: 0 auto 5px auto;">
            <span style="font-size: {{ $f9 }}; color: #64748b;">Customer Signature</span>
        </td>
        <td style="width: 50%; text-align: center;">
            <hr style="width: 55%; border: 0; border-top: 1px solid #94a3b8; margin: 0 auto 5px auto;">
            <span style="font-size: {{ $f9 }}; color: #64748b;">Authorized Signatory</span>
        </td>
    </tr>
</table>

<br>

<!-- Footer -->
<table cellpadding="0" cellspacing="0" style="width: 100%; font-family: helvetica;">
    <tr>
        <td style="text-align: center; font-size: {{ $f8 }}; color: #94a3b8; font-style: italic;">
            Generated by {{ $workshopName }}
        </td>
    </tr>
</table>

@endif
