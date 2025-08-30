<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory {
    public function definition(): array {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'start_date' => now()->subDays(rand(0,30))->toDateString(),
            'deadline' => now()->addDays(rand(10,60))->toDateString(),
        ];
    }
}
