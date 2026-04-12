    window.dataBulkOrderUuid = null;
    window.airtimeBulkOrderUuid = null;

    function formatRowCountLabel(n) {
        if (n == null || typeof n !== "number" || n < 0 || isNaN(n)) return "";
        return "· " + n + (n === 1 ? " row" : " rows");
    }

    window.bulkPreviewPerPage = 50;

    function hideBulkPreviewPagination() {
        $("#bulk-preview-pagination").addClass("hidden");
        $("#bulk-preview-prev, #bulk-preview-next").prop("disabled", true);
        $("#bulk-preview-page-label").text("");
    }

    function fetchAirtimeBulkPreviewPage(page) {
        var uuid = window.airtimeBulkOrderUuid;
        if (!uuid) return;
        var perPage = window.bulkPreviewPerPage || 50;
        $.ajax({
            url: "/airtime/bulk-order/" + encodeURIComponent(uuid) + "/preview-rows",
            type: "GET",
            data: { page: page, per_page: perPage },
            dataType: "json",
            success: function (resp) {
                var rows = resp.rows || [];
                var total = typeof resp.total === "number" ? resp.total : 0;
                var lastPage = typeof resp.last_page === "number" ? resp.last_page : 1;
                var currentPage = typeof resp.page === "number" ? resp.page : 1;
                window.bulkPreviewCurrentPage = currentPage;
                window.bulkPreviewLastPage = lastPage;

                var html = "<table class='table table-bordered table-hover' id='tblData' style='width:100%; margin:0 auto;'>";
                html += "<tr><th>phone_number</th><th>service</th><th>amount</th></tr>";
                for (var i = 0; i < rows.length; i++) {
                    var r = rows[i];
                    html += "<tr><td>" + escapeHtml(String(r.phone_number || "")) + "</td><td>" +
                        escapeHtml(String(r.network || "")) + "</td><td>" +
                        escapeHtml(String(r.amount != null ? r.amount : "")) + "</td></tr>";
                }
                html += "</table>";
                $(".table-modal").html(html);

                var from = total === 0 ? 0 : (currentPage - 1) * perPage + 1;
                var to = Math.min(total, currentPage * perPage);
                $("#bulk-preview-page-label").text("Rows " + from + "–" + to + " of " + total + " · Page " + currentPage + " of " + lastPage);
                $("#csvPreviewRowCount").text(formatRowCountLabel(total));

                if (lastPage <= 1) {
                    hideBulkPreviewPagination();
                } else {
                    $("#bulk-preview-pagination").removeClass("hidden");
                    $("#bulk-preview-prev").prop("disabled", currentPage <= 1);
                    $("#bulk-preview-next").prop("disabled", currentPage >= lastPage);
                }

                $("#exampleModal").modal("show");
            },
            error: function (xhr) {
                $(".table-modal").html("<p class=\"text-sm text-muted py-4\">Preview could not be loaded.</p>");
                hideBulkPreviewPagination();
                var json = xhr.responseJSON || {};
                Swal.fire({ title: json.message || "Could not load preview.", icon: "error", confirmButtonText: "OK" });
            }
        });
    }

    function fetchBulkPreviewPage(page) {
        var uuid = window.dataBulkOrderUuid;
        if (!uuid) return;
        var perPage = window.bulkPreviewPerPage || 50;
        $.ajax({
            url: "/data/bulk-order/" + encodeURIComponent(uuid) + "/preview-rows",
            type: "GET",
            data: { page: page, per_page: perPage },
            dataType: "json",
            success: function (resp) {
                var rows = resp.rows || [];
                var total = typeof resp.total === "number" ? resp.total : 0;
                var lastPage = typeof resp.last_page === "number" ? resp.last_page : 1;
                var currentPage = typeof resp.page === "number" ? resp.page : 1;
                window.bulkPreviewCurrentPage = currentPage;
                window.bulkPreviewLastPage = lastPage;

                var html = "<table class='table table-bordered table-hover' id='tblData' style='width:100%; margin:0 auto;'>";
                html += "<tr><th>phone_number</th><th>network_code</th><th>amount</th></tr>";
                for (var i = 0; i < rows.length; i++) {
                    var r = rows[i];
                    html += "<tr><td>" + escapeHtml(String(r.phone_number || "")) + "</td><td>" +
                        escapeHtml(String(r.network_code || "")) + "</td><td>" +
                        escapeHtml(String(r.amount != null ? r.amount : "")) + "</td></tr>";
                }
                html += "</table>";
                $(".table-modal").html(html);

                var from = total === 0 ? 0 : (currentPage - 1) * perPage + 1;
                var to = Math.min(total, currentPage * perPage);
                $("#bulk-preview-page-label").text("Rows " + from + "–" + to + " of " + total + " · Page " + currentPage + " of " + lastPage);
                $("#csvPreviewRowCount").text(formatRowCountLabel(total));

                if (lastPage <= 1) {
                    hideBulkPreviewPagination();
                } else {
                    $("#bulk-preview-pagination").removeClass("hidden");
                    $("#bulk-preview-prev").prop("disabled", currentPage <= 1);
                    $("#bulk-preview-next").prop("disabled", currentPage >= lastPage);
                }

                $("#exampleModal").modal("show");
            },
            error: function (xhr) {
                $(".table-modal").html("<p class=\"text-sm text-muted py-4\">Preview could not be loaded.</p>");
                hideBulkPreviewPagination();
                var json = xhr.responseJSON || {};
                Swal.fire({ title: json.message || "Could not load preview.", icon: "error", confirmButtonText: "OK" });
            }
        });
    }

    function bulkPreviewGoPrev() {
        var current = window.bulkPreviewCurrentPage || 1;
        if (current > 1) {
            if (window.dataBulkOrderUuid) fetchBulkPreviewPage(current - 1);
            else if (window.airtimeBulkOrderUuid) fetchAirtimeBulkPreviewPage(current - 1);
        }
    }

    function bulkPreviewGoNext() {
        var current = window.bulkPreviewCurrentPage || 1;
        var last = window.bulkPreviewLastPage || 1;
        if (current < last) {
            if (window.dataBulkOrderUuid) fetchBulkPreviewPage(current + 1);
            else if (window.airtimeBulkOrderUuid) fetchAirtimeBulkPreviewPage(current + 1);
        }
    }

    $(document).on("click", "#bulk-preview-prev", function () {
        if ($(this).prop("disabled")) return;
        if (!window.dataBulkOrderUuid && !window.airtimeBulkOrderUuid) return;
        bulkPreviewGoPrev();
    });

    $(document).on("click", "#bulk-preview-next", function () {
        if ($(this).prop("disabled")) return;
        if (!window.dataBulkOrderUuid && !window.airtimeBulkOrderUuid) return;
        bulkPreviewGoNext();
    });

    $('#submit-file').on("click", function(e) {
        e.preventDefault();
        var type = $("#type").val();
        if (type === "data") {
            uploadDataCsvToBackend();
            return;
        }
        if (type === "airtime") {
            uploadAirtimeCsvToBackend();
            return;
        }
        $('#files').parse({
            config: {
                delimiter: "",
                header: false,
                complete: displayHTMLTable,
            },
            error: function(err, file) {},
            beforeSend: function(){
                $("#csvPreviewRowCount").text("");
                Swal.showLoading();
            },
            complete: function(){
                Swal.close();
            },
        })
    })

    function uploadDataCsvToBackend() {
        var fileInput = document.getElementById("files");
        if (!fileInput || !fileInput.files || !fileInput.files.length) {
            Swal.fire({ title: "Select a CSV file", icon: "warning", confirmButtonText: "OK" });
            return;
        }
        var formData = new FormData();
        formData.append("csv", fileInput.files[0]);
        formData.append("_token", $('meta[name="csrf-token"]').attr("content"));
        $.ajax({
            url: "/data/import-csv",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            beforeSend: function () {
                $("#csvPreviewRowCount").text("");
                Swal.showLoading();
            },
            complete: function () { Swal.close(); },
            success: function (resp) {
                window.dataBulkOrderUuid = resp.bulk_order_uuid;
                window.bulkPreviewPerPage = 50;
                $(".table-modal").html("<p class=\"text-sm text-muted py-4\">Loading preview…</p>");
                $("#exampleModal").modal("show");
                fetchBulkPreviewPage(1);
            },
            error: function (xhr) {
                var json = xhr.responseJSON || {};
                Swal.fire({ title: json.message || "Import failed", icon: "error", confirmButtonText: "OK" });
            }
        });
    }

    function uploadAirtimeCsvToBackend() {
        var fileInput = document.getElementById("files");
        if (!fileInput || !fileInput.files || !fileInput.files.length) {
            Swal.fire({ title: "Select a CSV file", icon: "warning", confirmButtonText: "OK" });
            return;
        }
        var formData = new FormData();
        formData.append("csv", fileInput.files[0]);
        formData.append("_token", $('meta[name="csrf-token"]').attr("content"));
        $.ajax({
            url: "/airtime/import-csv",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            beforeSend: function () {
                $("#csvPreviewRowCount").text("");
                Swal.showLoading();
            },
            complete: function () { Swal.close(); },
            success: function (resp) {
                window.airtimeBulkOrderUuid = resp.bulk_order_uuid;
                window.bulkPreviewPerPage = 50;
                $(".table-modal").html("<p class=\"text-sm text-muted py-4\">Loading preview…</p>");
                $("#exampleModal").modal("show");
                fetchAirtimeBulkPreviewPage(1);
            },
            error: function (xhr) {
                var json = xhr.responseJSON || {};
                Swal.fire({ title: json.message || "Import failed", icon: "error", confirmButtonText: "OK" });
            }
        });
    }

    function escapeHtml(s) {
        return s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
    }


    $('#collect-user-email').click(function(e){
        const type = $("#type").val();
        if(type == "airtime"){
            validateAirtimeCsv();
        }else if(type=="data"){
            validateDataCsv();
        }
    });

    function syncPlanetfAccountChoiceStyles() {
        var $inputs = $('input[name="planetf_account"]');
        $inputs.each(function () {
            var $card = $(this).closest(".account-choice-label").find(".account-choice-card");
            if (this.checked) {
                $card.addClass("border-primary bg-primary/10 text-primary");
            } else {
                $card.removeClass("border-primary bg-primary/10 text-primary");
            }
        });
    }

    // Toggle existing (email + PIN) vs new (email only) — delegated so it always binds
    $(document).on("change", 'input[name="planetf_account"]', function () {
        var isExisting = $(this).val() === "yes";
        if (isExisting) {
            $("#payment-form-existing").removeClass("hidden").attr("aria-hidden", "false");
            $("#payment-form-new").addClass("hidden").attr("aria-hidden", "true");
            $("#email").prop("required", false);
            setTimeout(function () { $("#email-existing").focus(); }, 50);
        } else {
            $("#payment-form-existing").addClass("hidden").attr("aria-hidden", "true");
            $("#payment-form-new").removeClass("hidden").attr("aria-hidden", "false");
            $("#email").prop("required", true);
            setTimeout(function () { $("#email").focus(); }, 50);
        }
        syncPlanetfAccountChoiceStyles();
    });

    // Reset modal on open (programmatic check does not fire change — sync explicitly)
    $(document).on("paymentModalShow", function () {
        $("#account-no").prop("checked", true);
        $("#payment-form-existing").addClass("hidden").attr("aria-hidden", "true");
        $("#payment-form-new").removeClass("hidden").attr("aria-hidden", "false");
        $("#email").val("").prop("required", true);
        $("#email-existing").val("");
        $("#pin").val("");
        $("#proceed-to-pay").prop("disabled", false).text("Make payment");
        syncPlanetfAccountChoiceStyles();
        setTimeout(function () { $("#email").focus(); }, 80);
    });

    $(function () {
        syncPlanetfAccountChoiceStyles();
    });

    $('#proceed-to-pay').click(function(e) {
        e.preventDefault();
        var $btn = $(this);
        if ($btn.prop("disabled")) return;

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        var isExisting = $('input[name="planetf_account"]:checked').val() === "yes";
        var email, pin = null;

        if (isExisting) {
            email = $('#email-existing').val().trim();
            pin   = $('#pin').val().trim();
            if (!email || !pin) {
                Swal.fire({ title: "Enter email and PIN", icon: "error", confirmButtonText: "OK" });
                return;
            }
            if (!validateEmail(email)) {
                Swal.fire({ title: "Invalid email", icon: "error", confirmButtonText: "OK" });
                return;
            }
        } else {
            email = $('#email').val().trim();
            if (!email || !validateEmail(email)) {
                Swal.fire({ title: "Enter a valid email", icon: "error", confirmButtonText: "OK" });
                return;
            }
        }

        var type = $("#type").val();
        $btn.prop("disabled", true).text("Processing…");

        if (type === "airtime" && window.airtimeBulkOrderUuid) {
            makePaymentBulkAirtime(email, pin, $btn);
        } else if (type === "data" && window.dataBulkOrderUuid) {
            makePaymentBulkData(email, pin, $btn);
        } else {
            $btn.prop("disabled", false).text("Make payment");
            Swal.fire({
                title: "Import your CSV first",
                text: "Use “Preview File” so your list is imported on the server. Then complete payment in the same session.",
                icon: "warning",
                confirmButtonText: "OK"
            });
        }
    });


