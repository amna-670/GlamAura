<?php
session_start();
include("connect.php") ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>GlamAura</title>
    <link rel="icon" href="images2/icon.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.png" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/linearicons-v1.0.0/icon-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/slick/slick.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/MagnificPopup/magnific-popup.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/custom.css">
    <!--===============================================================================================-->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #333;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Full Width Slider */
        .full-width-slider {
            width: 100vw;
            position: relative;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            overflow: hidden;
            height: 600px;
            margin-bottom: 40px;
        }

        .slider {
            width: 300%;
            height: 100%;
            display: flex;
            transition: transform 0.8s ease-in-out;
        }

        .slide {
            width: 33.333%;
            position: relative;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            opacity: 0.7;
        }

        .slide-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            color: white;
            padding: 30px;
            text-align: center;
        }

        .slide-title {
            font-size: 2.5rem;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .slide-description {
            font-size: 1.2rem;
            margin-bottom: 25px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn {
            display: inline-block;
            background: #ff4d6d;
            color: white;
            padding: 15px 35px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #ff334d;
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(255, 77, 109, 0.5);
        }

        /* Manual slider controls */
        .slider-controls {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 15px;
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            z-index: 10;
        }

        .slider-dot {
            display: inline-block;
            width: 15px;
            height: 15px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .slider-dot:hover {
            background: white;
            transform: scale(1.2);
        }

        .slider-dot.active {
            background: #ff4d6d;
            transform: scale(1.2);
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .full-width-slider {
                height: 500px;
            }

            .slide-title {
                font-size: 2rem;
            }

            .slide-description {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 768px) {
            .full-width-slider {
                height: 400px;
            }

            .slide-title {
                font-size: 1.8rem;
            }

            .slide-description {
                font-size: 1rem;
                margin-bottom: 15px;
            }

            .slide-content {
                padding: 20px;
            }

            .btn {
                padding: 12px 25px;
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .full-width-slider {
                height: 350px;
            }

            .slide-title {
                font-size: 1.5rem;
                margin-bottom: 10px;
            }

            .slide-description {
                font-size: 0.9rem;
                margin-bottom: 12px;
            }

            .slide-content {
                padding: 15px;
            }

            .btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }

            .slider-dot {
                width: 12px;
                height: 12px;
            }
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 40px;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .product-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .product-info {
            padding: 20px;
        }

        .product-title {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #333;
        }

        .product-price {
            font-size: 1.3rem;
            color: #ff4d6d;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .product-link {
            display: block;
            text-align: center;
            background: #f8f9fa;
            color: #495057;
            padding: 10px;
            text-decoration: none;
            font-weight: 600;
            border-radius: 6px;
            transition: background 0.3s ease;
        }

        .product-link:hover {
            background: #e9ecef;
        }
    </style>
</head>

<body class="animsition">

    <!-- Header -->
    <header class="header-v3">
        <!-- Header desktop -->
        <div class="container-menu-desktop trans-03">
            <div class="wrap-menu-desktop">
                <nav class="limiter-menu-desktop p-l-45">

                    <!-- Logo desktop -->
                    <a href="#" class="logo">
                        <img src="images2/Logo (2).png" alt="IMG-LOGO">
                    </a>

                    <!-- Menu desktop -->
                    <div class="menu-desktop">
                        <ul class="main-menu">
                            <li>
                                <a href="index.php">Home</a>
                            </li>

                            <li>
                                <a href="product.php">Shop</a>
                            </li>

                            <li>
                                <a href="shoping-cart.php">Cart</a>
                            </li>

                            <li>
                                <a href="about.php">About</a>
                            </li>

                            <li>
                                <a href="contact.php">Contact</a>
                            </li>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <li>
                                    <a href="logout.php" class="logout-btn">Logout</a>
                                </li>
                            <?php else: ?>
                                <li>
                                    <a href="login.php">Login</a>
                                </li>

                                <li>
                                    <a href="signup.php">Sign Up</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Icon header -->
                    <div class="wrap-icon-header flex-w flex-r-m h-full">
                        <div class="flex-c-m h-full p-r-25 bor6">
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Header Mobile -->
        <div class="wrap-header-mobile">
            <!-- Logo moblie -->
            <div class="logo-mobile">
                <a href="index.php"><img src="images2/Logo (2).png" alt="IMG-LOGO"></a>
            </div>

            <!-- Button show menu -->
            <div class="btn-show-menu-mobile hamburger hamburger--squeeze">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </div>
        </div>


        <!-- Menu Mobile -->
        <div class="menu-mobile">
            <ul class="main-menu-m">
                <li>
                    <a href="index.php">Home</a>
                </li>

                <li>
                    <a href="product.php">Shop</a>
                </li>

                <li>
                    <a href="shoping-cart.php">Cart</a>
                </li>

                <li>
                    <a href="about.php">About</a>
                </li>

                <li>
                    <a href="contact.php">Contact</a>
                </li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li>
                        <a href="logout.php" class="logout-btn">Logout</a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="login.php">Login</a>
                    </li>

                    <li>
                        <a href="signup.php">Sign Up</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <!-- Full Width Slider -->
    <div class="full-width-slider">
        <div class="slider" id="slider">
            <div class="slide">
                <img src="images2/slider1.jpg">
                <div class="slide-content">
                    <h2 class="slide-title">Luxury Makeup Collection</h2>
                    <p class="slide-description">Discover our premium range of long-lasting, vibrant colors for every
                        skin tone.</p>
                    <a href="product.php" class="btn">Shop Now</a>
                </div>
            </div>
            <div class="slide">
                <img src="images2/slider2.jpeg">
                <div class="slide-content">
                    <h2 class="slide-title">Jewelry Collection</h2>
                    <p class="slide-description">Elevate your elegance with timeless jewelry that speaks your style.</p>
                    <a href="product.php" class="btn">Explore</a>
                </div>
            </div>
            <div class="slide">
                <img src="images2/slider3.jpeg">
                <div class="slide-content">
                    <h2 class="slide-title">New Lipstick Shades</h2>
                    <p class="slide-description">Try our latest matte and glossy finishes in 12 new shades.</p>
                    <a href="product.php" class="btn">View Collection</a>
                </div>
            </div>
        </div>

        <div class="slider-controls">
            <span class="slider-dot active" data-slide="0"></span>
            <span class="slider-dot" data-slide="1"></span>
            <span class="slider-dot" data-slide="2"></span>
        </div>
    </div>

    <!-- Featured Products Section -->
    <div class="container">
        <h2 style="text-align: center; margin: 50px 0 30px; color: #333;">Featured Products</h2>

        <div class="product-grid">
            <div class="product-card">
                <img src="images2/product1.jpg">
                <div class="product-info">
                    <h3 class="product-title">Matte Finish Foundation</h3>
                    <div class="product-price">Rs.2455</div>
                    <a href="product.php" class="product-link">Explore More</a>
                </div>
            </div>

            <div class="product-card">
                <img src="images2/product2.jpg">
                <div class="product-info">
                    <h3 class="product-title">Professional Eyeshadow Palette</h3>
                    <div class="product-price">Rs.1279</div>
                    <a href="product.php" class="product-link">Explore More</a>
                </div>
            </div>

            <div class="product-card">
                <img src="images2/product3.jpg">
                <div class="product-info">
                    <h3 class="product-title">Fesciory Leopard Bracelet for Women</h3>
                    <div class="product-price">Rs.699</div>
                    <a href="product.php" class="product-link">Explore More</a>
                </div>
            </div>
        </div>
    </div>


    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <div class="testimonials-heading">
                <h2>What Our Customers Say</h2>
                <p>Discover why our customers love GlamAura products</p>
            </div>

            <div class="testimonials-container">
                <!-- Testimonial 1 -->
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="testimonial-text">
                            "The quality of these products is exceptional! My skin has never felt better. The delivery
                            was fast and the packaging was beautiful."
                        </div>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Ayesha Khan"
                                class="testimonial-avatar">
                            <div class="testimonial-info">
                                <h4>Ayesha Khan</h4>
                                <p>Loyal Customer</p>
                                <div class="testimonial-rating">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="testimonial-text">
                            "I've tried many beauty brands, but GlamAura stands out. Their products are natural,
                            effective, and worth every penny. Highly recommended!"
                        </div>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Sana Malik"
                                class="testimonial-avatar">
                            <div class="testimonial-info">
                                <h4>Sana Malik</h4>
                                <p>Beauty Blogger</p>
                                <div class="testimonial-rating">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star-half"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="testimonial-text">
                            "The customer service is outstanding! They helped me choose the perfect products for my skin
                            type. I'm completely satisfied with my purchase."
                        </div>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Maria Rodriguez"
                                class="testimonial-avatar">
                            <div class="testimonial-info">
                                <h4>Maria Rodriguez</h4>
                                <p>Regular Client</p>
                                <div class="testimonial-rating">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Our team  -->


    <div class="our-team">
        <h2 class="heading-team">Diverse Minds <span>—Common Goal</span></h2>

        <div class="team-container">
            <div class="team-card">
                <div class="team-img">
                    <img src="images2/team.jpg" alt="">
                </div>
                <div class="team-desciption">

                    <h4> Amna Idrees </h4>

                    <h5>Web Developer</h5>

                    <a href="tell:+1 323-913-4688">info@amnaidrees.com</a>
                </div>
            </div>

            <!-- 2nd card -->
            <div class="team-card">
                <div class="team-img">
                    <img src="images2/team.jpg" alt="">
                </div>
                <div class="team-desciption">

                    <h4> Hafiz Saim Ali </h4>

                    <h5> Web Developer</h5>

                    <a href="tell:+1 323-913-4688">info@saimali.com</a>
                </div>
            </div>

            <!-- 3rd   -->
            <div class="team-card">
                <div class="team-img">
                    <img src="images2/team.jpg" alt="">
                </div>
                <div class="team-desciption">

                    <h4> Yaseen Rehman </h4>

                    <h5>Web Developer</h5>

                    <a href="tell:+1 323-913-4688">info@yaseenrehman.com</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg3 p-t-75 p-b-32">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-lg-3 p-b-50">
                    <h4 class="stext-301 cl0 p-b-30">
                        Categories
                    </h4>

                    <ul>
                        <li class="p-b-10">
                            <a href="product.php" class="stext-107 cl7 hov-cl1 trans-04">
                                Cosmetics
                            </a>
                        </li>

                        <li class="p-b-10">
                            <a href="product.php" class="stext-107 cl7 hov-cl1 trans-04">
                                Jewelry
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-sm-6 col-lg-3 p-b-50">
                    <h4 class="stext-301 cl0 p-b-30">
                        Help
                    </h4>

                    <ul>
                        <li class="p-b-10">
                            <a href="contact.php" class="stext-107 cl7 hov-cl1 trans-04">
                                Contact
                            </a>
                        </li>

                        <li class="p-b-10">
                            <a href="shoping-cart.php" class="stext-107 cl7 hov-cl1 trans-04">
                                Cart
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-sm-6 col-lg-3 p-b-50">
                    <h4 class="stext-301 cl0 p-b-30">
                        GET IN TOUCH
                    </h4>

                    <p class="stext-107 cl7 size-201">
                        Plot no.234 , Sharah-e-Faisal, Karachi

                    <div class="p-t-27">
                        <a href="https://www.facebook.com/" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
                            <i class="fa fa-facebook"></i>
                        </a>

                        <a href="https://www.instagram.com/" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
                            <i class="fa fa-instagram"></i>
                        </a>

                        <a href="https://www.pinterest.com/" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
                            <i class="fa fa-pinterest-p"></i>
                        </a>
                    </div>
                </div>

                <p class="stext-107 cl6 txt-center">
                    Copyright &copy; All rights reserved | Made with <i class="fa fa-heart-o" aria-hidden="true"></i> by
                    Jenny</p>
            </div>
        </div>
    </footer>




    <!-- Back to top -->
    <div class="btn-back-to-top" id="myBtn">
        <span class="symbol-btn-back-to-top">
            <i class="zmdi zmdi-chevron-up"></i>
        </span>
    </div>


    <!--===============================================================================================-->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/animsition/js/animsition.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <!--===============================================================================================-->
    <script src="vendor/daterangepicker/moment.min.js"></script>
    <script src="vendor/daterangepicker/daterangepicker.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/slick/slick.min.js"></script>
    <script src="js/slick-custom.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/parallax100/parallax100.js"></script>
    <script>
        $('.parallax100').parallax100();
    </script>
    <!--===============================================================================================-->
    <script src="vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>

    <!--===============================================================================================-->
    <script src="vendor/isotope/isotope.pkgd.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/sweetalert/sweetalert.min.js"></script>

    <!--===============================================================================================-->
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>

    <!--===============================================================================================-->
    <script src="js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slider = document.getElementById('slider');
            const dots = document.querySelectorAll('.slider-dot');
            let currentSlide = 0;
            const slideCount = 3;
            let slideInterval;

            // Function to show a specific slide
            function showSlide(index) {
                // Update slider position
                slider.style.transform = `translateX(-${index * 33.333}%)`;

                // Update active dot
                dots.forEach((dot, i) => {
                    if (i === index) {
                        dot.classList.add('active');
                    } else {
                        dot.classList.remove('active');
                    }
                });

                currentSlide = index;
            }

            // Function to move to next slide
            function nextSlide() {
                let nextIndex = (currentSlide + 1) % slideCount;
                showSlide(nextIndex);
            }

            // Start auto sliding
            function startSlider() {
                slideInterval = setInterval(nextSlide, 2000); // Change slide every 2 seconds
            }

            // Stop auto sliding
            function stopSlider() {
                clearInterval(slideInterval);
            }

            // Add click events to dots
            dots.forEach((dot, index) => {
                dot.addEventListener('click', function () {
                    stopSlider();
                    showSlide(index);
                    startSlider();
                });
            });

            // Start the slider
            startSlider();

            // Pause slider when user hovers over it
            slider.addEventListener('mouseenter', stopSlider);
            slider.addEventListener('mouseleave', startSlider);
        });

    </script>

    <script>
        document.getElementById("hamburger").addEventListener("click", function () {
            document.getElementById("mobileMenu").classList.toggle("active");
            this.classList.toggle("open");
        });
    </script>
</body>

</html>