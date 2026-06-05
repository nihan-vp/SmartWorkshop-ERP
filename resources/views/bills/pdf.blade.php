@php
    $workshopName = trim($bill->workshop->name ?? 'Suhaim Soft Work Shop');
    $address = trim($bill->workshop->address ?? "123 Workshop Avenue, City");
    $phone = trim($bill->workshop->phone ?? '+91 9876543210');
    $email = trim($bill->workshop->email ?? 'info@suhaimsoft.com');

    // Clean up address formatting
    $address = preg_replace('/^(o)?zon\s+detailing\s*(&|and)?\s*(car\s*wash)?/i', '', $address);
    $address = ltrim($address, " \t\n\r\0\x0B,/-");

    if ($phone) {
        $address = str_ireplace($phone, '', $address);
    }
    if ($email) {
        $address = str_ireplace($email, '', $address);
    }

    $address = preg_replace('/[\r\n]+/', "\n", $address);
    $address = preg_replace('/,\s*,/', ',', $address);
    $address = trim($address, " \t\n\r\0\x0B,/-");
    
    $isPos = in_array(strtoupper($size ?? 'A4'), ['80MM', '58MM']);
    
    $primaryColor = '#1e3a8a'; // Deep professional blue
    $accentColor = '#f3f4f6';
    $textColor = '#1f2937';
    $mutedColor = '#6b7280';
@endphp

