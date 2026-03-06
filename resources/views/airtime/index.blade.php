@extends('layouts.navbar')

@section('content')

    <head>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        @if (session('status'))
            <script>
                swal("{{ session('status') }}");
            </script>
        @endif
    </head>

    <body class="overflow-x-hidden">
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">

                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="button" id="collect-user-email" class="btn btn-success">Proceed</button>

                    </div>
                </div>
            </div>
        </div>

        {{-- payment modal --}}

        <div class="modal" id="paymentModal" tabindex="-1" role="dialog" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center">Enter your e-mail</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post">
                            @csrf
                            <input type="email" id="email" name="email" placeholder="Email" />

                            <input type="submit" id="proceed-to-pay" class="btn btn-success" value="Proceed to Pay" />
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="slider_area">
            <div class="single_slider d-flex align-items-center justify-content-center slider_bg_1 overlay2">
                <div class="container">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-xl-9">
                            <div class="slider_text text-center">
                                <p>The Best Domain & Hosting Provider In The Area</p>
                                <h3>Go Big with your next Domain</h3>
                                <div class="find_dowmain">
                                    <form method="post" action="{{ route('prev') }}"  class="find_dowmain_form">
                                    @csrf
                                        <input id="files" type="file" accept=".csv" required
                                            placeholder="Find your domain">
                                        <button id="submit-file" type="submit">Preview File</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#submit-file').on("click", function(e) {
                    e.preventDefault();

                    $('#files').parse({
                        config: {
                            delimiter: "",
                            header: false,
                            complete: displayHTMLTable,
                        },
                        before: function(file, inputElement) {
                            console.log('entering before');
                            $("body").addClass("loading");
                        },
                        error: function(err, file) {

                        },
                        complete: function() {
                            console.log('entering complete');

                            $("body").removeClass("loading");
                        }
                    })
                })

                $('#collect-user-email').click(function(e) {
                    $('#exampleModal').modal("hide");
                    $('#paymentModal').modal("show");
                });


                $('#proceed-to-pay').click(function(e) {
                    e.preventDefault();


                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    const email = $('#email').val();
                    const data = JSON.stringify(convertToJson());

                    $.ajax({
                        type: "POST",
                        url: "{{ route('data.store') }}",
                        dataType: 'JSON',
                        data: {
                            'data': data,
                            'email': email
                        },

                        success: function(response) {
                            if (response.success === 0) {
                                // $('#paymentModal').modal("hide");
                                Swal.fire(
                                    'Data Uploaded Successfully!',
                                    'success'
                                )

                                window.location = 'http://localhost:8000/'
                            }
                        },
                        error: function(response) {
                            console.log(response);

                        }

                    });
                });


            });


            function displayHTMLTable(results) {
                var table = "<table class='table table-bordered table-responsive table-hover' id='tblData'>";
                var data = results.data;

                for (i = 0; i < data.length; i++) {
                    table += "<tr>";
                    var row = data[i];
                    var cells = row.join(",").split(",");

                    for (j = 0; j < cells.length; j++) {
                        table += "<td>";
                        table += cells[j];
                        table += "</th>";
                    }

                    table += "</tr>";
                }
                table += "</table>";

                $(".table-responsive").html(table);
                $('#exampleModal').modal("show");
            }


            function convertToJson() {

                var table = document.getElementById("tblData");
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

             function collectUserEmail(){

    }

        </script>



        @include('layouts.footer')



        <script src="hostza-master/js/vendor/modernizr-3.5.0.min.js"></script>
        <script src="hostza-master/js/vendor/jquery-1.12.4.min.js"></script>
        <script src="hostza-master/js/popper.min.js"></script>
        <script src="hostza-master/js/bootstrap.min.js"></script>
        <script src="hostza-master/js/owl.carousel.min.js"></script>
        <script src="hostza-master/js/isotope.pkgd.min.js"></script>
        <script src="hostza-master/js/ajax-form.js"></script>
        <script src="hostza-master/js/waypoints.min.js"></script>
        <script src="hostza-master/js/jquery.counterup.min.js"></script>
        <script src="hostza-master/js/imagesloaded.pkgd.min.js"></script>
        <script src="hostza-master/js/scrollIt.js"></script>
        <script src="hostza-master/js/jquery.scrollUp.min.js"></script>
        <script src="hostza-master/js/wow.min.js"></script>
        <script src="hostza-master/js/nice-select.min.js"></script>
        <script src="hostza-master/js/jquery.slicknav.min.js"></script>
        <script src="hostza-master/js/jquery.magnific-popup.min.js"></script>
        <script src="hostza-master/js/plugins.js"></script>
        <script src="hostza-master/js/gijgo.min.js"></script>

        <!--contact js-->
        <script src="hostza-master/js/contact.js"></script>
        <script src="hostza-master/js/jquery.ajaxchimp.min.js"></script>
        <script src="hostza-master/js/jquery.form.js"></script>
        <script src="hostza-master/js/jquery.validate.min.js"></script>
        <script src="hostza-master/js/mail-script.js"></script>
        <script src="hostza-master/js/main.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.2/papaparse.min.js"
            integrity="sha512-SGWgwwRA8xZgEoKiex3UubkSkV1zSE1BS6O4pXcaxcNtUlQsOmOmhVnDwIvqGRfEmuz83tIGL13cXMZn6upPyg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>


        <div class="overlay"></div>

    </body>
@endsection
