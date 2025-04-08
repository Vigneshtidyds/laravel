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



    // Store new task
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


    // Update a task
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id'
        ]);

        $task->update([
            'name' => $request->name,
            'due_date' => $request->due_date,
        ]);

        if ($request->assigned_users) {
            $task->assignedUsers()->sync($request->assigned_users);
        }

        return response()->json(
            $task->load('assignedUsers'),
            201
        );
        
        
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
        $query = $request->query('q'); // Get the search query
        $users = User::where('email', 'like', "%$query%")
                    ->select('id', 'email')
                    ->get();

        return response()->json($users);
    }
}