function displayHTMLTable(results) {
    var data = Array.isArray(results.data) ? results.data : [];

    // Remove completely empty rows (helps with large sheets and trailing blanks)
    data = data.filter(function (row) {
        if (!Array.isArray(row)) return false;
        return row.some(function (cell) {
            return String(cell).trim() !== '';
        });
    });

    if (!data.length) {
        Swal.fire({
            title: "No rows found in CSV",
            icon: "error",
            button: "close"
        });
        return;
    }

    // Enforce consistent column count based on first non-empty row
    var expectedLength = data[0].length;
    var cleanData = data.filter(function (row) {
        return row.length === expectedLength;
    });

    if (!cleanData.length) {
        Swal.fire({
            title: "CSV format error",
            text: "All rows appear to have different number of columns.",
            icon: "error",
            button: "close"
        });
        return;
    }

    hideBulkPreviewPagination();

    var table = "<table class='table table-bordered table-hover' id='tblData' style='width:100%; margin:0 auto;'>";

    for (var i = 0; i < cleanData.length; i++) {
        table += "<tr>";
        var row = cleanData[i];
        for (var j = 0; j < row.length; j++) {
            var cell = row[j] != null ? String(row[j]) : "";
            // Basic HTML escaping to avoid breaking markup
            cell = cell
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;");
            table += "<td>" + cell + "</td>";
        }
        table += "</tr>";
    }

    table += "</table>";

    $(".table-modal").html(table);
    $("#csvPreviewRowCount").text(formatRowCountLabel(cleanData.length));
    $('#exampleModal').modal("show");
}

    function convertDataToJson() {

        var table = document.getElementById("tblData");
        return  convertToJson(table);

    }

