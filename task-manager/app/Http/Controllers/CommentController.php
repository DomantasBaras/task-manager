<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  

class CommentController extends Controller
{
    // Get all comments for a task
    public function index(Task $task)
    {
        return $task->comments()->with('replies')->get();
    }



    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'comment' => 'required|string',
        ]);

        // Get the authenticated user's username
        $username = $request->username ? $request->username : 'Guest';  

        $comment = new Comment([
            'task_id' => $request->task_id,
            'comment' => $request->comment,
            'username' => $username,  
        ]);

        $comment->save();

        return response()->json($comment, 201); 
    }


    // Delete a comment (soft delete logic)
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json(['message' => 'Comment deleted (soft delete logic).']);
    }
}

