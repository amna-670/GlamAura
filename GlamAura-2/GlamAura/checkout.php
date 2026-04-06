<?php
session_start();
include("connect.php");

// --- Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = 'checkout.php';
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// --- Get user
$user = [];
$res = mysqli_query($con, "SELECT * FROM users WHERE id = '$user_id'");
if ($res && mysqli_num_rows($res) > 0) {
    $user = mysqli_fetch_assoc($res);
}

// --- Get cart
$cart_items = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $pid => $qty) {
        $q = mysqli_query($con, "SELECT * FROM product WHERE id='$pid'");
        if ($q && mysqli_num_rows($q)) {
            $p = mysqli_fetch_assoc($q);
            $p['quantity'] = $qty;
            $p['subtotal'] = $p['price'] * $qty;
            $total += $p['subtotal'];
            $cart_items[$pid] = $p;
        }
    }
}
if (!$cart_items) {
    header('Location: shoping-cart.php');
    exit;
}

// --- Helpers
function validPhone($phone, $required = true) {
    return (!$required && empty($phone)) || preg_match('/^\d{11}$/', $phone);
}
function validEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
    $domain = substr(strrchr($email, "@"), 1);
    return checkdnsrr($domain, "MX");
}

// --- Handle form
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $email = trim($_POST['email']);
    $work_phone = trim($_POST['work_phone']);
    $cell_no = trim($_POST['cell_no']);
    $dob = trim($_POST['dob']);
    $category = trim($_POST['category']);
    $remarks = trim($_POST['remarks']);

    // --- Validation
    if (!$name) $errors['name'] = "Name is required";
    if (!$address) $errors['address'] = "Address is required";
    if (!$email) $errors['email'] = "Email is required";
    elseif (!validEmail($email)) $errors['email'] = "Invalid or non-existent email domain";
    if (!validPhone($cell_no)) $errors['cell_no'] = "Cell must be 11 digits";
    if (!validPhone($work_phone, false)) $errors['work_phone'] = "Work phone must be 11 digits";

    // --- If valid, insert order
    if (!$errors) {
        $order_q = "INSERT INTO orders 
            (user_id, name, address, email, work_phone, cell_no, dob, category, remarks, total_amount, status) 
            VALUES ('$user_id','$name','$address','$email','$work_phone','$cell_no','$dob','$category','$remarks','$total','pending')";
        if (mysqli_query($con, $order_q)) {
            $order_id = mysqli_insert_id($con);

            foreach ($cart_items as $pid => $item) {
                $q = "INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) 
                      VALUES ('$order_id','$pid','{$item['quantity']}','{$item['price']}','{$item['subtotal']}')";
                mysqli_query($con, $q);
            }

            // Update user fields
            $fields = [];
            foreach (['address','email','work_phone','cell_no','dob','category','remarks'] as $f) {
                if (!empty($$f)) $fields[] = "$f='" . mysqli_real_escape_string($con, $$f) . "'";
            }
            if ($fields) {
                mysqli_query($con, "UPDATE users SET " . implode(',', $fields) . " WHERE id='$user_id'");
            }

            // Clear cart
            $_SESSION['cart'] = [];
            if (!empty($_SESSION['cart_id'])) {
                $cid = $_SESSION['cart_id'];
                mysqli_query($con, "DELETE FROM cart_items WHERE cart_id='$cid'");
                mysqli_query($con, "DELETE FROM carts WHERE id='$cid'");
                unset($_SESSION['cart_id']);
            }

            $_SESSION['order_success'] = true;
            $_SESSION['order_id'] = $order_id;
            header('Location: order-success.php');
            exit;
        } else {
            $errors['general'] = "Error placing order: " . mysqli_error($con);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>GlamAura - Checkout</title>
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
    <style>
        .checkout-container {
            padding: 50px 0;
        }

        .order-summary {
            background: #f8f8f8;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }
        
        .form-control.error {
            border-color: red;
        }
        
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .btn-checkout {
            background: #717fe0;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-checkout:hover {
            background: #5c6bc0;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #ffeeee;
            border-radius: 5px;
            border: 1px solid #ffcccc;
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
    <section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('images2/bg-02.jpg');">
        <h2 class="ltext-105 cl0 txt-center">
            Checkout
        </h2>
    </section>

    <!-- Checkout content -->
    <section class="checkout-container bg0 p-t-75 p-b-85">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-xl-8">
                    <div class="checkout-form">
                        <h4 class="mtext-105 cl2 p-b-30">Billing Details</h4>

                        <?php if (isset($errors['general'])): ?>
                            <div class="error"><?php echo $errors['general']; ?></div>
                        <?php endif; ?>

                        <form method="post" action="" id="checkoutForm">
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name" class="form-control <?php echo isset($errors['name']) ? 'error' : ''; ?>"
                                    value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : (isset($user['username']) ? htmlspecialchars($user['username']) : ''); ?>"
                                    required>
                                <?php if (isset($errors['name'])): ?>
                                    <div class="error-message"><?php echo $errors['name']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="address">Address *</label>
                                <textarea id="address" name="address" class="form-control <?php echo isset($errors['address']) ? 'error' : ''; ?>" rows="3"
                                    required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : (isset($user['address']) ? htmlspecialchars($user['address']) : ''); ?></textarea>
                                <?php if (isset($errors['address'])): ?>
                                    <div class="error-message"><?php echo $errors['address']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" class="form-control <?php echo isset($errors['email']) ? 'error' : ''; ?>"
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : (isset($user['email']) ? htmlspecialchars($user['email']) : ''); ?>"
                                    required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="error-message"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="work_phone">Work Phone No.</label>
                                        <input type="tel" id="work_phone" name="work_phone" class="form-control <?php echo isset($errors['work_phone']) ? 'error' : ''; ?>"
                                            value="<?php echo isset($_POST['work_phone']) ? htmlspecialchars($_POST['work_phone']) : (isset($user['work_phone']) ? htmlspecialchars($user['work_phone']) : ''); ?>"
                                            maxlength="11">
                                        <?php if (isset($errors['work_phone'])): ?>
                                            <div class="error-message"><?php echo $errors['work_phone']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cell_no">Cell No. *</label>
                                        <input type="tel" id="cell_no" name="cell_no" class="form-control <?php echo isset($errors['cell_no']) ? 'error' : ''; ?>" required
                                            value="<?php echo isset($_POST['cell_no']) ? htmlspecialchars($_POST['cell_no']) : (isset($user['cell_no']) ? htmlspecialchars($user['cell_no']) : ''); ?>"
                                            maxlength="11">
                                        <?php if (isset($errors['cell_no'])): ?>
                                            <div class="error-message"><?php echo $errors['cell_no']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="dob">Date Of Birth</label>
                                <input type="date" id="dob" name="dob" class="form-control"
                                    value="<?php echo isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : (isset($user['dob']) ? htmlspecialchars($user['dob']) : ''); ?>">
                            </div>

                            <div class="form-group">
                                <label for="category">Category</label>
                                <select id="category" name="category" class="form-control">
                                    <option value="">Select Category</option>
                                    <option value="Cosmetics" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Cosmetics') || (isset($user['category']) && $user['category'] == 'Cosmetics') ? 'selected' : ''; ?>>Cosmetics
                                    </option>
                                    <option value="Jewelry" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Jewelry') || (isset($user['category']) && $user['category'] == 'Jewelry') ? 'selected' : ''; ?>>Jewelry</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="remarks">Remarks (Additional Information)</label>
                                <textarea id="remarks" name="remarks" class="form-control"
                                    rows="3"><?php echo isset($_POST['remarks']) ? htmlspecialchars($_POST['remarks']) : (isset($user['remarks']) ? htmlspecialchars($user['remarks']) : ''); ?></textarea>
                            </div>

                            <button type="submit" class="btn-checkout">Place Order</button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-4 col-xl-4">
                    <div class="order-summary">
                        <h4 class="mtext-105 cl2 p-b-30">Your Order</h4>

                        <?php foreach ($cart_items as $item): ?>
                            <div class="order-item">
                                <div>
                                    <span class="stext-110"><?php echo htmlspecialchars($item['name']); ?> ×
                                        <?php echo $item['quantity']; ?></span>
                                </div>
                                <div>
                                    <span class="stext-110">Rs. <?php echo number_format($item['subtotal'], 2); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="order-item" style="border-top: 2px solid #ddd; padding-top: 15px;">
                            <div>
                                <span class="mtext-101 cl2">Total:</span>
                            </div>
                            <div>
                                <span class="mtext-101 cl2">Rs. <?php echo number_format($total, 2); ?></span>
                            </div>
                        </div>
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
    <!--===============================================================================================-->
    <script src="vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>

    <!--===============================================================================================-->
    <script src="js/main.js"></script>

  <script>
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    if (!this.checkValidity()) {
        e.preventDefault();
        alert("Please fix errors before submitting!");
    }
});
</script>


</body>

</html>