@php
    /**
     * Sidebar menu definition.
     * Items automatically enable themselves once their route exists
     * (each upcoming module registers its routes), so this file never
     * needs touching as modules land. 'roles' limits visibility.
     */
    $menu = [
        ['section' => null, 'items' => [
            ['label' => 'Dashboard', 'icon' => 'fa-gauge-high', 'route' => 'dashboard', 'roles' => ['admin', 'teacher', 'student']],
        ]],
        ['section' => 'Academic', 'items' => [
            ['label' => 'Students', 'icon' => 'fa-user-graduate', 'route' => 'students.index', 'roles' => ['admin', 'teacher']],
            ['label' => 'Teachers', 'icon' => 'fa-chalkboard-user', 'route' => 'teachers.index', 'roles' => ['admin']],
            ['label' => 'Courses', 'icon' => 'fa-book-open', 'route' => 'courses.index', 'roles' => ['admin', 'teacher', 'student']],
            ['label' => 'Batches', 'icon' => 'fa-layer-group', 'route' => 'batches.index', 'roles' => ['admin', 'teacher']],
            ['label' => 'Subjects', 'icon' => 'fa-bookmark', 'route' => 'subjects.index', 'roles' => ['admin', 'teacher']],
            ['label' => 'Enrollments', 'icon' => 'fa-clipboard-list', 'route' => 'enrollments.index', 'roles' => ['admin']],
        ]],
        ['section' => 'Operations', 'items' => [
            ['label' => 'Attendance', 'icon' => 'fa-calendar-check', 'route' => 'attendance.index', 'roles' => ['admin', 'teacher', 'student']],
            ['label' => 'Fees', 'icon' => 'fa-file-invoice-dollar', 'route' => 'fees.index', 'roles' => ['admin', 'student']],
            ['label' => 'Examinations', 'icon' => 'fa-graduation-cap', 'route' => 'exams.index', 'roles' => ['admin', 'teacher', 'student']],
            ['label' => 'Reports', 'icon' => 'fa-chart-column', 'route' => 'reports.index', 'roles' => ['admin', 'teacher']],
        ]],
        ['section' => 'System', 'items' => [
            ['label' => 'Settings', 'icon' => 'fa-gear', 'route' => 'settings.index', 'roles' => ['admin']],
            ['label' => 'My Profile', 'icon' => 'fa-circle-user', 'route' => 'profile.edit', 'roles' => ['admin', 'teacher', 'student']],
        ]],
    ];
@endphp

<aside class="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <span class="brand-mark"><i class="fa-solid fa-graduation-cap"></i></span>
        <span class="brand-text">{{ config('app.name') }}</span>
    </a>

    <nav class="sidebar-nav">
        @foreach ($menu as $group)
            @php
                $visible = collect($group['items'])
                    ->filter(fn ($item) => auth()->user()->hasRole(...$item['roles']));
            @endphp

            @if ($visible->isNotEmpty())
                @if ($group['section'])
                    <div class="sidebar-section">{{ $group['section'] }}</div>
                @endif

                @foreach ($visible as $item)
                    @if (Route::has($item['route']))
                        <a href="{{ route($item['route']) }}"
                           class="sidebar-link {{ request()->routeIs(Str::before($item['route'], '.').'*') ? 'active' : '' }}">
                            <i class="fa-solid {{ $item['icon'] }}"></i>
                            <span class="link-label">{{ $item['label'] }}</span>
                        </a>
                    @else
                        <span class="sidebar-link disabled" title="Coming soon">
                            <i class="fa-solid {{ $item['icon'] }}"></i>
                            <span class="link-label">{{ $item['label'] }}</span>
                            <span class="soon-pill">SOON</span>
                        </span>
                    @endif
                @endforeach
            @endif
        @endforeach
    </nav>

    <div class="sidebar-footer">
        Signed in as <strong>{{ auth()->user()->role->label() }}</strong>
    </div>
</aside>
