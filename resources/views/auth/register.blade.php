<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-2">
                <img src="{{ asset('images/planetf_logo.f2c8caa1.svg') }}" alt="Planet F" class="h-12 w-auto">
                <span class="text-xs font-semibold text-grey tracking-wide uppercase">Planet F</span>
            </a>
        </x-slot>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}" class="text-left text-white">
            @csrf

            <div class="mb-[22px]">
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name') }}"
                       placeholder="{{ __('Name') }}"
                       required
                       autofocus
                       class="w-full rounded-md border border-dark_border bg-transparent px-5 py-3 text-base text-white outline-none transition placeholder:text-muted focus:border-primary focus:ring-2 focus:ring-primary/60">
            </div>

            <div class="mb-[22px]">
                <input type="email"
                       name="email"
                       id="email"
                       value="{{ old('email') }}"
                       placeholder="{{ __('Email') }}"
                       required
                       class="w-full rounded-md border border-dark_border bg-transparent px-5 py-3 text-base text-white outline-none transition placeholder:text-muted focus:border-primary focus:ring-2 focus:ring-primary/60">
            </div>

            <div class="mb-[22px]">
                <input type="password"
                       name="password"
                       id="password"
                       placeholder="{{ __('Password') }}"
                       required
                       autocomplete="new-password"
                       class="w-full rounded-md border border-dark_border bg-transparent px-5 py-3 text-base text-white outline-none transition placeholder:text-muted focus:border-primary focus:ring-2 focus:ring-primary/60">
            </div>

            <div class="mb-[22px]">
                <input type="password"
                       name="password_confirmation"
                       id="password_confirmation"
                       placeholder="{{ __('Confirm Password') }}"
                       required
                       class="w-full rounded-md border border-dark_border bg-transparent px-5 py-3 text-base text-white outline-none transition placeholder:text-muted focus:border-primary focus:ring-2 focus:ring-primary/60">
            </div>

            <div class="mb-9">
                <button type="submit"
                        class="w-full py-3 rounded-lg text-lg font-semibold bg-primary text-darkmode border border-primary shadow-cause-shadow hover:bg-transparent hover:text-primary hover:shadow-none transition">
                    {{ __('Sign Up') }}
                </button>
            </div>
        </form>

        <p class="text-white text-sm mb-4">
            {{ __('By creating an account you agree to our') }}
            <a href="#" class="text-primary hover:underline">{{ __('Privacy') }}</a>
            {{ __('and') }}
            <a href="#" class="text-primary hover:underline">{{ __('Policy') }}</a>.
        </p>

        <p class="text-white text-base">
            {{ __('Already have an account?') }}
            <a href="https://www.planetf.ng/login" class="text-primary hover:underline">{{ __('Sign In') }}</a>
        </p>
    </x-auth-card>
</x-guest-layout>
