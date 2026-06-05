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
    $f12 = round(12 * $scale) . 'px';
    $f13 = round(13 * $scale) . 'px';
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
{{-- Clean A4 / A5 / Letter Invoice Layout                        --}}
{{-- ============================================================ --}}

<!-- Header: Workshop Info & Invoice Title -->
<table cellpadding="0" cellspacing="0" style="width: 100%; font-family: helvetica;">
    <tr>
        <!-- Left Side: Logo & Business Details -->
        <td style="width: 55%; vertical-align: top; text-align: left;">
            @if(isset($bill->workshop->logo) && file_exists(public_path('storage/' . $bill->workshop->logo)))
                <img src="{{ public_path('storage/' . $bill->workshop->logo) }}" alt="Logo" style="height: {{ round(70 * $scale) }}px; max-width: {{ round(150 * $scale) }}px; margin-bottom: 8px;"><br>
            @endif
            <span style="font-size: {{ $f13 }}; font-weight: bold; color: #111827; line-height: 1.4;">{{ $workshopName }}</span><br>
            <span style="font-size: {{ $f10 }}; color: #4b5563; line-height: 1.5;">
                {{ $address }}<br>
                @if($phone)<strong>Phone:</strong> {{ $phone }}<br>@endif
                @if($email)<strong>Email:</strong> {{ $email }}@endif
            </span>
        </td>

        <!-- Right Side: Invoice Meta Details -->
        <td style="width: 45%; text-align: right; vertical-align: top;">
            <span style="font-size: {{ $f24 }}; font-weight: bold; color: #111827; letter-spacing: 0.5px;">INVOICE</span><br>
            <br>
            <span style="font-size: {{ $f10 }}; color: #4b5563; line-height: 1.8;">
                <strong>Invoice No:</strong> {{ $bill->bill_number }}<br>
                <strong>Date:</strong> {{ $bill->bill_date ? (is_string($bill->bill_date) ? \Carbon\Carbon::parse($bill->bill_date)->format('d M Y') : $bill->bill_date->format('d M Y')) : date('d M Y') }}<br>
                <strong>Status:</strong>
                @if($bill->payment_status === 'paid')
                    <span style="color: #059669; font-weight: bold;">PAID</span>
                @elseif($bill->payment_status === 'partial')
                    <span style="color: #d97706; font-weight: bold;">PARTIAL</span>
                @else
                    <span style="color: #dc2626; font-weight: bold;">DUE</span>
                @endif
            </span>
        </td>
    </tr>
</table>

<br><br>
<div style="border-top: 1px solid #e5e7eb; width: 100%;"></div>
<br><br>

<!-- Billed To & Vehicle Info -->
<table cellpadding="0" cellspacing="0" style="width: 100%; font-family: helvetica;">
    <tr>
        <td style="width: 50%; vertical-align: top;">
            <span style="font-size: {{ $f9 }}; font-weight: bold; color: #9ca3af; letter-spacing: 0.8px; text-transform: uppercase;">Billed To</span><br>
            <br>
            <span style="font-size: {{ $f12 }}; font-weight: bold; color: #111827;">{{ strtoupper($bill->customer->name ?? 'N/A') }}</span><br>
            <span style="font-size: {{ $f10 }}; color: #4b5563; line-height: 1.6;">
                @if($bill->customer->address){{ $bill->customer->address }}<br>@endif
                @if($bill->customer->phone)<strong>Phone:</strong> {{ $bill->customer->phone }}<br>@endif
            </span>
        </td>
        <td style="width: 50%; vertical-align: top;">
            <span style="font-size: {{ $f9 }}; font-weight: bold; color: #9ca3af; letter-spacing: 0.8px; text-transform: uppercase;">Vehicle & Details</span><br>
            <br>
            <span style="font-size: {{ $f10 }}; color: #4b5563; line-height: 1.6;">
                <strong>Vehicle Owner:</strong> {{ strtoupper($bill->vehicle && $bill->vehicle->customer ? $bill->vehicle->customer->name : ($bill->customer->name ?? 'N/A')) }}<br>
                <strong>Vehicle:</strong> {{ $bill->vehicle ? $bill->vehicle->make . ' ' . $bill->vehicle->model : 'N/A' }}
                @if($bill->vehicle) ({{ $bill->vehicle->plate_number }})@endif<br>
                <strong>Payment Method:</strong> {{ strtoupper($bill->payment_method ?? 'N/A') }}<br>
                <strong>Invoice Date:</strong> {{ $bill->bill_date ? (is_string($bill->bill_date) ? \Carbon\Carbon::parse($bill->bill_date)->format('d M Y') : $bill->bill_date->format('d M Y')) : date('d M Y') }}
            </span>
        </td>
    </tr>
</table>

<br><br>

