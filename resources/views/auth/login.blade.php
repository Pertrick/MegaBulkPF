<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-2">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-primary text-darkmode font-bold text-lg">MB</span>
                <span class="text-xs font-semibold text-grey tracking-wide uppercase">Mega Bulk Service</span>
            </a>
        </x-slot>

        <x-auth-session-status class="mb-4" :status="session('status')" />
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}" class="text-left text-white">
            @csrf

            <div class="mb-[22px]">
                <input type="email"
                       name="email"
                       id="email"
                       value="{{ old('email') }}"
                       placeholder="{{ __('Email') }}"
                       required
                       autofocus
                       class="w-full rounded-md border border-dark_border bg-transparent px-5 py-3 text-base text-white outline-none transition placeholder:text-muted focus:border-primary focus:ring-2 focus:ring-primary/60">
            </div>

            <div class="mb-[22px]">
                <input type="password"
                       name="password"
                       id="password"
                       placeholder="{{ __('Password') }}"
                       required
                       autocomplete="current-password"
                       class="w-full rounded-md border border-dark_border bg-transparent px-5 py-3 text-base text-white outline-none transition placeholder:text-muted focus:border-primary focus:ring-2 focus:ring-primary/60">
            </div>

            <div class="mb-6">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me"
                           type="checkbox"
                           name="remember"
                           class="rounded border-dark_border bg-transparent text-primary focus:border-primary focus:ring-primary">
                    <span class="ml-2 text-sm text-grey">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="mb-9">
                <button type="submit"
                        class="w-full py-3 rounded-lg text-lg font-semibold bg-primary text-darkmode border border-primary shadow-cause-shadow hover:bg-transparent hover:text-primary hover:shadow-none transition">
                    {{ __('Sign In') }}
                </button>
            </div>
        </form>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="mb-4 inline-block text-sm text-white hover:text-primary transition">
                {{ __('Forgot your password?') }}
            </a>
        @endif

        <p class="text-white text-base">
            {{ __('Not a member yet?') }}
            <a href="{{ route('register') }}" class="text-primary hover:underline">{{ __('Sign Up') }}</a>
        </p>
    </x-auth-card>
</x-guest-layout>
