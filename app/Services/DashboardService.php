<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Aggregates every figure the dashboard needs in one place so the
 * controller stays thin. As new modules land (Students, Courses, Fees,
 * Attendance...) their real queries replace the placeholder series here.
 */
class DashboardService
{
    /**
     * Seconds the heavy aggregates are cached. Short enough to feel live,
     * long enough to keep repeated dashboard hits cheap.
     */
    private const CACHE_TTL = 60;

    /**
     * @return array<string, mixed>
     */
    public function statistics(): array
    {
        return Cache::remember('dashboard.statistics', self::CACHE_TTL, function (): array {
            return [
                'total_students' => User::where('role', UserRole::Student)->count(),
                'total_teachers' => User::where('role', UserRole::Teacher)->count(),
                // Courses module (module 4) will replace these with Course queries.
                'total_courses' => 0,
                'active_courses' => 0,
                // Attendance module (module 8) will replace this with today's real ratio.
                'today_attendance_rate' => null,
                'monthly_registrations' => User::whereBetween('created_at', [
                    now()->startOfMonth(), now()->endOfMonth(),
                ])->count(),
                // Fees module (module 9) will replace these with Payment sums.
                'fees_collected' => 0.0,
                'fees_pending' => 0.0,
                'active_users' => User::where('status', UserStatus::Active)->count(),
            ];
        });
    }

    /**
     * Revenue collected per month for the last 12 months.
     * Zero-filled until the Fees module supplies real payments.
     *
     * @return array{labels: array<int, string>, data: array<int, float>}
     */
    public function revenueChart(): array
    {
        $months = $this->lastMonths(12);

        return [
            'labels' => $months->map(fn (Carbon $m) => $m->format('M Y'))->all(),
            'data' => $months->map(fn () => 0.0)->all(),
        ];
    }

    /**
     * New user registrations per month for the last 12 months.
     *
     * @return array{labels: array<int, string>, data: array<int, int>}
     */
    public function registrationChart(): array
    {
        $months = $this->lastMonths(12);

        // Grouped in PHP so the query stays portable (MySQL in prod, SQLite in tests).
        $counts = User::where('created_at', '>=', $months->first()->copy()->startOfMonth())
            ->pluck('created_at')
            ->countBy(fn (Carbon $createdAt) => $createdAt->format('Y-m'));

        return [
            'labels' => $months->map(fn (Carbon $m) => $m->format('M Y'))->all(),
            'data' => $months->map(fn (Carbon $m) => (int) ($counts[$m->format('Y-m')] ?? 0))->all(),
        ];
    }

    /**
     * Gender distribution of student accounts for the pie chart.
     *
     * @return array{labels: array<int, string>, data: array<int, int>}
     */
    public function genderChart(): array
    {
        $counts = User::where('role', UserRole::Student)
            ->whereNotNull('gender')
            ->selectRaw('gender, COUNT(*) as total')
            ->groupBy('gender')
            ->pluck('total', 'gender');

        return [
            'labels' => $counts->keys()->map(fn (string $g) => ucfirst($g))->all(),
            'data' => $counts->values()->map(fn ($v) => (int) $v)->all(),
        ];
    }

    /**
     * Attendance rate for each of the last 7 days.
     * Zero-filled until the Attendance module supplies real records.
     *
     * @return array{labels: array<int, string>, data: array<int, int>}
     */
    public function attendanceChart(): array
    {
        $days = collect(range(6, 0))->map(fn (int $i) => now()->subDays($i));

        return [
            'labels' => $days->map(fn (Carbon $d) => $d->format('D'))->all(),
            'data' => $days->map(fn () => 0)->all(),
        ];
    }

    /**
     * Latest activity feed entries with their actor eager-loaded.
     *
     * @return Collection<int, ActivityLog>
     */
    public function recentActivities(int $limit = 8): Collection
    {
        return ActivityLog::with('user:id,name,avatar')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Events for the calendar widget. The Notifications/Events module
     * (module 12) will feed real upcoming events here.
     *
     * @return array<int, array{title: string, start: string, className?: string}>
     */
    public function calendarEvents(): array
    {
        return [];
    }

    /**
     * @return Collection<int, Carbon> oldest → newest month starts
     */
    private function lastMonths(int $count): Collection
    {
        return collect(range($count - 1, 0))
            ->map(fn (int $i) => now()->copy()->subMonths($i)->startOfMonth());
    }
}
