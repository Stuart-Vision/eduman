<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_middleware_blocks_unauthorized_roles(): void
    {
        Route::middleware(['web', 'auth', 'role:admin'])
            ->get('/_test/admin-only', fn () => 'ok');

        $student = User::factory()->student()->create();

        $this->actingAs($student)->get('/_test/admin-only')->assertForbidden();
    }

    public function test_role_middleware_allows_authorized_roles(): void
    {
        Route::middleware(['web', 'auth', 'role:admin'])
            ->get('/_test/admin-only', fn () => 'ok');

        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/_test/admin-only')->assertOk();
    }

    public function test_admin_passes_every_gate_via_gate_before(): void
    {
        $admin = User::factory()->admin()->create();

        $this->assertTrue(Gate::forUser($admin)->allows('manage-academics'));
        $this->assertTrue(Gate::forUser($admin)->allows('access-admin'));
    }

    public function test_teacher_gates(): void
    {
        $teacher = User::factory()->teacher()->create();

        $this->assertTrue(Gate::forUser($teacher)->allows('manage-academics'));
        $this->assertFalse(Gate::forUser($teacher)->allows('access-admin'));
    }

    public function test_registration_always_creates_a_student_account(): void
    {
        $this->post('/register', [
            'name' => 'Sneaky User',
            'email' => 'sneaky@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            // Mass-assignment attempt — must be ignored.
            'role' => 'admin',
        ]);

        $this->assertSame(
            UserRole::Student,
            User::where('email', 'sneaky@example.com')->firstOrFail()->role,
        );
    }
}
