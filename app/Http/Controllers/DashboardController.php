<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboard,
    ) {
    }

    /**
     * Role-aware dashboard. All roles share the layout; the view hides
     * finance/admin widgets from teachers and students via @can checks.
     */
    public function __invoke(): View
    {
        return view('dashboard.index', [
            'stats' => $this->dashboard->statistics(),
            'recentActivities' => $this->dashboard->recentActivities(),
            // Single JSON payload consumed by initDashboard() in app.js.
            'chartPayload' => [
                'revenue' => $this->dashboard->revenueChart(),
                'registrations' => $this->dashboard->registrationChart(),
                'gender' => $this->dashboard->genderChart(),
                'attendance' => $this->dashboard->attendanceChart(),
                'events' => $this->dashboard->calendarEvents(),
            ],
        ]);
    }
}
