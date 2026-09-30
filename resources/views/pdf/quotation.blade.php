<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quotation {{ $quotation->quotation_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; }
        .company-name { font-size: 20px; font-weight: bold; color: #1a56db; }
        .document-title { font-size: 24px; font-weight: bold; text-align: right; color: #1a56db; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th { background-color: #1a56db; color: white; padding: 8px; text-align: left; font-size: 11px; }
        .items-table td { padding: 8px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
        .items-table tr:nth-child(even) { background-color: #f9fafb; }
        .totals { float: right; width: 300px; }
        .totals table { width: 100%; }
        .totals td { padding: 5px 8px; }
        .totals .total-row { font-weight: bold; font-size: 14px; border-top: 2px solid #1a56db; }
        .text-right { text-align: right; }
        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 10px; }
    </style>
</head>
<body>
    <table style="width:100%; margin-bottom: 30px;">
        <tr>
            <td style="width:60%">
                <div class="company-name">{{ $quotation->company->name ?? 'Company' }}</div>
                <div>{{ $quotation->company->address ?? '' }}</div>
                <div>{{ $quotation->company->city ?? '' }}, {{ $quotation->company->state ?? '' }} {{ $quotation->company->postal_code ?? '' }}</div>
                <div>Phone: {{ $quotation->company->phone ?? '' }}</div>
                <div>Email: {{ $quotation->company->email ?? '' }}</div>
            </td>
            <td style="width:40%; text-align: right;">
                <div class="document-title">QUOTATION</div>
                <div style="font-size: 14px; margin-top: 5px;">#{{ $quotation->quotation_number }}</div>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td style="width: 50%;">
                <strong>To:</strong><br>
                {{ $quotation->customer->name ?? '' }}<br>
                {{ $quotation->customer->address ?? '' }}<br>
                {{ $quotation->customer->city ?? '' }}, {{ $quotation->customer->state ?? '' }}<br>
                {{ $quotation->customer->email ?? '' }}
            </td>
            <td style="width: 50%; text-align: right;">
                <strong>Quotation Date:</strong> {{ $quotation->quotation_date }}<br>
                <strong>Valid Until:</strong> {{ $quotation->valid_until }}<br>
                <strong>Status:</strong> {{ ucfirst($quotation->status) }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Description</th>
                <th class="text-right">Qty</th>
                <th>UoM</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Tax</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name ?? '' }}</td>
                <td>{{ $item->description ?? '-' }}</td>
                <td class="text-right">{{ number_format($item->quantity, 2) }}</td>
                <td>{{ $item->unitOfMeasure->name ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td class="text-right">{{ $item->tax_percent ?? 0 }}%</td>
                <td class="text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr><td>Subtotal</td><td class="text-right">Rp {{ number_format($quotation->subtotal, 0, ',', '.') }}</td></tr>
            <tr><td>Tax</td><td class="text-right">Rp {{ number_format($quotation->tax_amount, 0, ',', '.') }}</td></tr>
            <tr><td>Discount</td><td class="text-right">Rp {{ number_format($quotation->discount_amount, 0, ',', '.') }}</td></tr>
            <tr class="total-row"><td>Total</td><td class="text-right">Rp {{ number_format($quotation->total_amount, 0, ',', '.') }}</td></tr>
        </table>
    </div>

    <div style="clear:both;"></div>

    @if($quotation->terms_conditions)
    <div style="margin-top: 30px;">
        <strong>Terms & Conditions:</strong><br>
        {!! nl2br(e($quotation->terms_conditions)) !!}
    </div>
    @endif

    @if($quotation->notes)
    <div style="margin-top: 20px;">
        <strong>Notes:</strong><br>
        {{ $quotation->notes }}
    </div>
    @endif

    <div class="footer">
        <p>This quotation is valid until {{ $quotation->valid_until }}.</p>
        <p>{{ $quotation->company->name ?? '' }} | {{ $quotation->company->website ?? '' }}</p>
    </div>
</body>
</html>
