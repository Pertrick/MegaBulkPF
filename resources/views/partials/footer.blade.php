<footer class="border-t border-dark_border/60 bg-darkmode relative overflow-visible">
    <div class="pointer-events-none absolute bg-gradient-to-br from-primary to-tealGreen w-96 h-96 rounded-full -top-40 -right-32 blur-400 opacity-60 z-0 [transform:translateZ(0)]" style="filter: blur(100px); -webkit-filter: blur(100px);"></div>
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12 relative z-10">
        <div class="grid gap-8 md:grid-cols-3">
            <div>
                <h3 class="text-sm font-semibold text-grey tracking-wide uppercase mb-4">
                    Contact us
                </h3>
                <p class="text-section text-sm mb-3">
                    <a class="text-grey hover:text-primary transition" href="https://wa.me/2348031230068" target="_blank">
                        +2348031230068
                    </a><br>
                    <a class="text-grey hover:text-primary transition" href="mailto:info@planetf.com.ng">
                        Support@planetf.ng
                    </a>
                </p>
                <div class="flex items-center gap-3">
                    <a href="https://www.facebook.com/share/CeEB9FfEcJ321fN2/?mibextid=WC7FNe" target="_blank"
                       class="h-9 w-9 inline-flex items-center justify-center rounded-full bg-dark_grey text-grey hover:text-primary hover:bg-dark_grey/80 transition"
                       aria-label="Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="https://x.com/planetf_ng1?s=11&t=3ZNS95UUIBAkS5ZKl-NLGQ" target="_blank"
                       class="h-9 w-9 inline-flex items-center justify-center rounded-full bg-dark_grey text-grey hover:text-primary hover:bg-dark_grey/80 transition"
                       aria-label="X (Twitter)">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                    <a href="https://www.tiktok.com/@planetf_ng?_t=8m8V7Bp4oLu&_r=1" target="_blank"
                       class="h-9 w-9 inline-flex items-center justify-center rounded-full bg-dark_grey text-grey hover:text-primary hover:bg-dark_grey/80 transition"
                       aria-label="TikTok">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    {{-- <a href="#"
                       class="h-9 w-9 inline-flex items-center justify-center rounded-full bg-dark_grey text-grey hover:text-primary hover:bg-dark_grey/80 transition"
                       aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a> --}}
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-grey tracking-wide uppercase mb-4">
                    Services
                </h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ route('airtime') }}" class="text-section hover:text-primary transition">
                            Airtime
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('data') }}" class="text-section hover:text-primary transition">
                            Data
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('how-to-use') }}" class="text-section hover:text-primary transition">
                            How to use
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-grey tracking-wide uppercase mb-4">
                    Newsletter
                </h3>
                <form action="#" class="space-y-3">
                    <input type="email"
                           class="w-full rounded-lg border border-dark_border bg-darkmode/60 text-grey placeholder-muted px-3 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-60"
                           placeholder="Enter your email">
                    <button type="submit"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-primary text-darkmode text-sm font-semibold border border-primary hover:bg-transparent hover:text-primary transition">
                        Sign Up
                    </button>
                </form>
                <p class="text-section text-xs mt-3">
                    Subscribe to get updates on new offers and discounts.
                </p>
            </div>
        </div>

        <div class="mt-10 border-t border-dark_border/60 pt-6 text-center text-xs text-section">
            <p>
                &copy; <script>document.write(new Date().getFullYear());</script>
                All rights reserved |
                <a href="/" class="text-grey hover:text-primary transition">Planet F</a>
            </p>
        </div>
    </div>
</footer>

@stack('scripts')
