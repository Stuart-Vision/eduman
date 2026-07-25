<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Welcome') — {{ config('app.name', 'EduManage') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-page">
        <div class="glass-card fade-up">
            <div class="text-center mb-4">
                <div class="brand-mark"><i class="fa-solid fa-graduation-cap"></i></div>
                <h1 class="h4 fw-bold mb-1">{{ config('app.name') }}</h1>
                <p class="mb-0" style="color: rgba(255,255,255,.65); font-size: .85rem;">
                    @yield('subtitle', 'Student Management System')
                </p>
            </div>

            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
