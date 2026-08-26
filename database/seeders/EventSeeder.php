<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventSpace;
use App\Models\StandType;
use App\Models\SpacePosition;
use App\Models\Furniture;
use App\Models\EventService;
use App\Models\AttendeeType;

class EventSeeder extends Seeder
{
    public function run()
    {
        $event = Event::firstOrCreate(
            ['event_code' => 'TECH-2026'],
            [
                'name' => 'Technology & Innovation Expo 2026',
                'description' => 'The premier annual international technology and trade exhibition, showcasing breakthrough innovations, digital transformation, and industrial advancements.',
                'start_date' => '2026-10-15',
                'end_date' => '2026-10-19',
                'registration_open_date' => '2026-08-01',
                'registration_close_date' => '2026-10-10',
                'venue' => 'International Exhibition Centre',
                'address' => '100 Exhibition Way, Harare',
                'country' => 'Zimbabwe',
                'currency' => 'USD',
                'vat_rate' => 15.00,
                'status' => 'registration_open',
                'terms_and_conditions' => '1. Full payment is due within 14 days of quotation acceptance. 2. Cancellation within 30 days incurs a 20% processing fee. 3. Exhibitors must adhere to safety and booth height restrictions.',
                'booking_guidelines' => 'Select your desired hall space, stand type, and dimensions. Complete furniture and utility requirements before final quotation submission.',
                'contact_info' => 'Email: events@exhibition.co.zw | Phone: +263 242 700000',
            ]
        );

        // Exhibition Spaces
        $hallA = EventSpace::firstOrCreate(
            ['event_id' => $event->id, 'name' => 'Hall A'],
            [
                'code' => 'HALL-A',
                'description' => 'Main Innovation & Tech Pavilion',
                'location' => 'Main Complex Ground Floor',
                'width' => 50,
                'length' => 100,
                'min_size' => 9.00,
                'max_size' => 500.00,
                'price_per_sqm' => 50.00,
                'fixed_price' => 0.00,
                'availability_status' => 'available',
            ]
        );

        $hallB = EventSpace::firstOrCreate(
            ['event_id' => $event->id, 'name' => 'Hall B'],
            [
                'code' => 'HALL-B',
                'description' => 'Digital Economy & FinTech Hall',
                'location' => 'East Wing Complex',
                'width' => 40,
                'length' => 80,
                'min_size' => 9.00,
                'max_size' => 300.00,
                'price_per_sqm' => 45.00,
                'fixed_price' => 0.00,
                'availability_status' => 'available',
            ]
        );

        // Stand Types
        $premiumStand = StandType::firstOrCreate(
            ['event_id' => $event->id, 'name' => 'Premium Stand'],
            [
                'description' => 'Includes premium wall panels, spotlights, fascia board with company name, and carpet.',
                'base_price' => 200.00,
                'status' => true,
            ]
        );

        $shellScheme = StandType::firstOrCreate(
            ['event_id' => $event->id, 'name' => 'Shell Scheme'],
            [
                'description' => 'Standard octanorm shell scheme booth with basic fascia board and lighting.',
                'base_price' => 100.00,
                'status' => true,
            ]
        );

        $spaceOnly = StandType::firstOrCreate(
            ['event_id' => $event->id, 'name' => 'Space Only'],
            [
                'description' => 'Bare floor space for custom booth design and construction.',
                'base_price' => 0.00,
                'status' => true,
            ]
        );

        // Space Positions
        $positions = [
            ['position_number' => 'A12', 'label' => 'Stand A12 - Entrance Corner', 'position_type' => 'Corner', 'additional_fee' => 50.00],
            ['position_number' => 'A01', 'label' => 'Stand A01 - Main Entrance', 'position_type' => 'Entrance', 'additional_fee' => 100.00],
            ['position_number' => 'A05', 'label' => 'Stand A05 - Central Aisle', 'position_type' => 'Central', 'additional_fee' => 25.00],
            ['position_number' => 'B02', 'label' => 'Stand B02 - Standard Aisle', 'position_type' => 'Standard', 'additional_fee' => 0.00],
        ];

        foreach ($positions as $pos) {
            SpacePosition::firstOrCreate(
                ['event_space_id' => $hallA->id, 'position_number' => $pos['position_number']],
                [
                    'label' => $pos['label'],
                    'position_type' => $pos['position_type'],
                    'additional_fee' => $pos['additional_fee'],
                    'status' => 'available',
                ]
            );
        }

        // Furniture Catalogue
        $furnitureList = [
            ['name' => 'Chair', 'category' => 'Seating', 'unit_price' => 10.00, 'available_quantity' => 200],
            ['name' => 'Table', 'category' => 'Tables', 'unit_price' => 50.00, 'available_quantity' => 100],
            ['name' => 'Counter', 'category' => 'Counters', 'unit_price' => 100.00, 'available_quantity' => 50],
            ['name' => 'Display Stand', 'category' => 'Displays', 'unit_price' => 75.00, 'available_quantity' => 40],
            ['name' => 'Executive Chair', 'category' => 'Seating', 'unit_price' => 25.00, 'available_quantity' => 60],
            ['name' => 'LED Screen 55"', 'category' => 'AV', 'unit_price' => 250.00, 'available_quantity' => 20],
        ];

        foreach ($furnitureList as $f) {
            Furniture::firstOrCreate(
                ['event_id' => $event->id, 'name' => $f['name']],
                [
                    'category' => $f['category'],
                    'unit_price' => $f['unit_price'],
                    'available_quantity' => $f['available_quantity'],
                    'status' => true,
                ]
            );
        }

        // Event Services
        $servicesList = [
            ['name' => 'Electricity 220V Outlet', 'category' => 'Utilities', 'unit_price' => 100.00],
            ['name' => 'High-Speed Dedicated WiFi', 'category' => 'Internet', 'unit_price' => 150.00],
            ['name' => 'Daily Booth Cleaning', 'category' => 'Services', 'unit_price' => 50.00],
            ['name' => 'Dedicated Security Guard', 'category' => 'Security', 'unit_price' => 120.00],
            ['name' => 'Official Catalogue Full Page Advert', 'category' => 'Advertising', 'unit_price' => 300.00],
        ];

        foreach ($servicesList as $s) {
            EventService::firstOrCreate(
                ['event_id' => $event->id, 'name' => $s['name']],
                [
                    'category' => $s['category'],
                    'unit_price' => $s['unit_price'],
                    'availability' => true,
                    'status' => true,
                ]
            );
        }

        // Attendee Types
        $ticketTypes = [
            ['name' => 'Exhibitor Staff', 'price' => 0.00, 'description' => 'Complimentary badge for booth staff.'],
            ['name' => 'Delegate', 'price' => 20.00, 'description' => 'Conference & Exhibition access ticket.'],
            ['name' => 'VIP Pass', 'price' => 50.00, 'description' => 'Access to VIP lounge and networking dinner.'],
            ['name' => 'Guest Pass', 'price' => 10.00, 'description' => 'Day visitor access.'],
        ];

        foreach ($ticketTypes as $t) {
            AttendeeType::firstOrCreate(
                ['event_id' => $event->id, 'name' => $t['name']],
                [
                    'price' => $t['price'],
                    'description' => $t['description'],
                    'status' => true,
                ]
            );
        }
    }
}
