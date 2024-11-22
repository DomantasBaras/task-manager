<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Log;

class TaskController extends Controller
{
    // Get all tasks
    public function index()
    {
        return Task::all();
    }

    // Get a specific task
    public function show(Task $task)
    {
        return $task->load('comments');
    }

    public function getLogs(Task $task)
    {
        $logs = Log::where('entity_type', Task::class)
            ->where('entity_id', $task->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json($logs);
    }
    // Create a new task
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'creator' => 'required|string|max:255',
            'tester' => 'required|string|max:255',
            'assignee' => 'required|string|max:255',
            'status' => 'required|in:paused,in progress,testing,released',
            'type' => 'required|in:Task,Bug',
        ]);

        $task = Task::create($validated);

        return response()->json($task, 201);
    }
    public function changeType(Request $request, Task $task)
    {
        $request->validate([
            'type' => 'required|in:Task,Bug',
        ]);

        $task->update(['type' => $request->type]);
        return response()->json([
            'message' => 'Task type updated successfully',
            'task' => $task,
        ]);

    }

    public function update(Request $request, Task $task)
    {
        // Validate the request data
        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'creator' => 'string|max:255',
            'tester' => 'string|max:255',
            'assignee' => 'string|max:255',
            'status' => 'in:paused,in progress,testing,released',
            'type' => 'in:Task,Bug',
        ]);

        // Store the original values
        $originalData = $task->getOriginal();

        // Compare original data with validated data
        foreach ($validated as $key => $newValue) {
            $oldValue = $originalData[$key] ?? null;

            // If there's a change, log it
            if ($oldValue !== $newValue) {
                Log::create([
                    'entity_type' => get_class($task),
                    'entity_id' => $task->id,
                    'field_changed' => $key,
                    'old_value' => $this->formatDateIfNeeded($oldValue),
                    'new_value' => $this->formatDateIfNeeded($newValue),
                ]);
            }
        }

        // Now update the model
        $task->update($validated);

        return response()->json($task);
    }

    private function formatDateIfNeeded($value)
    {
        if ($value instanceof \Carbon\Carbon) {
            return $value->format('Y-m-d H:i:s');
        }

        return $value;
    }



    // Change the status of a task
    public function changeStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:paused,in progress,testing,released',
        ]);

        $task->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated successfully.', 'task' => $task]);
    }

    // Controller Method to Add Comments
    public function addComment(Request $request, $id)
    {
        $comment = Comment::create([
            'entity_id' => $id,
            'comment' => $request->comment,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json($comment);
    }

}

