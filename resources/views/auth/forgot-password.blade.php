@extends('layouts.guest')

@section('title', 'Forgot password')
@section('subtitle', "We'll email you a password reset link.")

@section('content')
    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="you@example.com" required autofocus>
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <button type="submit" class="btn btn-light w-100 py-2">
            <i class="fa-solid fa-paper-plane me-2"></i>Email reset link
        </button>

        <p class="text-center small mt-4 mb-0" style="color: rgba(255,255,255,.7);">
            Remembered it? <a href="{{ route('login') }}">Back to sign in</a>
        </p>
    </form>
@endsection
