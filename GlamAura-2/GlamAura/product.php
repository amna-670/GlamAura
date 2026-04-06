<?php
session_start();
include("connect.php");

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function getIDActive($con, $table, $id)
{
    $query = "SELECT * FROM $table WHERE id= '$id'";
    return mysqli_query($con, $query);
}

// Get the specific category ID from URL parameter
$category_id = isset($_GET['category']) ? $_GET['category'] : null;

// Get search query if exists
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

// If we have a category ID, get that specific category
if ($category_id) {
    $category_result = getIDActive($con, "category", $category_id);
    $specific_category = mysqli_fetch_assoc($category_result);
}

// Add to cart functionality
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

    // Validate quantity
    if ($quantity < 1) {
        $quantity = 1;
    }

    // Add product to cart or update quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    // Set success message and refresh to show it
    $_SESSION['success_message'] = 'Product added to cart successfully!';
    
    // Preserve search query and category in redirect
    $redirect_url = $_SERVER['PHP_SELF'];
    $params = [];
    if (isset($_GET['category'])) $params[] = 'category=' . $_GET['category'];
    if (!empty($search_query)) $params[] = 'q=' . urlencode($search_query);
    if (!empty($params)) $redirect_url .= '?' . implode('&', $params);
    
    header("Location: " . $redirect_url);
    exit();
}

// Handle quantity updates via form submission
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

    if ($quantity < 1) {
        $quantity = 1;
    }

    $_SESSION['cart'][$product_id] = $quantity;

    // Preserve search query and category in redirect
    $redirect_url = $_SERVER['PHP_SELF'];
    $params = [];
    if (isset($_GET['category'])) $params[] = 'category=' . $_GET['category'];
    if (!empty($search_query)) $params[] = 'q=' . urlencode($search_query);
    if (!empty($params)) $redirect_url .= '?' . implode('&', $params);
    
    header("Location: " . $redirect_url);
    exit();
}

// Check for success message in session
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
// Clear the message after retrieving it
unset($_SESSION['success_message']);

