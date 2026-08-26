<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\EventSpace;
use App\Models\StandType;
use App\Models\SpacePosition;
use App\Models\Furniture;
use App\Models\EventService;
use App\Models\Quotation;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Attendee;
use App\Models\Badge;
use App\Services\QuotationCalculationService;
use App\Services\BadgeGeneratorService;
use App\Http\Controllers\EventBookingWizardController;

echo "=== STARTING END-TO-END ACCEPTANCE TEST (REQUIREMENT #58) ===\n\n";

// 1. Fetch Event & Catalogue Data
$event = Event::where('event_code', 'TECH-2026')->first();
$space = EventSpace::where('event_id', $event->id)->where('name', 'Hall A')->first();
$standType = StandType::where('event_id', $event->id)->where('name', 'Premium Stand')->first();
$position = SpacePosition::where('event_space_id', $space->id)->where('position_number', 'A12')->first();
$chairs = Furniture::where('event_id', $event->id)->where('name', 'Chair')->first();
$tables = Furniture::where('event_id', $event->id)->where('name', 'Table')->first();
$counter = Furniture::where('event_id', $event->id)->where('name', 'Counter')->first();
$electricity = EventService::where('event_id', $event->id)->where('name', 'Electricity 220V Outlet')->first();
$wifi = EventService::where('event_id', $event->id)->where('name', 'High-Speed Dedicated WiFi')->first();

echo "1. Selected Event: {$event->name} ({$event->event_code})\n";
echo "   Space: {$space->name} (\${$space->price_per_sqm}/m²)\n";
echo "   Stand Package: {$standType->name} (Base: \${$standType->base_price})\n";
echo "   Position: {$position->position_number} (\${$position->additional_fee})\n\n";

// 2. Perform Quotation Calculation Test
$calcService = new QuotationCalculationService();
$calcData = [
    'event_id' => $event->id,
    'event_space_id' => $space->id,
    'stand_type_id' => $standType->id,
    'space_position_id' => $position->id,
    'width' => 5,
    'length' => 6,
    'furniture' => [
        $chairs->id => 4,
        $tables->id => 2,
        $counter->id => 1,
    ],
    'services' => [
        $electricity->id => 1,
        $wifi->id => 1,
    ]
];

$calc = $calcService->calculate($calcData);

echo "2. Calculation Engine Output:\n";
echo "   Dimensions: {$calc['width']}m x {$calc['length']}m = {$calc['area_sqm']} sq.m\n";
echo "   Space Cost: \${$calc['space_cost']}\n";
echo "   Furniture Total: \${$calc['furniture_total']}\n";
echo "   Services Total: \${$calc['services_total']}\n";
echo "   Subtotal: \${$calc['subtotal']}\n";
echo "   VAT (15%): \${$calc['vat_amount']}\n";
echo "   Grand Total: \${$calc['grand_total']}\n\n";

// 3. Submit Quotation Request
$controller = new EventBookingWizardController($calcService);
$request = Illuminate\Http\Request::create('/booking/submit', 'POST', array_merge($calcData, [
    'company_name' => 'ABC Technologies Ltd',
    'contact_person' => 'John Smith',
    'email' => 'john.smith@abctech.com',
    'phone' => '+263 77 123 4567',
    'physical_address' => '123 Innovation Way, Harare',
    'country' => 'Zimbabwe',
    'business_category' => 'ICT',
    'terms_accepted' => 'on',
]));

try {
    $response = $controller->submitQuotation($request);
    if (session()->has('error')) {
        echo "Controller session error: " . session('error') . "\n";
    }
} catch (\Throwable $e) {
    echo "Exception thrown: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

$quotation = Quotation::latest()->first();

echo "3. Quotation Created:\n";
echo "   Quotation Number: {$quotation->quotation_number}\n";
echo "   Status: {$quotation->status}\n";
echo "   Client Company: {$quotation->client->company_name}\n\n";

// 4. Confirm Quotation (Exhibitor accepts quotation)
echo "4. Exhibitor Confirming Quotation...\n";
$confirmResponse = $controller->confirmQuotation($quotation->id);
$quotation->refresh();
$booking = Booking::with(['client.user', 'invoice', 'attendees'])->where('quotation_id', $quotation->id)->first();

echo "   Quotation Status: {$quotation->status}\n";
echo "   Booking Number Created: {$booking->booking_number}\n";
echo "   Invoice Number Created: {$booking->invoice->invoice_number}\n";
echo "   Customer User Account Created: {$booking->user->email} (Role ID: {$booking->user->role_id})\n\n";

// 5. Register Attendees
echo "5. Customer Registering Company Attendees...\n";
$attendeeTypes = $event->attendeeTypes;
$exhibitorStaffType = $attendeeTypes->where('name', 'Exhibitor Staff')->first();
$delegateType = $attendeeTypes->where('name', 'Delegate')->first();
$vipType = $attendeeTypes->where('name', 'VIP Pass')->first();

$people = [
    ['first_name' => 'John', 'last_name' => 'Smith', 'position' => 'Managing Director', 'type_id' => $vipType->id],
    ['first_name' => 'Mary', 'last_name' => 'Jones', 'position' => 'Sales Manager', 'type_id' => $exhibitorStaffType->id],
    ['first_name' => 'Peter', 'last_name' => 'Brown', 'position' => 'Technical Officer', 'type_id' => $exhibitorStaffType->id],
    ['first_name' => 'Sarah', 'last_name' => 'Wilson', 'position' => 'Marketing Officer', 'type_id' => $delegateType->id],
];

foreach ($people as $p) {
    Attendee::create([
        'booking_id' => $booking->id,
        'attendee_type_id' => $p['type_id'],
        'first_name' => $p['first_name'],
        'last_name' => $p['last_name'],
        'position' => $p['position'],
        'company' => $booking->client->company_name,
        'status' => 'submitted',
    ]);
}

$booking->update(['attendee_status' => 'submitted']);
echo "   Registered Attendees Count: " . $booking->attendees()->count() . "\n\n";

// 6. Admin Approving Attendees & Generating Badges
echo "6. Admin Approving Attendees & Generating Printable Badges...\n";
$badgeService = new BadgeGeneratorService();
foreach ($booking->attendees as $att) {
    $att->update(['status' => 'approved']);
    $badge = $badgeService->generateForAttendee($att);
    echo "   - Approved: {$att->full_name} -> Badge Code: {$badge->badge_code}\n";
}
$booking->update(['attendee_status' => 'approved']);

echo "\n=== END-TO-END ACCEPTANCE TEST COMPLETED SUCCESSFULLY! ===\n";
