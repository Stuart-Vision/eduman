<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    public function definition(): array
    {
        $action = fake()->randomElement(['created', 'updated', 'logged_in', 'registered']);

        return [
            'user_id' => User::factory(),
            'action' => $action,
            'description' => fake()->sentence(6),
            'ip_address' => fake()->ipv4(),
            'created_at' => fake()->dateTimeBetween('-14 days'),
        ];
    }
}
