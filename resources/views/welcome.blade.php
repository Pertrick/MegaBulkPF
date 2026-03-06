@include('partials.header')

<body class="font-sans antialiased bg-darkmode text-grey min-h-screen">
    @include('partials.navigation')

    <main>
        @if (session('message'))
            <div class="max-w-screen-xl mx-auto px-4 py-4">
                <div class="rounded-lg bg-success/20 border border-success text-grey px-4 py-3 text-center">
                    {{ session('message') }}
                </div>
            </div>
        @endif

        <section class="relative py-16 md:py-24 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-b from-darklight/40 to-darkmode" aria-hidden="true"></div>
            <div class="pointer-events-none absolute w-50 h-50 bg-gradient-to-bl from-tealGreen from-50% to-charcoalGray to-60% blur-400 rounded-full -top-40 -right-10 z-0"></div>
            <div class="relative z-10 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-10 lg:gap-14 items-center">
                    <div class="text-center lg:text-left order-2 lg:order-1">
                        <p class="text-primary text-sm font-semibold uppercase tracking-[0.2em] mb-4">
                            The Best Data and Airtime Provider
                        </p>
                        <h1 class="text-44 md:text-54 font-bold text-white leading-tight mb-6">
                            Go Big with your next Data or Airtime
                        </h1>
                        <p class="text-grey text-lg max-w-xl mx-auto lg:mx-0 mb-8 lg:mb-10">
                            Bulk data purchase made easy and affordable. Choose from our pool of discounted airtime and data across all networks.
                        </p>
                    </div>
                    <div class="order-1 lg:order-2 flex justify-center">
                        <img src="{{ asset('images/data.jpg') }}"
                             alt="Data and airtime connectivity"
                             class="w-full max-w-lg rounded-2xl border border-dark_border shadow-cause-shadow object-cover" loading="lazy">
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 md:py-24 border-t border-dark_border relative overflow-hidden">
            <div class="pointer-events-none absolute w-72 h-72 bg-gradient-to-bl from-tealGreen from-50% to-charcoalGray to-60% blur-400 rounded-full -top-32 -left-24 z-0 opacity-70"></div>
            <div class="pointer-events-none absolute w-96 h-96 bg-gradient-to-br from-primary from-40% to-secondary to-70% blur-400 rounded-full -bottom-40 -right-24 z-0 opacity-70"></div>

            <div class="relative z-10 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-white">
                <div class="text-center mb-14">
                    <h2 class="text-30 md:text-36 font-bold text-white mb-4">
                        Choose your Service Plan
                    </h2>
                    <p class="text-muted text-base max-w-xl mx-auto">
                        Choose the best of services from our pool of discounted Airtime and Data. Our data services cover all networks.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                    <div class="rounded-2xl bg-dark_grey border border-dark_border p-8 shadow-cause-shadow hover:border-primary/50 transition">
                        <div class="inline-flex h-14 w-14 items-center justify-center rounded-xl bg-primary/20 text-primary mb-6">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <h3 class="text-22 font-semibold text-white mb-3">Airtime</h3>
                        <p class="text-muted text-sm mb-4">
                            Bulk purchase of airtime made easy and affordable.
                        </p>
                        <p class="text-primary text-sm font-semibold mb-6">From &#8358;50</p>
                        <a href="{{ route('airtime') }}"
                           class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-primary text-darkmode text-sm font-semibold border border-primary hover:bg-transparent hover:text-primary transition">
                            See more
                        </a>
                    </div>

                    <div class="rounded-2xl bg-dark_grey border border-dark_border p-8 shadow-cause-shadow hover:border-secondary/50 transition">
                        <div class="inline-flex h-14 w-14 items-center justify-center rounded-xl bg-secondary/20 text-secondary mb-6">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-22 font-semibold text-white mb-3">Data Subscription</h3>
                        <p class="text-muted text-sm mb-4">
                            Purchase data in bulk at a discounted price, simply and easy.
                        </p>
                        <p class="text-secondary text-sm font-semibold mb-6">From 100 MB</p>
                        <a href="{{ route('data') }}"
                           class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-secondary text-darkmode text-sm font-semibold border border-secondary hover:bg-transparent hover:text-secondary transition">
                            See more
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 md:py-24 border-t border-dark_border/60 bg-darkmode relative overflow-hidden">
            <div class="pointer-events-none absolute bg-gradient-to-br from-tealGreen to-charcoalGray w-96 h-96 rounded-full -bottom-64 -left-32 blur-400 opacity-60 z-0"></div>
            <div class="pointer-events-none absolute bg-gradient-to-bl from-tealGreen from-40% to-charcoalGray to-80% w-72 h-72 rounded-full -top-24 -right-20 blur-400 opacity-70 z-0"></div>

            <div class="relative z-10 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-white">
                <div class="grid gap-10 lg:grid-cols-2 items-center">
                    <div>
                        <p class="text-primary text-sm font-semibold uppercase tracking-[0.2em] mb-3">
                            Why customers choose us
                        </p>
                        <h2 class="text-30 md:text-36 font-bold text-white mb-4">
                            Fast, reliable and built for volume.
                        </h2>
                        <p class="text-muted text-sm md:text-base mb-6">
                            Mega Bulk Service is designed for businesses and resellers that need consistent uptime,
                            instant delivery and transparent pricing across all major networks.
                        </p>
                        <ul class="space-y-3 text-sm text-grey">
                            <li class="flex items-start gap-3">
                                <span class="mt-1 h-5 w-5 rounded-full bg-primary/20 text-primary flex items-center justify-center text-xs">✓</span>
                                <span>Automated processing so your airtime and data land in seconds.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-1 h-5 w-5 rounded-full bg-secondary/20 text-secondary flex items-center justify-center text-xs">✓</span>
                                <span>Competitive bulk pricing that helps you maximise your margins.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-1 h-5 w-5 rounded-full bg-warning/20 text-warning flex items-center justify-center text-xs">✓</span>
                                <span>Simple dashboard to track your transactions and balances.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl bg-dark_grey border border-dark_border p-6 shadow-cause-shadow">
                            <p class="text-30 font-semibold text-primary mb-1">99.9%</p>
                            <p class="text-grey text-sm mb-2">Uptime</p>
                            <p class="text-muted text-xs">
                                Infrastructure aimed at keeping your transactions flowing round the clock.
                            </p>
                        </div>
                        <div class="rounded-2xl bg-dark_grey border border-dark_border p-6 shadow-cause-shadow">
                            <p class="text-30 font-semibold text-secondary mb-1">Instant</p>
                            <p class="text-grey text-sm mb-2">Delivery</p>
                            <p class="text-muted text-xs">
                                Real‑time processing so your customers are never kept waiting.
                            </p>
                        </div>
                        <div class="rounded-2xl bg-dark_grey border border-dark_border p-6 shadow-cause-shadow sm:col-span-2">
                            <p class="text-22 font-semibold text-white mb-2">Dedicated support</p>
                            <p class="text-muted text-xs mb-4">
                                Reach us on WhatsApp or email whenever you need assistance with a transaction.
                            </p>
                            <a href="https://wa.me/+2347011223737" target="_blank"
                               class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-primary text-darkmode text-xs font-semibold border border-primary hover:bg-transparent hover:text-primary transition">
                                Chat on WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')
