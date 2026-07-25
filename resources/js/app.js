/* ==========================================================================
   EduManage — application JavaScript
   Vendors are bundled once here and exposed as globals so Blade views can
   use small page scripts (@push('scripts')) without their own bundles.
   ========================================================================== */

import * as bootstrap from 'bootstrap';
import Chart from 'chart.js/auto';
import Swal from 'sweetalert2';
import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-buttons-bs5';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';

window.bootstrap = bootstrap;
window.Chart = Chart;
window.Swal = Swal;
window.$ = window.jQuery = $;
window.DataTable = DataTable;
window.FullCalendar = { Calendar, dayGridPlugin };

/* --------------------------------------------------------------------------
   Theme (light / dark) — persisted in localStorage.
   An inline <head> script applies the saved theme pre-paint; this handles
   the toggle button and re-skins live charts on switch.
   -------------------------------------------------------------------------- */
const THEME_KEY = 'edumanage-theme';

function currentTheme() {
    return document.documentElement.getAttribute('data-bs-theme') || 'light';
}

/** Chart colors validated for both surfaces (see dataviz palette). */
export function chartPalette() {
    const dark = currentTheme() === 'dark';
    return {
        series: dark
            ? ['#3987e5', '#d95926', '#199e70']
            : ['#2a78d6', '#eb6834', '#1baf7a'],
        ink: dark ? '#aab0c3' : '#5b6176',
        grid: dark ? '#2b3244' : '#e7e9f2',
        surface: dark ? '#1e2430' : '#ffffff',
    };
}

function applyChartDefaults() {
    const p = chartPalette();
    Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
    Chart.defaults.color = p.ink;
    Chart.defaults.borderColor = p.grid;
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.boxWidth = 8;
    Chart.defaults.plugins.tooltip.backgroundColor = currentTheme() === 'dark' ? '#12141d' : '#1f2437';
    Chart.defaults.plugins.tooltip.padding = 10;
    Chart.defaults.plugins.tooltip.cornerRadius = 8;
}

function updateThemeToggleIcon() {
    const icon = document.querySelector('#theme-toggle i');
    if (icon) icon.className = currentTheme() === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
}

function setTheme(theme) {
    document.documentElement.setAttribute('data-bs-theme', theme);
    localStorage.setItem(THEME_KEY, theme);
    updateThemeToggleIcon();
    // Rebuild dashboard charts so grids/series pick up the new surface colors.
    initDashboard();
}

/* --------------------------------------------------------------------------
   Toast + confirm helpers (used by every module)
   -------------------------------------------------------------------------- */
const toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    timer: 3500,
    timerProgressBar: true,
    showConfirmButton: false,
});

window.notify = (icon, title) => toast.fire({ icon, title });

window.confirmAction = (options = {}) =>
    Swal.fire({
        title: options.title ?? 'Are you sure?',
        text: options.text ?? "You won't be able to revert this.",
        icon: options.icon ?? 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#6b7280',
        confirmButtonText: options.confirmText ?? 'Yes, continue',
    });

/* --------------------------------------------------------------------------
   Dashboard charts & calendar
   Reads the JSON payload rendered by dashboard/index.blade.php.
   -------------------------------------------------------------------------- */
let dashboardCharts = [];

function buildChart(id, config) {
    const canvas = document.getElementById(id);
    if (!canvas) return;
    canvas.closest('.chart-box')?.querySelector('.chart-skeleton')?.remove();
    dashboardCharts.push(new Chart(canvas, config));
}

