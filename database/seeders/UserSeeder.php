<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Demo credentials (all use password: "password"):
     *  - admin@edumanage.test
     *  - teacher@edumanage.test
     *  - student@edumanage.test
     */
    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'System Administrator',
            'email' => 'admin@edumanage.test',
            'gender' => 'male',
            'created_at' => now()->subMonths(11),
        ]);

        User::factory()->teacher()->create([
            'name' => 'Jane Peterson',
            'email' => 'teacher@edumanage.test',
            'gender' => 'female',
            'created_at' => now()->subMonths(10),
        ]);

        User::factory()->student()->create([
            'name' => 'John Carter',
            'email' => 'student@edumanage.test',
            'gender' => 'male',
            'created_at' => now()->subMonths(9),
        ]);

        // Bulk demo data so charts and counters have something to show.
        $teachers = User::factory()->teacher()->count(8)->create();
        $students = User::factory()->student()->count(40)->create();

        // A realistic-looking activity feed for the dashboard widget.
        $students->random(10)
            ->merge($teachers->random(3))
            ->each(function (User $user) use ($admin) {
                ActivityLog::factory()->create([
                    'user_id' => $admin->id,
                    'action' => 'created',
                    'description' => "Account created for {$user->name}",
                    'subject_type' => $user->getMorphClass(),
                    'subject_id' => $user->id,
                ]);
            });
    }
}
