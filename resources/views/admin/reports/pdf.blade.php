<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>MOSRAC Official Event Report</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0056b3; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #0056b3; }
        .header p { margin: 2px 0; color: #666; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; font-size: 9px; text-align: center; color: #777; border-top: 1px solid #ddd; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Ministry of Sport, Recreation, Arts and Culture (MOSRAC)</h2>
        <p>Official Event Performance & Financial Summary Report</p>
        <p>Generated: {{ date('F d, Y H:i:s') }}</p>
    </div>

    @if($event)
        <h3>Event: {{ $event->name }} ({{ $event->event_code }})</h3>
        <p><strong>Venue:</strong> {{ $event->venue ?? 'Harare Convention Centre' }} | <strong>Dates:</strong> {{ $event->start_date ? $event->start_date->format('d M Y') : '' }} - {{ $event->end_date ? $event->end_date->format('d M Y') : '' }}</p>
    @else
        <h3>Global Multi-Event Summary</h3>
    @endif

    <table>
        <thead>
            <tr>
                <th>Booking Ref</th>
                <th>Company / Client</th>
                <th>Status</th>
                <th class="text-right">Grand Total</th>
                <th class="text-right">Amount Paid</th>
                <th class="text-right">Outstanding</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $b)
                <tr>
                    <td>{{ $b->booking_number }}</td>
                    <td>{{ optional($b->client)->company_name ?? (optional($b->client)->name ?? 'Exhibitor') }}</td>
                    <td>{{ strtoupper($b->status) }}</td>
                    <td class="text-right">${{ number_format($b->grand_total, 2) }}</td>
                    <td class="text-right">${{ number_format(optional($b->invoice)->amount_paid ?? 0, 2) }}</td>
                    <td class="text-right">${{ number_format(optional($b->invoice)->amount_outstanding ?? $b->grand_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>MOSRAC Event Platform &copy; {{ date('Y') }} Ministry of Sport, Recreation, Arts and Culture. All Rights Reserved.</p>
    </div>
</body>
</html>
