<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Fetch all tasks with assigned users
    public function index()
    {
        return response()->json(
            Task::with('assignedUsers')
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    // Store new task
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'status' => 'nullable|in:todo,in_progress,completed,blocked',
            'bucket_id' => 'required|exists:buckets,id',
            'progress' => 'nullable|string',
            'notes' => 'nullable|string',
            'checklist' => 'nullable|array',
            'attachments' => 'nullable|array',
            'comments' => 'nullable|array',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
        ]);

        $task = Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'status' => $request->status ?? 'todo',
            'bucket_id' => $request->bucket_id,
            'progress' => $request->progress,
            'notes' => $request->notes,
            'checklist' => $request->checklist,
            'attachments' => $request->attachments,
            'comments' => $request->comments,
            'created_by' => auth()->id(),
        ]);

        if ($request->assigned_users) {
            $task->assignedUsers()->sync($request->assigned_users);
        }

        return response()->json($task->load('assignedUsers'), 201);
    }

    // Update a task
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'status' => 'nullable|in:todo,in_progress,completed,blocked',
            'progress' => 'nullable|string',
            'notes' => 'nullable|string',
            'checklist' => 'nullable|array',
            'attachments' => 'nullable|array',
            'comments' => 'nullable|array',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
        ]);

        $task->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'status' => $request->status,
            'progress' => $request->progress,
            'notes' => $request->notes,
            'checklist' => $request->checklist,
            'attachments' => $request->attachments,
            'comments' => $request->comments,
            'updated_by' => auth()->id(),
        ]);

        if ($request->assigned_users) {
            $task->assignedUsers()->sync($request->assigned_users);
        }

        return response()->json($task->load('assignedUsers'), 200);
    }

    // Delete a task
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->assignedUsers()->detach();
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    // Get users for auto-suggestions
    public function getUsers(Request $request)
    {
        $query = $request->query('q');
        $users = User::where('email', 'like', "%$query%")
            ->select('id', 'email')
            ->get();

        return response()->json($users);
    }
}
