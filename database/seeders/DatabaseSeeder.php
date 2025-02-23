<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventParticipant;
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
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        User::factory(10)->create();

        Event::factory(20)->create();

        EventParticipant::factory(20)->create();
    }
}
