<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delivery Order {{ $deliveryOrder->do_number }}</title>
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
        .text-right { text-align: right; }
        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 10px; }
        .signature-area { margin-top: 60px; }
        .signature-area table { width: 100%; }
        .signature-area td { text-align: center; padding-top: 60px; border-top: 1px solid #333; width: 30%; }
    </style>
</head>
<body>
    <table style="width:100%; margin-bottom: 30px;">
        <tr>
            <td style="width:60%">
                <div class="company-name">{{ $deliveryOrder->company->name ?? 'Company' }}</div>
                <div>{{ $deliveryOrder->company->address ?? '' }}</div>
                <div>{{ $deliveryOrder->company->city ?? '' }}, {{ $deliveryOrder->company->state ?? '' }} {{ $deliveryOrder->company->postal_code ?? '' }}</div>
                <div>Phone: {{ $deliveryOrder->company->phone ?? '' }}</div>
            </td>
            <td style="width:40%; text-align: right;">
                <div class="document-title">DELIVERY ORDER</div>
                <div style="font-size: 14px; margin-top: 5px;">#{{ $deliveryOrder->do_number }}</div>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td style="width: 50%;">
                <strong>Ship To:</strong><br>
                {{ $deliveryOrder->salesOrder->customer->name ?? '' }}<br>
                {{ $deliveryOrder->shipping_address ?? $deliveryOrder->salesOrder->customer->address ?? '' }}<br>
                {{ $deliveryOrder->salesOrder->customer->city ?? '' }}, {{ $deliveryOrder->salesOrder->customer->state ?? '' }}<br>
                {{ $deliveryOrder->salesOrder->customer->phone ?? '' }}
            </td>
            <td style="width: 50%; text-align: right;">
                <strong>DO Date:</strong> {{ $deliveryOrder->do_date }}<br>
                <strong>SO Reference:</strong> {{ $deliveryOrder->salesOrder->so_number ?? '-' }}<br>
                <strong>Warehouse:</strong> {{ $deliveryOrder->warehouse->name ?? '-' }}<br>
                <strong>Status:</strong> {{ ucfirst($deliveryOrder->status) }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Description</th>
                <th class="text-right">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deliveryOrder->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name ?? '' }}</td>
                <td>{{ $item->description ?? '-' }}</td>
                <td class="text-right">{{ number_format($item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($deliveryOrder->notes)
    <div style="margin-top: 30px;">
        <strong>Notes:</strong><br>
        {{ $deliveryOrder->notes }}
    </div>
    @endif

    <div class="signature-area">
        <table>
            <tr>
                <td style="border: none; padding-bottom: 60px;">Prepared By</td>
                <td style="border: none; padding-bottom: 60px;"></td>
                <td style="border: none; padding-bottom: 60px;">Received By</td>
            </tr>
            <tr>
                <td>(_________________)</td>
                <td></td>
                <td>(_________________)</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>{{ $deliveryOrder->company->name ?? '' }} | {{ $deliveryOrder->company->website ?? '' }}</p>
    </div>
</body>
</html>