function convertToJson(table){
    console.log(table);
    var header = [];
    var rows = [];

    for (var i = 0; i < table.rows[0].cells.length; i++) {
        header.push(table.rows[0].cells[i].innerHTML);
    }

    for (var i = 1; i < table.rows.length; i++) {
        var row = {};
        for (var j = 0; j < table.rows[i].cells.length; j++) {
            row[header[j]] = table.rows[i].cells[j].innerHTML;
        }
        rows.push(row);
    }

    return rows;
}


function validateEmail(email) {
    const res =
        /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return res.test(String(email).toLowerCase());
}

function makePaymentBulkAirtime(email, pin, $payBtn) {
    var uuid = window.airtimeBulkOrderUuid;
    if (!uuid || String(uuid).trim() === "") {
        if ($payBtn && $payBtn.length) $payBtn.prop("disabled", false).text("Make payment");
        Swal.fire({
            title: "Import your CSV first",
            text: "Use “Preview File” so your list is imported on the server. Then complete payment in the same session.",
            icon: "warning",
            confirmButtonText: "OK"
        });
        return;
    }
    var payload = { email: email, bulk_order_uuid: String(uuid).trim() };
    if (pin) payload.pin = pin;
    $.ajax({
        type: "POST",
        url: "/airtime/store",
        dataType: "JSON",
        contentType: "application/json; charset=UTF-8",
        data: JSON.stringify(payload),
        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
        success: function (payout) {
            var url = payout && payout.checkout_url ? payout.checkout_url : null;
            if (url) {
                $("#paymentModal").modal("hide");
                window.location = url;
                return;
            }
            if (payout && (payout.success === 1 || payout.success === true)) {
                if ($payBtn && $payBtn.length) $payBtn.prop("disabled", false).text("Make payment");
                $("#paymentModal").modal("hide");
                window.airtimeBulkOrderUuid = null;
                Swal.fire({
                    title: payout.message || "Purchase successful.",
                    icon: "success",
                    confirmButtonText: "OK"
                });
                return;
            }
            if ($payBtn && $payBtn.length) $payBtn.prop("disabled", false).text("Make payment");
            Swal.fire({ title: "Something went wrong", icon: "error", confirmButtonText: "OK" });
        },
        error: function (xhr) {
            var json = xhr.responseJSON || {};
            var msg = json.message || "Please try again.";
            if (json.errors && typeof json.errors === "object") {
                var firstKey = Object.keys(json.errors)[0];
                if (firstKey && Array.isArray(json.errors[firstKey]) && json.errors[firstKey].length) {
                    msg = json.errors[firstKey][0];
                }
            }
            if ($payBtn && $payBtn.length) $payBtn.prop("disabled", false).text("Make payment");
            Swal.fire({ title: msg, icon: "error", confirmButtonText: "OK" });
        }
    });
}

