<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Teacher = 'teacher';
    case Student = 'student';

    /**
     * Human-friendly label for UI display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Teacher => 'Teacher',
            self::Student => 'Student',
        };
    }

    /**
     * Bootstrap badge class used across the UI for consistent role colors.
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::Admin => 'text-bg-danger',
            self::Teacher => 'text-bg-info',
            self::Student => 'text-bg-primary',
        };
    }

    /**
     * All raw values — handy for validation rules and migrations.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
