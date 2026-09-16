<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Invoice;

$invoices = Invoice::where('payment_status', 1)->get();
foreach ($invoices as $inv) {
    if ($inv->amount_outstanding > 0 || $inv->amount_paid < $inv->total) {
        $inv->amount_paid = $inv->total;
        $inv->amount_outstanding = 0;
        $inv->save();
        echo "Fixed invoice #" . $inv->id . " (total=" . $inv->total . ")\n";
    }
}
echo "Done.\n";