function makePaymentBulkData(email, pin, $payBtn) {
    var uuid = window.dataBulkOrderUuid;
    if (!uuid || String(uuid).trim() === "") {
        if ($payBtn && $payBtn.length) $payBtn.prop("disabled", false).text("Make payment");
        Swal.fire({
            title: "Import your CSV first",
            text: "Use “Preview File” so your list is imported on the server. Then complete payment in the same session.",
            icon: "warning",
            confirmButtonText: "OK"
        });
        return;
    }
    var payload = { email: email, bulk_order_uuid: String(uuid).trim() };
    if (pin) payload.pin = pin;
    $.ajax({
        type: "POST",
        url: "/data/store",
        dataType: "JSON",
        contentType: "application/json; charset=UTF-8",
        data: JSON.stringify(payload),
        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
        success: function (payout) {
            var url = payout && payout.checkout_url ? payout.checkout_url : null;
            if (url) {
                $("#paymentModal").modal("hide");
                window.location = url;
                return;
            }
            if (payout && (payout.success === 1 || payout.success === true)) {
                if ($payBtn && $payBtn.length) $payBtn.prop("disabled", false).text("Make payment");
                $("#paymentModal").modal("hide");
                window.dataBulkOrderUuid = null;
                Swal.fire({
                    title: payout.message || "Purchase successful.",
                    icon: "success",
                    confirmButtonText: "OK"
                });
                return;
            }
            if ($payBtn && $payBtn.length) $payBtn.prop("disabled", false).text("Make payment");
            Swal.fire({ title: "Something went wrong", icon: "error", confirmButtonText: "OK" });
        },
        error: function (xhr) {
            var json = xhr.responseJSON || {};
            var msg = json.message || "Please try again.";
            if (json.errors && typeof json.errors === "object") {
                var firstKey = Object.keys(json.errors)[0];
                if (firstKey && Array.isArray(json.errors[firstKey]) && json.errors[firstKey].length) {
                    msg = json.errors[firstKey][0];
                }
            }
            if ($payBtn && $payBtn.length) $payBtn.prop("disabled", false).text("Make payment");
            Swal.fire({ title: msg, icon: "error", confirmButtonText: "OK" });
        }
    });
}

