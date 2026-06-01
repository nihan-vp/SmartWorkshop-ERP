@php
    $workshopName = trim($bill->workshop->name ?? 'Suhaim Soft Work Shop');
    $address = trim($bill->workshop->address ?? "123 Workshop Avenue, City");
    $phone = trim($bill->workshop->phone ?? '+91 9876543210');
    $email = trim($bill->workshop->email ?? 'info@suhaimsoft.com');

    // Robustly clean up any duplicate company name from the start of the address
    // Matches "OZON Detailing & Car Wash", "ZON Detailing & Car Wash", line breaks, etc.
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
@endphp

<table cellpadding="0" cellspacing="0" style="width: 100%; font-family: helvetica;">
    <tr>
        <!-- Left Side: Logo & Business Details -->
        <td style="width: 55%; vertical-align: top; text-align: left;">
            @if(isset($bill->workshop->logo) && file_exists(public_path('storage/' . $bill->workshop->logo)))
                <img src="{{ public_path('storage/' . $bill->workshop->logo) }}" alt="Logo" style="height: 70px; max-width: 150px; margin-bottom: 8px;"><br>
            @endif
            <span style="font-size: 13px; font-weight: bold; color: #111827; line-height: 1.4;">{{ $workshopName }}</span><br>
            <span style="font-size: 10px; color: #4b5563; line-height: 1.5;">
                {!! nl2br(e($address)) !!}<br>
                <strong>Phone:</strong> {{ $phone }}<br>
                <strong>Email:</strong> {{ $email }}
            </span>
        </td>

        <!-- Right Side: Invoice Meta Details -->
        <td style="width: 45%; text-align: right; vertical-align: top;">
            <span style="font-size: 24px; font-weight: bold; color: #111827; letter-spacing: 0.5px;">INVOICE</span><br>
            <br>
            <span style="font-size: 10px; color: #4b5563; line-height: 1.6;">
                <strong>Invoice No:</strong> {{ $bill->bill_number }}<br>
                <strong>Date:</strong> {{ $bill->bill_date ? (is_string($bill->bill_date) ? \Carbon\Carbon::parse($bill->bill_date)->format('d M Y') : $bill->bill_date->format('d M Y')) : date('d M Y') }}<br>
                <strong>Status:</strong> <span style="color: #111827; font-weight: bold;">{{ strtoupper($bill->payment_status) }}</span>
            </span>
        </td>
    </tr>
</table>

<br><br><br>

<!-- Billed To Section -->
<table cellpadding="0" cellspacing="0" style="width: 100%; font-family: helvetica;">
    <tr>
        <td style="width: 50%; vertical-align: top;">
            <span style="font-size: 10px; font-weight: bold; color: #9ca3af; letter-spacing: 0.5px; text-transform: uppercase;">BILLED TO</span><br>
            <br>
            <span style="font-size: 12px; font-weight: bold; color: #111827;">{{ strtoupper($bill->customer->name ?? 'N/A') }}</span><br>
            <span style="font-size: 10px; color: #4b5563; line-height: 1.5;">
                @if($bill->customer->address){{ $bill->customer->address }}<br>@endif
                @if($bill->customer->phone)<strong>Phone:</strong> {{ $bill->customer->phone }}<br>@endif
            </span>
        </td>
        <td style="width: 50%; vertical-align: top;">
            <span style="font-size: 10px; font-weight: bold; color: #9ca3af; letter-spacing: 0.5px; text-transform: uppercase;">VEHICLE & DETAILS</span><br>
            <br>
            <span style="font-size: 10px; color: #4b5563; line-height: 1.5;">
                <strong>Vehicle:</strong> {{ $bill->vehicle ? $bill->vehicle->make . ' ' . $bill->vehicle->model : 'N/A' }} ({{ $bill->vehicle ? $bill->vehicle->plate_number : 'N/A' }})<br>
                <strong>Payment Method:</strong> {{ strtoupper($bill->payment_method ?? 'N/A') }}<br>
                <strong>Created At:</strong> {{ $bill->created_at ? (is_string($bill->created_at) ? \Carbon\Carbon::parse($bill->created_at)->format('d M Y') : $bill->created_at->format('d M Y')) : date('d M Y') }}
            </span>
        </td>
    </tr>
</table>

<br><br><br>

