<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'task_id' => Task::factory(), // Link to a Task
            'comment' => $this->faker->sentence(),
            'parent_id' => null, // Or use a random comment ID for replies
        ];
    }
}
