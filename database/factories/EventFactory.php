<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'name' => $name = $this->faker->sentence(),
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(rand(50, 100)),
            'location' => $this->faker->city(),
            'is_private' => $this->faker->boolean(),
            'max_participants' => random_int(5, 100),
            'start_date' => $start = now()->addDays(rand(1, 356)),
            'end_date' => $start->addDay(rand(1, 7)),
        ];
    }
}
