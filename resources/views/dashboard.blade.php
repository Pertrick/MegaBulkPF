<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-muted uppercase tracking-[0.2em] mb-1">
                    {{ __('Overview') }}
                </p>
                <h2 class="font-semibold text-24 text-grey leading-tight">
                    {{ __('Dashboard') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8 space-y-6">
        <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-2xl bg-dark_grey border border-dark_border px-6 py-5 shadow-cause-shadow">
                <p class="text-sm text-muted mb-2">{{ __('Balance') }}</p>
                <p class="text-30 font-semibold text-primary">₦0.00</p>
            </div>
            <div class="rounded-2xl bg-dark_grey border border-dark_border px-6 py-5 shadow-cause-shadow">
                <p class="text-sm text-muted mb-2">{{ __('Airtime Purchases') }}</p>
                <p class="text-30 font-semibold text-grey">0</p>
            </div>
            <div class="rounded-2xl bg-dark_grey border border-dark_border px-6 py-5 shadow-cause-shadow">
                <p class="text-sm text-muted mb-2">{{ __('Data Purchases') }}</p>
                <p class="text-30 font-semibold text-grey">0</p>
            </div>
        </div>

        <div class="rounded-2xl bg-dark_grey border border-dark_border px-6 py-6 shadow-cause-shadow">
            <h3 class="text-18 font-semibold text-grey mb-2">
                {{ __('Welcome back!') }}
            </h3>
            <p class="text-sm text-section">
                {{ __("You're logged in. As we integrate more of the Crypgo UI, key stats and recent activity will appear here.") }}
            </p>
        </div>
    </div>
</x-app-layout>
