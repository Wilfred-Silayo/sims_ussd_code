<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Update Password
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </header>

    <form method="post" action="{{ route('lecturer.password.update') }}" class="mt-6 py-6">
        @if (session('status') === 'password-updated')
        <p id="success-message" class="text-sm alert alert-success">{{ __('Saved.') }}</p>
        @endif
        @if (session('error'))
        <div id="successmessage" class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>{{ session('error') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @csrf
        @method('put')

        <div>
            <label for="current_password" class="form-label text-md-end">
                {{ __('Current Password') }}
            </label>
            <input id="current_password" name="current_password" type="password"
                class="mb-3 form-control @error('current_password') is-invalid @enderror"
                autocomplete="current-password" />
            @error('current_password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div>
            <label for="password" class="form-label text-md-end">
                {{ __('New Password') }}
            </label>
            <input id="password" name="password" type="password"
                class=" mb-3 form-control @error('password') is-invalid @enderror" autocomplete="new-password" />
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="form-label text-md-end">
                {{ __('Confirm Password') }}
            </label>
            <input id="password_confirmation" name="password_confirmation" type="password"
                class="mb-3 form-control @error('password_confirmation') is-invalid @enderror"
                autocomplete="new-password" />
            @error('password_confirmation')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-4 py-2 text-white btn btn-secondary">
                {{ __('Save') }}
            </button>

            <script>
            // Check if the success message element exists
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                // Hide the success message after 2 seconds
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 2000);
            }
            </script>

        </div>
    </form>
</section>