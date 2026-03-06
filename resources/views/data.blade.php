
@include('partials.header')

<body class="font-sans antialiased bg-darkmode text-grey min-h-screen">
    @include('partials.navigation')

    <main class="py-16 md:py-20">
        {{-- Hero / upload section (match home gradient) --}}
        <section class="relative py-12 md:py-16 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-b from-darklight/40 to-darkmode" aria-hidden="true"></div>
            <div class="pointer-events-none absolute w-50 h-50 bg-gradient-to-bl from-tealGreen from-50% to-charcoalGray to-60% blur-400 rounded-full -top-40 -right-10 z-0"></div>
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
                            Bulk Data Upload
                        </p>
                        <h1 class="text-36 md:text-44 font-bold leading-tight mb-4">
                            Go Big with your next Data
                        </h1>
                        <p class="text-grey text-base md:text-lg max-w-xl mx-auto lg:mx-0 mb-6">
                            Upload your CSV file and distribute data bundles instantly across all major networks.
                        </p>
                        <a href="{{ route('download.data') }}"
                           class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-secondary text-darkmode text-sm font-semibold border border-secondary hover:bg-transparent hover:text-secondary transition">
                            DOWNLOAD DATA SAMPLE CSV FILE
                        </a>
                    </div>

                    <div class="bg-dark_grey border border-dark_border rounded-2xl shadow-cause-shadow p-6 text-white">
                        <h2 class="text-22 font-semibold mb-3 text-left">
                            Upload CSV file
                        </h2>
                        <p class="text-muted text-sm mb-4 text-left">
                            Select a CSV file containing the phone numbers, plans and amounts you want to send.
                        </p>
                        <form class="space-y-4" id="data-upload-form">
                            <div class="flex flex-col gap-2 items-start">
                                <input id="files" type="file" accept=".csv" required
                                       class="block w-full text-sm text-white file:mr-4 file:rounded-md file:border-0 file:bg-primary file:px-3 file:py-2 file:text-xs file:font-semibold file:text-darkmode hover:file:bg-transparent hover:file:text-primary hover:file:border hover:file:border-primary border border-dark_border rounded-lg bg-darkmode/60 px-3 py-2 cursor-pointer">
                                <input type="hidden" value="data" id="type">
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

        {{-- Pricing section: modern network tabs --}}
        <section class="py-16 md:py-20 border-t border-dark_border relative overflow-hidden">
            <div class="pointer-events-none absolute w-72 h-72 bg-gradient-to-bl from-tealGreen from-50% to-charcoalGray to-60% blur-400 rounded-full -top-32 -right-24 z-0 opacity-70"></div>
            <div class="pointer-events-none absolute w-96 h-96 bg-gradient-to-br from-primary from-40% to-secondary to-70% blur-400 rounded-full -bottom-48 -left-32 z-0 opacity-60"></div>

            <div class="relative z-10 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-white">
                <div class="text-center mb-10">
                    <h2 class="text-30 md:text-36 font-bold mb-4">
                        Data pricing
                    </h2>
                    <p class="text-muted text-sm max-w-xl mx-auto mb-6">
                        Browse our discounted data bundles by network. Switch tabs to see pricing for each provider.
                    </p>

                    <div class="flex flex-wrap items-center justify-center gap-3 text-sm">
                        <button type="button"
                                class="provider-tab inline-flex items-center gap-2 rounded-full px-4 py-2 bg-primary text-darkmode border border-primary shadow-cause-shadow transition"
                                data-provider-tab="mtn">
                            <img src="/images/MTN-logo.jpg" alt="MTN" class="h-6 w-6 rounded-full object-cover">
                            <span class="font-semibold">MTN</span>
                        </button>
                        <button type="button"
                                class="provider-tab inline-flex items-center gap-2 rounded-full px-4 py-2 bg-dark_grey text-grey border border-dark_border hover:border-secondary/60 hover:text-secondary transition"
                                data-provider-tab="airtel">
                            <img src="/images/airtel-logo.jpg" alt="Airtel" class="h-6 w-6 rounded-full object-cover">
                            <span class="font-semibold">Airtel</span>
                        </button>
                        <button type="button"
                                class="provider-tab inline-flex items-center gap-2 rounded-full px-4 py-2 bg-dark_grey text-grey border border-dark_border hover:border-secondary/60 hover:text-secondary transition"
                                data-provider-tab="glo">
                            <img src="/images/glo-logo.jpg" alt="Glo" class="h-6 w-6 rounded-full object-cover">
                            <span class="font-semibold">Glo</span>
                        </button>
                        <button type="button"
                                class="provider-tab inline-flex items-center gap-2 rounded-full px-4 py-2 bg-dark_grey text-grey border border-dark_border hover:border-secondary/60 hover:text-secondary transition"
                                data-provider-tab="etisalat">
                            <img src="/images/9mobile-logo.jpg" alt="9Mobile" class="h-6 w-6 rounded-full object-cover">
                            <span class="font-semibold">9Mobile</span>
                        </button>
                    </div>
                </div>

                {{-- MTN tab --}}
                <div class="provider-panel rounded-2xl bg-dark_grey border border-dark_border p-6 md:p-8 shadow-cause-shadow" data-provider-panel="mtn">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm text-white border-separate border-spacing-y-2">
                            <thead>
                                <tr class="text-muted border-b border-dark_border/60">
                                    <th class="pb-2 font-semibold"></th>
                                    <th class="pb-2 font-semibold">Product / Service</th>
                                    <th class="pb-2 font-semibold">Code</th>
                                    <th class="pb-2 font-semibold">Amount</th>
                                    <th class="pb-2 font-semibold">Regular Discount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mtn as $value)
                                    <tr class="align-middle">
                                        <td class="py-2">
                                            <img src="/images/MTN-logo.jpg" width="40" height="40" class="rounded-full inline-block" alt="MTN">
                                        </td>
                                        <td class="py-2">{{ $value['name'] }}</td>
                                        <td class="py-2">{{ $value['coded'] }}</td>
                                        <td class="py-2">&#8358;{{ $value['price'] }}</td>
                                        <td class="py-2 text-primary font-semibold">3%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex items-center justify-between text-xs text-muted">
                        <span data-page-info>Showing 1–10 of 0</span>
                        <div class="flex items-center gap-2">
                            <button type="button" class="page-prev px-3 py-1 rounded-full bg-dark_grey/60 border border-dark_border text-grey disabled:opacity-40">
                                Prev
                            </button>
                            <button type="button" class="page-next px-3 py-1 rounded-full bg-dark_grey/60 border border-dark_border text-grey disabled:opacity-40">
                                Next
                            </button>
                        </div>
                    </div>

                    <p class="mt-6 text-muted text-sm">
                        Fast and reliable bulk data subscription on MTN.
                    </p>
                </div>

                {{-- Airtel tab --}}
                <div class="provider-panel rounded-2xl bg-dark_grey border border-dark_border p-6 md:p-8 shadow-cause-shadow hidden" data-provider-panel="airtel">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm text-white border-separate border-spacing-y-2">
                            <thead>
                                <tr class="text-muted border-b border-dark_border/60">
                                    <th class="pb-2 font-semibold"></th>
                                    <th class="pb-2 font-semibold">Product / Service</th>
                                    <th class="pb-2 font-semibold">Code</th>
                                    <th class="pb-2 font-semibold">Amount</th>
                                    <th class="pb-2 font-semibold">Regular Discount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($airtel as $value)
                                    <tr class="align-middle">
                                        <td class="py-2">
                                            <img src="/images/airtel-logo.jpg" width="40" height="40" class="rounded-full inline-block" alt="Airtel">
                                        </td>
                                        <td class="py-2">{{ $value['name'] }}</td>
                                        <td class="py-2">{{ $value['coded'] }}</td>
                                        <td class="py-2">&#8358;{{ $value['price'] }}</td>
                                        <td class="py-2 text-primary font-semibold">3%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex items-center justify-between text-xs text-muted">
                        <span data-page-info>Showing 1–10 of 0</span>
                        <div class="flex items-center gap-2">
                            <button type="button" class="page-prev px-3 py-1 rounded-full bg-dark_grey/60 border border-dark_border text-grey disabled:opacity-40">
                                Prev
                            </button>
                            <button type="button" class="page-next px-3 py-1 rounded-full bg-dark_grey/60 border border-dark_border text-grey disabled:opacity-40">
                                Next
                            </button>
                        </div>
                    </div>

                    <p class="mt-6 text-muted text-sm">
                        Affordable bulk data subscription on Airtel.
                    </p>
                </div>

                {{-- Glo tab --}}
                <div class="provider-panel rounded-2xl bg-dark_grey border border-dark_border p-6 md:p-8 shadow-cause-shadow hidden" data-provider-panel="glo">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm text-white border-separate border-spacing-y-2">
                            <thead>
                                <tr class="text-muted border-b border-dark_border/60">
                                    <th class="pb-2 font-semibold"></th>
                                    <th class="pb-2 font-semibold">Product / Service</th>
                                    <th class="pb-2 font-semibold">Code</th>
                                    <th class="pb-2 font-semibold">Amount</th>
                                    <th class="pb-2 font-semibold">Regular Discount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($glo as $value)
                                    <tr class="align-middle">
                                        <td class="py-2">
                                            <img src="/images/glo-logo.jpg" width="40" height="40" class="rounded-full inline-block" alt="Glo">
                                        </td>
                                        <td class="py-2">{{ $value['name'] }}</td>
                                        <td class="py-2">{{ $value['coded'] }}</td>
                                        <td class="py-2">&#8358;{{ $value['price'] }}</td>
                                        <td class="py-2 text-primary font-semibold">3%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex items-center justify-between text-xs text-muted">
                        <span data-page-info>Showing 1–10 of 0</span>
                        <div class="flex items-center gap-2">
                            <button type="button" class="page-prev px-3 py-1 rounded-full bg-dark_grey/60 border border-dark_border text-grey disabled:opacity-40">
                                Prev
                            </button>
                            <button type="button" class="page-next px-3 py-1 rounded-full bg-dark_grey/60 border border-dark_border text-grey disabled:opacity-40">
                                Next
                            </button>
                        </div>
                    </div>

                    <p class="mt-6 text-muted text-sm">
                        Competitive bulk data plans on Glo.
                    </p>
                </div>

                {{-- 9Mobile tab --}}
                <div class="provider-panel rounded-2xl bg-dark_grey border border-dark_border p-6 md:p-8 shadow-cause-shadow hidden" data-provider-panel="etisalat">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm text-white border-separate border-spacing-y-2">
                            <thead>
                                <tr class="text-muted border-b border-dark_border/60">
                                    <th class="pb-2 font-semibold"></th>
                                    <th class="pb-2 font-semibold">Product / Service</th>
                                    <th class="pb-2 font-semibold">Code</th>
                                    <th class="pb-2 font-semibold">Amount</th>
                                    <th class="pb-2 font-semibold">Regular Discount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($etisalat as $value)
                                    <tr class="align-middle">
                                        <td class="py-2">
                                            <img src="/images/9mobile-logo.jpg" width="40" height="40" class="rounded-full inline-block" alt="9Mobile">
                                        </td>
                                        <td class="py-2">{{ $value['name'] }}</td>
                                        <td class="py-2">{{ $value['coded'] }}</td>
                                        <td class="py-2">&#8358;{{ $value['price'] }}</td>
                                        <td class="py-2 text-primary font-semibold">3%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex items-center justify-between text-xs text-muted">
                        <span data-page-info>Showing 1–10 of 0</span>
                        <div class="flex items-center gap-2">
                            <button type="button" class="page-prev px-3 py-1 rounded-full bg-dark_grey/60 border border-dark_border text-grey disabled:opacity-40">
                                Prev
                            </button>
                            <button type="button" class="page-next px-3 py-1 rounded-full bg-dark_grey/60 border border-dark_border text-grey disabled:opacity-40">
                                Next
                            </button>
                        </div>
                    </div>

                    <p class="mt-6 text-muted text-sm">
                        Reliable bulk data subscription on 9Mobile.
                    </p>
                </div>
            </div>
        </section>
    </main>

    @include('modals.csv')
    @push('scripts')
        @include('partials.scripts-csv')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tabButtons = document.querySelectorAll('.provider-tab');
                const panels = document.querySelectorAll('.provider-panel');
                const pageSize = 10;
                const panelRenderers = {};

                // Setup simple client-side pagination for each provider panel
                panels.forEach((panel) => {
                    const providerKey = panel.getAttribute('data-provider-panel');
                    const rows = Array.from(panel.querySelectorAll('tbody tr'));
                    const info = panel.querySelector('[data-page-info]');
                    const prevBtn = panel.querySelector('.page-prev');
                    const nextBtn = panel.querySelector('.page-next');

                    const total = rows.length;
                    const totalPages = Math.max(1, Math.ceil(total / pageSize));
                    let currentPage = 1;

                    function renderPage(page) {
                        if (!total) {
                            if (info) info.textContent = 'No records';
                            if (prevBtn) prevBtn.disabled = true;
                            if (nextBtn) nextBtn.disabled = true;
                            return;
                        }

                        const safePage = Math.min(Math.max(page, 1), totalPages);
                        const start = (safePage - 1) * pageSize;
                        const end = start + pageSize;

                        rows.forEach((row, idx) => {
                            if (idx >= start && idx < end) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        });

                        if (info) {
                            const from = start + 1;
                            const to = Math.min(end, total);
                            info.textContent = `Showing ${from}–${to} of ${total}`;
                        }

                        if (prevBtn) prevBtn.disabled = safePage <= 1;
                        if (nextBtn) nextBtn.disabled = safePage >= totalPages;

                        currentPage = safePage;
                    }

                    if (prevBtn) {
                        prevBtn.addEventListener('click', function () {
                            renderPage(currentPage - 1);
                        });
                    }

                    if (nextBtn) {
                        nextBtn.addEventListener('click', function () {
                            renderPage(currentPage + 1);
                        });
                    }

                    // Initialize first page for each panel
                    renderPage(1);
                    if (providerKey) {
                        panelRenderers[providerKey] = renderPage;
                    }
                });

                tabButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        const target = button.getAttribute('data-provider-tab');

                        tabButtons.forEach((btn) => {
                            btn.classList.remove('bg-primary', 'text-darkmode', 'border-primary', 'shadow-cause-shadow');
                            btn.classList.remove('bg-dark_grey', 'text-grey', 'border-dark_border');
                            btn.classList.add('bg-dark_grey', 'text-grey', 'border-dark_border');
                        });

                        button.classList.remove('bg-dark_grey', 'text-grey', 'border-dark_border');
                        button.classList.add('bg-primary', 'text-darkmode', 'border-primary', 'shadow-cause-shadow');

                        panels.forEach((panel) => {
                            if (panel.getAttribute('data-provider-panel') === target) {
                                panel.classList.remove('hidden');
                            } else {
                                panel.classList.add('hidden');
                            }
                        });

                        // Reset visible panel to its first page when switching tabs
                        if (panelRenderers[target]) {
                            panelRenderers[target](1);
                        }
                    });
                });
            });
        </script>
    @endpush
    @include('partials.footer')
 