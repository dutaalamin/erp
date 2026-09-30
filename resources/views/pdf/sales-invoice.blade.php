<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; }
        .header { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .company-info { margin-bottom: 20px; }
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
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .badge-paid { background: #d1fae5; color: #065f46; }
        .badge-unpaid { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <table style="width:100%; margin-bottom: 30px;">
        <tr>
            <td style="width:60%">
                <div class="company-name">{{ $invoice->company->name ?? 'Company' }}</div>
                <div>{{ $invoice->company->address ?? '' }}</div>
                <div>{{ $invoice->company->city ?? '' }}, {{ $invoice->company->state ?? '' }} {{ $invoice->company->postal_code ?? '' }}</div>
                <div>Phone: {{ $invoice->company->phone ?? '' }}</div>
                <div>Email: {{ $invoice->company->email ?? '' }}</div>
            </td>
            <td style="width:40%; text-align: right;">
                <div class="document-title">INVOICE</div>
                <div style="font-size: 14px; margin-top: 5px;">#{{ $invoice->invoice_number }}</div>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td style="width: 50%;">
                <strong>Bill To:</strong><br>
                {{ $invoice->customer->name ?? '' }}<br>
                {{ $invoice->customer->address ?? '' }}<br>
                {{ $invoice->customer->city ?? '' }}, {{ $invoice->customer->state ?? '' }}<br>
                {{ $invoice->customer->email ?? '' }}
            </td>
            <td style="width: 50%; text-align: right;">
                <strong>Invoice Date:</strong> {{ $invoice->invoice_date }}<br>
                <strong>Due Date:</strong> {{ $invoice->due_date }}<br>
                <strong>Status:</strong> {{ ucfirst($invoice->status) }}
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
                <th class="text-right">Unit Price</th>
                <th class="text-right">Tax</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name ?? '' }}</td>
                <td>{{ $item->description ?? '-' }}</td>
                <td class="text-right">{{ number_format($item->quantity, 2) }}</td>
                <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td class="text-right">{{ $item->tax_percent }}%</td>
                <td class="text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr><td>Subtotal</td><td class="text-right">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td></tr>
            <tr><td>Tax</td><td class="text-right">Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</td></tr>
            <tr><td>Discount</td><td class="text-right">Rp {{ number_format($invoice->discount_amount, 0, ',', '.') }}</td></tr>
            <tr class="total-row"><td>Total</td><td class="text-right">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td></tr>
            <tr><td>Paid</td><td class="text-right">Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</td></tr>
            <tr><td><strong>Balance Due</strong></td><td class="text-right"><strong>Rp {{ number_format($invoice->total_amount - $invoice->paid_amount, 0, ',', '.') }}</strong></td></tr>
        </table>
    </div>

    <div style="clear:both;"></div>

    @if($invoice->notes)
    <div style="margin-top: 30px;">
        <strong>Notes:</strong><br>
        {{ $invoice->notes }}
    </div>
    @endif

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>{{ $invoice->company->name ?? '' }} | {{ $invoice->company->website ?? '' }}</p>
    </div>
</body>
</html>
