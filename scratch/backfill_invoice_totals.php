<?php

use App\Models\Invoice;

$invoices = Invoice::with('items')
    ->where(function ($q) {
        $q->whereNull('total')->orWhere('total', 0);
    })
    ->get();

$fixed = 0;
foreach ($invoices as $inv) {
    if ($inv->items->isEmpty()) continue;

    $subtotal = $inv->items->sum(fn($i) => $i->quantity * $i->unit_price);
    $vatAmount = $subtotal * (($inv->vat ?? 0) / 100);
    $discount  = floatval($inv->discount ?? 0);
    $total     = $subtotal + $vatAmount - $discount;
    $amountPaid = floatval($inv->amount_paid ?? 0);

    $inv->update([
        'total'              => $total,
        'amount_outstanding' => max(0, $total - $amountPaid),
    ]);

    echo "Fixed Invoice #{$inv->id} (number: {$inv->invoice_number}) → total = {$total}" . PHP_EOL;
    $fixed++;
}

echo PHP_EOL . "Done. Fixed {$fixed} invoice(s)." . PHP_EOL;
