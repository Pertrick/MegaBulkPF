<header x-data="{ open: false }" class="relative z-40">
    <nav class="w-full border-b border-dark_border/60 bg-darkmode/90 backdrop-blur">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-8 lg:px-10 h-20 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/planetf_logo.f2c8caa1.svg') }}" alt="Planet F" class="h-8 w-auto">
            </a>

            <div class="hidden md:flex items-center gap-10">
                <a href="{{ url('/') }}"
                   class="text-base md:text-lg font-medium {{ request()->is('/') ? 'text-primary' : 'text-grey hover:text-primary' }} transition">
                    Home
                </a>
                <a href="{{ route('airtime') }}"
                   class="text-base md:text-lg font-medium {{ request()->is('airtime*') ? 'text-primary' : 'text-grey hover:text-primary' }} transition">
                    Airtime
                </a>
                <a href="{{ route('data') }}"
                   class="text-base md:text-lg font-medium {{ request()->is('data*') ? 'text-primary' : 'text-grey hover:text-primary' }} transition">
                    Data
                </a>
            </div>

            <div class="hidden md:flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="text-base md:text-lg font-medium text-grey hover:text-primary transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="text-base md:text-lg font-medium text-grey hover:text-primary transition">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-primary text-darkmode text-base font-semibold border border-primary hover:bg-transparent hover:text-primary transition">
                        Sign Up
                    </a>
                @endauth
            </div>

            <div class="md:hidden">
                <button type="button"
                        @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-grey hover:text-primary hover:bg-dark_grey focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-darkmode">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>
    <div
        x-cloak
        :class="{'block': open, 'hidden': !open}"
        class="md:hidden hidden bg-darkmode border-b border-dark_border/60">
        <div class="px-4 pt-4 pb-6 space-y-3">
            <a href="{{ url('/') }}"
               @click="open = false"
               class="block text-base font-medium {{ request()->is('/') ? 'text-primary' : 'text-grey hover:text-primary' }} transition">
                Home
            </a>
            <a href="{{ route('airtime') }}"
               @click="open = false"
               class="block text-base font-medium {{ request()->is('airtime*') ? 'text-primary' : 'text-grey hover:text-primary' }} transition">
                Airtime
            </a>
            <a href="{{ route('data') }}"
               @click="open = false"
               class="block text-base font-medium {{ request()->is('data*') ? 'text-primary' : 'text-grey hover:text-primary' }} transition">
                Data
            </a>

            <div class="mt-4 border-t border-dark_border/60 pt-4 space-y-2">
                @auth
                    <a href="{{ route('dashboard') }}"
                       @click="open = false"
                       class="block text-base font-medium text-grey hover:text-primary transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       @click="open = false"
                       class="block text-base font-medium text-grey hover:text-primary transition">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}"
                       @click="open = false"
                       class="inline-flex items-center justify-center w-full mt-2 px-5 py-2.5 rounded-lg bg-primary text-darkmode text-base font-semibold border border-primary hover:bg-transparent hover:text-primary transition">
                        Sign Up
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>