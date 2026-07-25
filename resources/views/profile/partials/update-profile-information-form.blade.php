<p class="text-body-secondary small">Update your account's name and email address.</p>

<form id="send-verification" method="POST" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="row g-3">
        <div class="col-md-6">
            <label for="name" class="form-label">Full name</label>
            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}"
                   class="form-control @error('name') is-invalid @enderror" required autocomplete="name">
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
                   class="form-control @error('email') is-invalid @enderror" required autocomplete="username">
            <x-input-error :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="form-text text-warning">
                    Your email address is unverified.
                    <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">
                        Click here to re-send the verification email.
                    </button>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <div class="form-text text-success">
                        A new verification link has been sent to your email address.
                    </div>
                @endif
            @endif
        </div>
    </div>

    <div class="mt-3 d-flex align-items-center gap-3">
        <button type="submit" class="btn btn-primary"><i class="fa-regular fa-floppy-disk me-2"></i>Save changes</button>
        @if (session('status') === 'profile-updated')
            <span class="text-success small"><i class="fa-solid fa-check me-1"></i>Saved.</span>
        @endif
    </div>
</form>
