<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventSpace;
use App\Models\StandType;
use App\Models\SpacePosition;
use App\Models\Furniture;
use App\Models\EventService;

class QuotationCalculationService
{
    /**
     * Calculate exhibition booking pricing details.
     */
    public function calculate(array $data)
    {
        $eventId = $data['event_id'] ?? null;
        $event = $eventId ? Event::find($eventId) : null;

        $width = floatval($data['width'] ?? 0);
        $length = floatval($data['length'] ?? 0);
        $customArea = floatval($data['custom_area'] ?? $data['requested_area'] ?? $data['calculated_area'] ?? 0);

        if ($customArea > 0) {
            $areaSqm = $customArea;
            if ($width <= 0 || $length <= 0) {
                $side = round(sqrt($customArea), 2);
                $width = $side;
                $length = $side;
            }
        } else {
            $areaSqm = $width * $length;
        }

        // Space cost
        $spaceCost = 0;
        $targetSpace = null;
        if (!empty($data['event_space_id'])) {
            $targetSpace = EventSpace::find($data['event_space_id']);
            if ($targetSpace) {
                if ($targetSpace->fixed_price > 0) {
                    $spaceCost += floatval($targetSpace->fixed_price);
                } else {
                    $spaceCost += $areaSqm * floatval($targetSpace->price_per_sqm);
                }
            }
        }

        // Stand type base price
        if (!empty($data['stand_type_id'])) {
            $standType = StandType::find($data['stand_type_id']);
            if ($standType) {
                $spaceCost += floatval($standType->base_price);
            }
        }

        // Position fee
        if (!empty($data['space_position_id'])) {
            $position = SpacePosition::find($data['space_position_id']);
            if ($position) {
                $spaceCost += floatval($position->additional_fee);
            }
        }

        // Ticket pricing calculation
        $vipCount = intval($data['vip_tickets_count'] ?? 0);
        $generalCount = intval($data['general_tickets_count'] ?? 0);
        $delegateCount = intval($data['delegate_tickets_count'] ?? 0);

        $vipPrice = $targetSpace ? floatval($targetSpace->vip_ticket_price) : 100.00;
        $generalPrice = $targetSpace ? floatval($targetSpace->general_ticket_price) : 25.00;
        $delegatePrice = $targetSpace ? floatval($targetSpace->delegate_ticket_price) : 50.00;

        $vipTotal = $vipCount * $vipPrice;
        $generalTotal = $generalCount * $generalPrice;
        $delegateTotal = $delegateCount * $delegatePrice;
        $ticketsCost = $vipTotal + $generalTotal + $delegateTotal;

        $ticketItems = [];
        if ($vipCount > 0) {
            $ticketItems[] = [
                'name' => "VIP Attendee Pass ({$vipCount}x @ \${$vipPrice})",
                'quantity' => $vipCount,
                'unit_price' => $vipPrice,
                'total_price' => $vipTotal,
            ];
        }
        if ($generalCount > 0) {
            $ticketItems[] = [
                'name' => "General Admission Pass ({$generalCount}x @ \${$generalPrice})",
                'quantity' => $generalCount,
                'unit_price' => $generalPrice,
                'total_price' => $generalTotal,
            ];
        }
        if ($delegateCount > 0) {
            $ticketItems[] = [
                'name' => "Delegate Pass ({$delegateCount}x @ \${$delegatePrice})",
                'quantity' => $delegateCount,
                'unit_price' => $delegatePrice,
                'total_price' => $delegateTotal,
            ];
        }

        // Furniture total
        $furnitureTotal = 0;
        $furnitureItems = [];
        if (!empty($data['furniture']) && is_array($data['furniture'])) {
            foreach ($data['furniture'] as $furnitureId => $quantity) {
                $qty = intval($quantity);
                if ($qty > 0) {
                    $item = Furniture::find($furnitureId);
                    if ($item) {
                        $itemTotal = $qty * floatval($item->unit_price);
                        $furnitureTotal += $itemTotal;
                        $furnitureItems[] = [
                            'item_id' => $item->id,
                            'name' => $item->name,
                            'quantity' => $qty,
                            'unit_price' => floatval($item->unit_price),
                            'total_price' => $itemTotal,
                        ];
                    }
                }
            }
        }

        // Services total
        $servicesTotal = 0;
        $serviceItems = [];
        if (!empty($data['services']) && is_array($data['services'])) {
            foreach ($data['services'] as $serviceId => $quantity) {
                $qty = intval($quantity);
                if ($qty > 0) {
                    $svc = EventService::find($serviceId);
                    if ($svc) {
                        $svcTotal = $qty * floatval($svc->unit_price);
                        $servicesTotal += $svcTotal;
                        $serviceItems[] = [
                            'item_id' => $svc->id,
                            'name' => $svc->name,
                            'quantity' => $qty,
                            'unit_price' => floatval($svc->unit_price),
                            'total_price' => $svcTotal,
                        ];
                    }
                }
            }
        }

        $subtotal = $spaceCost + $ticketsCost + $furnitureTotal + $servicesTotal;
        $discount = floatval($data['discount'] ?? 0);
        $taxableAmount = max(0, $subtotal - $discount);

        $vatRate = $event ? floatval($event->vat_rate) : 15.0;
        $vatAmount = round($taxableAmount * ($vatRate / 100), 2);
        $grandTotal = round($taxableAmount + $vatAmount, 2);

        return [
            'width' => $width,
            'length' => $length,
            'area_sqm' => $areaSqm,
            'space_cost' => round($spaceCost, 2),
            'tickets_cost' => round($ticketsCost, 2),
            'vip_count' => $vipCount,
            'general_count' => $generalCount,
            'delegate_count' => $delegateCount,
            'ticket_items' => $ticketItems,
            'furniture_total' => round($furnitureTotal, 2),
            'services_total' => round($servicesTotal, 2),
            'furniture_items' => $furnitureItems,
            'service_items' => $serviceItems,
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'vat_rate' => $vatRate,
            'vat_amount' => $vatAmount,
            'grand_total' => $grandTotal,
        ];
    }
}
