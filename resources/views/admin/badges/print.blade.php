<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Badges</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background: #eef2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .badge-card {
            width: 350px;
            height: 520px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            margin: 20px auto;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 2px solid #ddd;
            page-break-after: always;
        }
        .badge-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #fff;
            padding: 20px 15px;
            text-align: center;
            border-bottom: 5px solid #f39c12;
        }
        .badge-body {
            padding: 20px;
            text-align: center;
        }
        .attendee-name { font-size: 1.5rem; font-weight: 800; color: #1e3c72; margin-bottom: 5px; }
        .company-name { font-size: 1.1rem; font-weight: 600; color: #555; margin-bottom: 10px; }
        .position-title { font-size: 0.95rem; color: #777; }
        .pass-badge {
            background: #f39c12;
            color: #fff;
            padding: 8px 20px;
            font-size: 1.1rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
            margin-top: 15px;
            border-radius: 20px;
        }
        .badge-footer {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-top: 1px solid #eee;
        }
        .qr-placeholder {
            width: 110px;
            height: 110px;
            margin: 0 auto 10px;
            background: #f0f0f0;
            border: 2px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
            color: #444;
            border-radius: 8px;
        }
        @media print {
            body { background: none; }
            .no-print { display: none; }
            .badge-card { box-shadow: none; margin: 10px auto; }
        }
    </style>
</head>
<body>

<div class="container text-center my-3 no-print">
    <button onclick="window.print()" class="btn btn-success btn-lg font-weight-bold shadow">
        <i class="fas fa-print mr-2"></i> Print All Badges
    </button>
</div>

<div class="d-flex flex-wrap justify-content-center">
    @foreach($badges as $badge)
        <div class="badge-card">
            <div class="badge-header">
                <h5 class="font-weight-bold mb-0" style="font-size: 1.1rem;">{{ $badge->booking->event->name ?? 'EXHIBITION 2026' }}</h5>
                <small class="text-warning font-weight-bold">{{ $badge->booking->event->venue ?? 'Exhibition Centre' }}</small>
            </div>

            <div class="badge-body">
                <div class="attendee-name">{{ $badge->attendee->full_name }}</div>
                <div class="company-name">{{ $badge->attendee->company ?? ($badge->booking->client->company_name ?? 'Exhibitor') }}</div>
                <div class="position-title">{{ $badge->attendee->position ?? 'Exhibitor Representative' }}</div>

                <div class="pass-badge">
                    {{ optional($badge->attendee->attendeeType)->name ?? 'EXHIBITOR' }}
                </div>
            </div>

            <div class="badge-footer">
                <div class="qr-placeholder">
                    <div class="text-center">
                        <i class="fas fa-qrcode fa-3x text-dark mb-1"></i><br>
                        <span>{{ $badge->badge_code }}</span>
                    </div>
                </div>
                <small class="text-muted font-weight-bold">ID: {{ $badge->badge_code }}</small>
            </div>
        </div>
    @endforeach
</div>

</body>
</html>
