<?php
session_start();
include("connect.php");

// Initialize cart in session if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get or create cart in database
function getOrCreateCart($con, $user_id = null)
{
    if (isset($_SESSION['cart_id'])) {
        $cart_id = $_SESSION['cart_id'];
        $cart_query = mysqli_query($con, "SELECT * FROM carts WHERE id = '$cart_id'");
        if (mysqli_num_rows($cart_query) > 0) {
            return $cart_id;
        }
    }

    // Create new cart
    $session_id = session_id();
    $query = "INSERT INTO carts (user_id, session_id) VALUES (";
    $query .= $user_id ? "'$user_id'" : "NULL";
    $query .= ", '$session_id')";

    if (mysqli_query($con, $query)) {
        $cart_id = mysqli_insert_id($con);
        $_SESSION['cart_id'] = $cart_id;
        return $cart_id;
    }

    return false;
}

// Sync session cart with database
function syncCartWithDatabase($con, $cart_id)
{
    // Get current cart items from database
    $db_items = [];
    $query = "SELECT product_id, quantity FROM cart_items WHERE cart_id = '$cart_id'";
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $db_items[$row['product_id']] = $row['quantity'];
    }

    // Update database with session cart
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        if (isset($db_items[$product_id])) {
            if ($db_items[$product_id] != $quantity) {
                // Update quantity
                mysqli_query($con, "UPDATE cart_items SET quantity = '$quantity' 
                                   WHERE cart_id = '$cart_id' AND product_id = '$product_id'");
            }
            unset($db_items[$product_id]);
        } else {
            // Add new item
            mysqli_query($con, "INSERT INTO cart_items (cart_id, product_id, quantity) 
                               VALUES ('$cart_id', '$product_id', '$quantity')");
        }
    }

    // Remove items that are no longer in session cart
    foreach ($db_items as $product_id => $quantity) {
        mysqli_query($con, "DELETE FROM cart_items 
                           WHERE cart_id = '$cart_id' AND product_id = '$product_id'");
    }

    // Update session cart with any changes from database
    $query = "SELECT product_id, quantity FROM cart_items WHERE cart_id = '$cart_id'";
    $result = mysqli_query($con, $query);
    $_SESSION['cart'] = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['cart'][$row['product_id']] = $row['quantity'];
    }
}

// Get current user ID (if logged in)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Get or create cart
$cart_id = getOrCreateCart($con, $user_id);

