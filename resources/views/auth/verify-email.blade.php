@extends('layouts.guest')

@section('title', 'Verify email')
@section('subtitle', 'One more step to activate your account.')

@section('content')
    <p class="small text-center" style="color: rgba(255,255,255,.8);">
        Thanks for signing up! Please verify your email address by clicking the link
        we just emailed to you. Didn't receive it? We'll gladly send another.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success py-2 small" role="alert">
            <i class="fa-solid fa-circle-check me-1"></i>
            A new verification link has been sent to your email address.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
        @csrf
        <button type="submit" class="btn btn-light w-100 py-2">
            <i class="fa-solid fa-envelope me-2"></i>Resend verification email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="text-center">
        @csrf
        <button type="submit" class="btn btn-link btn-sm text-white">Sign out</button>
    </form>
@endsection
