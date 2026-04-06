<?php
session_start();
include("connect.php");

// Redirect to login if not authenticated
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}


if (isset($_POST["add_category_btn"])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    
    // Image handling
    $filename = "";
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
        
        if(in_array($image_ext, $allowed_extensions)) {
            $filename = time() . '_' . rand(1000, 9999) . '.' . $image_ext;
            $upload_dir = 'uploads/';
            
            if(move_uploaded_file($image_tmp, $upload_dir . $filename)) {
                $cate_query = "INSERT INTO category (name, image, description) VALUES ('$name', '$filename', '$description')";
                $cate_query_run = mysqli_query($con, $cate_query);

                if($cate_query_run) {
                    $_SESSION['message'] = "Category added Successfully";
                } else {
                    unlink($upload_dir . $filename);
                    $_SESSION['message'] = "Database Error";
                }
            } else {
                $_SESSION['message'] = "Failed to upload image";
            }
        } else {
            $_SESSION['message'] = "Invalid image format";
        }
    } else {
        $_SESSION['message'] = "Please select an image";
    }
    header('location: add_category.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Category</title>
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
          <li class="active"><a href="add_category.php"><i class="fas fa-users"></i> <span>Add Category</span></a></li>
          <li><a href="category.php"><i class="fas fa-list"></i> <span>View Category</span></a></li>
          <li><a href="add_products.php"><i class="fas fa-cart-plus"></i> <span>Add Products</span></a></li>
          <li><a href="products.php"><i class="fas fa-shopping-bag"></i> <span>View Products</span></a></li>
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
        <h1>Add Category</h1>
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
        <h3 class="mb-4">Category Form</h3>
        <form action="" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="name" class="form-label">Category Name</label>
            <input type="text" class="form-control" name="name" placeholder="Enter category name" required>
          </div>
          <div class="mb-3">
            <label for="image" class="form-label">Upload Image</label>
            <input class="form-control" type="file" name="image" accept="image/*" required>
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="4" placeholder="Enter description" required></textarea>
          </div>
          <button type="submit" name="add_category_btn" class="btn btn-primary">Save</button>
        </form>
      </div>
    </main>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Alertify JS -->
     <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>
     <script>
      <?php if(isset($_SESSION['message'])) { ?>
       alertify.set('notifier','position', 'top-right');
       alertify.success('<?=$_SESSION['message']?>');
     </script>
       <?php 
       unset($_SESSION['message']);
      }
       ?>
</body>
</html>