// Sync session with database
if ($cart_id) {
    syncCartWithDatabase($con, $cart_id);
}

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $product_id = mysqli_real_escape_string($con, $_POST['product_id']);
        $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

        // Validate product exists
        $product_query = mysqli_query($con, "SELECT * FROM product WHERE id = '$product_id'");
        if (mysqli_num_rows($product_query) > 0) {
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = $quantity;
            }

            // Update database
            if ($cart_id) {
                $check_query = mysqli_query($con, "SELECT * FROM cart_items 
                                                 WHERE cart_id = '$cart_id' AND product_id = '$product_id'");
                if (mysqli_num_rows($check_query) > 0) {
                    mysqli_query($con, "UPDATE cart_items SET quantity = quantity + '$quantity' 
                                       WHERE cart_id = '$cart_id' AND product_id = '$product_id'");
                } else {
                    mysqli_query($con, "INSERT INTO cart_items (cart_id, product_id, quantity) 
                                       VALUES ('$cart_id', '$product_id', '$quantity')");
                }
            }

            $_SESSION['success_message'] = 'Product added to cart successfully!';
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if (isset($_POST['remove_item'])) {
        $product_id = mysqli_real_escape_string($con, $_POST['product_id']);

        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);

            // Remove from database
            if ($cart_id) {
                mysqli_query($con, "DELETE FROM cart_items 
                                   WHERE cart_id = '$cart_id' AND product_id = '$product_id'");
            }
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantities'] as $product_id => $quantity) {
            $product_id = mysqli_real_escape_string($con, $product_id);
            $quantity = (int) $quantity;

            if ($quantity <= 0) {
                unset($_SESSION['cart'][$product_id]);
                if ($cart_id) {
                    mysqli_query($con, "DELETE FROM cart_items 
                                       WHERE cart_id = '$cart_id' AND product_id = '$product_id'");
                }
            } else {
                $_SESSION['cart'][$product_id] = $quantity;
                if ($cart_id) {
                    $check_query = mysqli_query($con, "SELECT * FROM cart_items 
                                                     WHERE cart_id = '$cart_id' AND product_id = '$product_id'");
                    if (mysqli_num_rows($check_query) > 0) {
                        mysqli_query($con, "UPDATE cart_items SET quantity = '$quantity' 
                                           WHERE cart_id = '$cart_id' AND product_id = '$product_id'");
                    } else {
                        mysqli_query($con, "INSERT INTO cart_items (cart_id, product_id, quantity) 
                                           VALUES ('$cart_id', '$product_id', '$quantity')");
                    }
                }
            }
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = [];

        if ($cart_id) {
            // Delete items
            mysqli_query($con, "DELETE FROM cart_items WHERE cart_id = '$cart_id'");

            // Delete cart record itself
            $deleteCart = mysqli_query($con, "DELETE FROM carts WHERE id = '$cart_id'");

            if (!$deleteCart) {
                die("Error deleting cart: " . mysqli_error($con));
            }

            // Remove cart_id from session
            unset($_SESSION['cart_id']);
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }


}

// Get product details function
function getProductDetails($con, $product_id)
{
    $query = "SELECT * FROM product WHERE id = '$product_id'";
    $result = mysqli_query($con, $query);
    return mysqli_fetch_assoc($result);
}

// Calculate cart total
$total = 0;
$cart_items = [];
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product = getProductDetails($con, $product_id);
        if ($product) {
            $product['quantity'] = $quantity;
            $product['subtotal'] = $product['price'] * $quantity;
            $total += $product['subtotal'];
            $cart_items[$product_id] = $product;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>GlamAura - Shopping Cart</title>
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
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .cart-table th,
        .cart-table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .cart-table th {
            background-color: #f8f8f8;
            font-weight: 600;
        }

        .cart-product {
            display: flex;
            align-items: center;
        }

        .cart-product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 15px;
            border-radius: 5px;
        }

        .cart-product-name {
            font-weight: 500;
            color: #333;
            text-decoration: none;
        }

        .cart-product-name:hover {
            color: #717fe0;
        }

        .cart-quantity {
            width: 80px;
            text-align: center;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn-remove {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-remove:hover {
            background: #ee5253;
        }

        .cart-totals {
            background: #f8f8f8;
            padding: 30px;
            border-radius: 10px;
        }

        .cart-totals h4 {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }

        .cart-totals-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .cart-totals-total {
            font-size: 18px;
            font-weight: 600;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }

        .empty-cart {
            text-align: center;
            padding: 50px 0;
        }

        .empty-cart-icon {
            font-size: 80px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .btn-continue-shopping {
            background: #717fe0;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: background 0.3s;
        }

        .btn-continue-shopping:hover {
            background: #5c6bc0;
            color: white;
        }

        @media (max-width: 768px) {
            .cart-table thead {
                display: none;
            }

            .cart-table tr {
                display: block;
                margin-bottom: 20px;
                border: 1px solid #eee;
                border-radius: 5px;
                padding: 10px;
            }

            .cart-table td {
                display: block;
                text-align: right;
                padding: 10px;
                position: relative;
            }

            .cart-table td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                font-weight: 600;
            }

            .cart-product {
                justify-content: flex-end;
            }
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
    <section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('images2/cart page.jpg');">
        <h2 class="ltext-105 cl0 txt-center">
            My Cart
        </h2>
    </section>

    <!-- breadcrumb -->
    <div class="container">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="index.php" class="stext-109 cl8 hov-cl1 trans-04">
                Home
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <span class="stext-109 cl4">
                Shopping Cart
            </span>
        </div>
    </div>

    <!-- Cart content -->
    <section class="cart bg0 p-t-75 p-b-85">
        <div class="container">
            <?php if (!empty($cart_items)): ?>
                <!-- Update Cart Form -->
                <form method="post" action="" id="updateCartForm">
                    <div class="table-responsive">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $product_id => $product):
                                    // Handle product image
                                    $image_path = "images/default-product.jpg"; // Default image
                                    if (!empty($product['image'])) {
                                        $possible_paths = [
                                            "../admin panel/uploads/" . $product['image'],
                                            "uploads/" . $product['image'],
                                            "images/" . $product['image'],
                                            $product['image']
                                        ];

                                        foreach ($possible_paths as $path) {
                                            if (file_exists($path)) {
                                                $image_path = $path;
                                                break;
                                            }
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="cart-product">
                                                <img src="<?php echo $image_path; ?>"
                                                    alt="<?php echo htmlspecialchars($product['name']); ?>"
                                                    class="cart-product-img">
                                                <div>
                                                    <div class="font-weight-bold">
                                                        <?php echo htmlspecialchars($product['name']); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rs. <?php echo number_format($product['price'], 2); ?></td>
                                        <td>
                                            <input type="number" name="quantities[<?php echo $product_id; ?>]"
                                                value="<?php echo $product['quantity']; ?>" min="1" class="form-control"
                                                style="width: 80px; margin: 0 auto;">
                                        </td>
                                        <td>Rs. <?php echo number_format($product['subtotal'], 2); ?></td>
                                        <td>
                                            <!-- Individual Remove Form for each product -->
                                            <form method="post" action="" style="display: inline;">
                                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                                <button type="submit" name="remove_item" class="btn-remove">
                                                    <i class="fa fa-trash"></i> Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="product.php" class="btn btn-secondary">Continue Shopping</a>
                        <div>
                            <button type="submit" name="update_cart" class="btn btn-primary">
                                Update Cart
                            </button>
                            <button type="submit" name="clear_cart" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to clear your cart?');">
                                Clear Cart
                            </button>
                        </div>
                    </div>
                </form>

                <div class="row mt-5 justify-content-center">
                    <div class="col-md-6 offset-md-6">
                        <div class="cart-totals">
                            <h4 class="mb-4">Cart Totals</h4>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>Rs. <?php echo number_format($total, 2); ?></span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping</span>
                                <span>Free</span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-4 font-weight-bold">
                                <span>Total</span>
                                <span>Rs. <?php echo number_format($total, 2); ?></span>
                            </div>

                            <a href="checkout.php" class="btn btn-success btn-block btn-lg">
                                Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-cart">
                    <div class="empty-cart-icon">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <h3>Your cart is empty</h3>
                    <p>Looks like you haven't added any items to your cart yet.</p>
                    <a href="product.php" class="btn-continue-shopping">Continue Shopping</a>
                </div>
            <?php endif; ?>
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
    < <!--===============================================================================================-->
        <script src="vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
        <!--===============================================================================================-->
        <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>

        <!--===============================================================================================-->
        <script src="js/main.js"></script>

</body>

</html>