<!-- Items Table -->
<table cellpadding="{{ round(8 * $scale) }}" cellspacing="0" style="width: 100%; font-family: helvetica; border-collapse: collapse; border: 1px solid #d1d5db;">
    <thead>
        <tr style="background-color: #f9fafb;">
            <th style="width: 50%; text-align: left; font-size: {{ $f10 }}; font-weight: bold; color: #111827; border-bottom: 1px solid #d1d5db; border-right: 1px solid #d1d5db;">Description</th>
            <th style="width: 15%; text-align: center; font-size: {{ $f10 }}; font-weight: bold; color: #111827; border-bottom: 1px solid #d1d5db; border-right: 1px solid #d1d5db;">Qty</th>
            <th style="width: 15%; text-align: right; font-size: {{ $f10 }}; font-weight: bold; color: #111827; border-bottom: 1px solid #d1d5db; border-right: 1px solid #d1d5db;">Unit Price</th>
            <th style="width: 20%; text-align: right; font-size: {{ $f10 }}; font-weight: bold; color: #111827; border-bottom: 1px solid #d1d5db; padding-right: 8px;">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bill->items as $index => $item)
        @php $rowBg = $index % 2 === 0 ? '#ffffff' : '#f9fafb'; @endphp
        <tr style="background-color: {{ $rowBg }};">
            <td style="width: 50%; text-align: left; font-size: {{ $f10 }}; color: #111827; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb;">
                <strong>{{ strtoupper($item->item_name) }}</strong>
                <br><span style="font-size: {{ $f8 }}; color: #6b7280;">{{ ucfirst($item->item_type) }}</span>
            </td>
            <td style="width: 15%; text-align: center; font-size: {{ $f10 }}; color: #4b5563; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb;">{{ floatval($item->quantity) }}</td>
            <td style="width: 15%; text-align: right; font-size: {{ $f10 }}; color: #4b5563; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb;">{{ number_format($item->unit_price, 2) }}</td>
            <td style="width: 20%; text-align: right; font-size: {{ $f10 }}; font-weight: bold; color: #111827; border-bottom: 1px solid #e5e7eb; padding-right: 8px;">{{ number_format($item->total, 2) }}</td>
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
        <td style="width: 30%; text-align: right; color: #6b7280;">Services Subtotal:</td>
        <td style="width: 20%; text-align: right; color: #111827; padding-right: 8px;">{{ number_format($servicesTotal, 2) }}</td>
    </tr>
    @endif
    @if($productsTotal > 0)
    <tr>
        <td style="width: 50%;"></td>
        <td style="width: 30%; text-align: right; color: #6b7280;">Products Subtotal:</td>
        <td style="width: 20%; text-align: right; color: #111827; padding-right: 8px;">{{ number_format($productsTotal, 2) }}</td>
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
        <td style="width: 30%; text-align: right; color: #6b7280;">Tax:</td>
        <td style="width: 20%; text-align: right; color: #111827; padding-right: 8px;">{{ number_format($bill->tax, 2) }}</td>
    </tr>
    @endif
    <tr>
        <td style="width: 50%;"></td>
        <td colspan="2" style="border-top: 1px solid #e5e7eb;"></td>
    </tr>
    <tr>
        <td style="width: 50%;"></td>
        <td style="width: 30%; text-align: right; font-weight: bold; font-size: {{ $f12 }}; color: #111827; padding-top: 4px;">Total Amount:</td>
        <td style="width: 20%; text-align: right; font-weight: bold; font-size: {{ $f12 }}; color: #111827; padding-top: 4px; padding-right: 8px;">{{ number_format($bill->total, 2) }}</td>
    </tr>
    @if($bill->amount_paid > 0)
    <tr>
        <td style="width: 50%;"></td>
        <td style="width: 30%; text-align: right; color: #059669; font-size: {{ $f10 }};">Amount Paid:</td>
        <td style="width: 20%; text-align: right; color: #059669; font-weight: bold; font-size: {{ $f10 }}; padding-right: 8px;">{{ number_format($bill->amount_paid, 2) }}</td>
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
<div style="border-top: 1px solid #e5e7eb; width: 100%;"></div>
<br>

<!-- Notes -->
<table cellpadding="0" cellspacing="0" style="width: 100%; font-family: helvetica; font-size: {{ $f10 }};">
    <tr>
        <td>
            <strong style="color: #111827;">Notes:</strong><br>
            <span style="color: #6b7280; line-height: 1.5;">
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
            <hr style="width: 55%; border: 0; border-top: 1px solid #9ca3af; margin: 0 auto 5px auto;">
            <span style="font-size: {{ $f9 }}; color: #9ca3af;">Customer Signature</span>
        </td>
        <td style="width: 50%; text-align: center;">
            <hr style="width: 55%; border: 0; border-top: 1px solid #9ca3af; margin: 0 auto 5px auto;">
            <span style="font-size: {{ $f9 }}; color: #9ca3af;">Authorized Signatory</span>
        </td>
    </tr>
</table>

<br>

<!-- Footer -->
<table cellpadding="0" cellspacing="0" style="width: 100%; font-family: helvetica;">
    <tr>
        <td style="text-align: center; font-size: {{ $f8 }}; color: #9ca3af; font-style: italic;">
            Generated by {{ $workshopName }}
        </td>
    </tr>
</table>

@endif
