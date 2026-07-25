<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_admins_see_the_full_dashboard_with_finance_widgets(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Total Students')
            ->assertSee('Fees Collected')
            ->assertSee('Revenue');
    }

    public function test_students_see_the_dashboard_without_finance_widgets(): void
    {
        $student = User::factory()->student()->create();

        $this->actingAs($student)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Total Students')
            ->assertDontSee('Fees Collected');
    }

    public function test_deactivated_users_are_logged_out_and_blocked(): void
    {
        $user = User::factory()->student()->inactive()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect('/login');

        $this->assertGuest();
    }
}
