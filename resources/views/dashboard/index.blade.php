@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <x-page-header title="Welcome back, {{ auth()->user()->name }} 👋">
        <x-slot:breadcrumbs>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </x-slot:breadcrumbs>
    </x-page-header>

    {{-- KPI row (all roles) --}}
    <div class="row g-3 mb-3">
        <div class="col-sm-6 col-xl-3 fade-up">
            <x-stat-card label="Total Students" :value="number_format($stats['total_students'])"
                         icon="fa-user-graduate" tint="primary" />
        </div>
        <div class="col-sm-6 col-xl-3 fade-up fade-up-delay-1">
            <x-stat-card label="Total Teachers" :value="number_format($stats['total_teachers'])"
                         icon="fa-chalkboard-user" tint="violet" />
        </div>
        <div class="col-sm-6 col-xl-3 fade-up fade-up-delay-2">
            <x-stat-card label="Total Courses" :value="number_format($stats['total_courses'])"
                         icon="fa-book-open" tint="info"
                         trend="{{ $stats['active_courses'] }} active" trendIcon="fa-circle-check" />
        </div>
        <div class="col-sm-6 col-xl-3 fade-up fade-up-delay-3">
            <x-stat-card label="Registrations This Month" :value="number_format($stats['monthly_registrations'])"
                         icon="fa-user-plus" tint="success" trend="{{ now()->format('F Y') }}" />
        </div>
    </div>

    {{-- Finance & operations row (admin only) --}}
    @can('access-admin')
        <div class="row g-3 mb-3">
            <div class="col-sm-6 col-xl-3 fade-up">
                <x-stat-card label="Fees Collected" :value="'Rs ' . number_format($stats['fees_collected'], 2)"
                             icon="fa-file-invoice-dollar" tint="success" />
            </div>
            <div class="col-sm-6 col-xl-3 fade-up fade-up-delay-1">
                <x-stat-card label="Pending Fees" :value="'Rs ' . number_format($stats['fees_pending'], 2)"
                             icon="fa-hourglass-half" tint="warning" />
            </div>
            <div class="col-sm-6 col-xl-3 fade-up fade-up-delay-2">
                <x-stat-card label="Today's Attendance"
                             :value="$stats['today_attendance_rate'] !== null ? $stats['today_attendance_rate'] . '%' : '—'"
                             icon="fa-calendar-check" tint="info" trend="Attendance module coming" />
            </div>
            <div class="col-sm-6 col-xl-3 fade-up fade-up-delay-3">
                <x-stat-card label="Active Users" :value="number_format($stats['active_users'])"
                             icon="fa-bolt" tint="danger" />
            </div>
        </div>
    @endcan

    {{-- Charts --}}
    <div class="row g-3 mb-3">
        @can('access-admin')
            <div class="col-lg-8 fade-up">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fa-solid fa-chart-line me-2 text-primary"></i>Revenue — last 12 months</span>
                    </div>
                    <div class="card-body">
                        <div class="chart-box">
                            <div class="chart-skeleton skeleton"></div>
                            <canvas id="revenueChart" role="img" aria-label="Monthly revenue line chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        <div class="col-lg-4 fade-up fade-up-delay-1">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fa-solid fa-venus-mars me-2 text-primary"></i>Students by Gender
                </div>
                <div class="card-body">
                    <div class="chart-box">
                        <div class="chart-skeleton skeleton"></div>
                        <canvas id="genderChart" role="img" aria-label="Student gender distribution chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-6 fade-up">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fa-solid fa-user-plus me-2 text-primary"></i>Monthly Registrations
                </div>
                <div class="card-body">
                    <div class="chart-box chart-sm">
                        <div class="chart-skeleton skeleton"></div>
                        <canvas id="registrationChart" role="img" aria-label="Monthly registrations bar chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 fade-up fade-up-delay-1">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fa-solid fa-calendar-check me-2 text-primary"></i>Attendance — last 7 days
                </div>
                <div class="card-body">
                    <div class="chart-box chart-sm">
                        <div class="chart-skeleton skeleton"></div>
                        <canvas id="attendanceChart" role="img" aria-label="Weekly attendance line chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Activity, calendar & quick actions --}}
    <div class="row g-3">
        <div class="col-lg-4 fade-up">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i>Recent Activities
                </div>
                <div class="card-body">
                    <ul class="activity-feed">
                        @forelse ($recentActivities as $activity)
                            @php $p = $activity->presenter(); @endphp
                            <li>
                                <span class="feed-icon icon-tint-{{ $p['class'] === 'secondary' ? 'primary' : $p['class'] }}">
                                    <i class="fa-solid {{ $p['icon'] }}"></i>
                                </span>
                                <div class="min-w-0">
                                    <div class="feed-text">{{ $activity->description }}</div>
                                    <div class="feed-time">
                                        {{ $activity->user?->name ?? 'System' }} · {{ $activity->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="justify-content-center text-body-secondary py-4">
                                <i class="fa-regular fa-folder-open me-2"></i>No activity yet
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4 fade-up fade-up-delay-1">
            <div class="card h-100 calendar-widget">
                <div class="card-header">
                    <i class="fa-regular fa-calendar me-2 text-primary"></i>Calendar
                </div>
                <div class="card-body">
                    <div id="dashboard-calendar"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 fade-up fade-up-delay-2">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fa-solid fa-bolt me-2 text-primary"></i>Quick Actions
                </div>
                <div class="card-body">
                    @php
                        $actions = [
                            ['label' => 'New Student', 'icon' => 'fa-user-plus', 'route' => 'students.create', 'roles' => ['admin']],
                            ['label' => 'New Teacher', 'icon' => 'fa-chalkboard-user', 'route' => 'teachers.create', 'roles' => ['admin']],
                            ['label' => 'Take Attendance', 'icon' => 'fa-calendar-check', 'route' => 'attendance.create', 'roles' => ['admin', 'teacher']],
                            ['label' => 'Record Payment', 'icon' => 'fa-money-bill-wave', 'route' => 'fees.create', 'roles' => ['admin']],
                            ['label' => 'New Course', 'icon' => 'fa-book-open', 'route' => 'courses.create', 'roles' => ['admin']],
                            ['label' => 'My Profile', 'icon' => 'fa-circle-user', 'route' => 'profile.edit', 'roles' => ['admin', 'teacher', 'student']],
                        ];
                    @endphp
                    <div class="row g-2">
                        @foreach ($actions as $action)
                            @continue(! auth()->user()->hasRole(...$action['roles']))
                            <div class="col-6">
                                @if (Route::has($action['route']))
                                    <a href="{{ route($action['route']) }}" class="quick-action">
                                        <i class="fa-solid {{ $action['icon'] }}"></i>{{ $action['label'] }}
                                    </a>
                                @else
                                    <span class="quick-action disabled" title="Coming soon">
                                        <i class="fa-solid {{ $action['icon'] }}"></i>{{ $action['label'] }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart payload consumed by resources/js/app.js → initDashboard() --}}
    <script type="application/json" id="dashboard-data">@json($chartPayload)</script>
@endsection
