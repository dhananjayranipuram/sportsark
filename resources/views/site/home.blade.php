@extends('layouts.site')

@section('content')
<!-- start of hero -->
<section class="hero-section-s2">
    <div class="hero-wraper">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-10 col-12">
                    <div class="hero-content-slider">
                        <div class="item">
                            <h3 class="wow fadeInDown" data-wow-duration="1600ms">Welcome to </h3>
                            <h2 class="wow fadeInDown" data-wow-duration="1600ms">SportsArk</h2>
                            <p class="wow fadeInUp" data-wow-duration="1600ms">Experience premium sports grounds tailored to your game. Book your pitch by the hour and play your way. 
                                From football to padel, we have the perfect field for you!</p>
                            <div class="hero-btn wow fadeInUp" data-wow-duration="1800ms">
                                <a href="categories.html" class="theme-btn">find your grounds</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="right-img wow fadeInRightSlow" data-wow-duration="1500ms">
            <img src="assets/images/slider/slide-4.png" alt="">
        </div>
    </div>
</section>
<!-- end of hero -->


<!-- start of popular-grounds-->
<section id="ground" class="featured-section section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-12">
                <div class="wpo-section-title s2">
                    <span>//Grounds</span>
                    <h2>Our Play grounds</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col col-xs-12 sortable-gallery">
                <div class="gallery-filters">
                    <div class="row justify-content-center">
                        <div class="col-lg-10 col-12">
                            <ul class="category-item">
                                <li>
                                    <a data-filter=".all" href="#" class="featured-btn current">
                                        All
                                    </a>
                                </li>
                                <li>
                                    <a data-filter=".football" href="#" class="featured-btn">
                                        Football
                                    </a>
                                </li>
                                <li>
                                    <a data-filter=".basketball" href="#" class="featured-btn">
                                        Basketball
                                    </a>
                                </li>
                                <li>
                                    <a data-filter=".cricket" href="#" class="featured-btn">
                                        Cricket
                                    </a>
                                </li>
                                <li>
                                    <a data-filter=".padel" href="#" class="featured-btn">
                                        Padel
                                    </a>
                                </li>
                                
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="gallery-container gallery-fancybox masonry-gallery row" style="position: relative; height: 995.188px;">
            <div class="col-xl-3 col-lg-4 col-md-6 col-12 custom-grid all football zoomIn" data-wow-duration="2000ms" style="position: absolute; left: 0px; top: 0px;">
                <div class="featured-card">
                    <div class="image">
                        <img src="assets/images/featured/1.jpg" alt="">
                    </div>
                    <div class="content">
                        <div class="top-content">
                            <ul>
                                <li>
                                    <span>9</span>
                                    <span class="date">A side</span>
                                </li>
                                <li>
                                    <span>AED 300</span>
                                    <span class="date">Per Hour</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-lg-4 col-md-6 col-12 custom-grid all basketball zoomIn" data-wow-duration="2000ms" style="position: absolute; left: 660px; top: 0px;">
                <div class="featured-card">
                    <div class="image">
                        <img src="assets/images/featured/3.jpg" alt="">
                    </div>
                    <div class="content">
                        <div class="top-content">
                            <ul>
                                <li>
                                    <span>9</span>
                                    <span class="date">A side</span>
                                </li>
                                <li>
                                    <span>AED 300</span>
                                    <span class="date">Per Hour</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-12 custom-grid all football zoomIn" data-wow-duration="2000ms" style="position: absolute; left: 330px; top: 0px;">
                <div class="featured-card">
                    <div class="image">
                        <img src="assets/images/featured/2.jpg" alt="">
                    </div>
                    <div class="content">
                        <div class="top-content">
                            <ul>
                                <li>
                                    <span>9</span>
                                    <span class="date">A side</span>
                                </li>
                                <li>
                                    <span>AED 300</span>
                                    <span class="date">Per Hour</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-12 custom-grid all basketball zoomIn" data-wow-duration="2000ms" style="position: absolute; left: 990px; top: 0px;">
                <div class="featured-card">
                    <div class="image">
                        <img src="assets/images/featured/4.jpg" alt="">
                    </div>
                    <div class="content">
                        <div class="top-content">
                            <ul>
                                <li>
                                    <span>9</span>
                                    <span class="date">A side</span>
                                </li>
                                <li>
                                    <span>AED 300</span>
                                    <span class="date">Per Hour</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-xl-3 col-lg-4 col-md-6 col-12 custom-grid all cricket zoomIn" data-wow-duration="2000ms" style="position: absolute; left: 330px; top: 497px;">
                <div class="featured-card">
                    <div class="image">
                        <img src="assets/images/featured/6.jpg" alt="">
                    </div>
                    <div class="content">
                        <div class="top-content">
                            <ul>
                                <li>
                                    <span>9</span>
                                    <span class="date">A side</span>
                                </li>
                                <li>
                                    <span>AED 300</span>
                                    <span class="date">Per Hour</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-12 custom-grid all padel zoomIn" data-wow-duration="2000ms" style="position: absolute; left: 660px; top: 497px;">
                <div class="featured-card">
                    <div class="image">
                        <img src="assets/images/featured/7.jpg" alt="">
                    </div>
                    <div class="content">
                        <div class="top-content">
                            <ul>
                                <li>
                                    <span>9</span>
                                    <span class="date">A side</span>
                                </li>
                                <li>
                                    <span>AED 300</span>
                                    <span class="date">Per Hour</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-12 custom-grid all cricket zoomIn" data-wow-duration="2000ms" style="position: absolute; left: 0px; top: 497px;">
                <div class="featured-card">
                    <div class="image">
                        <img src="assets/images/featured/5.jpg" alt="">
                    </div>
                    <div class="content">
                        <div class="top-content">
                            <ul>
                                <li>
                                    <span>9</span>
                                    <span class="date">A side</span>
                                </li>
                                <li>
                                    <span>AED 300</span>
                                    <span class="date">Per Hour</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-12 custom-grid all padel zoomIn" data-wow-duration="2000ms" style="position: absolute; left: 990px; top: 497px;">
                <div class="featured-card">
                    <div class="image">
                        <img src="assets/images/featured/8.jpg" alt="">
                    </div>
                    <div class="content">
                        <div class="top-content">
                            <ul>
                                <li>
                                    <span>9</span>
                                    <span class="date">A side</span>
                                </li>
                                <li>
                                    <span>AED 300</span>
                                    <span class="date">Per Hour</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="featured-all-btn">
            <a href="Grounds.html" class="theme-btn-s2">view all Groundss</a>
        </div>
    </div>
