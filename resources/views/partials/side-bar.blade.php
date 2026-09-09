<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link text-center d-block">
        <img src="{{asset($global_settings['logo'] ?? '')}}" alt="{{$global_settings['app_name'] ?? ''}}" class="mx-auto d-block"
             style="width: 140px; border: none; box-shadow: none;">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Active Event Context Switcher -->
        @php
            $navEvents = \App\Models\Event::latest()->get();
            $selectedNavEventId = session('selected_event_id');
        @endphp
        <div class="user-panel mt-3 pb-2 mb-3">
            <div class="px-2">
                <small class="text-muted d-block uppercase font-weight-bold text-xs mb-1">Active Event Context</small>
                <form action="{{ route('admin.set-event') }}" method="POST" id="sidebar-event-switcher">
                    @csrf
                    <select name="event_id" class="form-control form-control-sm bg-dark text-light border-secondary" onchange="switchEventContext(this)">
                        <option value="all" {{ session('global_mode') ? 'selected' : '' }}>-- ALL EVENTS (Global Summary) --</option>
                        @foreach($navEvents as $eItem)
                            <option value="{{ $eItem->id }}" {{ $selectedNavEventId == $eItem->id ? 'selected' : '' }}>
                                {{ Str::limit($eItem->name, 28) }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2 mb-5">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="/dashboard" class="nav-link {{ request()->is('dashboard') || request()->is('admin') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- EVENT & VENUE MANAGEMENT -->
                <li class="nav-header font-weight-bold text-warning">EVENT & VENUES</li>

                <li class="nav-item">
                    <a href="{{ route('admin.events.index') }}" class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt nav-icon"></i>
                        <p>Events Management</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.venues.index') }}" class="nav-link {{ request()->routeIs('admin.venues.*') ? 'active' : '' }}">
                        <i class="fas fa-building nav-icon"></i>
                        <p>Venues & Halls</p>
                    </a>
                </li>

                <!-- PLANNING & AGENDA -->
                <li class="nav-header font-weight-bold text-info">PLANNING & AGENDA</li>

                <li class="nav-item">
                    <a href="{{ route('admin.programme.index') }}" class="nav-link {{ request()->routeIs('admin.programme.*') ? 'active' : '' }}">
                        <i class="fas fa-clock nav-icon"></i>
                        <p>Programme Agenda</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.speakers.index') }}" class="nav-link {{ request()->routeIs('admin.speakers.*') ? 'active' : '' }}">
                        <i class="fas fa-microphone-alt nav-icon"></i>
                        <p>Speaker Directory</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.tasks.index') }}" class="nav-link {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}">
                        <i class="fas fa-tasks nav-icon"></i>
                        <p>Event Preparation Tasks</p>
                    </a>
                </li>

                <!-- REGISTRATION & PARTICIPATION -->
                <li class="nav-header font-weight-bold text-success">REGISTRATION & PARTICIPATION</li>

                <li class="nav-item">
                    <a href="{{ route('admin.attendees.index') }}" class="nav-link {{ request()->routeIs('admin.attendees.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog nav-icon"></i>
                        <p>Attendee Roster</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.tickets.index') }}" class="nav-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
                        <i class="fas fa-ticket-alt nav-icon"></i>
                        <p>Ticket Issuance</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.checkin.scanner') }}" class="nav-link {{ request()->routeIs('admin.checkin.*') ? 'active' : '' }}">
                        <i class="fas fa-qrcode nav-icon text-success"></i>
                        <p>QR Entrance Scanner</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.badges.index') }}" class="nav-link {{ request()->routeIs('admin.badges.*') ? 'active' : '' }}">
                        <i class="fas fa-id-card nav-icon"></i>
                        <p>Badge Management</p>
                    </a>
                </li>

                <!-- COMMERCIAL & EXHIBITIONS -->
                <li class="nav-header font-weight-bold text-primary">COMMERCIAL & EXHIBITION</li>

                <li class="nav-item">
                    <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                        <i class="fas fa-file-contract nav-icon"></i>
                        <p>Confirmed Bookings</p>
                    </a>
                </li>

                @can('list', 'quotation')
                <li class="nav-item">
                    <a href="/quotation" class="nav-link {{ request()->is('quotation*') ? 'active' : '' }}">
                        <i class="fas fa-receipt nav-icon"></i>
                        <p>Exhibitor Quotations</p>
                    </a>
                </li>
                @endcan

                @can('list', 'invoice')
                <li class="nav-item">
                    <a href="/invoice" class="nav-link {{ request()->is('invoice*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice-dollar nav-icon"></i>
                        <p>Tax Invoices</p>
                    </a>
                </li>
                @endcan

                <li class="nav-item">
                    <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                        <i class="fas fa-money-check-alt nav-icon text-warning"></i>
                        <p>
                            Payment Verifications
                            @php
                                $pendingPayCount = \App\Models\Payment::whereIn('status',['submitted','pending'])->count();
                            @endphp
                            @if($pendingPayCount > 0)
                                <span class="badge badge-warning right">{{ $pendingPayCount }}</span>
                            @endif
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.sponsors.index') }}" class="nav-link {{ request()->routeIs('admin.sponsors.*') ? 'active' : '' }}">
                        <i class="fas fa-handshake nav-icon"></i>
                        <p>Sponsors Management</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.vendors.index') }}" class="nav-link {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
                        <i class="fas fa-truck nav-icon"></i>
                        <p>Service Vendors</p>
                    </a>
                </li>

                <!-- REPORTING & SYSTEM -->
                <li class="nav-header font-weight-bold text-light">REPORTING & SYSTEM</li>

                <li class="nav-item">
                    <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line nav-icon text-info"></i>
                        <p>Reports & Analytics</p>
                    </a>
                </li>

                @can('list', 'client')
                <li class="nav-item">
                    <a href="/client" class="nav-link {{ request()->is('client*') ? 'active' : '' }}">
                        <i class="fas fa-address-book nav-icon"></i>
                        <p>Exhibitors & Clients</p>
                    </a>
                </li>
                @endcan

                @can('list', 'expense')
                <li class="nav-item">
                    <a href="/expense" class="nav-link {{ request()->is('expense*') ? 'active' : '' }}">
                        <i class="fas fa-hand-holding-usd nav-icon"></i>
                        <p>Event Expenses</p>
                    </a>
                </li>
                @endcan

                @can('list', 'user')
                <li class="nav-item has-treeview {{ request()->is('user*') || request()->is('role*') || request()->is('permission*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('user*') || request()->is('role*') || request()->is('permission*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Users & Access
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/user/" class="nav-link {{ request()->is('user') || request()->is('user/edit*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>System Users</p>
                            </a>
                        </li>
                        @can('list', 'role')
                        <li class="nav-item">
                            <a href="{{route('role.index')}}" class="nav-link {{ request()->routeIs('role.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        @endcan
                        @can('list', 'permission')
                        <li class="nav-item">
                            <a href="{{route('permission.index')}}" class="nav-link {{ request()->routeIs('permission.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Permissions</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @can('update', 'setting')
                <li class="nav-item has-treeview {{ request()->is('settings*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('settings*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            System Settings
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/settings/system" class="nav-link {{ request()->is('settings/system') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>System Metadata</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/settings/smtp" class="nav-link {{ request()->is('settings/smtp') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>SMTP Mail Settings</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/settings/email" class="nav-link {{ request()->is('settings/email') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ministry Banking Details</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
