@extends('layout')
@section('title', 'Event Tasks & Operations')

@section('content')
<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-1">Event Preparation Tasks</h2>
            <p class="text-muted mb-0">Track staff assignments, operational tasks, priorities, and deadlines.</p>
        </div>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTaskModal">
            <i class="fas fa-plus mr-1"></i> Assign New Task
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Priority</th>
                            <th>Task Title</th>
                            <th>Event</th>
                            <th>Assigned Officer</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td>
                                    @if($task->priority === 'URGENT')
                                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-exclamation-triangle mr-1"></i> URGENT</span>
                                    @elseif($task->priority === 'HIGH')
                                        <span class="badge badge-warning text-dark px-2 py-1">HIGH</span>
                                    @else
                                        <span class="badge badge-light border">{{ $task->priority }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $task->title }}</div>
                                    <small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                                </td>
                                <td>{{ optional($task->event)->name }}</td>
                                <td><i class="fas fa-user-circle text-secondary mr-1"></i> {{ optional($task->assignedUser)->name ?? 'Unassigned' }}</td>
                                <td><small class="text-muted">{{ $task->due_date ? $task->due_date->format('Y-m-d') : 'No deadline' }}</small></td>
                                <td>
                                    @if($task->status === 'COMPLETED')
                                        <span class="badge badge-success">COMPLETED</span>
                                    @elseif($task->status === 'IN_PROGRESS')
                                        <span class="badge badge-info">IN PROGRESS</span>
                                    @elseif($task->status === 'BLOCKED')
                                        <span class="badge badge-danger">BLOCKED</span>
                                    @else
                                        <span class="badge badge-light border">TO DO</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.tasks.status', $task->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <select name="status" class="form-control form-control-sm d-inline-block w-auto" onchange="this.form.submit()">
                                            <option value="TODO" {{ $task->status === 'TODO' ? 'selected' : '' }}>To Do</option>
                                            <option value="IN_PROGRESS" {{ $task->status === 'IN_PROGRESS' ? 'selected' : '' }}>In Progress</option>
                                            <option value="BLOCKED" {{ $task->status === 'BLOCKED' ? 'selected' : '' }}>Blocked</option>
                                            <option value="COMPLETED" {{ $task->status === 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No preparation tasks assigned yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">
        {{ $tasks->links() }}
    </div>
</div>
@endsection

@section('modals')
<!-- Modal for Creating Task -->
<div class="modal fade" id="createTaskModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.tasks.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Assign Preparation Task</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Event *</label>
                        <select name="event_id" class="form-control" required>
                            @foreach($events as $evt)
                                <option value="{{ $evt->id }}">{{ $evt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Task Title *</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Inspect Hall A Stand Electrical Installations" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Assigned Staff / Officer</label>
                            <select name="assigned_to" class="form-control">
                                <option value="">-- Unassigned --</option>
                                @foreach($users as $usr)
                                    <option value="{{ $usr->id }}">{{ $usr->name }} ({{ $usr->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Priority *</label>
                            <select name="priority" class="form-control" required>
                                <option value="LOW">Low</option>
                                <option value="MEDIUM" selected>Medium</option>
                                <option value="HIGH">High</option>
                                <option value="URGENT">URGENT</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Due Date</label>
                            <input type="date" name="due_date" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Task Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
