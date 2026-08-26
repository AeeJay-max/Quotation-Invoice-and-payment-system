<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Booking;
use App\Services\BadgeGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    protected $badgeService;

    public function __construct(BadgeGeneratorService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    public function adminIndex(Request $request)
    {
        $query = Badge::with(['attendee.attendeeType', 'booking.client', 'booking.event'])->latest();

        if ($request->filled('booking_id')) {
            $query->where('booking_id', $request->booking_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $badges = $query->paginate(20);
        $bookings = Booking::with('client')->latest()->get();

        return view('admin.badges.index', compact('badges', 'bookings'));
    }

    public function customerIndex()
    {
        $user = Auth::user();
        $clientId = $user->client_id ?? optional($user->client()->first())->id;

        $badges = Badge::whereHas('booking', function ($q) use ($clientId, $user) {
            $q->where('client_id', $clientId)->orWhere('user_id', $user->id);
        })->with(['attendee.attendeeType', 'booking.event'])->latest()->get();

        return view('customer.badges.index', compact('badges'));
    }

    public function printBadge($id)
    {
        $badge = Badge::with(['attendee.attendeeType', 'booking.event', 'booking.client'])->findOrFail($id);
        $badge->update(['status' => 'printed', 'printed_at' => now()]);

        $badges = collect([$badge]);
        return view('admin.badges.print', compact('badges'));
    }

    public function printBatch(Request $request)
    {
        $badgeIds = $request->input('badge_ids', []);
        if (empty($badgeIds) && $request->filled('booking_id')) {
            $badgeIds = Badge::where('booking_id', $request->booking_id)->pluck('id')->toArray();
        }

        $badges = Badge::whereIn('id', $badgeIds)
            ->with(['attendee.attendeeType', 'booking.event', 'booking.client'])
            ->get();

        foreach ($badges as $badge) {
            $badge->update(['status' => 'printed', 'printed_at' => now()]);
        }

        return view('admin.badges.print', compact('badges'));
    }

    public function updateStatus(Request $request, $id)
    {
        $badge = Badge::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:generated,printed,collected',
        ]);

        $updateData = ['status' => $validated['status']];
        if ($validated['status'] === 'printed' && !$badge->printed_at) {
            $updateData['printed_at'] = now();
        }

        $badge->update($updateData);

        return back()->with('success', 'Badge status updated.');
    }
}
