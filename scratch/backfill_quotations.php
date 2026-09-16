<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\QuotationItem;

$invoices = Invoice::with('items')->get();
foreach ($invoices as $invoice) {
    // 1. Check if invoice_number is empty. If so, generate it and save.
    if (empty($invoice->invoice_number)) {
        $year = date('Y', strtotime($invoice->created_at ?? now()));
        // e.g. INV-2026-000036 (using ID padded to 6 chars to be unique and clear)
        $invoice->invoice_number = 'INV-' . $year . '-' . str_pad($invoice->id, 6, '0', STR_PAD_LEFT);
        $invoice->save();
        echo "Generated invoice_number " . $invoice->invoice_number . " for Invoice #" . $invoice->id . "\n";
    }

    // 2. Check if a quotation already exists for this invoice.
    if ($invoice->quotation_id) {
        $quotation = Quotation::find($invoice->quotation_id);
        if ($quotation && $quotation->quotation_number !== $invoice->invoice_number) {
            $quotation->quotation_number = $invoice->invoice_number;
            $quotation->save();
            echo "Updated quotation_number to match invoice_number (" . $invoice->invoice_number . ") for Quotation #" . $quotation->id . "\n";
        }
        continue; // Already has quotation, updated the number if needed
    }

    // 3. Otherwise, create the quotation for this invoice
    $quotation = Quotation::create([
        'quotation_number' => $invoice->invoice_number,
        'client_id'        => $invoice->client_id,
        'event_id'         => $invoice->event_id,
        'user_id'          => $invoice->user_id,
        'create_date'      => $invoice->create_date,
        'due_date'         => $invoice->due_date,
        'note'             => $invoice->note,
        'payment_type'     => $invoice->payment_type,
        'payment_status'   => $invoice->payment_status,
        'payment_currency' => $invoice->payment_currency,
        'discount'         => $invoice->discount,
        'terms_condition'  => $invoice->terms_condition,
        'vat'              => $invoice->vat,
        'subtotal'         => $invoice->total / (1 + ($invoice->vat / 100)) + $invoice->discount,
        'total'            => $invoice->total,
        'status'           => 'accepted', // Auto-accepted since it's already an invoice
    ]);

    // Create the items
    foreach ($invoice->items as $item) {
        QuotationItem::create([
            'quotation_id' => $quotation->id,
            'quantity'     => $item->quantity,
            'description'  => $item->description,
            'unit_price'   => $item->unit_price,
        ]);
    }

    // Link the invoice to the new quotation
    $invoice->quotation_id = $quotation->id;
    $invoice->save();

    echo "Backfilled Quotation #" . $quotation->id . " for Invoice #" . $invoice->id . "\n";
}

echo "Done backfilling.\n";
