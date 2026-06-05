<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .header {
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .title {
        font-size: 24px;
        font-weight: bold;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    .info-table td {
        vertical-align: top;
        padding: 5px;
    }

    .items-table {
        margin-top: 20px;
    }

    .items-table th {
        background: #f2f2f2;
        border: 1px solid #ccc;
        padding: 8px;
        text-align: left;
    }

    .items-table td {
        border: 1px solid #ccc;
        padding: 8px;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .totals {
        margin-top: 20px;
    }

    .totals td {
        padding: 6px;
    }

    .grand-total {
        font-size: 18px;
        font-weight: bold;
        background: #f2f2f2;
    }

    .footer {
        margin-top: 40px;
        text-align: center;
        font-size: 11px;
        color: #666;
    }

    .status-paid {
        color: green;
        font-weight: bold;
    }

    .status-pending {
        color: red;
        font-weight: bold;
    }
</style>

@php
    $workshopName = $bill->workshop->name ?? 'Suhaim Soft Workshop';
    $address = $bill->workshop->address ?? '';
    $phone = $bill->workshop->phone ?? '';
    $email = $bill->workshop->email ?? '';

    $subTotal = $bill->items->sum('total');
    $discount = $bill->discount ?? 0;
    $grandTotal = $subTotal - $discount;

    $amountPaid = $bill->amount_paid ?? 0;
    $balance = $grandTotal - $amountPaid;
@endphp

<div class="header">
    <table>
        <tr>
            <td width="60%">
                @if(!empty($bill->workshop->logo))
                    <img src="{{ public_path('storage/'.$bill->workshop->logo) }}" width="80">
                @endif

                <div class="title">
                    {{ $workshopName }}
                </div>

                <div>{{ $address }}</div>
                <div>{{ $phone }}</div>
                <div>{{ $email }}</div>
            </td>

            <td width="40%" class="text-right">
                <h2>INVOICE</h2>
                <strong>No:</strong>
                {{ $bill->bill_number }}
                <br>
                <strong>Date:</strong>
                {{ date('d-m-Y', strtotime($bill->bill_date)) }}
            </td>
        </tr>
    </table>
</div>

<table class="info-table">
    <tr>
        <td width="50%">
            <strong>Bill To</strong><br>
            {{ $bill->customer->name ?? 'Customer' }}<br>
            {{ $bill->customer->phone ?? '' }}<br>
            {{ $bill->customer->address ?? '' }}
        </td>

        <td width="50%">
            @if($bill->vehicle)
                <strong>Vehicle Details</strong><br>
                Plate: {{ $bill->vehicle->plate_number ?? '' }}<br>
                Make: {{ $bill->vehicle->make ?? '' }}<br>
                Model: {{ $bill->vehicle->model ?? '' }}
            @endif
        </td>
    </tr>
</table>

<table class="items-table">
    <thead>
        <tr>
            <th width="5%" class="text-center">#</th>
            <th width="45%">Description</th>
            <th width="10%" class="text-center">Qty</th>
            <th width="20%" class="text-right">Rate</th>
            <th width="20%" class="text-right">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bill->items as $index => $item)
            <tr>
                <td class="text-center">
                    {{ $index + 1 }}
                </td>
                <td>
                    {{ $item->item_name }}
                </td>
                <td class="text-center">
                    {{ $item->quantity }}
                </td>
                <td class="text-right">
                    {{ number_format($item->unit_price, 2) }}
                </td>
                <td class="text-right">
                    {{ number_format($item->total, 2) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>

<table class="totals">
    <tr>
        <td width="60%"></td>
        <td width="20%">
            Subtotal
        </td>
        <td width="20%" class="text-right">
            ₹{{ number_format($subTotal, 2) }}
        </td>
    </tr>

    <tr>
        <td></td>
        <td>
            Discount
        </td>
        <td class="text-right">
            ₹{{ number_format($discount, 2) }}
        </td>
    </tr>

    <tr>
        <td></td>
        <td>
            Paid
        </td>
        <td class="text-right">
            ₹{{ number_format($amountPaid, 2) }}
        </td>
    </tr>

    <tr class="grand-total">
        <td></td>
        <td>
            Total
        </td>
        <td class="text-right">
            ₹{{ number_format($grandTotal, 2) }}
        </td>
    </tr>

    @if($balance > 0)
    <tr>
        <td></td>
        <td>
            Balance
        </td>
        <td class="text-right">
            ₹{{ number_format($balance, 2) }}
        </td>
    </tr>
    @endif
</table>

<br>

<div>
    Status: 
    @if($bill->payment_status == 'paid')
        <span class="status-paid">PAID</span>
    @else
        <span class="status-pending">PENDING</span>
    @endif
</div>

<table style="width: 100%; margin-top: 50px;">
    <tr>
        <td style="text-align: left; vertical-align: bottom; width: 60%;">
            <div style="font-size: 11px; color: #666;">
                This is a computer generated invoice - no signature required.
            </div>
        </td>
        <td style="text-align: right; vertical-align: bottom; width: 40%;">
            <div style="border-top: 1px solid #000; width: 180px; display: inline-block; margin-bottom: 5px;"></div>
            <br>
            <strong>Authorized Signatory</strong>
        </td>
    </tr>
</table>

<div class="footer">
    <strong>
        Thank You For Choosing {{ $workshopName }}
    </strong>
</div>