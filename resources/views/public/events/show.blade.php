@extends('public-layout')
@section('title', $event->name)

@section('content')
<div class="container-fluid p-0">
    <!-- Hero Banner Section -->
    <div class="bg-dark text-white text-center py-5 px-3" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
        <div class="container py-4">
            <span class="badge badge-warning text-dark font-weight-bold px-3 py-2 uppercase mb-3">{{ $event->event_type ?? 'Exhibition' }}</span>
            <h1 class="display-4 font-weight-bold mb-3">{{ $event->name }}</h1>
            <p class="lead text-light max-w-700 mx-auto mb-4">{{ $event->description }}</p>
            
            <div class="d-flex justify-content-center flex-wrap gap-3 mb-4">
                <span class="bg-white-20 px-3 py-2 rounded text-white"><i class="far fa-calendar-alt mr-2 text-warning"></i> {{ $event->start_date ? $event->start_date->format('M d, Y') : '' }} - {{ $event->end_date ? $event->end_date->format('M d, Y') : '' }}</span>
                <span class="bg-white-20 px-3 py-2 rounded text-white"><i class="fas fa-map-marker-alt mr-2 text-warning"></i> {{ $event->venue ?? 'Harare Convention Center' }}</span>
            </div>

            @if($event->hasModule('exhibition'))
                <a href="{{ route('public.booking.wizard', ['event_id' => $event->id]) }}" class="btn btn-warning btn-lg font-weight-bold shadow-sm px-4">
                    <i class="fas fa-store mr-2"></i> Book Exhibition Space / Stand
                </a>
            @endif
        </div>
    </div>

    <div class="container my-5">
        <!-- Event Agenda / Programme -->
        @if($event->hasModule('programme') && $event->sessions->count() > 0)
            <div class="mb-5">
                <h3 class="font-weight-bold text-dark mb-4 border-bottom pb-2"><i class="fas fa-clock text-primary mr-2"></i> Official Agenda & Sessions</h3>
                <div class="row">
                    @foreach($event->sessions as $session)
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge badge-info">{{ strtoupper($session->session_type) }}</span>
                                        <small class="text-muted"><i class="far fa-clock mr-1"></i> {{ $session->start_time }} - {{ $session->end_time }}</small>
                                    </div>
                                    <h5 class="font-weight-bold text-dark mb-2">{{ $session->title }}</h5>
                                    <p class="text-muted small mb-3">{{ $session->description }}</p>
                                    @if($session->speakers->count() > 0)
                                        <div class="border-top pt-2">
                                            <small class="text-muted d-block mb-1">Speakers:</small>
                                            @foreach($session->speakers as $spk)
                                                <span class="badge badge-light border text-dark">{{ $spk->full_name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Confirmed Exhibitors Directory -->
        @if($event->hasModule('exhibition') && $confirmedExhibitors->count() > 0)
            <div class="mb-5">
                <h3 class="font-weight-bold text-dark mb-4 border-bottom pb-2"><i class="fas fa-store text-success mr-2"></i> Confirmed Exhibitors</h3>
                <div class="row">
                    @foreach($confirmedExhibitors as $exh)
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm border-0 p-3">
                                <h6 class="font-weight-bold text-dark mb-1">{{ $exh->company_name }}</h6>
                                <small class="text-muted">{{ $exh->business_category ?? 'Exhibitor' }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Official Sponsors -->
        @if($event->sponsors->count() > 0)
            <div class="mb-5">
                <h3 class="font-weight-bold text-dark mb-4 border-bottom pb-2"><i class="fas fa-handshake text-warning mr-2"></i> Official Event Sponsors</h3>
                <div class="d-flex flex-wrap gap-4 align-items-center">
                    @foreach($event->sponsors as $sp)
                        <div class="card shadow-sm border-0 px-4 py-3 text-center">
                            <h5 class="font-weight-bold mb-1">{{ $sp->name }}</h5>
                            <span class="badge badge-warning text-dark">{{ strtoupper($sp->sponsor_package) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
