@extends('layouts.guest')

@section('title', 'Confirm password')
@section('subtitle', 'Please confirm your password to continue.')

@section('content')
    <form method="POST" action="{{ route('password.confirm') }}" novalidate>
        @csrf

        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input id="password" type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required autocomplete="current-password">
                <button type="button" class="input-group-text" data-toggle-password="#password" aria-label="Show password">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <button type="submit" class="btn btn-light w-100 py-2">
            <i class="fa-solid fa-shield-halved me-2"></i>Confirm
        </button>
    </form>
@endsection
