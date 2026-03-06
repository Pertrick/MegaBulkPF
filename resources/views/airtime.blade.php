@include('partials.header')

<body class="font-sans antialiased bg-darkmode text-grey min-h-screen overflow-x-hidden">
    @include('partials.navigation')

    <main class="py-16 md:py-20">
        {{-- Hero / upload section (match home gradient) --}}
        <section class="relative py-12 md:py-16 overflow-visible">
            <div class="absolute inset-0 bg-gradient-to-b from-darklight/40 to-darkmode" aria-hidden="true"></div>
            <div class="pointer-events-none absolute w-50 h-50 bg-gradient-to-bl from-tealGreen from-50% to-charcoalGray to-60% blur-400 rounded-full -top-40 -right-10 z-0 [transform:translateZ(0)]" style="filter: blur(120px); -webkit-filter: blur(120px);"></div>
            <div class="relative z-10 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                @if (session('message'))
                    <div class="mb-6">
                        <div class="rounded-lg bg-success/15 border border-success text-white px-4 py-3 text-center text-sm">
                            {{ session('message') }}
                        </div>
                    </div>
                @endif

                <div class="grid lg:grid-cols-2 gap-10 lg:gap-14 items-center">
                    <div class="text-center lg:text-left text-white">
                        <p class="text-primary text-sm font-semibold uppercase tracking-[0.2em] mb-3">
                            Bulk Airtime Upload
                        </p>
                        <h1 class="text-36 md:text-44 font-bold leading-tight mb-4">
                            Go Big with your next Airtime
                        </h1>
                        <p class="text-grey text-base md:text-lg max-w-xl mx-auto lg:mx-0 mb-6">
                            Upload your CSV file and distribute airtime instantly across all major networks.
                        </p>
                        <a href="{{ route('download.airtime') }}"
                           class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-secondary text-darkmode text-sm font-semibold border border-secondary hover:bg-transparent hover:text-secondary transition">
                            DOWNLOAD AIRTIME SAMPLE CSV FILE
                        </a>
                    </div>

                    <div class="bg-dark_grey border border-dark_border rounded-2xl shadow-cause-shadow p-6 text-white">
                        <h2 class="text-22 font-semibold mb-3 text-left">
                            Upload CSV file
                        </h2>
                        <p class="text-muted text-sm mb-4 text-left">
                            Select a CSV file containing the phone numbers and amounts you want to send.
                        </p>
                        <form class="space-y-4" id="airtime-upload-form">
                            <div class="flex flex-col gap-2 items-start">
                                <input id="files" type="file" accept=".csv" required
                                       class="block w-full text-sm text-white file:mr-4 file:rounded-md file:border-0 file:bg-primary file:px-3 file:py-2 file:text-xs file:font-semibold file:text-darkmode hover:file:bg-transparent hover:file:text-primary hover:file:border hover:file:border-primary border border-dark_border rounded-lg bg-darkmode/60 px-3 py-2 cursor-pointer">
                                <input type="hidden" value="airtime" id="type">
                            </div>
                            <button id="submit-file" type="submit"
                                    class="w-full inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-primary text-darkmode text-sm font-semibold border border-primary hover:bg-transparent hover:text-primary transition">
                                Preview File
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        {{-- Pricing section: 4 network cards --}}
        <section class="py-16 md:py-20 border-t border-dark_border relative overflow-visible">
            <div class="pointer-events-none absolute w-72 h-72 bg-gradient-to-bl from-tealGreen from-50% to-charcoalGray to-60% blur-400 rounded-full -top-32 -right-24 z-0 opacity-70 [transform:translateZ(0)]" style="filter: blur(120px); -webkit-filter: blur(120px);"></div>
            <div class="pointer-events-none absolute w-96 h-96 bg-gradient-to-br from-primary from-40% to-secondary to-70% blur-400 rounded-full -bottom-48 -left-32 z-0 opacity-60 [transform:translateZ(0)]" style="filter: blur(120px); -webkit-filter: blur(120px);"></div>

            <div class="relative z-10 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-white">
                <div class="text-center mb-12">
                    <h2 class="text-30 md:text-36 font-bold mb-3">
                        Airtime pricing
                    </h2>
                    <p class="text-muted text-base max-w-md mx-auto">
                        Same great 3% discount across all networks. From &#8358;50 · Max &#8358;5,000 per transaction.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6">
                    {{-- MTN --}}
                    <div class="group rounded-2xl bg-dark_grey border border-dark_border p-6 shadow-cause-shadow hover:border-primary/50 hover:shadow-lg hover:shadow-primary/5 transition-all duration-300">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/10">
                                <img src="/images/MTN-logo.jpg" width="40" height="40" class="rounded-full object-cover" alt="MTN">
                            </div>
                            <span class="text-lg font-bold text-white">MTN</span>
                        </div>
                        <p class="text-muted text-sm mb-4">
                            Bulk airtime at 3% off. Instant delivery.
                        </p>
                        <div class="inline-flex items-center gap-2 rounded-lg bg-primary/15 px-3 py-1.5 text-sm font-semibold text-primary border border-primary/30">
                            <span>3%</span>
                            <span>Discount</span>
                        </div>
                    </div>

                    {{-- Airtel --}}
                    <div class="group rounded-2xl bg-dark_grey border border-dark_border p-6 shadow-cause-shadow hover:border-secondary/50 hover:shadow-lg hover:shadow-secondary/5 transition-all duration-300">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/10">
                                <img src="/images/airtel-logo.jpg" width="40" height="40" class="rounded-full object-cover" alt="Airtel">
                            </div>
                            <span class="text-lg font-bold text-white">Airtel</span>
                        </div>
                        <p class="text-muted text-sm mb-4">
                            Bulk airtime at 3% off. Instant delivery.
                        </p>
                        <div class="inline-flex items-center gap-2 rounded-lg bg-secondary/15 px-3 py-1.5 text-sm font-semibold text-secondary border border-secondary/30">
                            <span>3%</span>
                            <span>Discount</span>
                        </div>
                    </div>

                    {{-- Glo --}}
                    <div class="group rounded-2xl bg-dark_grey border border-dark_border p-6 shadow-cause-shadow hover:border-primary/50 hover:shadow-lg hover:shadow-primary/5 transition-all duration-300">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/10">
                                <img src="/images/glo-logo.jpg" width="40" height="40" class="rounded-full object-cover" alt="Glo">
                            </div>
                            <span class="text-lg font-bold text-white">Glo</span>
                        </div>
                        <p class="text-muted text-sm mb-4">
                            Bulk airtime at 3% off. Instant delivery.
                        </p>
                        <div class="inline-flex items-center gap-2 rounded-lg bg-primary/15 px-3 py-1.5 text-sm font-semibold text-primary border border-primary/30">
                            <span>3%</span>
                            <span>Discount</span>
                        </div>
                    </div>

                    {{-- 9Mobile --}}
                    <div class="group rounded-2xl bg-dark_grey border border-dark_border p-6 shadow-cause-shadow hover:border-secondary/50 hover:shadow-lg hover:shadow-secondary/5 transition-all duration-300">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/10">
                                <img src="/images/9mobile-logo.jpg" width="40" height="40" class="rounded-full object-cover" alt="9Mobile">
                            </div>
                            <span class="text-lg font-bold text-white">9Mobile</span>
                        </div>
                        <p class="text-muted text-sm mb-4">
                            Bulk airtime at 3% off. Instant delivery.
                        </p>
                        <div class="inline-flex items-center gap-2 rounded-lg bg-secondary/15 px-3 py-1.5 text-sm font-semibold text-secondary border border-secondary/30">
                            <span>3%</span>
                            <span>Discount</span>
                        </div>
                    </div>
                </div>

                <p class="mt-8 text-center text-muted text-sm">
                    Fast, reliable bulk airtime distribution across all networks.
                </p>
            </div>
        </section>
    </main>

    {{-- CSV preview modal and footer --}}
    @include('modals.csv')
    @push('scripts')
        @include('partials.scripts-csv')
    @endpush
    @include('partials.footer')
