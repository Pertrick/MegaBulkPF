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

{{-- Payment / email modal --}}
<div id="paymentModal" class="fixed inset-0 z-[999] hidden items-center justify-center p-4" role="dialog" aria-modal="true" tabindex="-1" data-keyboard="false" data-backdrop="static">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" data-dismiss="backdrop"></div>
    <div class="relative w-full max-w-md rounded-2xl border border-dark_border bg-dark_grey shadow-2xl overflow-hidden px-6 py-6">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-lg font-semibold text-white">Enter your e-mail</h5>
            <button type="button" class="p-2 rounded-lg text-grey hover:text-white hover:bg-white/10 transition" data-dismiss="modal" aria-label="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="" method="post" class="space-y-4">
            @csrf
            <input type="email" id="email" name="email" required placeholder="Email"
                   class="w-full rounded-lg border border-dark_border bg-darkmode/60 px-4 py-3 text-white placeholder-muted focus:border-primary focus:ring-2 focus:ring-primary/60 outline-none">
            <input type="submit" id="proceed-to-pay" class="w-full py-3 rounded-lg bg-primary text-darkmode font-semibold border border-primary hover:bg-transparent hover:text-primary transition cursor-pointer" value="Make payment">
        </form>
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
