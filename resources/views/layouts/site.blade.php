<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="wpOceans">
    <link rel="shortcut icon" type="image/png" href="assets/images/favicon.png">
    <title>SportsArk </title>
    <link href="assets/css/themify-icons.css" rel="stylesheet">
    <link href="assets/css/flaticon.css" rel="stylesheet">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/jquery-ui.css" rel="stylesheet">
    <link href="assets/css/animate.css" rel="stylesheet">
    <link href="assets/css/nice-select.css" rel="stylesheet">
    <link href="assets/css/owl.carousel.css" rel="stylesheet">
    <link href="assets/css/owl.theme.css" rel="stylesheet">
    <link href="assets/css/slick.css" rel="stylesheet">
    <link href="assets/css/slick-theme.css" rel="stylesheet">
    <link href="assets/css/swiper.min.css" rel="stylesheet">
    <link href="assets/css/owl.transitions.css" rel="stylesheet">
    <link href="assets/css/jquery.fancybox.css" rel="stylesheet">
    <link href="assets/css/odometer-theme-default.css" rel="stylesheet">
    <link href="assets/sass/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Jersey&display=swap">

</head>

<body>

    <!-- start page-wrapper -->
    <div class="page-wrapper">
        <!-- start preloader -->
        <div class="preloader">
            <div class="vertical-centered-box">
                <div class="content">
                    <div class="loader-circle"></div>
                    <div class="loader-line-mask">
                        <div class="loader-line"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end preloader -->

        <!-- Start header -->
        <header id="header">
            <div class="wpo-site-header">
                <nav class="navigation navbar navbar-expand-lg navbar-light">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-3 col-md-3 col-3 d-lg-none dl-block">
                                <div class="mobail-menu">
                                    <button type="button" class="navbar-toggler open-btn">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar first-angle"></span>
                                        <span class="icon-bar middle-angle"></span>
                                        <span class="icon-bar last-angle"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-4">
                                <div class="navbar-header">
                                    <a class="navbar-brand" href="index.html"><img src="assets/images/logo.png"
                                            alt="logo"></a>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-1 col-1">
                                <div id="navbar" class="collapse navbar-collapse navigation-holder">
                                    <button class="menu-close"><i class="ti-close"></i></button>
                                    <ul class="nav navbar-nav mb-2 mb-lg-0">
                                        <li><a href="index.html">Home</a></li>
                                        <li><a href="#ground">Ground</a></li>
                                        <li><a href="#about">About us</a></li>
                                        <li><a href="#testimonial">Testimonial</a></li>
                                        <li><a href="#contact">Contact</a></li>
                                    </ul>

                                </div><!-- end of nav-collapse -->
                            </div>
                            <div class="col-lg-3 col-md-4 col-4">
                                <div class="header-right">
                                    
                                </div>
                            </div>
                        </div>
                    </div><!-- end of container -->
                </nav>
            </div>
        </header>

        @yield('content')

        <!-- start of wpo-site-footer-section -->
        <footer id="contact" class="wpo-site-footer">
            <div class="wpo-upper-footer">
                <div class="container">
                    <div class="row">
                        <div class="col col-xl-5 col-lg-4 col-md-6 col-sm-12 col-12">
                            <div class="widget about-widget">
                                <div class="logo widget-title">
                                    <img src="assets/images/logofooter.png" alt="blog">
                                </div>
                                <p style=" color: #ffffff; ">SportsArk offers top-quality sports facilities with flexible bookings, excellent grounds, and great value. Join us and be part of our vibrant sports community!.</p>
                                <br>
                                <div class="social-widget">
                                    <ul>
                                        <li>
                                            <a href="#">
                                                <i class="ti-facebook"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="ti-instagram"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fab fa-tiktok"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="ti-youtube"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                        
                        
                        <div class="col col-xl-3 col-lg-4 col-md-6 col-sm-12 col-12">
                            <div class="widget link-widget">
                                <div class="widget-title">
                                    <h3>Grounds:</h3>
                                </div>
                                <ul>
                                    <li><a href="#">Foootball</a></li>
                                    <li><a href="#">Cricket</a></li>
                                    <li><a href="#">Padel</a></li>
                                    <li><a href="#">Basketball</a></li>
                                </ul>
                            </div>
                        </div>
                       
                        <div class="col col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                            <div class="widget subscribe-widget">
                                <div class="widget-title">
                                    <h3>subscribe newsletter:</h3>
                                </div>
                                <form>
                                    <input type="text" class="form-control" placeholder="Enter your email:">
                                    <input class="theme-btn-s2" type="submit" value="subscribe now">
                                    <label class="checkbox">
                                        <input class="checks" type="checkbox" />
                                        <span>I agree to email receive.</span>
                                    </label>

                                </form>
                            </div>
                        </div>
                    </div>
                </div> <!-- end container -->
            </div>
            <div class="wpo-lower-footer">
                <div class="container">
                    <div class="row">
                        <div class="col col-xs-8 col-lg-8 col-md-12 col-12">
                            <p class="copyright"> Copyright &copy; 2025 SportsArk.  All Rights Reserved.</p>
                        </div>
                        <div class="col col-xs-4 col-lg-4 col-md-12 col-12">
                            <p class="right"> Design & Developed by <a href="https://growtharkmedia.com/" target="_blank">Growthark Media</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end of wpo-site-footer-section -->

    </div>
    <!-- end of page-wrapper -->

    <!-- All JavaScript files
    ================================================== -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!-- Plugins for this template -->
    <script src="assets/js/modernizr.custom.js"></script>
    <script src="assets/js/jquery.dlmenu.js"></script>
    <script src="assets/js/jquery-plugin-collection.js"></script>
    <!-- Custom script for this template -->
    <script src="assets/js/script.js"></script>
</body>

</html>