<!-- Items Table -->
<table cellpadding="8" cellspacing="0" style="width: 100%; font-family: helvetica; border-collapse: collapse; border: 1px solid #9ca3af;">
    <thead>
        <tr style="background-color: #f3f4f6;">
            <th style="width: 50%; text-align: left; font-size: 10px; font-weight: bold; color: #111827; border-bottom: 1px solid #9ca3af; border-right: 1px solid #9ca3af;">Description</th>
            <th style="width: 15%; text-align: center; font-size: 10px; font-weight: bold; color: #111827; border-bottom: 1px solid #9ca3af; border-right: 1px solid #9ca3af;">Quantity</th>
            <th style="width: 15%; text-align: right; font-size: 10px; font-weight: bold; color: #111827; border-bottom: 1px solid #9ca3af; border-right: 1px solid #9ca3af;">Unit Price</th>
            <th style="width: 20%; text-align: right; font-size: 10px; font-weight: bold; color: #111827; border-bottom: 1px solid #9ca3af; padding-right: 5px;">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bill->items as $index => $item)
        <tr>
            <td style="width: 50%; text-align: left; font-size: 10px; font-weight: bold; color: #111827; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb;">{{ strtoupper($item->item_name) }}</td>
            <td style="width: 15%; text-align: center; font-size: 10px; color: #4b5563; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb;">{{ floatval($item->quantity) }}</td>
            <td style="width: 15%; text-align: right; font-size: 10px; color: #4b5563; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb;">{{ floatval($item->unit_price) }}</td>
            <td style="width: 20%; text-align: right; font-size: 10px; font-weight: bold; color: #111827; border-bottom: 1px solid #e5e7eb; padding-right: 5px;">{{ floatval($item->total) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<br>

<!-- Totals Table (No Grid Borders, Positioned Under the Items Table) -->
<table cellpadding="4" cellspacing="0" style="width: 100%; font-family: helvetica; font-size: 10px; margin-top: 5px;">
    <tr>
        <td style="width: 50%;"></td>
        <td style="width: 30%; text-align: right; color: #4b5563;">Subtotal (Services Charges):</td>
        <td style="width: 20%; text-align: right; font-weight: bold; color: #111827; padding-right: 6px;">{{ floatval($bill->items->filter(function($item) { return strtolower($item->item_type) == 'service'; })->sum('total')) ?: '0' }}</td>
    </tr>
    <tr>
        <td style="width: 50%;"></td>
        <td style="width: 30%; text-align: right; color: #4b5563;">Subtotal (Products):</td>
        <td style="width: 20%; text-align: right; font-weight: bold; color: #111827; padding-right: 6px;">{{ floatval($bill->items->filter(function($item) { return strtolower($item->item_type) == 'product'; })->sum('total')) ?: '0' }}</td>
    </tr>
    <tr>
        <td style="width: 50%;"></td>
        <td style="width: 30%; text-align: right; color: #4b5563;">Discount:</td>
        <td style="width: 20%; text-align: right; font-weight: bold; color: #111827; padding-right: 6px;">{{ $bill->discount > 0 ? '-' . floatval($bill->discount) : '0' }}</td>
    </tr>
    <tr>
        <td style="width: 50%;"></td>
        <td style="width: 30%; text-align: right; color: #4b5563;">Tax:</td>
        <td style="width: 20%; text-align: right; font-weight: bold; color: #111827; padding-right: 6px;">{{ floatval($bill->tax) ?: '0' }}</td>
    </tr>
    <tr>
        <td style="width: 50%;"></td>
        <td style="width: 30%; text-align: right; font-weight: bold; color: #111827; padding-top: 6px;">Total Amount:</td>
        <td style="width: 20%; text-align: right; font-weight: bold; color: #111827; padding-top: 6px; padding-right: 6px;">{{ floatval($bill->total) }}</td>
    </tr>
</table>

<br><br>

<!-- Notes -->
<table cellpadding="0" cellspacing="0" style="width: 100%; font-family: helvetica; font-size: 10px;">
    <tr>
        <td style="width: 100%;">
            <strong style="color: #111827;">Notes:</strong><br>
            <span style="color: #4b5563; line-height: 1.4;">
                {{ $bill->notes ?? 'Thank you for choosing ' . ($bill->workshop->name ?? 'Suhaim Soft Work Shop') . '. Please settle the invoice within 7 days.' }}
            </span>
        </td>
    </tr>
</table>

<br><br><br><br>

<!-- Footer -->
<table cellpadding="0" cellspacing="0" style="width: 100%; font-family: helvetica;">
    <tr>
        <td style="width: 100%; text-align: center; font-size: 9px; color: #9ca3af; font-style: italic;">
            Generated by {{ $bill->workshop->name ?? 'Suhaim Soft Work Shop' }}
        </td>
    </tr>
</table>
