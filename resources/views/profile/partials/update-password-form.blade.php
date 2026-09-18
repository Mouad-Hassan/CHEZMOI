<section>
    <header>
        <h2 class="flex items-center gap-2 text-lg font-bold text-charcoal">
            <span class="h-5 w-1 rounded-full bg-gold block"></span>
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-charcoal/70">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p id="password-status-message"
                    class="text-sm text-gold-dark font-semibold"
                >{{ __('Saved.') }}</p>
                <script>
                    setTimeout(() => {
                        const message = document.getElementById('password-status-message');
                        if (message) {
                            message.style.display = 'none';
                        }
                    }, 2000);
                </script>
            @endif
        </div>
    </form>
</section>