function initDashboard() {
    const el = document.getElementById('dashboard-data');
    if (!el) return;

    dashboardCharts.forEach((c) => c.destroy());
    dashboardCharts = [];
    applyChartDefaults();

    const data = JSON.parse(el.textContent);
    const p = chartPalette();
    const gridless = { grid: { display: false }, border: { display: false } };
    const softGrid = { grid: { color: p.grid }, border: { display: false }, beginAtZero: true };

    buildChart('revenueChart', {
        type: 'line',
        data: {
            labels: data.revenue.labels,
            datasets: [{
                label: 'Revenue',
                data: data.revenue.data,
                borderColor: p.series[0],
                backgroundColor: p.series[0] + '22',
                borderWidth: 2,
                fill: true,
                tension: 0.35,
                pointRadius: 0,
                pointHoverRadius: 5,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { display: false } },
            scales: { x: gridless, y: softGrid },
        },
    });

    buildChart('registrationChart', {
        type: 'bar',
        data: {
            labels: data.registrations.labels,
            datasets: [{
                label: 'Registrations',
                data: data.registrations.data,
                backgroundColor: p.series[0],
                borderRadius: 4,
                maxBarThickness: 26,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: gridless, y: { ...softGrid, ticks: { precision: 0 } } },
        },
    });

    buildChart('genderChart', {
        type: 'doughnut',
        data: {
            labels: data.gender.labels,
            datasets: [{
                data: data.gender.data,
                backgroundColor: p.series,
                borderColor: p.surface, // 2px surface gap between segments
                borderWidth: 2,
                hoverOffset: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: { legend: { position: 'bottom' } },
        },
    });

    buildChart('attendanceChart', {
        type: 'line',
        data: {
            labels: data.attendance.labels,
            datasets: [{
                label: 'Attendance %',
                data: data.attendance.data,
                borderColor: p.series[2],
                backgroundColor: p.series[2] + '22',
                borderWidth: 2,
                fill: true,
                tension: 0.35,
                pointRadius: 3,
                pointHoverRadius: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { display: false } },
            scales: { x: gridless, y: { ...softGrid, max: 100 } },
        },
    });

    // Calendar widget (re-created on theme change is harmless & cheap)
    const calendarEl = document.getElementById('dashboard-calendar');
    if (calendarEl) {
        calendarEl.innerHTML = '';
        new Calendar(calendarEl, {
            plugins: [dayGridPlugin],
            initialView: 'dayGridMonth',
            height: 'auto',
            headerToolbar: { left: 'title', center: '', right: 'prev,next' },
            events: data.events,
        }).render();
    }
}

/* --------------------------------------------------------------------------
   Boot
   -------------------------------------------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
    updateThemeToggleIcon();
    initDashboard();

    // Theme toggle
    document.getElementById('theme-toggle')?.addEventListener('click', () => {
        setTheme(currentTheme() === 'dark' ? 'light' : 'dark');
    });

    // Sidebar: collapse on desktop, off-canvas on mobile
    const mobile = () => window.matchMedia('(max-width: 991.98px)').matches;

    document.getElementById('sidebar-toggle')?.addEventListener('click', () => {
        if (mobile()) {
            document.body.classList.toggle('sidebar-mobile-open');
        } else {
            document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('edumanage-sidebar', document.body.classList.contains('sidebar-collapsed') ? '1' : '0');
        }
    });

    document.querySelector('.sidebar-backdrop')?.addEventListener('click', () => {
        document.body.classList.remove('sidebar-mobile-open');
    });

    if (!mobile() && localStorage.getItem('edumanage-sidebar') === '1') {
        document.body.classList.add('sidebar-collapsed');
    }

    // Flash messages from the session → SweetAlert toasts
    const flash = document.body.dataset;
    if (flash.flashSuccess) window.notify('success', flash.flashSuccess);
    if (flash.flashError) window.notify('error', flash.flashError);
    if (flash.flashStatus) window.notify('info', flash.flashStatus);

    // Any form marked data-confirm shows a SweetAlert first
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            window.confirmAction({ text: form.dataset.confirm }).then((r) => {
                if (r.isConfirmed) form.submit();
            });
        });
    });

    // Password visibility toggles
    document.querySelectorAll('[data-toggle-password]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const input = document.querySelector(btn.dataset.togglePassword);
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
            btn.querySelector('i')?.classList.toggle('fa-eye');
            btn.querySelector('i')?.classList.toggle('fa-eye-slash');
        });
    });
});

// Hide the page loader once everything has rendered
window.addEventListener('load', () => {
    document.getElementById('page-loader')?.classList.add('loaded');
});
