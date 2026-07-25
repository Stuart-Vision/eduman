@extends('layouts.guest')

@section('title', 'Reset password')
@section('subtitle', 'Choose a new password for your account.')

@section('content')
    <form method="POST" action="{{ route('password.store') }}" novalidate>
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                   class="form-control @error('email') is-invalid @enderror" required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New password</label>
            <div class="input-group">
                <input id="password" type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Min. 8 characters" required autocomplete="new-password">
                <button type="button" class="input-group-text" data-toggle-password="#password" aria-label="Show password">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-control" required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <button type="submit" class="btn btn-light w-100 py-2">
            <i class="fa-solid fa-key me-2"></i>Reset password
        </button>
    </form>
@endsection
