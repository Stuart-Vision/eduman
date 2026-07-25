<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'gender',
        'phone',
        'avatar',
        'status',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'status' => UserStatus::class,
        ];
    }

    /* -----------------------------------------------------------------
     |  Role helpers
     | -----------------------------------------------------------------
     */

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isTeacher(): bool
    {
        return $this->role === UserRole::Teacher;
    }

    public function isStudent(): bool
    {
        return $this->role === UserRole::Student;
    }

    public function hasRole(UserRole|string ...$roles): bool
    {
        foreach ($roles as $role) {
            $role = $role instanceof UserRole ? $role : UserRole::from($role);

            if ($this->role === $role) {
                return true;
            }
        }

        return false;
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::Active;
    }

    /* -----------------------------------------------------------------
     |  Relationships
     | -----------------------------------------------------------------
     */

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /* -----------------------------------------------------------------
     |  Presentation helpers
     | -----------------------------------------------------------------
     */

    /**
     * Public URL of the avatar, or null when the user has none uploaded.
     */
    public function avatarUrl(): ?string
    {
        return $this->avatar ? Storage::url($this->avatar) : null;
    }

    /**
     * Initials used for the fallback avatar bubble (e.g. "John Doe" => "JD").
     */
    public function initials(): string
    {
        return collect(explode(' ', trim($this->name)))
            ->filter()
            ->take(2)
            ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
    }
}
