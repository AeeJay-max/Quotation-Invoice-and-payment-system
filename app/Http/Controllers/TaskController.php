<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $selectedEventId = session('selected_event_id');
        $query = Task::with(['event', 'assignedUser'])->latest();

        if ($selectedEventId) {
            $query->where('event_id', $selectedEventId);
        }

        $tasks = $query->paginate(20);
        $events = Event::all();
        $users = User::all();

        return view('admin.tasks.index', compact('tasks', 'events', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:LOW,MEDIUM,HIGH,URGENT',
            'due_date' => 'nullable|date',
        ]);

        Task::create($validated);
        return back()->with('success', 'Event task assigned.');
    }

    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:TODO,IN_PROGRESS,BLOCKED,COMPLETED,CANCELLED',
        ]);

        $update = ['status' => $validated['status']];
        if ($validated['status'] === 'COMPLETED') {
            $update['completed_at'] = now();
        }

        $task->update($update);
        return back()->with('success', 'Task status updated.');
    }
}
