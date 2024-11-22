<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'creator' => $this->faker->name(),
            'tester' => $this->faker->name(),
            'assignee' => $this->faker->name(),
            'status' => $this->faker->randomElement(['paused', 'in progress', 'testing', 'released']),
            'type' => $this->faker->randomElement(['Task', 'Bug']),
        ];
    }
}