function validateAirtimeCsv(){
    if (!window.airtimeBulkOrderUuid) {
        Swal.fire({
            title: "Import your CSV first",
            text: "Use “Preview File” so your list is imported on the server.",
            icon: "warning",
            confirmButtonText: "OK"
        });
        return;
    }
    var status = validatePhone();
    if (!status) return;
    Swal.fire({
        title: "CSV Validation Successful",
        icon: "success",
        timer: 2000,
        showConfirmButton: false
    });
    $("#exampleModal").modal("hide");
    $("#paymentModal").modal("show");
}


function validateDataCsv(){
    if (!window.dataBulkOrderUuid) {
        Swal.fire({
            title: "Import your CSV first",
            text: "Use “Preview File” so your list is imported on the server.",
            icon: "warning",
            confirmButtonText: "OK"
        });
        return;
    }
    var status = validatePhone();
    if (!status) return;
    Swal.fire({
        title: "CSV Validation Successful",
        icon: "success",
        timer: 2000,
        showConfirmButton: false
    });
    $("#exampleModal").modal("hide");
    $("#paymentModal").modal("show");
}


function validatePhone(){
    const data = convertDataToJson();
    const phone_number = [];

   Object.entries(data).forEach(([key, values]) => {
        phone_number.push(values['phone_number']);    
   });

    const isTrue = (phone) => phone.charAt(0) == 0 && phone.length == "11";

    const status = phone_number.every(isTrue);
    console.log(status);
    if(!status){
         Swal.fire({
             title:"Invalid Phone Number Entry!",
             icon: "error",
             button:"close"
         });
    }

    return status;
}


