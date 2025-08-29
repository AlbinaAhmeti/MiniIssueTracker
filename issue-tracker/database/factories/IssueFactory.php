<?php

namespace Database\Factories;
use App\Models\Project;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Issue>
 */
class IssueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['open', 'in_progress', 'closed'];
        $priorities = ['low', 'medium', 'high'];
        return [
            'project_id' => Project::factory(),
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraphs(2, true),
            'status' => $this->faker->randomElement($statuses),
            'priority' => $this->faker->randomElement($priorities),
            'due_date' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
        ];
    }
}
