<?php
session_start();
include("connect.php");

// Redirect to login if not authenticated
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

function getAll($table) {
    global $con;
    $query = "SELECT * FROM $table";
    return mysqli_query($con, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Products</title>
  <link rel="icon" href="uploads/icon.png">
  <link rel="stylesheet" href="css/style.css">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Alertify JS  -->
   <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/bootstrap.min.css"/>
  
  <style>
    .product-image {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 4px;
    }
    .no-image {
      width: 80px;
      height: 80px;
      background-color: #f8f9fa;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 4px;
      color: #6c757d;
    }
  </style>
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
        <h1>Products</h1>
        <div class="user-profile">
           <img src="https://ui-avatars.com/api/?name=Admin+User&background=random" alt="Admin User">
          <div class="user-info">
            <span class="user-name">Admin</span>
            <span class="user-role">Administrator</span>
          </div>
        </div>
      </div>

      <div class="card">
        <h3 class="mb-4">All Products</h3>
        
        <?php if(isset($_SESSION['message'])): ?>
          <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <table class="table table-striped table-bordered">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Image</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Description</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php  
            $product = getAll("product");
            if(mysqli_num_rows($product) > 0) {
              while($item = mysqli_fetch_assoc($product)) {
            ?>
                <tr>
                  <td><?= $item['id']; ?></td>
                  <td><?= $item['name']; ?></td>
                  <td>
                    <?php if(!empty($item['image'])): ?>
                      <img src="uploads/<?= $item['image']; ?>" alt="<?= $item['name']; ?>" class="product-image">
                    <?php else: ?>
                      <div class="no-image">
                        <i class="fas fa-image"></i>
                      </div>
                    <?php endif; ?>
                  </td>
                   <td><?= $item['quantity']; ?></td>
                    <td><?= $item['price']; ?></td>
                  <td><?= $item['description']; ?></td>
                  <td>
                    <a href="edit_products.php?id=<?= $item['id']; ?>" class="btn btn-primary btn-sm">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="delete_products.php?id=<?= $item['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">
                      <i class="fas fa-trash"></i> Delete
                    </a>
                  </td>
                </tr>
            <?php
              }
            } else {
              echo '<tr><td colspan="5" class="text-center">No products found</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Alertify JS -->
  <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>
  
</body>
</html>