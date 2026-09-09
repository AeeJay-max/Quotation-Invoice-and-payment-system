@extends('layout')
@section('title', 'QR Entrance Check-In Scanner')

@section('content')
<div class="content-wrapper p-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-dark text-white text-center py-3">
                    <h4 class="font-weight-bold mb-0"><i class="fas fa-qrcode text-warning mr-2"></i> QR Entrance Check-In</h4>
                    <small class="text-muted">Live Event Operations Command</small>
                </div>
                <div class="card-body p-4">
                    @if($activeEvent)
                        <div class="alert alert-info py-2 text-center mb-4">
                            <small class="text-muted d-block">Active Scanning Event:</small>
                            <strong class="h6 mb-0">{{ $activeEvent->name }}</strong>
                        </div>
                    @else
                        <div class="alert alert-warning text-center">
                            Please select an active event from the sidebar context menu before scanning.
                        </div>
                    @endif

                    <div id="scanResult" class="alert d-none text-center font-weight-bold mb-4"></div>

                    <form id="checkinScanForm" onsubmit="event.preventDefault(); submitScan();">
                        <input type="hidden" id="event_id" value="{{ optional($activeEvent)->id }}">
                        
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Scan QR Code or Type Pass Code *</label>
                            <div class="input-group input-group-lg">
                                <input type="text" id="qr_code" class="form-control text-center font-weight-bold letter-spacing-1" placeholder="BDG-XXXXXXXX or TCK-XXXXXXXX" autofocus autocomplete="off" required>
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="submit">
                                        <i class="fas fa-check-circle mr-1"></i> Verify
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted form-text mt-1 text-center">Compatible with barcode scanners, camera readers, or manual entry.</small>
                        </div>

                        <div class="form-group">
                            <label class="small text-muted">Entrance Gate / Station Name</label>
                            <input type="text" id="station_name" class="form-control form-control-sm text-center" value="Gate 1 - Main Entrance">
                        </div>

                        <div class="custom-control custom-checkbox text-center mt-3">
                            <input type="checkbox" class="custom-control-input" id="allow_reentry">
                            <label class="custom-control-label small text-muted" for="allow_reentry">Allow Re-entry (Bypass duplicate check-in alert)</label>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <a href="{{ route('admin.checkin.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-list mr-1"></i> View Check-In History Log
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function submitScan() {
    const code = document.getElementById('qr_code').value.trim();
    const eventId = document.getElementById('event_id').value;
    const stationName = document.getElementById('station_name').value;
    const allowReentry = document.getElementById('allow_reentry').checked ? 1 : 0;
    const resultDiv = document.getElementById('scanResult');

    if (!code || !eventId) {
        alert('Please enter or scan a valid code.');
        return;
    }

    fetch("{{ route('admin.checkin.scan') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            qr_code: code,
            event_id: eventId,
            station_name: stationName,
            allow_reentry: allowReentry
        })
    })
    .then(response => response.json().then(data => ({ status: response.status, body: data })))
    .then(res => {
        resultDiv.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-warning');
        if (res.status === 200 && res.body.success) {
            resultDiv.classList.add('alert-success');
            resultDiv.innerHTML = `<i class="fas fa-check-circle fa-2x d-block mb-1"></i> ${res.body.message}`;
            document.getElementById('qr_code').value = '';
        } else if (res.status === 409) {
            resultDiv.classList.add('alert-warning');
            resultDiv.innerHTML = `<i class="fas fa-exclamation-triangle fa-2x d-block mb-1"></i> ${res.body.message}`;
        } else {
            resultDiv.classList.add('alert-danger');
            resultDiv.innerHTML = `<i class="fas fa-times-circle fa-2x d-block mb-1"></i> ${res.body.message || 'Check-in failed'}`;
        }
        document.getElementById('qr_code').focus();
    })
    .catch(err => {
        resultDiv.classList.remove('d-none');
        resultDiv.classList.add('alert-danger');
        resultDiv.innerHTML = `<i class="fas fa-times-circle mr-2"></i> System Error during check-in scan.`;
    });
}
</script>
@endpush
