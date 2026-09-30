<?php

namespace App\Http\Controllers;

use App\Models\SalesInvoice;
use App\Models\PurchaseOrder;
use App\Models\DeliveryOrder;
use App\Models\Quotation;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function salesInvoice(SalesInvoice $salesInvoice)
    {
        $salesInvoice->load(['company', 'customer', 'items.product']);
        $pdf = Pdf::loadView('pdf.sales-invoice', ['invoice' => $salesInvoice]);
        return $pdf->download("Invoice-{$salesInvoice->invoice_number}.pdf");
    }

    public function purchaseOrder(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['company', 'supplier', 'items.product', 'items.unitOfMeasure']);
        $pdf = Pdf::loadView('pdf.purchase-order', ['purchaseOrder' => $purchaseOrder]);
        return $pdf->download("PO-{$purchaseOrder->po_number}.pdf");
    }

    public function deliveryOrder(DeliveryOrder $deliveryOrder)
    {
        $deliveryOrder->load(['company', 'salesOrder.customer', 'warehouse', 'items.product']);
        $pdf = Pdf::loadView('pdf.delivery-order', ['deliveryOrder' => $deliveryOrder]);
        return $pdf->download("DO-{$deliveryOrder->do_number}.pdf");
    }

    public function quotation(Quotation $quotation)
    {
        $quotation->load(['company', 'customer', 'items.product', 'items.unitOfMeasure']);
        $pdf = Pdf::loadView('pdf.quotation', ['quotation' => $quotation]);
        return $pdf->download("Quotation-{$quotation->quotation_number}.pdf");
    }
}
