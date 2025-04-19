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
        return response()->json(Task::with('assignedUsers')->orderBy('created_at', 'desc')->get());
    }


    public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'bucket_id' => 'required|exists:buckets,id',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id'
        ]);

        $task = Task::create([
            'name' => $request->name,
            'due_date' => $request->due_date,
            'bucket_id' => $request->bucket_id,
        ]);

        if ($request->assigned_users) {
            $task->assignedUsers()->sync($request->assigned_users);
        }

        // Reload fresh assigned users from DB
        return response()->json(
            $task->load('assignedUsers'),
            201
        );
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'name'           => 'sometimes|string|max:255',
            'due_date'       => 'nullable|date',
            'bucket_id'      => 'sometimes|exists:buckets,id',
            'description'    => 'nullable|string',
            'start_date'     => 'nullable|date',
            'priority'       => 'nullable|in:Low,Medium,High',
            'status'         => ['nullable',Rule::in(['Not Started', 'not_started', 'In Progress', 'in_progress', 'Completed', 'completed'])],
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
            'checklist'      => 'nullable|array',
            'attachments'    => 'nullable|array',
            'comments'       => 'nullable|array',
        ]);        

        $task->update([
            'name'         => $request->name,
            'due_date'     => $request->due_date,
            'bucket_id'    => $request->bucket_id,
            'description'  => $request->description,
            'start_date'   => $request->start_date,
            'priority'     => $request->priority,
            'status'       => $request->status,
            'checklist'    => $request->checklist,
            'attachments'  => $request->attachments,
            'comments'     => $request->comments,
            'updated_by'   => auth()->id(),
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
        $task->assignedUsers()->detach(); // remove assigned users
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    // Get users for auto-suggestions
    public function getUsers(Request $request)
    {
        $query = $request->query('q');
        $users = User::where('email', 'like', "%$query%")
                    ->orWhere('name', 'like', "%$query%")
                    ->select('id', 'email', 'name', 'profile_pic')
                    ->get();
        return response()->json($users);
    }
}
