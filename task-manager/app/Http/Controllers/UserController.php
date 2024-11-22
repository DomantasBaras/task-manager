<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Get all tasks
    public function index()
    {
        return USer::all();
    }

    // Get a specific task
    public function show(User $user)
    {
        return $user->load('comments'); 
    }

    // Create a new task
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string',
            'password' => 'required|string|max:255',
        ]);

        $user = User::create($validated);

        return response()->json($user, 201);
    }
}

