<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simulate the customer user (user_id=2 from prior inspection was customer)
// Check what users exist and their client_id columns
echo "=== USERS ===\n";
App\Models\User::all(['id','name','email','client_id','role_id','is_admin'])->each(function($u){
    echo "  id={$u->id} name={$u->name} client_id=" . var_export($u->client_id, true) . " role_id={$u->role_id}\n";
});

echo "\n=== INVOICES (all) ===\n";
App\Models\Invoice::all(['id','invoice_number','client_id','user_id','quotation_id','amount_outstanding','total'])->each(function($i){
    echo "  id={$i->id} invoice_number={$i->invoice_number} client_id={$i->client_id} user_id={$i->user_id} quotation_id={$i->quotation_id} total={$i->total} outstanding={$i->amount_outstanding}\n";
});

echo "\n=== CLIENTS ===\n";
App\Models\Client::all(['id','name','company_name','user_id'])->each(function($c){
    echo "  id={$c->id} name={$c->name} user_id=" . var_export($c->user_id, true) . "\n";
});

echo "\n=== PAYMENTS (all) ===\n";
App\Models\Payment::all(['id','client_id','invoice_id','quotation_id','amount','status'])->each(function($p){
    echo "  id={$p->id} client_id={$p->client_id} invoice_id={$p->invoice_id} amount={$p->amount} status={$p->status}\n";
});
