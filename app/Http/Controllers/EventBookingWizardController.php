<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Client;
use App\Models\User;
use App\Models\Role;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\BookingStatusHistory;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Services\QuotationCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventBookingWizardController extends Controller
{
    protected $calculationService;

    public function __construct(QuotationCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    public function wizard(Request $request)
    {
        $events = Event::whereIn('status', ['published', 'registration_open'])
            ->with(['spaces.positions', 'standTypes', 'furniture', 'services'])
            ->get();

        $selectedEventId = $request->get('event_id', optional($events->first())->id);
        $selectedEvent = $selectedEventId ? Event::with(['spaces.positions', 'standTypes', 'furniture', 'services'])->find($selectedEventId) : null;

        return view('public.booking-wizard', compact('events', 'selectedEvent'));
    }

    public function calculateAjax(Request $request)
    {
        $result = $this->calculationService->calculate($request->all());
        return response()->json($result);
    }

    public function submitQuotation(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'company_name' => 'required|string|max:255',
            'registration_number' => 'nullable|string|max:100',
            'contact_person' => 'required|string|max:255',
            'position' => 'nullable|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'mobile' => 'nullable|string|max:50',
            'physical_address' => 'required|string|max:255',
            'postal_address' => 'nullable|string|max:255',
            'country' => 'required|string|max:100',
            'website' => 'nullable|string|max:255',
            'business_category' => 'required|string|max:100',
            'event_space_id' => 'required|exists:event_spaces,id',
            'stand_type_id' => 'required|exists:stand_types,id',
            'width' => 'required|numeric|min:1',
            'length' => 'required|numeric|min:1',
            'space_position_id' => 'nullable|exists:space_positions,id',
            'furniture' => 'nullable|array',
            'services' => 'nullable|array',
            'people_count' => 'required|integer|min:1',
            'terms_accepted' => 'required',
            'password' => Auth::check() ? 'nullable' : 'required|string|min:6|confirmed',
        ]);

        $event = Event::findOrFail($validated['event_id']);
        $calc = $this->calculationService->calculate($validated);

        DB::beginTransaction();
        try {
            // Find or create Exhibitor User Account
            $user = Auth::user();
            if (!$user) {
                $customerRole = \App\Models\Role::where('name', 'Customer')->first();
                $roleId = $customerRole ? $customerRole->id : 2;

                $user = User::firstOrCreate(
                    ['email' => $validated['email']],
                    [
                        'name' => $validated['contact_person'],
                        'status' => true,
                        'role_id' => $roleId,
                        'password' => bcrypt($validated['password']),
                    ]
                );

                Auth::login($user);
            }

            // Find or create Client
            $client = Client::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'user_id' => $user->id,
                    'name' => $validated['contact_person'],
                    'company_name' => $validated['company_name'],
                    'address' => $validated['physical_address'],
                    'phone' => $validated['phone'],
                    'registration_number' => $validated['registration_number'] ?? null,
                    'position' => $validated['position'] ?? null,
                    'mobile' => $validated['mobile'] ?? null,
                    'postal_address' => $validated['postal_address'] ?? null,
                    'country' => $validated['country'],
                    'website' => $validated['website'] ?? null,
                    'business_category' => $validated['business_category'],
                ]
            );

            if (!$client->user_id) {
                $client->update(['user_id' => $user->id]);
            }
            if (!$user->client_id) {
                $user->update(['client_id' => $client->id]);
            }

            // Generate Quotation Number
            $quotationNumber = 'QUO-' . date('Y') . '-' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

            $quotation = Quotation::create([
                'quotation_number' => $quotationNumber,
                'event_id' => $event->id,
                'client_id' => $client->id,
                'user_id' => $user->id,
                'create_date' => now(),
                'due_date' => now()->addDays(14),
                'note' => "Event Exhibition Booking for {$event->name}. Space: Width {$calc['width']}m x Length {$calc['length']}m ({$calc['area_sqm']} sq.m)",
                'terms_condition' => $event->terms_and_conditions ?? 'Standard event booking terms apply.',
                'discount' => $calc['discount'],
                'vat' => $calc['vat_amount'],
                'space_cost' => $calc['space_cost'],
                'furniture_total' => $calc['furniture_total'],
                'services_total' => $calc['services_total'],
                'subtotal' => $calc['subtotal'],
                'total' => $calc['grand_total'],
                'payment_type' => 1,
                'payment_currency' => 1,
                'status' => 'pending',
                'people_count' => $validated['people_count'],
            ]);

            // Save Quotation Items
            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'description' => "Exhibition Space & Stand: {$calc['width']}m x {$calc['length']}m ({$calc['area_sqm']}m²)",
                'quantity' => 1,
                'unit_price' => $calc['space_cost'],
            ]);

            foreach ($calc['furniture_items'] as $fItem) {
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'description' => "Furniture: {$fItem['name']}",
                    'quantity' => $fItem['quantity'],
                    'unit_price' => $fItem['unit_price'],
                ]);
            }

            foreach ($calc['service_items'] as $sItem) {
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'description' => "Service: {$sItem['name']}",
                    'quantity' => $sItem['quantity'],
                    'unit_price' => $sItem['unit_price'],
                ]);
            }

            DB::commit();

            return redirect()->route('public.quotation.view', $quotation->id)
                ->with('success', 'Quotation request submitted successfully! It is now pending admin approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error submitting quotation: ' . $e->getMessage());
        }
    }

    public function showPublicQuotation($id)
    {
        $quotation = Quotation::with([
            'event',
            'client',
            'items',
            'booking.space',
            'booking.standType',
            'booking.position',
            'booking.items'
        ])->findOrFail($id);

        $bankDetails = \App\Models\Settings::where('type', 'email')->pluck('description', 'label')->toArray();

        return view('public.quotation-view', compact('quotation', 'bankDetails'));
    }

    public function confirmQuotation($id)
    {
        $quotation = Quotation::with(['event', 'client', 'items', 'booking'])->findOrFail($id);

        if ($quotation->status === 'accepted') {
            return redirect()->route('customer.dashboard')->with('info', 'This quotation has already been accepted and confirmed.');
        }

        if ($quotation->status !== 'approved') {
            return redirect()->route('customer.dashboard')->with('error', 'This quotation has not been approved by an administrator yet, so it cannot be confirmed.');
        }

        DB::beginTransaction();
        try {
            $client = $quotation->client;
            $user = null;

            // Step 6: Create or activate Customer Account
            if ($client->user_id) {
                $user = User::find($client->user_id);
            }

            if (!$user) {
                $existingUser = User::where('email', $client->email)->first();
                if ($existingUser) {
                    $user = $existingUser;
                } else {
                    // Create customer user account with default password 'password'
                    $customerRole = Role::firstOrCreate(['name' => 'Customer']);
                    $user = User::create([
                        'name' => $client->name,
                        'email' => $client->email,
                        'password' => Hash::make('password'),
                        'phone' => $client->phone,
                        'status' => true,
                        'is_admin' => false,
                        'role_id' => $customerRole->id,
                        'client_id' => $client->id,
                    ]);
                }
                $client->update(['user_id' => $user->id]);
            }

            // Step 1: Update Quotation Status to ACCEPTED
            $quotation->update(['status' => 'accepted']);

            // Step 5: Update Booking Status
            $booking = $quotation->booking;
            if (!$booking) {
                $bookingNumber = 'BOOK-' . date('Y') . '-' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
                $booking = Booking::create([
                    'booking_number' => $bookingNumber,
                    'event_id' => $quotation->event_id,
                    'client_id' => $client->id,
                    'user_id' => $user->id,
                    'quotation_id' => $quotation->id,
                    'subtotal' => $quotation->subtotal,
                    'discount' => $quotation->discount,
                    'vat_amount' => $quotation->vat,
                    'grand_total' => $quotation->total,
                    'status' => 'accepted',
                    'accepted_at' => now(),
                    'people_count' => $quotation->people_count,
                ]);

                $quotation->update(['booking_id' => $booking->id]);

            } else {
                $booking->update([
                    'user_id' => $user->id,
                    'status' => 'accepted',
                    'accepted_at' => now(),
                ]);
            }

            // Generate Invoice automatically from Confirmed Booking
            if (!$booking->invoice_id) {
                $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
                $invoice = Invoice::create([
                    'invoice_number' => $invoiceNumber,
                    'event_id' => $quotation->event_id,
                    'booking_id' => $booking->id,
                    'quotation_id' => $quotation->id,
                    'client_id' => $client->id,
                    'user_id' => $user->id,
                    'create_date' => now(),
                    'due_date' => now()->addDays(14),
                    'note' => "Invoice for Confirmed Event Exhibition Booking {$booking->booking_number}",
                    'terms_condition' => $quotation->terms_condition,
                    'discount' => $quotation->discount,
                    'vat' => $quotation->vat,
                    'payment_type' => 1,
                    'payment_currency' => 1,
                    'payment_status' => 1, // Unpaid / Pending in existing lookup
                    'total' => $quotation->total,
                    'amount_paid' => 0,
                    'amount_outstanding' => $quotation->total,
                ]);

                foreach ($quotation->items as $item) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'description' => $item->description,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                    ]);
                }

                $booking->update(['invoice_id' => $invoice->id]);
                $quotation->update(['invoice_id' => $invoice->id]);
            }

            BookingStatusHistory::create([
                'booking_id' => $booking->id,
                'user_id' => $user->id,
                'status' => 'Quotation Accepted',
                'notes' => "Customer confirmed quotation. Booking {$booking->booking_number} created and account activated.",
            ]);

            DB::commit();

            // Auto-login customer
            Auth::login($user);

            return redirect()->route('customer.dashboard')
                ->with('success', "Quotation successfully confirmed! Welcome to your Customer Portal. Your initial login password is 'password'.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error confirming quotation: ' . $e->getMessage());
        }
    }
}
