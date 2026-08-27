<?php
// Run: php scratch/inspect_quotation.php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$ptCount = App\Models\PaymentType::count();
$pcCount = App\Models\PaymentCurrency::count();
$clientCount = App\Models\Client::count();

echo "=== TABLE COUNTS ===\n";
echo "payment_types:      $ptCount\n";
echo "payment_currencies: $pcCount\n";
echo "clients:            $clientCount\n";

echo "\n=== PAYMENT TYPES ===\n";
App\Models\PaymentType::all()->each(fn($r) => print("  id={$r->id} name={$r->name}\n"));

echo "\n=== PAYMENT CURRENCIES ===\n";
App\Models\PaymentCurrency::all()->each(fn($r) => print("  id={$r->id} name={$r->name}\n"));

echo "\n=== QUOTATION #1 RAW ===\n";
$q = App\Models\Quotation::find(1);
if (!$q) { echo "Quotation 1 NOT FOUND\n"; exit; }
echo "  id:               {$q->id}\n";
echo "  payment_type col: " . var_export($q->payment_type, true) . "\n";
echo "  payment_currency: " . var_export($q->payment_currency, true) . "\n";
echo "  client_id:        " . var_export($q->client_id, true) . "\n";
echo "  user_id:          " . var_export($q->user_id, true) . "\n";
echo "  status:           {$q->status}\n";

echo "\n=== RELATIONSHIP RESOLUTION ===\n";
$pt = $q->paymentType;
echo "  paymentType:      " . ($pt ? "id={$pt->id} name={$pt->name}" : "NULL") . "\n";
$pc = $q->paymentCurrency;
echo "  paymentCurrency:  " . ($pc ? "id={$pc->id} name={$pc->name}" : "NULL") . "\n";
$cl = $q->client;
echo "  client:           " . ($cl ? "id={$cl->id} name={$cl->name}" : "NULL") . "\n";
