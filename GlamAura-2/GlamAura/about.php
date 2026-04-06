<?php
session_start();
include("connect.php") ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>GlamAura - About</title>
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
</head>

<body class="animsition">

  <!-- Header -->
    <header class="header-v3">
        <!-- Header desktop -->
        <div class="container-menu-desktop trans-03">
            <div class="wrap-menu-desktop">
                <nav class="limiter-menu-desktop p-l-45">

                    <!-- Logo desktop -->
                    <a href="#" class="logo .size-124">
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


	<!-- Title page -->
	<section class="txt-center p-lr-15 p-tb-92" style="background-image: url('images2/about page .png');">
		<h2 class="ltext-105 cl0 txt-center">
			About
		</h2>
	</section>


	<!-- Content page -->
	<section class="bg0 p-t-75 p-b-120">
		<div class="container">
			<div class="row p-b-148">
				<div class="col-md-7 col-lg-8">
					<div class="p-t-7 p-r-85 p-r-15-lg p-r-0-md">
						<h3 class="mtext-111 cl2 p-b-16">
							Our Story
						</h3>

						<p class="stext-113 cl6 p-b-26">
							Glam Aura was created with one vision — to celebrate beauty and elegance in every form. From
							timeless jewelry pieces to luxurious cosmetics, we bring you products that help you shine
							with confidence and grace.
						</p>

						<p class="stext-113 cl6 p-b-26">
							Our journey began with a passion for style and self-care. We believe that true beauty comes
							from within, and the right accessories and cosmetics are a way to reflect your inner glow.
							Every collection we design is inspired by modern sophistication blended with timeless charm.
						</p>

						<p class="stext-113 cl6 p-b-26">
							At Glam Aura, quality and authenticity are at the heart of everything we do. Each jewelry
							piece is carefully crafted, and every cosmetic is thoughtfully selected to ensure safety,
							elegance, and style,
						</p>

						<p class="stext-113 cl6 p-b-26">
							Our customers are our inspiration. Whether it’s a special occasion or everyday glamour, we
							aim to empower you with products that make you feel confident, radiant, and uniquely you.
						</p>
					</div>
				</div>

				<div class="col-11 col-md-5 col-lg-4 m-lr-auto">
					<div class="how-bor1 ">
						<div class="hov-img0">
							<img src="images2/about-img1.jpg" alt="IMG">
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="order-md-2 col-md-7 col-lg-8 p-b-30">
					<div class="p-t-7 p-l-85 p-l-15-lg p-l-0-md">
						<h3 class="mtext-111 cl2 p-b-16">
							Our Mission
						</h3>

						<p class="stext-113 cl6 p-b-26">
							At Glam Aura, our mission is to empower individuals to express their true elegance and
							confidence through timeless jewelry and premium beauty essentials. We believe that luxury
							should not just adorn you, but inspire you. From radiant gemstones to high-quality
							cosmetics, every product we offer is crafted to enhance your natural aura and celebrate your
							individuality. Our commitment is to deliver not just style, but an experience of glamour
							that lasts.
						</p>

						<div class="stext-113 cl6 p-b-26">
							<ul>
								<li><b>Elegance & Confidence :</b> Helping customers feel beautiful inside and out.</li>
								<li><b>Quality & Craftsmanship :</b> Offering carefully curated jewelry and cosmetics.
								</li>
								<li><b>Luxury & Accessibility :</b> Bringing premium beauty within reach.</li>
								<li><b>Empowerment :</b> Inspiring self-love and individuality through our collections.
								</li>
							</ul>
						</div>
					</div>
				</div>

				<div class="order-md-1 col-11 col-md-5 col-lg-4 m-lr-auto p-b-30">
					<div class="how-bor2">
						<div class="hov-img0">
							<img src="images2/about-02.jpg" alt="IMG">
						</div>
					</div>
				</div>
			</div>
		</div>


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
	</section>


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
	<script src="vendor/select2/select2.min.js"></script>
	<script>
		$(".js-select2").each(function () {
			$(this).select2({
				minimumResultsForSearch: 20,
				dropdownParent: $(this).next('.dropDownSelect2')
			});
		})
	</script>
	<!--===============================================================================================-->
	<script src="vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script>
		$('.js-pscroll').each(function () {
			$(this).css('position', 'relative');
			$(this).css('overflow', 'hidden');
			var ps = new PerfectScrollbar(this, {
				wheelSpeed: 1,
				scrollingThreshold: 1000,
				wheelPropagation: false,
			});

			$(window).on('resize', function () {
				ps.update();
			})
		});
	</script>
	<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>

</html>