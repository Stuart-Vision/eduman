<p class="text-body-secondary small">Use a long, random password to keep your account secure.</p>

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="row g-3">
        <div class="col-md-12">
            <label for="update_password_current_password" class="form-label">Current password</label>
            <input id="update_password_current_password" type="password" name="current_password"
                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                   autocomplete="current-password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div class="col-md-6">
            <label for="update_password_password" class="form-label">New password</label>
            <input id="update_password_password" type="password" name="password"
                   class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                   autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password')" />
        </div>

        <div class="col-md-6">
            <label for="update_password_password_confirmation" class="form-label">Confirm new password</label>
            <input id="update_password_password_confirmation" type="password" name="password_confirmation"
                   class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                   autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>
    </div>

    <div class="mt-3 d-flex align-items-center gap-3">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-key me-2"></i>Update password</button>
        @if (session('status') === 'password-updated')
            <span class="text-success small"><i class="fa-solid fa-check me-1"></i>Saved.</span>
        @endif
    </div>
</form>
