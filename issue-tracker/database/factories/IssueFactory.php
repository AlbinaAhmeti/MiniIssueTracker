<?php

namespace Database\Factories;
use App\Models\Project;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Issue>
 */
class IssueFactory extends Factory {
    public function definition(): array {
        return [
            'project_id' => Project::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['open','in_progress','closed']),
            'priority' => $this->faker->randomElement(['low','medium','high']),
            'due_date' => $this->faker->optional()->date(),
        ];
    }
}

