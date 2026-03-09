

    $('#submit-file').on("click", function(e) {
        e.preventDefault();
        $('#files').parse({
            config: {
                delimiter: "",
                header: false,
                complete: displayHTMLTable,
            },
            error: function(err, file) {},
            beforeSend: function(){
                Swal.showLoading();
            },
            complete: function(){
                Swal.close();
            },
        })
    })


    $('#collect-user-email').click(function(e){
        const type = $("#type").val();
        if(type == "airtime"){
            validateAirtimeCsv();
        }else if(type=="data"){
            validateDataCsv();
        }
    });

    $('input[name="planetf_account"]').on("change", function() {
        var isExisting = $(this).val() === "yes";
        if (isExisting) {
            $("#payment-form-existing").removeClass("hidden").attr("aria-hidden", "false");
            $("#payment-form-new").addClass("hidden").attr("aria-hidden", "true");
            $("#email").prop("required", false);
            setTimeout(function() { $("#email-existing").focus(); }, 50);
        } else {
            $("#payment-form-existing").addClass("hidden").attr("aria-hidden", "true");
            $("#payment-form-new").removeClass("hidden").attr("aria-hidden", "false");
            $("#email").prop("required", true);
            setTimeout(function() { $("#email").focus(); }, 50);
        }
    });

    $(document).on("paymentModalShow", function() {
        $("#account-no").prop("checked", true);
        $("#payment-form-existing").addClass("hidden").attr("aria-hidden", "true");
        $("#payment-form-new").removeClass("hidden").attr("aria-hidden", "false");
        $("#email").prop("required", true).val("");
        $("#email-existing").val("");
        $("#pin").val("");
        $("#proceed-to-pay").prop("disabled", false).text("Make payment");
        setTimeout(function() { $("#email").focus(); }, 80);
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
            pin = $('#pin').val();
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

        if (type === "airtime") {
            makePayment(type, JSON.stringify(convertDataToJson()), email, pin, $btn);
        } else if (type === "data") {
            makePayment(type, JSON.stringify(convertUpdatedDataToJson()), email, pin, $btn);
        } else {
            $btn.prop("disabled", false).text("Make payment");
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
    $('#exampleModal').modal("show");
}

    function convertDataToJson() {

        var table = document.getElementById("tblData");
        return  convertToJson(table);

    }

    function convertUpdatedDataToJson() {
        var table = document.getElementById("updatedTblData");
        return convertToJson(table);


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

function makePayment(type, data, email, pin, $payBtn) {
    var payload = { email: email, data: data };
    if (pin) payload.pin = pin;

    $.ajax({
        type: "POST",
        url: "/" + type + "/store",
        dataType: "JSON",
        data: payload,
        success: function(payout) {
            var url = payout && payout.checkout_url ? payout.checkout_url : null;

            if (url) {
                $('#paymentModal').modal("hide");
                window.location = url;
                return;
            }

            if (payout && (payout.success === 1 || payout.success === true)) {
                if ($payBtn && $payBtn.length) $payBtn.prop("disabled", false).text("Make payment");
                $('#paymentModal').modal("hide");
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
        error: function(xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : "Please try again.";
            if ($payBtn && $payBtn.length) $payBtn.prop("disabled", false).text("Make payment");
            Swal.fire({ title: msg, icon: "error", confirmButtonText: "OK" });
        }
    });
}


function validateAirtimeCsv(){
    let status = validatePhone();
  
     if(status){
        if(!validateNetwork()){
            Swal.fire({
                title:"Invalid Network Service!",
                icon: "error",
                confirmButtonText:"Close"
            });
        }else{
            Swal.fire({
                title:"CSV Validation Successful",
                icon: "success",
                timer: 2000,
                showConfirmButton: false
            });
        $('#exampleModal').modal("hide");
        $('#paymentModal').modal("show");
     }
    }
}


function validateDataCsv(){

  let status = validatePhone();

   console.log(status);

   if(status){
    validateCode(function(error, response){
        if (error || !response || response.status !== 'ok') {
            var msg = (response && response.message) ? response.message : "Invalid Data Code Entry!";
            if (error && error.responseJSON && error.responseJSON.message) {
                msg = error.responseJSON.message;
            }
            Swal.fire({
                title: msg,
                icon: "error",
                confirmButtonText: "Close"
            });
        } else {
            Swal.fire({
                title:"CSV Validation Successful",
                icon: "success",
                timer: 2000,
                showConfirmButton: false
            });

            $('#exampleModal').modal("hide");

            updateDataTable(function(updateError, updateResponse){
                if (updateError || !Array.isArray(updateResponse)) {
                    var tableMsg = (updateError && updateError.responseJSON) ? updateError.responseJSON : "Unable to build data table from submitted CSV.";
                    Swal.fire({
                        title: "Data table error",
                        text: tableMsg,
                        icon: "error",
                        confirmButtonText: "Close"
                    });
                    return;
                }

                console.log(updateResponse);
                appendDataToTable(updateResponse);
                $('#updateTableModal').modal("show");
            });
            
            $('#accept').on('click', () =>{
                $('#updateTableModal').modal("hide");
                $('#paymentModal').modal("show");
            });
        }
      });
   }
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


function validateCode(callback){
    const data = convertDataToJson();
    const codes = [];

   Object.entries(data).forEach(([key, values]) => {
        codes.push(values['code']);   
   });

   console.log(codes);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
       $.ajax({
        type: "POST",
        url: "/data/validate",
        dataType: 'JSON',
        data: {
            'values': codes,
        },
        beforeSend: function(){
            Swal.showLoading();
        },
        complete: function(){
            Swal.close();
        },
        success: function(resp){
            callback(null, resp);
        },
        error: function(jqXHR){
            callback(jqXHR, null);
        }
    });
   }


   function validateNetwork(){
    const network = ['MTN', "AIRTEL", "9MOBILE", "GLO"];

    const data = convertDataToJson();
    const service = [];

   Object.entries(data).forEach(([key, values]) => {
        service.push(values['service'].toUpperCase());    
   });

   console.log(service);

   let isService = true;
    service.forEach(element =>{
        if(!network.includes(element)){
            isService = false;
            return isService;
        }

        return isService;
   });

   console.log(isService);

    return isService;

   }

   function updateDataTable(callback){
    const data = convertDataToJson();
    const codes = [];
    const phone_number =[];

   Object.entries(data).forEach(([key, values]) => {
        codes.push(values['code']);   
        phone_number.push(values['phone_number']);
   });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
       $.ajax({
        type: "POST",
        url: "/data/update_table",
        dataType: 'JSON',
        data: {
            'values': codes,
            'phone': phone_number
        },
        beforeSend: function(){
            Swal.showLoading();
        },
        complete: function(){
            Swal.close();
        },
        success: function(resp){
            callback(null, resp);
        },
        error:  function(jqXHR){
            callback(jqXHR, null);
        }
    });

   }

   function appendDataToTable(responseData){
   
        var table = "<table class='table table-bordered table-hover' id='updatedTblData' style='width:100%; margin:0 auto;'>";
   
        console.log(responseData);
        for (i = 0; i < responseData.length; i++) {
            table += "<tr>";
            var row = responseData[i];
            var cells = row.join(",").split(",");
            for (j = 0; j < cells.length; j++) {
                table += "<td>" ;
                table += cells[j];
                table += "</td>";
            }
            table += "</tr>";
        }
        table += "</table>";
    
        $(".update-table-modal").html(table);
        $("#updateTableModal").modal('show');

   }



