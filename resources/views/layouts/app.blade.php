<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'EduManage') }}</title>

    {{-- Apply the saved theme before first paint to avoid a flash --}}
    <script>
        document.documentElement.setAttribute(
            'data-bs-theme',
            localStorage.getItem('edumanage-theme') || 'light'
        );
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    data-flash-success="{{ session('success') }}"
    data-flash-error="{{ session('error') }}"
    data-flash-status="{{ session('status') }}"
>
    <div id="page-loader"><div class="spinner-brand" role="status" aria-label="Loading"></div></div>

    <div class="app-shell">
        @include('layouts.partials.sidebar')
        <div class="sidebar-backdrop" aria-hidden="true"></div>

        <div class="app-main">
            @include('layouts.partials.topbar')

            <main class="app-content">
                @yield('content')
            </main>

            <footer class="app-footer">
                <span>&copy; {{ date('Y') }} {{ config('app.name') }} — Student Management System</span>
                <span>v1.0</span>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