@if($isPos)
    <!-- POS / Thermal Receipt Layout -->
    <div style="font-family: helvetica, sans-serif; font-size: {{ strtoupper($size ?? 'A4') === '58MM' ? '7px' : '9px' }}; color: #000; text-align: center; width: 100%;">
        @if(isset($bill->workshop->logo) && file_exists(public_path('storage/' . $bill->workshop->logo)))
            <img src="{{ public_path('storage/' . $bill->workshop->logo) }}" alt="Logo" style="height: 40px; margin-bottom: 5px;"><br>
        @endif
        <strong style="font-size: {{ strtoupper($size ?? 'A4') === '58MM' ? '10px' : '12px' }};">{{ strtoupper($workshopName) }}</strong><br>
        {!! nl2br(e($address)) !!}<br>
        @if($phone) Ph: {{ $phone }}<br> @endif
        <hr style="border-top: 1px dashed #000; margin: 5px 0;">
        <div style="text-align: left;">
            <strong>INVOICE:</strong> {{ $bill->bill_number }}<br>
            <strong>DATE:</strong> {{ $bill->bill_date ? (is_string($bill->bill_date) ? \Carbon\Carbon::parse($bill->bill_date)->format('d-m-Y H:i') : $bill->bill_date->format('d-m-Y H:i')) : date('d-m-Y H:i') }}<br>
            <strong>CUSTOMER:</strong> {{ strtoupper($bill->customer->name ?? 'N/A') }}<br>
            @if($bill->vehicle)
            <strong>VEHICLE:</strong> {{ $bill->vehicle->plate_number }}
            @endif
        </div>
        <hr style="border-top: 1px dashed #000; margin: 5px 0;">
        <table width="100%" cellpadding="1" style="font-size: {{ strtoupper($size ?? 'A4') === '58MM' ? '7px' : '9px' }}; text-align: left;">
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
        <table width="100%" cellpadding="1" style="font-size: {{ strtoupper($size ?? 'A4') === '58MM' ? '7px' : '9px' }}; text-align: right;">
            @php 
                $servicesTotal = $bill->items->filter(function($item) { return strtolower($item->item_type) == 'service'; })->sum('total');
                $productsTotal = $bill->items->filter(function($item) { return strtolower($item->item_type) == 'product'; })->sum('total');
            @endphp
            @if($servicesTotal > 0)
            <tr>
                <td width="60%">Services:</td>
                <td width="40%">{{ number_format($servicesTotal, 2) }}</td>
            </tr>
            @endif
            @if($productsTotal > 0)
            <tr>
                <td width="60%">Products:</td>
                <td width="40%">{{ number_format($productsTotal, 2) }}</td>
            </tr>
            @endif
            @if($bill->discount > 0)
            <tr>
                <td width="60%">Discount:</td>
                <td width="40%">-{{ number_format($bill->discount, 2) }}</td>
            </tr>
            @endif
            @if($bill->tax > 0)
            <tr>
                <td width="60%">Tax:</td>
                <td width="40%">{{ number_format($bill->tax, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td width="60%"><strong>TOTAL:</strong></td>
                <td width="40%"><strong>{{ number_format($bill->total, 2) }}</strong></td>
            </tr>
            <tr>
                <td width="60%">Paid:</td>
                <td width="40%">{{ number_format($bill->amount_paid, 2) }}</td>
            </tr>
            <tr>
                <td width="60%">Status:</td>
                <td width="40%"><strong>{{ strtoupper($bill->payment_status) }}</strong></td>
            </tr>
        </table>
        <hr style="border-top: 1px dashed #000; margin: 5px 0;">
        <div style="text-align: center; margin-top: 5px;">
            Thank you for your business!<br>
            Please visit again.
        </div>
    </div>

@else
    <!-- Premium A4/A5/LETTER Layout -->
    <table cellpadding="0" cellspacing="0" style="width: 100%; font-family: helvetica;">
        <tr>
            <td style="width: 50%; vertical-align: middle;">
                @if(isset($bill->workshop->logo) && file_exists(public_path('storage/' . $bill->workshop->logo)))
                    <img src="{{ public_path('storage/' . $bill->workshop->logo) }}" alt="Logo" style="height: 60px; max-width: 180px;"><br>
                @else
                    <h2 style="color: {{ $primaryColor }}; margin: 0; padding: 0;">{{ strtoupper($workshopName) }}</h2>
                @endif
            </td>
            <td style="width: 50%; text-align: right; vertical-align: middle;">
                <span style="font-size: 32px; font-weight: bold; color: {{ $primaryColor }};">INVOICE</span><br>
                <span style="font-size: 11px; color: {{ $mutedColor }};"># {{ $bill->bill_number }}</span>
            </td>
        </tr>
    </table>
    
    <br>
    
    <!-- Business Info & Invoice Meta -->
    <table cellpadding="0" cellspacing="0" style="width: 100%; font-family: helvetica;">
        <tr>
            <td style="width: 50%; vertical-align: top; border-right: 2px solid {{ $accentColor }}; padding-right: 10px;">
                <h4 style="color: {{ $textColor }}; margin: 0 0 5px 0;">FROM</h4>
                <strong style="color: {{ $primaryColor }}; font-size: 13px;">{{ $workshopName }}</strong><br>
                <span style="font-size: 11px; color: {{ $mutedColor }}; line-height: 1.5;">
                    {!! nl2br(e($address)) !!}<br>
                    @if($phone)<strong>Phone:</strong> {{ $phone }}<br>@endif
                    @if($email)<strong>Email:</strong> {{ $email }}@endif
                </span>
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 20px;">
                <h4 style="color: {{ $textColor }}; margin: 0 0 5px 0;">INVOICE DETAILS</h4>
                <table cellpadding="2" cellspacing="0" style="width: 100%; font-size: 11px; color: {{ $mutedColor }};">
                    <tr>
                        <td width="40%"><strong>Invoice Date:</strong></td>
                        <td width="60%" style="color: {{ $textColor }};">{{ $bill->bill_date ? (is_string($bill->bill_date) ? \Carbon\Carbon::parse($bill->bill_date)->format('d M Y') : $bill->bill_date->format('d M Y')) : date('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td width="40%"><strong>Payment Status:</strong></td>
                        <td width="60%">
                            @if($bill->payment_status === 'paid')
                                <strong style="color: #059669;">PAID</strong>
                            @elseif($bill->payment_status === 'partial')
                                <strong style="color: #d97706;">PARTIAL</strong>
                            @else
                                <strong style="color: #dc2626;">DUE</strong>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td width="40%"><strong>Method:</strong></td>
                        <td width="60%" style="color: {{ $textColor }};">{{ strtoupper($bill->payment_method ?? 'N/A') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <br><br>

    <!-- Customer & Vehicle Info -->
    <table cellpadding="10" cellspacing="0" style="width: 100%; font-family: helvetica; background-color: {{ $accentColor }}; border-radius: 5px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <h4 style="color: {{ $textColor }}; margin: 0 0 5px 0;">BILLED TO</h4>
                <strong style="color: {{ $primaryColor }}; font-size: 12px;">{{ strtoupper($bill->customer->name ?? 'N/A') }}</strong><br>
                <span style="font-size: 11px; color: {{ $mutedColor }}; line-height: 1.4;">
                    @if($bill->customer->address){{ $bill->customer->address }}<br>@endif
                    @if($bill->customer->phone)<strong>Phone:</strong> {{ $bill->customer->phone }}<br>@endif
                </span>
            </td>
            <td style="width: 50%; vertical-align: top;">
                <h4 style="color: {{ $textColor }}; margin: 0 0 5px 0;">VEHICLE INFO</h4>
                <span style="font-size: 11px; color: {{ $mutedColor }}; line-height: 1.4;">
                    @if($bill->vehicle)
                        <strong style="color: {{ $textColor }};">Make & Model:</strong> {{ $bill->vehicle->make }} {{ $bill->vehicle->model }}<br>
                        <strong style="color: {{ $textColor }};">Plate Number:</strong> <span style="font-size: 13px; font-weight: bold; color: {{ $primaryColor }};">{{ $bill->vehicle->plate_number }}</span>
                    @else
                        N/A
                    @endif
                </span>
            </td>
        </tr>
    </table>

    <br><br>

    <!-- Items Table -->
    <table cellpadding="8" cellspacing="0" style="width: 100%; font-family: helvetica; border-collapse: collapse;">
        <thead>
            <tr style="background-color: {{ $primaryColor }}; color: #ffffff;">
                <th style="width: 5%; text-align: center; font-size: 11px; font-weight: bold;">#</th>
                <th style="width: 45%; text-align: left; font-size: 11px; font-weight: bold;">Description</th>
                <th style="width: 15%; text-align: center; font-size: 11px; font-weight: bold;">Qty</th>
                <th style="width: 15%; text-align: right; font-size: 11px; font-weight: bold;">Price</th>
                <th style="width: 20%; text-align: right; font-size: 11px; font-weight: bold;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bill->items as $index => $item)
            @php $bgColor = $index % 2 == 0 ? '#ffffff' : '#f9fafb'; @endphp
            <tr style="background-color: {{ $bgColor }}; border-bottom: 1px solid #e5e7eb;">
                <td style="width: 5%; text-align: center; font-size: 11px; color: {{ $mutedColor }}; border-bottom: 1px solid #e5e7eb;">{{ $index + 1 }}</td>
                <td style="width: 45%; text-align: left; font-size: 11px; font-weight: bold; color: {{ $textColor }}; border-bottom: 1px solid #e5e7eb;">
                    {{ strtoupper($item->item_name) }}
                    <br><span style="font-size: 9px; font-weight: normal; color: {{ $mutedColor }};">{{ ucfirst($item->item_type) }}</span>
                </td>
                <td style="width: 15%; text-align: center; font-size: 11px; color: {{ $mutedColor }}; border-bottom: 1px solid #e5e7eb;">{{ floatval($item->quantity) }}</td>
                <td style="width: 15%; text-align: right; font-size: 11px; color: {{ $mutedColor }}; border-bottom: 1px solid #e5e7eb;">{{ number_format($item->unit_price, 2) }}</td>
                <td style="width: 20%; text-align: right; font-size: 11px; font-weight: bold; color: {{ $textColor }}; border-bottom: 1px solid #e5e7eb;">{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <br><br>

    <!-- Totals Table -->
    <table cellpadding="6" cellspacing="0" style="width: 100%; font-family: helvetica;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                @if(!empty($bill->notes))
                    <div style="background-color: #fef3c7; border-left: 3px solid #f59e0b; padding: 10px; font-size: 10px; color: #92400e;">
                        <strong>Notes:</strong><br>
                        {{ $bill->notes }}
                    </div>
                @else
                    <div style="padding: 10px; font-size: 11px; color: {{ $mutedColor }}; border-left: 3px solid {{ $primaryColor }}; background-color: {{ $accentColor }};">
                        <strong>Thank you for choosing {{ $workshopName }}!</strong><br>
                        We appreciate your business. Please reach out if you have any questions.
                    </div>
                @endif
            </td>
            <td style="width: 50%;">
                <table cellpadding="4" cellspacing="0" style="width: 100%; font-size: 11px;">
                    @php 
                        $servicesTotal = $bill->items->filter(function($item) { return strtolower($item->item_type) == 'service'; })->sum('total');
                        $productsTotal = $bill->items->filter(function($item) { return strtolower($item->item_type) == 'product'; })->sum('total');
                    @endphp
                    @if($servicesTotal > 0)
                    <tr>
                        <td style="width: 60%; text-align: right; color: {{ $mutedColor }};">Services Subtotal:</td>
                        <td style="width: 40%; text-align: right; font-weight: bold; color: {{ $textColor }};">{{ number_format($servicesTotal, 2) }}</td>
                    </tr>
                    @endif
                    @if($productsTotal > 0)
                    <tr>
                        <td style="width: 60%; text-align: right; color: {{ $mutedColor }};">Products Subtotal:</td>
                        <td style="width: 40%; text-align: right; font-weight: bold; color: {{ $textColor }};">{{ number_format($productsTotal, 2) }}</td>
                    </tr>
                    @endif
                    @if($bill->discount > 0)
                    <tr>
                        <td style="width: 60%; text-align: right; color: #dc2626;">Discount:</td>
                        <td style="width: 40%; text-align: right; font-weight: bold; color: #dc2626;">-{{ number_format($bill->discount, 2) }}</td>
                    </tr>
                    @endif
                    @if($bill->tax > 0)
                    <tr>
                        <td style="width: 60%; text-align: right; color: {{ $mutedColor }};">Tax:</td>
                        <td style="width: 40%; text-align: right; font-weight: bold; color: {{ $textColor }};">{{ number_format($bill->tax, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="2" style="border-top: 1px solid {{ $accentColor }};"></td>
                    </tr>
                    <tr>
                        <td style="width: 60%; text-align: right; font-weight: bold; font-size: 14px; color: {{ $primaryColor }};">TOTAL AMOUNT:</td>
                        <td style="width: 40%; text-align: right; font-weight: bold; font-size: 14px; color: {{ $primaryColor }};">{{ number_format($bill->total, 2) }}</td>
                    </tr>
                    @if($bill->amount_paid > 0)
                    <tr>
                        <td style="width: 60%; text-align: right; color: #059669; font-weight: bold;">Amount Paid:</td>
                        <td style="width: 40%; text-align: right; font-weight: bold; color: #059669;">{{ number_format($bill->amount_paid, 2) }}</td>
                    </tr>
                    @endif
                    @if($bill->total - $bill->amount_paid > 0)
                    <tr>
                        <td style="width: 60%; text-align: right; color: #dc2626; font-weight: bold;">Balance Due:</td>
                        <td style="width: 40%; text-align: right; font-weight: bold; color: #dc2626;">{{ number_format($bill->total - $bill->amount_paid, 2) }}</td>
                    </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <br><br><br>

    <!-- Footer Signatures -->
    <table cellpadding="0" cellspacing="0" style="width: 100%; font-family: helvetica;">
        <tr>
            <td style="width: 50%; text-align: center;">
                <hr style="width: 60%; border: 0; border-top: 1px solid {{ $mutedColor }}; margin: 0 auto 5px auto;">
                <span style="font-size: 10px; color: {{ $mutedColor }};">Customer Signature</span>
            </td>
            <td style="width: 50%; text-align: center;">
                <hr style="width: 60%; border: 0; border-top: 1px solid {{ $mutedColor }}; margin: 0 auto 5px auto;">
                <span style="font-size: 10px; color: {{ $mutedColor }};">Authorized Signatory</span>
            </td>
        </tr>
    </table>

@endif
