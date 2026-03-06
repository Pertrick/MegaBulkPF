{{-- CSV upload flow: jQuery, Bootstrap-modal shim, PapaParse adapter, SweetAlert2, main.js --}}
<style>
    .table-modal table, .update-table-modal table { width: 100%; border-collapse: collapse; }
    .table-modal th, .table-modal td, .update-table-modal th, .update-table-modal td {
        border: 1px solid rgba(255,255,255,0.15); padding: 0.5rem 0.75rem; text-align: left; color: #F5F7FA;
    }
    .table-modal tr:hover td, .update-table-modal tr:hover td { background: rgba(255,255,255,0.05); }

    /* SweetAlert2 dark theme to match app */
    .swal2-popup {
        background: #050816 !important; /* darkmode */
        color: #e5e7eb !important;
        border-radius: 1rem !important;
        border: 1px solid rgba(148, 163, 184, 0.5) !important;
    }
    .swal2-title,
    .swal2-html-container {
        color: #f9fafb !important;
    }
    .swal2-actions .swal2-styled.swal2-confirm {
        background: #22c55e !important; /* primary-ish */
        color: #020617 !important;
        border-radius: 9999px !important;
        box-shadow: none !important;
    }
    .swal2-actions .swal2-styled:focus {
        box-shadow: none !important;
    }
    .swal2-backdrop-show {
        background: rgba(15,23,42,0.85) !important;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" crossorigin="anonymous"></script>
<script>
(function() {
    // Bootstrap .modal('show'/'hide') shim for Tailwind modals
    $.fn.modal = function(action) {
        return this.each(function() {
            var $el = $(this);
            if (action === 'show') {
                $el.removeClass('hidden').addClass('flex items-center justify-center');
                $('body').addClass('overflow-hidden');
            } else if (action === 'hide') {
                $el.addClass('hidden').removeClass('flex items-center justify-center');
                $('body').removeClass('overflow-hidden');
            }
        });
    };
    $(document).on('click', '[data-dismiss="modal"]', function() {
        var $modal = $(this).closest('.fixed.inset-0');
        $modal.addClass('hidden').removeClass('flex items-center justify-center');
        $('body').removeClass('overflow-hidden');
    });
    $(document).on('click', '[data-dismiss="backdrop"]', function() {
        var $modal = $(this).parent();
        $modal.addClass('hidden').removeClass('flex items-center justify-center');
        $('body').removeClass('overflow-hidden');
    });
    // jQuery plugin for file input CSV parse (uses PapaParse)
    $.fn.parse = function(opts) {
        var input = this[0];
        if (!input || !input.files || !input.files[0]) return this;
        var file = input.files[0];

        var config = opts && opts.config ? opts.config : {};

        if (opts.beforeSend) opts.beforeSend();

        Papa.parse(file, {
            delimiter: config.delimiter || "",
            header: config.header !== undefined ? config.header : false,
            skipEmptyLines: config.skipEmptyLines !== undefined ? config.skipEmptyLines : 'greedy',
            worker: config.worker !== undefined ? config.worker : true,
            complete: function(papaResults) {
                if (config.complete) config.complete(papaResults);
                if (opts.complete) opts.complete();
            },
            error: opts.error || function() {}
        });
        return this;
    };
})();
</script>
<script src="{{ asset('js/main.js') }}"></script>
