<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Issue;
use App\Models\Tag;
use App\Models\Comment;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $tags = Tag::factory()->count(6)->create();

        Project::factory()->count(3)->create()->each(function ($p) use ($tags) {
            Issue::factory()->count(8)->create(['project_id' => $p->id])->each(function ($i) use ($tags) {
                $i->tags()->sync($tags->random(rand(0, 3))->pluck('id')->all());
                Comment::factory()->count(rand(0, 5))->create(['issue_id' => $i->id]);
            });
        });

        $users = \App\Models\User::pluck('id');
        Issue::all()->each(function ($i) use ($users) {
            $i->users()->sync($users->random(rand(0, 3))->all());
        });
    }
}
