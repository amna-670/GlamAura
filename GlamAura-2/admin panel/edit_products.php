<?php
session_start();
include("connect.php");

// Redirect to login if not authenticated
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Check if product ID is provided
if (!isset($_GET['id'])) {
  header('location: products.php');
  exit();
}

// Correct variable name - should be product_id not category_id
$product_id = mysqli_real_escape_string($con, $_GET['id']);

// Fetch product data first
function getByID($table, $id) {
  global $con;
  $query = "SELECT * FROM $table WHERE id='$id' ";
  return mysqli_query($con, $query);
}

$product_result = getByID("product", $product_id);

// Check if product exists
if (mysqli_num_rows($product_result) == 0) {
  $_SESSION['message'] = "Product not found";
  header('location: products.php');
  exit();
}

$data = mysqli_fetch_array($product_result);

// Process update only if form is submitted
if (isset($_POST["update_products_btn"])) {
  $name = mysqli_real_escape_string($con, $_POST['name']);
  $description = mysqli_real_escape_string($con, $_POST['description']);
  $quantity = mysqli_real_escape_string($con, $_POST['quantity']);
  $price = mysqli_real_escape_string($con, $_POST['price']);

  if (!empty($_FILES['image']['name'])) {
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
    $filename = time() . '.' . $image_ext;

    // Upload the file
    if(move_uploaded_file($image_tmp, 'uploads/' . $filename)) {
      // Update with new image including quantity and price
      $update_query = "UPDATE product SET name='$name', image='$filename', quantity='$quantity', price='$price', description='$description' WHERE id='$product_id'";
    } else {
      $_SESSION['message'] = "Error uploading image";
      // Remove the header redirect to stay on the same page
      // header("location: edit_product.php?id=$product_id");
      // exit();
    }
  } else {
    // Update without changing image but include quantity and price
    $update_query = "UPDATE product SET name='$name', quantity='$quantity', price='$price', description='$description' WHERE id='$product_id'";
  }

  $update_result = mysqli_query($con, $update_query);

  if ($update_result) {
    $_SESSION['message'] = "Product updated successfully";
    // Refresh the page data
    $product_result = getByID("product", $product_id);
    $data = mysqli_fetch_array($product_result);
  } else {
    $_SESSION['message'] = "Error updating product: " . mysqli_error($con);
  }
  
  // Remove the redirect to stay on the same page
  // header("location: edit_product.php?id=$product_id");
  // exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Product</title>
  <link rel="icon" href="uploads/icon.png">
  <link rel="stylesheet" href="css/style.css">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Alertify JS  -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css" />
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/bootstrap.min.css" />
</head>

<body>
  <div class="layout">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <div class="admin-icon">
          <i class="fas fa-user-shield"></i>
        </div>
        <h2>Admin Panel</h2>
      </div>
      <nav>
           <ul>
          <li><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
          <li><a href="add_category.php"><i class="fas fa-users"></i> <span>Add Category</span></a></li>
          <li><a href="category.php"><i class="fas fa-list"></i> <span>View Category</span></a></li>
          <li><a href="add_products.php"><i class="fas fa-cart-plus"></i> <span>Add Products</span></a></li>
          <li class="active"><a href="products.php"><i class="fas fa-shopping-bag"></i> <span>View Products</span></a></li>
        </ul>
      </nav>
      <div class="logout-section">
        <a href="logout.php" class="logout-btn">
          <i class="fas fa-sign-out-alt"></i>
          <span>Logout</span>
        </a>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Topbar -->
      <div class="topbar">
        <h1>Edit Category</h1>
        <div class="user-profile">
          <img src="https://ui-avatars.com/api/?name=Admin+User&background=random" alt="Admin User">
          <div class="user-info">
            <span class="user-name">Admin</span>
            <span class="user-role">Administrator</span>
          </div>
        </div>
      </div>

      <!-- Form Section -->
      <div class="card">
        <h3 class="mb-4">Edit Product Form</h3>
        <form action="" method="POST" enctype="multipart/form-data">
<input type="hidden" name="product_id" value="<?= $product_id ?>">

          <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" name="name" value="<?= $data['name'] ?>"
              placeholder="Enter Product name" required>
          </div>

          <div class="mb-3">
            <label for="image" class="form-label">Current Image</label>
            <div>
              <img src="uploads/<?= $data['image'] ?>" alt="<?= $data['name'] ?>" width="100px" height="100px"
                style="object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
            </div>
          </div>

          <div class="mb-3">
            <label for="image" class="form-label">Change Image (Optional)</label>
            <input class="form-control" type="file" name="image" accept="image/*">
          </div>

          <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" name="quantity" value="<?= $data['quantity'] ?>"
              placeholder="Enter Product quantity" required>
          </div>

          <div class="mb-3">
            <label for="price" class="form-label">Product Price</label>
            <input type="text" class="form-control" name="price" value="<?= $data['price'] ?>"
              placeholder="Enter Product price" required>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="4" placeholder="Enter description"
              required><?= $data['description'] ?></textarea>
          </div>

          <button type="submit" name="update_products_btn" class="btn btn-primary">Update</button>
          <a href="products.php" class="btn btn-secondary">Go back</a>
        </form>
      </div>
    </main>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Alertify JS -->
  <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>

  <script>
    <?php if (isset($_SESSION['message'])) { ?>
      alertify.set('notifier', 'position', 'top-right');
      alertify.success('<?= $_SESSION['message'] ?>');
      <?php
      unset($_SESSION['message']);
    } ?>
  </script>
</body>
</html>