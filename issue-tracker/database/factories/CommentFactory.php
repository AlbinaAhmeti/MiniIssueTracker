<?php

namespace Database\Factories;
use App\Models\Issue;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory {
    public function definition(): array {
        return [
            'issue_id' => Issue::factory(),
            'author_name' => $this->faker->name(),
            'body' => $this->faker->sentence(12),
        ];
    }
}

