<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Mega Box</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- <link rel="manifest" href="site.webmanifest"> -->
    <link rel="shortcut icon" type="image/x-icon" href="hostza-master/img/favicon.png">
    <!-- Place favicon.ico in the root directory -->

    <!-- CSS here -->
    <link rel="stylesheet" href="hostza-master/css/bootstrap.min.css">
    <link rel="stylesheet" href="hostza-master/css/owl.carousel.min.css">
    <link rel="stylesheet" href="hostza-master/css/magnific-popup.css">
    <link rel="stylesheet" href="hostza-master/css/font-awesome.min.css">
    <link rel="stylesheet" href="hostza-master/css/themify-icons.css">
    <link rel="stylesheet" href="hostza-master/css/nice-select.css">
    <link rel="stylesheet" href="hostza-master/css/flaticon.css">
    <link rel="stylesheet" href="hostza-master/css/gijgo.css">
    <link rel="stylesheet" href="hostza-master/css/animate.css">
    <link rel="stylesheet" href="hostza-master/css/slicknav.css">
    <link rel="stylesheet" href="hostza-master/css/style.css">
    <!-- <link rel="stylesheet" href="css/responsive.css"> -->
</head>

<body class="overflow-x-hidden">
   
    <!-- header-start -->
    <header>
        <div class="header-area ">
            <div id="sticky-header" class="main-header-area">
                <div class="container-fluid p-0">
                    <div class="row align-items-center no-gutters">
                        <div class="col-xl-2 col-lg-2">
                            {{-- <div class="logo-img">
                                <a href="index.html">
                                    <img src="hostza-master/img/logo.png" alt="">
                                </a>
                            </div> --}}
                        </div>
                        <div class="col-xl-7 col-lg-7">
                            <div class="main-menu  d-none d-lg-block">
                                <nav>
                                    <ul id="navigation">
                                        <li><a class="active" href="{{ url('/') }}">home</a></li>
                                        <li><a href="{{ route('listairt') }}">Airtime</a></li>
                                       <li><a href="{{ route("listdata") }}">Data</a></li>
                                        <li><a href="#">About</a></li>
                                        <li><a href="#">Contact</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 d-none d-lg-block">
                            <div class="log_chat_area d-flex align-items-center">
                                <a href="#test-form" class="login popup-with-form">
                                    <i class="flaticon-user"></i>
                                    <span>log in</span>
                                </a>
                                {{-- <div class="live_chat_btn">
                                    <a class="boxed_btn_green" href="#">
                                        <i class="flaticon-chat"></i>
                                        <span>Live Chat</span>
                                    </a>
                                </div> --}}
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mobile_menu d-block d-lg-none"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header-end -->

  
    <!-- latest_new_area_end -->

   
   
   <div>
   @yield('content')
   </div>
   

    <!-- JS here -->
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


</body>

</html>