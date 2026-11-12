<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pe-10" type="password" name="password" required autocomplete="new-password" />
                <button type="button" class="absolute end-2 top-2 text-gray-500" data-password-target="password" aria-label="Tampilkan password">◉</button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <div class="relative">
                <x-text-input id="password_confirmation" class="block mt-1 w-full pe-10"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />
                <button type="button" class="absolute end-2 top-2 text-gray-500" data-password-target="password_confirmation" aria-label="Tampilkan password">◉</button>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
<script>
    document.querySelectorAll('[data-password-target]').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById(button.dataset.passwordTarget);
            const visible = input.type === 'password';
            input.type = visible ? 'text' : 'password';
            button.textContent = visible ? '◉' : '◌';
            button.setAttribute('aria-label', visible ? 'Sembunyikan password' : 'Tampilkan password');
        });
    });
</script>
