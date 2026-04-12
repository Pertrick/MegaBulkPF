@include('partials.header')

<body class="font-sans antialiased bg-darkmode text-grey min-h-screen overflow-x-hidden flex flex-col">
    @include('partials.navigation')

    <main class="flex-1 relative py-16 md:py-20 overflow-visible">
        <div class="absolute inset-0 bg-gradient-to-b from-darklight/30 to-darkmode pointer-events-none" aria-hidden="true"></div>
        <div class="pointer-events-none absolute w-72 h-72 bg-gradient-to-bl from-tealGreen from-50% to-charcoalGray to-60% blur-400 rounded-full -top-24 -right-16 z-0 opacity-70 [transform:translateZ(0)]" style="filter: blur(120px); -webkit-filter: blur(120px);" aria-hidden="true"></div>
        <div class="pointer-events-none absolute w-80 h-80 bg-gradient-to-br from-primary from-40% to-secondary to-70% blur-400 rounded-full -bottom-32 -left-20 z-0 opacity-60 [transform:translateZ(0)]" style="filter: blur(120px); -webkit-filter: blur(120px);" aria-hidden="true"></div>

        <div class="relative z-10 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-lg mx-auto">
                <div class="rounded-2xl bg-dark_grey/90 backdrop-blur-md border border-dark_border shadow-cause-shadow overflow-hidden">
                    <div class="px-6 sm:px-10 pt-10 pb-8 text-center">
                        @if ($success)
                            <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-success/20 text-success ring-1 ring-success/30">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p class="text-primary text-xs font-semibold uppercase tracking-[0.2em] mb-2">Payment confirmed</p>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Thank you</h1>
                            <p class="text-muted text-sm mb-8">
                                Your payment was successful. If you purchased airtime or data, processing will continue automatically.
                            </p>
                        @else
                            <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-error/20 text-error ring-1 ring-error/30">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <p class="text-error text-xs font-semibold uppercase tracking-[0.2em] mb-2">Payment not completed</p>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">We could not verify this payment</h1>
                            <p class="text-muted text-sm mb-8">
                                The transaction was declined, cancelled, or is still pending with your bank. If money was debited, it is usually reversed within a short time. You can try again or contact support with your reference below.
                            </p>
                        @endif

                        @if ($payment)
                            <div class="rounded-xl border border-dark_border bg-darkmode/50 text-left divide-y divide-dark_border/80">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 px-4 py-3 sm:items-center">
                                    <span class="text-xs font-semibold text-muted uppercase tracking-wide sm:col-span-1">Email</span>
                                    <span class="text-sm text-grey sm:col-span-2 break-all">{{ $payment->email }}</span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 px-4 py-3 sm:items-center">
                                    <span class="text-xs font-semibold text-muted uppercase tracking-wide sm:col-span-1">Reference</span>
                                    <span class="text-sm text-grey font-mono sm:col-span-2 break-all">{{ $payment->reference_id }}</span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 px-4 py-3 sm:items-center">
                                    <span class="text-xs font-semibold text-muted uppercase tracking-wide sm:col-span-1">Service</span>
                                    <span class="text-sm text-grey sm:col-span-2">
                                        @if ($payment->bulk_order_id)
                                            Bulk
                                        @endif
                                        {{ ucfirst($payment->service) }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 px-4 py-3 sm:items-center">
                                    <span class="text-xs font-semibold text-muted uppercase tracking-wide sm:col-span-1">Amount</span>
                                    <span class="text-sm text-white font-semibold sm:col-span-2">&#8358;{{ number_format((float) $payment->amount, 2) }}</span>
                                </div>
                            </div>
                        @elseif (! $success)
                            <p class="text-sm text-muted text-left rounded-xl border border-dark_border bg-darkmode/50 px-4 py-3">
                                No matching order was found for this link. If you completed a payment, please contact support with your bank reference or receipt.
                            </p>
                        @endif

                        <div class="mt-8 flex flex-col sm:flex-row gap-3 sm:justify-center">
                            <a href="{{ url('/') }}"
                               class="inline-flex items-center justify-center px-6 py-3 rounded-lg {{ $success ? 'bg-primary text-darkmode border border-primary hover:bg-transparent hover:text-primary' : 'bg-dark_grey text-grey border border-dark_border hover:border-primary hover:text-primary' }} text-sm font-semibold transition">
                                Back to home
                            </a>
                            @auth
                                <a href="{{ route('dashboard') }}"
                                   class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-transparent text-primary border border-primary text-sm font-semibold hover:bg-primary/10 transition">
                                    Dashboard
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>
