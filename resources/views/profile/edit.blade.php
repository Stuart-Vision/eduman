@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    <x-page-header title="My Profile">
        <x-slot:breadcrumbs>
            <li class="breadcrumb-item active" aria-current="page">Profile</li>
        </x-slot:breadcrumbs>
    </x-page-header>

    <div class="row g-3">
        <div class="col-lg-4 fade-up">
            <div class="card text-center">
                <div class="card-body py-4">
                    @if (auth()->user()->avatarUrl())
                        <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}" class="avatar avatar-lg mb-3">
                    @else
                        <span class="avatar avatar-lg mb-3">{{ auth()->user()->initials() }}</span>
                    @endif
                    <h2 class="h5 fw-bold mb-1">{{ auth()->user()->name }}</h2>
                    <p class="text-body-secondary small mb-2">{{ auth()->user()->email }}</p>
                    <span class="badge {{ auth()->user()->role->badgeClass() }}">{{ auth()->user()->role->label() }}</span>
                    <hr>
                    <div class="small text-body-secondary text-start">
                        <div class="mb-1"><i class="fa-regular fa-clock me-2"></i>Last login:
                            {{ auth()->user()->last_login_at?->diffForHumans() ?? 'Never' }}</div>
                        <div><i class="fa-regular fa-calendar me-2"></i>Member since:
                            {{ auth()->user()->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card mb-3 fade-up">
                <div class="card-header"><i class="fa-regular fa-id-card me-2 text-primary"></i>Profile Information</div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card mb-3 fade-up fade-up-delay-1">
                <div class="card-header"><i class="fa-solid fa-lock me-2 text-primary"></i>Update Password</div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card border-danger-subtle fade-up fade-up-delay-2">
                <div class="card-header text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i>Danger Zone</div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