</section>


<!-- start of authorlist-->
<section id="about" class="authorlist-section section-padding">
    <div class="container">
        <div class="row ">
            <div class="col-xl-4 col-12">
                <div class="wpo-section-title s2 wow fadeInLeftSlow" data-wow-duration="1700ms">
                    <span>// About Us</span>
                    <h2 style=" color: #ffffff; ">Elevate Your Game at SportsArk.</h2>
                </div>
            </div>
            <div class="col-xl-8 col-12">
                <p style=" color: #ffffff; ">At SportsArk, we are dedicated to providing top-notch sports facilities for enthusiasts and professionals alike. 
                    Our range of meticulously maintained grounds caters to a variety of sports, including football, cricket, padel, and basketball. 
                    Whether you're organizing a friendly match, training session, or a competitive game, SportsArk ensures a seamless booking experience and a great play environment.</p>
                    <p style=" color: #ffffff; ">Our mission is to promote healthy living and community engagement through sports. With flexible hourly rentals, 
                        we make it easy for you to enjoy your favorite games without the hassle of long-term commitments. 
                        Join us at SportsArk and elevate your sporting experience!</p>

            </div>
        </div>
    </div>
</section>
<!-- end of authorlist-->

<!-- start of features-->
<section class="service-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-12">
                <div class="wpo-section-title s2  wow fadeInUp" data-wow-duration="1200ms">
                    <span>// why choose us</span>
                    <h2>Why We're the Best Choice.</h2>
                </div>
            </div>
        </div>
        <div class="service-wraper">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="service-card wow fadeInUp" data-wow-duration="1100ms">
                        <div class="left">
                            <div class="image">
                                <img src="assets/images/features/1.png" alt="" class="active">
                                <img src="assets/images/features/1.png" alt="" class="hover">
                            </div>
                        </div>
                        <div class="right">
                            <h2><a href="service-single.html">Variety of Grounds</a></h2>
                            <span>Choose from grounds for various sports and team sizes, including football, cricket, padel, etc.</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="service-card wow fadeInUp" data-wow-duration="1200ms">
                        <div class="left">
                            <div class="image">
                                <img src="assets/images/features/img-2.svg" alt="" class="active">
                                <img src="assets/images/features/img-2.svg" alt="" class="hover">
                            </div>
                        </div>
                        <div class="right">
                            <h2><a href="service-single.html">Flexible Booking</a></h2>
                            <span>Easily book your ground by the hour with our simple and hassle-free online system.</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="service-card wow fadeInUp" data-wow-duration="1300ms">
                        <div class="left">
                            <div class="image">
                                <img src="assets/images/features/3.png" alt="" class="active">
                                <img src="assets/images/features/3.png" alt="" class="hover">
                            </div>
                        </div>
                        <div class="right">
                            <h2><a href="service-single.html">Premium Facilities</a></h2>
                            <span>Play on well-maintained grounds with modern amenities to enhance your experience.</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="service-card wow fadeInUp" data-wow-duration="1400ms">
                        <div class="left">
                            <div class="image">
                                <img src="assets/images/features/4.png" alt="" class="active">
                                <img src="assets/images/features/4.png" alt="" class="hover">
                            </div>
                        </div>
                        <div class="right">
                            <h2><a href="service-single.html">Convenient Locations</a></h2>
                            <span>Our easily accessible locations make it simple for players from all areas to join in.</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="service-card wow fadeInUp" data-wow-duration="1500ms">
                        <div class="left">
                            <div class="image">
                                <img src="assets/images/features/5.png" alt="" class="active">
                                <img src="assets/images/features/5.png" alt="" class="hover">
                            </div>
                        </div>
                        <div class="right">
                            <h2><a href="service-single.html">Affordable Pricing</a></h2>
                            <span>Enjoy transparent, competitive pricing with no hidden fees, offering great value for top facilities.</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="service-card wow fadeInUp" data-wow-duration="1600ms">
                        <div class="left">
                            <div class="image">
                                <img src="assets/images/features/6.png" alt="" class="active">
                                <img src="assets/images/features/6.png" alt="" class="hover">
                            </div>
                        </div>
                        <div class="right">
                            <h2><a href="service-single.html">Community Focused</a></h2>
                            <span>We foster a vibrant sports community, bringing people together to enjoy fitness, teamwork, and fun.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end of features-->