// Get cart count for display
$cart_count = array_sum($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>GlamAura - Products</title>
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
        .search-container {
            display: flex;
            justify-content: flex-start;
            margin-top: 20px;
            margin-bottom: 20px;
            padding-left: 15px;
        }
        
        .search-form {
            display: flex;
            align-items: center;
            width: 100%;
            max-width: 500px;
        }
        
        .search-input {
            width: 300px;
            margin-right: 10px;
        }
        
        .categories-title {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>

<body class="animsition">

    <!-- Success Alert -->
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success">
            <i class="fa fa-check-circle alert-icon"></i>
            <div><?php echo $success_message; ?></div>
            <span class="alert-close" onclick="this.parentElement.style.display='none'">&times;</span>
        </div>
    <?php endif; ?>
    
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
    <section class="size-1001 bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('images2/product page.jpg');">
    </section>

    <div class="container" style="margin-top:20px; margin-bottom:20px;">
    <div class="d-flex justify-content-end">
        <form method="get" action="product.php" class="form-inline">
            <?php if ($category_id): ?>
                <input type="hidden" name="category" value="<?php echo $category_id; ?>">
            <?php endif; ?>
            <input type="text" name="q" class="form-control" 
                   placeholder="Search products..." 
                   value="<?php echo htmlspecialchars($search_query); ?>" 
                   style="width:300px; margin-right:10px;">
            <button type="submit" class="btn btn-primary">Search</button>
            <?php if (!empty($search_query)): ?>
                <a href="product.php<?php echo $category_id ? '?category='.$category_id : ''; ?>" 
                   class="btn btn-secondary" style="margin-left:10px;">
                    Clear Search
                </a>
            <?php endif; ?>
        </form>
    </div>
</div>


    <section class="bg0 p-t-23 p-b-20">
        <div class="container">
            <?php if (isset($specific_category)): ?>
                <h3 class="stext-107 cl2 p-b-10" style="font-size: 1.5rem; font-weight: 400;">
                    Category / <?= htmlspecialchars($specific_category['name']); ?>
                </h3>
            <?php else: ?>
                <h3 class="stext-107 cl2 p-b-10" style="font-size: 1.5rem; font-weight: 400;">
                    All Categories
                </h3>
            <?php endif; ?>
            
            <?php if (!empty($search_query)): ?>
                <p class="stext-107 cl2">
                    Search results for: <strong>"<?= htmlspecialchars($search_query) ?>"</strong>
                </p>
            <?php endif; ?>
        </div>
    </section>


    <!-- Products Section -->
    <section class="bg0 p-t-40 p-b-70">
        <div class="container">
            <!-- Categories Title - Centered -->
            <?php if (!isset($specific_category) && empty($search_query)): ?>
                <div class="p-b-30 categories-title">
                    <h3 class="mtext-105 cl2" style="font-size: 2rem; font-weight: 600;">
                        Our Categories
                    </h3>
                </div>
            <?php endif; ?>

            <div class="row justify-content-center text-center">
                <?php
                // If we're searching and not in a specific category
                if (!empty($search_query) && !$category_id) {
                    // Search across all products
                    $search_term = mysqli_real_escape_string($con, $search_query);
                    $product_query = mysqli_query($con, "SELECT * FROM product WHERE name LIKE '%$search_term%' OR description LIKE '%$search_term%'");
                    
                    if (mysqli_num_rows($product_query) > 0) {
                        while ($product = mysqli_fetch_assoc($product_query)) {
                            $image_path = "../admin panel/uploads/" . $product['image'];
                            if (!file_exists($image_path) || $product['image'] == '') {
                                $image_path = "https://via.placeholder.com/400x300.png?text=No+Image";
                            }
                            ?>
                            <!-- Product display code -->
                            <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 d-flex justify-content-center">
                                <!-- Block2 -->
                                <div class="block2"
                                    style="border: 1px solid #eee; border-radius: 8px; padding: 15px; height: 100%;">
                                    <div class="block2-pic hov-img0" style="height: 250px; overflow: hidden; border-radius: 5px;">
                                        <img src="<?= $image_path ?>" alt="<?= htmlspecialchars($product['name']) ?>"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>

                                    <div class="block2-txt flex-w flex-t p-t-14">
                                        <div class="block2-txt-child1 flex-col-l" style="width: 100%;">
                                            <span class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6"
                                                style="font-size: 1.1rem; font-weight: 500; color: #333; display: block; margin-bottom: 8px;">
                                                <?= htmlspecialchars($product['name']) ?>
                                            </span>

                                            <span class="stext-105 cl3"
                                                style="font-size: 1.2rem; font-weight: 600; color: #717fe0;">
                                                Rs. <?= number_format($product['price'], 2) ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Add to Cart Form -->
                                    <form method="post" action="" class="p-t-10">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <div class="flex-w flex-m p-b-10" style="justify-content: center; align-items: center;">
                                            <div class="quantity-controls">
                                                <button type="button" class="qty-btn"
                                                    onclick="this.nextElementSibling.stepDown();">-</button>
                                                <input class="qty-input" type="number" name="quantity" value="1" min="1">
                                                <button type="button" class="qty-btn"
                                                    onclick="this.previousElementSibling.stepUp();">+</button>
                                            </div>

                                            <button type="submit" name="add_to_cart"
                                                class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04"
                                                style="background: #717fe0; border: none; border-radius: 25px; padding: 10px 20px; color: white; font-weight: 500; margin-left: 10px;">
                                                Add to Cart
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="col-12 text-center">
                            <p class="stext-107 cl7" style="font-size: 1.2rem; padding: 40px;">
                                No products found for "<?= htmlspecialchars($search_query) ?>".
                            </p>
                        </div>
                        <?php
                    }
                }
                // If we're in a specific category
                else if (isset($specific_category)) {
                    // Build the query based on whether we're searching within a category
                    if (!empty($search_query)) {
                        $search_term = mysqli_real_escape_string($con, $search_query);
                        $product_query = mysqli_query($con, "SELECT * FROM product WHERE category_id = '$category_id' AND (name LIKE '%$search_term%' OR description LIKE '%$search_term%')");
                    } else {
                        $product_query = mysqli_query($con, "SELECT * FROM product WHERE category_id = '$category_id'");
                    }

                    if (mysqli_num_rows($product_query) > 0) {
                        while ($product = mysqli_fetch_assoc($product_query)) {
                            $image_path = "../admin panel/uploads/" . $product['image'];
                            if (!file_exists($image_path) || $product['image'] == '') {
                                $image_path = "https://via.placeholder.com/400x300.png?text=No+Image";
                            }
                            ?>
                            <!-- Product display code -->
                            <div class="col-sm-6 col-md-4 col-lg-3 p-b-35">
                                <!-- Block2 -->
                                <div class="block2"
                                    style="border: 1px solid #eee; border-radius: 8px; padding: 15px; height: 100%;">
                                    <div class="block2-pic hov-img0" style="height: 250px; overflow: hidden; border-radius: 5px;">
                                        <img src="<?= $image_path ?>" alt="<?= htmlspecialchars($product['name']) ?>"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>

                                    <div class="block2-txt flex-w flex-t p-t-14">
                                        <div class="block2-txt-child1 flex-col-l" style="width: 100%;">
                                            <span class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6"
                                                style="font-size: 1.1rem; font-weight: 500; color: #333; display: block; margin-bottom: 8px;">
                                                <?= htmlspecialchars($product['name']) ?>
                                            </span>

                                            <span class="stext-105 cl3"
                                                style="font-size: 1.2rem; font-weight: 600; color: #717fe0;">
                                                Rs. <?= number_format($product['price'], 2) ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Add to Cart Form -->
                                    <form method="post" action="" class="p-t-10">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <div class="flex-w flex-m p-b-10" style="justify-content: center; align-items: center;">
                                            <div class="quantity-controls">
                                                <button type="button" class="qty-btn"
                                                    onclick="this.nextElementSibling.stepDown();">-</button>
                                                <input class="qty-input" type="number" name="quantity" value="1" min="1">
                                                <button type="button" class="qty-btn"
                                                    onclick="this.previousElementSibling.stepUp();">+</button>
                                            </div>

                                            <button type="submit" name="add_to_cart"
                                                class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04"
                                                style="background: #717fe0; border: none; border-radius: 25px; padding: 10px 20px; color: white; font-weight: 500; margin-left: 10px;">
                                                Add to Cart
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="col-12 text-center">
                            <p class="stext-107 cl7" style="font-size: 1.2rem; padding: 40px;">
                                <?php 
                                if (!empty($search_query)) {
                                    echo 'No products found for "' . htmlspecialchars($search_query) . '" in this category.';
                                } else {
                                    echo 'No products available in this category.';
                                }
                                ?>
                            </p>
                        </div>
                        <?php
                    }
                } 
                // If we're not in a category and not searching, show all categories
                else {
                    // Fetch all categories from database
                    $category_query = mysqli_query($con, "SELECT * FROM category");

                    if (mysqli_num_rows($category_query) > 0) {
                        while ($category = mysqli_fetch_assoc($category_query)) {
                            $image_path = "../admin panel/uploads/" . $category['image'];
                            if (!file_exists($image_path) || $category['image'] == '') {
                                $image_path = "https://via.placeholder.com/400x300.png?text=No+Image";
                            }
                            ?>

                            <div class="col-sm-10 col-md-6 col-lg-4 p-b-35">
                                <div class="block2" style="margin: 0 auto; max-width: 400px;">
                                    <a href="product.php?category=<?= $category['id'] ?>" class="category-link">
                                        <div class="block2-pic hov-img0"
                                            style="height: 250px; overflow: hidden; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                                            <img src="<?= $image_path ?>" alt="<?= htmlspecialchars($category['name']) ?>"
                                                style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                                        </div>
                                    </a>

                                    <div class="block2-txt flex-w flex-t p-t-14" style="text-align: center; padding: 20px 0;">
                                        <div class="block2-txt-child1 flex-col-l" style="width: 100%;">
                                            <a href="product.php?category=<?= $category['id'] ?>"
                                                class="stext-104 cl4 hov-cl1 trans-04 p-b-6"
                                                style="font-size: 1.2rem; font-weight: 500; color: #333; display: block; margin-bottom: 10px; text-align: center;">
                                                <?= htmlspecialchars($category['name']) ?>
                                            </a>

                                            <span class="stext-105 cl3"
                                                style="font-size: 0.9rem; color: #666; line-height: 1.6; text-align: center; display: block;">
                                                <?= htmlspecialchars(substr($category['description'], 0, 100)) ?>...
                                            </span>

                                            <div style="text-align: center; margin-top: 15px;">
                                                <a href="product.php?category=<?= $category['id'] ?>"
                                                    class="flex-c-m stext-101 cl0 size-102 bg1 bor1 hov-btn1 p-lr-15 trans-04"
                                                    style="display: inline-flex; align-items: center; justify-content: center; padding: 10px 25px; background: linear-gradient(45deg, #717fe0, #a56de7); border: none; border-radius: 50px; font-weight: 500; color: white; text-decoration: none; font-size: 0.9rem;">
                                                    Shop Now
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                        }
                    } else {
                        ?>
                        <div class="col-12 text-center">
                            <p class="stext-107 cl7" style="font-size: 1.2rem; padding: 40px;">No categories available at the
                                moment.</p>
                        </div>
                        <?php
                    }
                }
                ?>
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
    <!--===============================================================================================-->
    <script src="vendor/daterangepicker/moment.min.js"></script>
    <script src="vendor/daterangepicker/daterangepicker.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/slick/slick.min.js"></script>
    <script src="js/slick-custom.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/parallax100/parallax100.js"></script>
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

</body>

</html>