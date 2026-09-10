@extends('layout')

@section('title', 'Create Client')
@section('clients-show')
    menu-open
@endsection
@section('list-clients')
    active
@endsection

@section('content')

    <div class="p-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="font-weight-bold text-dark mb-1">Exhibitors & Client Directory</h2>
                    <p class="text-muted mb-0">Manage registered event companies, client contact accounts, and system credentials.</p>
                </div>
                <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addClientModal">
                    <i class="fas fa-user-plus mr-1"></i> Register New Exhibitor / Client
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-4">
                    <form action="/client/search" method="get">
                        <div class="input-group">
                            <input name="term" type="text" class="form-control" placeholder="Search by name, email or company...">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-2">
                    <a href="/client" class="btn btn-outline-secondary">Show All</a>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                            <tr>
                                <th>Client ID</th>
                                <th>Contact Person</th>
                                <th>Company / Exhibitor Name</th>
                                <th>Email Address</th>
                                <th>Phone Number</th>
                                <th class="text-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($clients as $client)
                                <tr>
                                    <td><span class="badge badge-light border">CL-{{ str_pad($client->id, 5, '0', STR_PAD_LEFT) }}</span></td>
                                    <td><strong class="text-dark">{{ $client->name }}</strong></td>
                                    <td>{{ $client->company_name }}</td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{ $client->phone ?? 'N/A' }}</td>
                                    <td class="text-right">
                                        <a href="/client/edit/{{$client->id}}" class="btn btn-sm btn-outline-primary" title="Edit Client Details">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-users-slash fa-2x d-block mb-2 text-muted"></i>
                                        No clients found.<br>
                                        <button type="button" class="btn btn-primary btn-sm mt-2" data-toggle="modal" data-target="#addClientModal">
                                            <i class="fas fa-plus mr-1"></i> Register First Exhibitor / Client
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                {{ $clients->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <!-- Modal for Adding Client -->
    <div class="modal fade" id="addClientModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="/client/save" method="POST">
                    @csrf
                    <div class="modal-header bg-light">
                        <h5 class="modal-title font-weight-bold">Register New Exhibitor / Client Account</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-1"></i> <strong>Account Security Notice:</strong> 
                            Clients registered via the admin dashboard are assigned default login credentials (password: <code>password</code>). Upon initial login, they will be prompted to change their password and verify their email before accessing their portal.
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold">Contact Person Name *</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. John Doe" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold">Company / Exhibitor Name *</label>
                                <input type="text" name="company_name" class="form-control" placeholder="e.g. Acme Corporation" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold">Email Address *</label>
                                <input type="email" name="email" class="form-control" placeholder="e.g. client@company.com" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold">Phone Number *</label>
                                <input type="text" name="phone" class="form-control" placeholder="e.g. +263 77 123 4567" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Physical Address</label>
                                <input type="text" name="address" class="form-control" placeholder="e.g. 100 Sam Nujoma Street, Harare">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Default Password</label>
                                <input type="text" name="password" class="form-control" value="password" required>
                                <small class="text-muted">Client will be forced to change this upon first login.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary font-weight-bold">Register Exhibitor & Create Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
