<?php

namespace Database\Seeders;
use App\Models\Project;
use App\Models\Issue;
use App\Models\Tag;
use App\Models\Comment;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        
        $tags = Tag::factory()->count(8)->create();

        Project::factory()->count(5)->create()->each(function ($project) use ($tags) {
            $issues = Issue::factory()->count(rand(5,10))->create(['project_id' => $project->id]);
            foreach ($issues as $issue) {
                $issue->tags()->attach($tags->random(rand(1,3))->pluck('id')->toArray());
                Comment::factory()->count(rand(0,5))->create(['issue_id' => $issue->id]);
            }
        });
    }
}
