<?php

namespace Database\Seeders;

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
        $this->call(DemoSeeder::class); 

        $owner1 = \App\Models\User::factory()->create([
            'name' => 'Owner One',
            'email' => 'owner1@example.com',
            'password' => bcrypt('password'),
        ]);

        $owner2 = \App\Models\User::factory()->create([
            'name' => 'Owner Two',
            'email' => 'owner2@example.com',
            'password' => bcrypt('password'),
        ]);

        // Projekte që i takojnë owner1
        \App\Models\Project::factory()->count(3)->create(['owner_id' => $owner1->id]);

        // Projekte që i takojnë owner2
        \App\Models\Project::factory()->count(2)->create(['owner_id' => $owner2->id]);
    }
}
