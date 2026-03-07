@include('partials.header')

<body class="font-sans antialiased bg-darkmode text-grey min-h-screen overflow-x-hidden">
    @include('partials.navigation')

    <main>
        <section class="relative py-16 md:py-24 overflow-x-hidden overflow-y-visible">
            <div class="absolute inset-0 bg-gradient-to-b from-darklight/40 to-darkmode" aria-hidden="true"></div>
            <div class="pointer-events-none absolute w-50 h-50 bg-gradient-to-bl from-tealGreen from-50% to-charcoalGray to-60% blur-400 rounded-full -top-40 -right-10 z-0 [transform:translateZ(0)]" style="filter: blur(120px); -webkit-filter: blur(120px);"></div>
            <div class="relative z-10 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 min-w-0">
                <div class="text-center mb-14">
                    <p class="text-primary text-sm font-semibold uppercase tracking-[0.2em] mb-3">
                        Guide
                    </p>
                    <h1 class="text-36 md:text-44 font-bold text-white leading-tight mb-4">
                        How to use Planet F
                    </h1>
                    <p class="text-grey text-base md:text-lg max-w-2xl mx-auto">
                        Bulk airtime and data made simple. Follow these steps to upload your list, preview, and pay in minutes.
                    </p>
                </div>

                <div class="max-w-3xl mx-auto space-y-8 min-w-0 overflow-x-hidden">
                    {{-- Step 1 --}}
                    <div class="rounded-2xl bg-dark_grey border border-dark_border p-6 md:p-8 shadow-cause-shadow text-white min-w-0 break-words">
                        <h2 class="text-22 font-semibold mb-3 flex items-center gap-2">
                            <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-primary/20 text-primary text-sm font-bold">1</span>
                            Choose your service
                        </h2>
                        <p class="text-muted text-sm mb-4 break-words">
                            Go to <a href="{{ route('airtime') }}" class="text-primary hover:underline">Airtime</a> for bulk airtime (&#8358;50 – &#8358;5,000 per recipient) or <a href="{{ route('data') }}" class="text-secondary hover:underline">Data</a> for data bundles across MTN, Airtel, Glo, and 9Mobile.
                        </p>
                    </div>

                    {{-- Step 2 --}}
                    <div class="rounded-2xl bg-dark_grey border border-dark_border p-6 md:p-8 shadow-cause-shadow text-white min-w-0 break-words">
                        <h2 class="text-22 font-semibold mb-3 flex items-center gap-2">
                            <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-primary/20 text-primary text-sm font-bold">2</span>
                            Download the sample CSV file
                        </h2>
                        <p class="text-muted text-sm mb-4">
                            On the Airtime or Data page, click the button to download the sample CSV. Use it as a template so your file has the correct columns.
                        </p>
                        <ul class="text-sm text-grey space-y-2 mb-4">
                            <li><strong class="text-white">Airtime:</strong> phone numbers and amount per recipient.</li>
                            <li><strong class="text-white">Data:</strong> phone numbers, network, data plan (or plan code), and amount.</li>
                        </ul>
                        <p class="text-muted text-xs">
                            Fill the CSV with your recipients (no headers change). Save as CSV and keep file size reasonable for a smooth upload.
                        </p>
                    </div>

                    {{-- Step 3 --}}
                    <div class="rounded-2xl bg-dark_grey border border-dark_border p-6 md:p-8 shadow-cause-shadow text-white min-w-0 break-words">
                        <h2 class="text-22 font-semibold mb-3 flex items-center gap-2">
                            <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-primary/20 text-primary text-sm font-bold">3</span>
                            Upload your file and preview
                        </h2>
                        <p class="text-muted text-sm mb-4">
                            Select your CSV file in the upload box and click <strong class="text-white">Preview File</strong>. We'll show you a table of what will be sent.
                        </p>
                        <p class="text-muted text-sm mb-4">
                            <strong class="text-white">Data only:</strong> After preview, use <strong class="text-white">Validate</strong> so we can match your plans to our bundles and show the correct pricing. Then update the table if needed.
                        </p>
                    </div>

                    {{-- Step 4 --}}
                    <div class="rounded-2xl bg-dark_grey border border-dark_border p-6 md:p-8 shadow-cause-shadow text-white min-w-0 break-words">
                        <h2 class="text-22 font-semibold mb-3 flex items-center gap-2">
                            <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-primary/20 text-primary text-sm font-bold">4</span>
                            Pay and confirm
                        </h2>
                        <p class="text-muted text-sm mb-4">
                            Click <strong class="text-white">Proceed to payment</strong> (or similar). You'll be redirected to complete payment securely. After a successful payment, you'll see a confirmation and the airtime or data will be processed.
                        </p>
                        <p class="text-muted text-xs">
                            If you're redirected back to the site for verification, use the link or page you're given to confirm the transaction.
                        </p>
                    </div>

                    {{-- Help --}}
                    <div class="rounded-2xl bg-dark_grey border border-dark_border p-6 md:p-8 shadow-cause-shadow text-white min-w-0 break-words">
                        <h2 class="text-22 font-semibold mb-3">Need help?</h2>
                        <p class="text-muted text-sm mb-4">
                            For failed transactions, wrong numbers, or plan questions, contact us on WhatsApp or email. We're here to help.
                        </p>
                        <a href="https://wa.me/2348031230068" target="_blank"
                           class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-primary text-darkmode text-sm font-semibold border border-primary hover:bg-transparent hover:text-primary transition">
                            Chat on WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')