<!-- start of testimonial-->
<section id="testimonial" class="testimonial-section section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12">
                <div class="wpo-section-title  wow fadeInUp" data-wow-duration="1200ms">
                    <span>// customer testimonial</span>
                    <h2>Happy Players, Great Experiences.</h2>
                </div>
            </div>
        </div>
        <div class="testimonial-slider owl-carousel">
            <div class="testimonial-card wow fadeInUp" data-wow-duration="1400ms">
                <div class="top-content">
                    <div class="image">
                        <img src="assets/images/testimonial/1.jpg" alt="">
                    </div>
                    <div class="text">
                        <h3>Omar Hassan</h3>
                        <span>Al Qasba, Sharjah</span>
                    </div>
                </div>
                <div class="content">
                    <p>“SportsArk makes booking a ground so easy! The facilities are always top-notch, and our team loves playing here every weekend.”</p>
                </div>
            </div>
            <div class="testimonial-card wow fadeInUp" data-wow-duration="1600ms">
                <div class="top-content">
                    <div class="image">
                        <img src="assets/images/testimonial/1.jpg" alt="">
                    </div>
                    <div class="text">
                        <h3>Ali Ahmed</h3>
                        <span>Al Majaz Waterfront, Sharjah</span>
                    </div>
                </div>
                <div class="content">
                    <p>“The flexible booking system is a game-changer. We can always find a time that fits our schedule, and the grounds are excellent.”</p>
                </div>
            </div>
            <div class="testimonial-card wow fadeInUp" data-wow-duration="1800ms">
                <div class="top-content">
                    <div class="image">
                        <img src="assets/images/testimonial/1.jpg" alt="">
                    </div>
                    <div class="text">
                        <h3>Sara Khalid</h3>
                        <span>Palm Jumeirah, Dubai</span>
                    </div>
                </div>
                <div class="content">
                    <p>“I appreciate the affordable pricing with no hidden fees. The quality of the grounds and the overall experience is fantastic.”</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end of testimonial-->
@endsection