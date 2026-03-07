{{-- File content preview modal (Tailwind, no Bootstrap) --}}
<div id="exampleModal" class="fixed inset-0 z-[999] hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="exampleModalLongTitle" tabindex="-1" data-keyboard="false" data-backdrop="static">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" data-dismiss="backdrop"></div>
    <div class="relative w-full max-w-7xl h-[90vh] flex flex-col rounded-2xl border border-dark_border bg-dark_grey shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-dark_border">
            <h5 class="text-lg font-semibold text-white" id="exampleModalLongTitle">File content</h5>
            <button type="button" class="p-2 rounded-lg text-grey hover:text-white hover:bg-white/10 transition" data-dismiss="modal" aria-label="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-auto px-6 py-4">
            <div class="table-responsive table-modal overflow-x-auto text-white text-sm"></div>
        </div>
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-dark_border">
            <button type="button" class="px-4 py-2 rounded-lg border border-dark_border text-grey hover:text-white hover:bg-white/10 transition" data-dismiss="modal">Cancel</button>
            <button type="button" id="collect-user-email" class="px-4 py-2 rounded-lg bg-primary text-darkmode font-semibold border border-primary hover:bg-transparent hover:text-primary transition">Confirm</button>
        </div>
    </div>
</div>

{{-- Payment modal: existing user (email + PIN) or new (email only) --}}
<div id="paymentModal" class="fixed inset-0 z-[999] hidden items-center justify-center p-4" role="dialog" aria-modal="true" tabindex="-1" data-keyboard="false" data-backdrop="static">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" data-dismiss="backdrop"></div>
    <div class="relative w-full max-w-md min-h-[22rem] rounded-2xl border border-dark_border bg-dark_grey shadow-2xl px-6 py-8 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-white">Proceed to payment</h2>
                <button type="button" class="p-2 rounded-lg text-grey hover:text-white hover:bg-white/10 transition" data-dismiss="modal" aria-label="Close">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <p class="text-sm text-muted mb-3">Have a Planet F account?</p>
            <div class="flex gap-2 mb-6">
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="planetf_account" value="yes" class="peer sr-only" id="account-yes">
                    <span class="block rounded-lg border border-dark_border bg-darkmode/40 py-3 text-center text-sm text-grey transition peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary hover:border-primary/50 hover:bg-primary/5 hover:text-white">
                        Yes
                    </span>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="planetf_account" value="no" class="peer sr-only" id="account-no" checked>
                    <span class="block rounded-lg border border-dark_border bg-darkmode/40 py-3 text-center text-sm text-grey transition peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary hover:border-primary/50 hover:bg-primary/5 hover:text-white">
                        No
                    </span>
                </label>
            </div>

            <div id="payment-forms-wrap" class="min-h-[8rem]">
                <div id="payment-form-existing" class="hidden space-y-4" aria-hidden="true">
                    <div>
                        <label for="email-existing" class="block text-sm text-grey mb-2">Email</label>
                        <input type="email" id="email-existing" autocomplete="email" placeholder="Email"
                               class="w-full rounded-lg border border-dark_border bg-darkmode/60 px-4 py-3 text-sm text-white placeholder-muted focus:border-primary focus:ring-1 focus:ring-primary/50 outline-none">
                    </div>
                    <div>
                        <label for="pin" class="block text-sm text-grey mb-2">PIN</label>
                        <input type="password" id="pin" autocomplete="off" inputmode="numeric" maxlength="10" placeholder="PIN"
                               class="w-full rounded-lg border border-dark_border bg-darkmode/60 px-4 py-3 text-sm text-white placeholder-muted focus:border-primary focus:ring-1 focus:ring-primary/50 outline-none">
                    </div>
                </div>

                <div id="payment-form-new" class="space-y-4" aria-hidden="false">
                    <div>
                        <label for="email" class="block text-sm text-grey mb-2">Email</label>
                        <input type="email" id="email" required autocomplete="email" placeholder="Email"
                               class="w-full rounded-lg border border-dark_border bg-darkmode/60 px-4 py-3 text-sm text-white placeholder-muted focus:border-primary focus:ring-1 focus:ring-primary/50 outline-none">
                    </div>
                </div>
            </div>
        </div>

        <button type="button" id="proceed-to-pay" class="mt-6 w-full py-3 rounded-lg bg-primary text-darkmode text-sm font-semibold border border-primary hover:bg-transparent hover:text-primary transition disabled:opacity-60 disabled:cursor-not-allowed">
            Make payment
        </button>
    </div>
</div>

{{-- Update table modal (data flow) --}}
<div id="updateTableModal" class="fixed inset-0 z-[999] hidden items-center justify-center p-4" role="dialog" aria-modal="true" tabindex="-1" data-keyboard="false" data-backdrop="static">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" data-dismiss="backdrop"></div>
    <div class="relative w-full max-w-7xl h-[90vh] flex flex-col rounded-2xl border border-dark_border bg-dark_grey shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-dark_border">
            <h5 class="text-lg font-semibold text-white">File content</h5>
            <button type="button" class="p-2 rounded-lg text-grey hover:text-white hover:bg-white/10 transition" data-dismiss="modal" aria-label="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-auto px-6 py-4">
            <div class="table-responsive update-table-modal overflow-x-auto text-white text-sm"></div>
        </div>
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-dark_border">
            <button type="button" class="px-4 py-2 rounded-lg border border-dark_border text-grey hover:text-white hover:bg-white/10 transition" data-dismiss="modal">Cancel</button>
            <button type="button" id="accept" class="px-4 py-2 rounded-lg bg-primary text-darkmode font-semibold border border-primary hover:bg-transparent hover:text-primary transition">Accept</button>
        </div>
    </div>
</div>
