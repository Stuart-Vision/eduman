@extends('layouts.guest')

@section('title', 'Sign in')
@section('subtitle', 'Welcome back! Sign in to your account.')

@section('content')
    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="you@example.com" required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between">
                <label for="password" class="form-label">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small">Forgot password?</a>
                @endif
            </div>
            <div class="input-group">
                <input id="password" type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="••••••••" required autocomplete="current-password">
                <button type="button" class="input-group-text" data-toggle-password="#password" aria-label="Show password">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="form-check mb-4">
            <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
            <label for="remember_me" class="form-check-label small">Remember me</label>
        </div>

        <button type="submit" class="btn btn-light w-100 py-2">
            <i class="fa-solid fa-right-to-bracket me-2"></i>Sign in
        </button>

        @if (Route::has('register'))
            <p class="text-center small mt-4 mb-0" style="color: rgba(255,255,255,.7);">
                Don't have an account? <a href="{{ route('register') }}">Register</a>
            </p>
        @endif
    </form>

    @if (app()->environment('local'))
        <div class="demo-accounts mt-4 pt-3" style="border-top: 1px solid rgba(255,255,255,.2);">
            <p class="small text-center mb-2" style="color: rgba(255,255,255,.6);">Demo accounts — one-click fill</p>
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-sm" data-demo="admin@edumanage.test">Admin</button>
                <button type="button" class="btn btn-sm" data-demo="teacher@edumanage.test">Teacher</button>
                <button type="button" class="btn btn-sm" data-demo="student@edumanage.test">Student</button>
            </div>
        </div>

        @push('scripts')
            <script>
                document.querySelectorAll('[data-demo]').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        document.getElementById('email').value = btn.dataset.demo;
                        document.getElementById('password').value = 'password';
                    });
                });
            </script>
        @endpush
    @endif
@endsection
