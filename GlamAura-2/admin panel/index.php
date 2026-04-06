<?php
include("connect.php");  
?>

<?php
session_start();

// Redirect to login if not authenticated
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$topProducts = [
    ['name' => 'Oosh Pressed Powder 03 Warm Sand', 'category' => 'Cosmetics', 'price' => 899, 'sold' => 1245, 'revenue' => 1119255, 'status' => 'In Stock'],
    ['name' => 'Diamond Elegance Necklace', 'category' => 'Jewelry', 'price' => 12500, 'sold' => 87, 'revenue' => 1087500, 'status' => 'In Stock'],
    ['name' => 'Ruby Luxe Bracelet', 'category' => 'Jewelry', 'price' => 8500, 'sold' => 112, 'revenue' => 952000, 'status' => 'Low Stock'],
    ['name' => 'Velvet Matte Lipstick - Crimson', 'category' => 'Cosmetics', 'price' => 650, 'sold' => 987, 'revenue' => 641550, 'status' => 'In Stock'],
    ['name' => 'Sapphire Dream Earrings', 'category' => 'Jewelry', 'price' => 7200, 'sold' => 124, 'revenue' => 892800, 'status' => 'In Stock'],
    ['name' => 'Silk Foundation - Beige', 'category' => 'Cosmetics', 'price' => 1200, 'sold' => 532, 'revenue' => 638400, 'status' => 'In Stock'],
    ['name' => 'Pearl Classic Ring', 'category' => 'Jewelry', 'price' => 5600, 'sold' => 142, 'revenue' => 795200, 'status' => 'Out of Stock'],
    ['name' => 'Kajal Intense Eye Liner', 'category' => 'Cosmetics', 'price' => 299, 'sold' => 856, 'revenue' => 255944, 'status' => 'Low Stock'],
    ['name' => 'Gold Plated Anklet', 'category' => 'Jewelry', 'price' => 3200, 'sold' => 198, 'revenue' => 633600, 'status' => 'In Stock'],
    ['name' => 'Blush Bloom Cheek Color', 'category' => 'Cosmetics', 'price' => 750, 'sold' => 654, 'revenue' => 490500, 'status' => 'In Stock']
];

$topCustomers = [
    ['name' => 'Ali Ahmed', 'email' => 'ali.ahmed@gmail.com', 'orders' => 42, 'spent' => 152650, 'join_date' => '12/02/2021', 'status' => 'Active'],
    ['name' => 'Fatima Khan', 'email' => 'fatima.khan@gmail.com', 'orders' => 38, 'spent' => 138420, 'join_date' => '23/05/2021', 'status' => 'Active'],
    ['name' => 'Usman Malik', 'email' => 'usman.malik@gmail.com', 'orders' => 35, 'spent' => 125750, 'join_date' => '15/08/2020', 'status' => 'Active'],
    ['name' => 'Ayesha Raza', 'email' => 'ayesha.raza@gmail.com', 'orders' => 31, 'spent' => 118900, 'join_date' => '03/11/2021', 'status' => 'Active'],
    ['name' => 'Bilal Hassan', 'email' => 'bilal.hassan@gmail.com', 'orders' => 29, 'spent' => 105350, 'join_date' => '19/01/2022', 'status' => 'Active'],
    ['name' => 'Zainab Akhtar', 'email' => 'zainab.akhtar@gmail.com', 'orders' => 27, 'spent' => 98650, 'join_date' => '07/07/2021', 'status' => 'Active'],
    ['name' => 'Omar Farooq', 'email' => 'omar.farooq@gmail.com', 'orders' => 25, 'spent' => 92800, 'join_date' => '28/09/2020', 'status' => 'Inactive'],
    ['name' => 'Sana Sheikh', 'email' => 'sana.sheikh@gmail.com', 'orders' => 23, 'spent' => 87450, 'join_date' => '14/04/2022', 'status' => 'Active'],
    ['name' => 'Hassan Iqbal', 'email' => 'hassan.iqbal@gmail.com', 'orders' => 21, 'spent' => 82300, 'join_date' => '05/12/2021', 'status' => 'Active'],
    ['name' => 'Sadia Abbas', 'email' => 'sadia.abbas@gmail.com', 'orders' => 19, 'spent' => 76950, 'join_date' => '30/03/2022', 'status' => 'Active']
];

// Calculate totals
$totalRevenue = 0;
foreach ($topProducts as $product) {
    $totalRevenue += $product['revenue'];
}

$totalSpent = 0;
foreach ($topCustomers as $customer) {
    $totalSpent += $customer['spent'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Panel Dashboard</title>
  <link rel="icon" href="uploads/icon.png">
  <link rel="stylesheet" href="css/style.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Alertify JS -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/bootstrap.min.css"/>

</head>
<body>
  <style>
/* Dashboard Cards */
.content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

/* Report Styles */
.report-section {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
}

.report-section h2 {
    margin-bottom: 20px;
    color: #2a4365;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 10px;
}

.table-container {
    overflow-x: auto;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.report-table th,
.report-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.report-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #2d3748;
}

.report-table tr:hover {
    background-color: #f7fafc;
}

.total-row {
    font-weight: bold;
    background-color: #e9ecef;
}

.badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-success {
    background-color: #c6f6d5;
    color: #22543d;
}

.badge-warning {
    background-color: #fefcbf;
    color: #744210;
}

.badge-danger {
    background-color: #fed7d7;
    color: #742a2a;
}

.category-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.category-cosmetics {
    background-color: #e9d5ff;
    color: #6b21a8;
}

.category-jewelry {
    background-color: #bfdbfe;
    color: #1e40af;
}
  </style>

  <div class="container">
    <aside class="sidebar">
      <div class="sidebar-header">
        <div class="admin-icon">
          <i class="fas fa-user-shield"></i>
        </div>
        <h2>Admin Panel</h2>
      </div>
      <nav>
          <ul>
          <li class="active"><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
          <li><a href="add_category.php"><i class="fas fa-users"></i> <span>Add Category</span></a></li>
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

    <main class="main-content">
      <header class="topbar">
        <h1>Dashboard Overview</h1>
<div class="user-profile">
  <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['admin_name']); ?>&background=random" alt="Admin User">
  <div class="user-info">
    <span class="user-name"><?php echo $_SESSION['admin_name']; ?></span>
    <span class="user-role">Administrator</span>
  </div>
</div>
      </header>

      <section class="content">
        <div class="card">
          <div class="card-icon">
            <i class="fas fa-users"></i>
          </div>
          <h3>Total Category</h3>
          <?php
            $sql = "SELECT * FROM category";
            $data = mysqli_query($con, $sql);
            $count = mysqli_num_rows($data);
          ?>
          <div class="number"><?php echo $count ?></div>
          <div class="card-footer">
            <div class="positive-change">
            </div>
          </div>
        </div>
        
         <div class="card">
          <div class="card-icon">
            <i class="fas fa-users"></i>
          </div>
          <h3>Total Products</h3>
          <?php
            $sql = "SELECT * FROM product";
            $data = mysqli_query($con, $sql);
            $count = mysqli_num_rows($data);
          ?>
          <div class="number"><?php echo $count ?></div>
          <div class="card-footer">
            <div class="positive-change">
            </div>
          </div>
        </div>
      </section>

         <div class="report-section">
          <h2>Top 10 Best Selling Products</h2>
          <div class="table-container">
            <table class="report-table">
              <thead>
                <tr>
                  <th>Rank</th>
                  <th>Product Name</th>
                  <th>Category</th>
                  <th>Price</th>
                  <th>Units Sold</th>
                  <th>Total Revenue</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $rank = 1;
                foreach($topProducts as $product): 
                  $categoryClass = strtolower($product['category']) == 'cosmetics' ? 'category-cosmetics' : 'category-jewelry';
                ?>
                <tr>
                  <td><?php echo $rank++; ?></td>
                  <td><?php echo $product['name']; ?></td>
                  <td><span class="category-badge <?php echo $categoryClass; ?>"><?php echo $product['category']; ?></span></td>
                  <td>Rs.<?php echo number_format($product['price'], 2); ?></td>
                  <td><?php echo number_format($product['sold']); ?></td>
                  <td>Rs.<?php echo number_format($product['revenue'], 2); ?></td>
                  <td>
                    <?php if($product['status'] == 'In Stock'): ?>
                      <span class="badge badge-success">In Stock</span>
                    <?php elseif($product['status'] == 'Low Stock'): ?>
                      <span class="badge badge-warning">Low Stock</span>
                    <?php else: ?>
                      <span class="badge badge-danger">Out of Stock</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                  <td colspan="5"><strong>Total Revenue from Top Products:</strong></td>
                  <td colspan="2"><strong>Rs.<?php echo number_format($totalRevenue, 2); ?></strong></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="report-section">
          <h2>Top 10 Customers</h2>
          <div class="table-container">
            <table class="report-table">
              <thead>
                <tr>
                  <th>Rank</th>
                  <th>Customer Name</th>
                  <th>Email</th>
                  <th>Orders</th>
                  <th>Total Spent</th>
                  <th>Join Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $rank = 1;
                foreach($topCustomers as $customer): 
                ?>
                <tr>
                  <td><?php echo $rank++; ?></td>
                  <td><?php echo $customer['name']; ?></td>
                  <td><?php echo $customer['email']; ?></td>
                  <td><?php echo $customer['orders']; ?></td>
                  <td>Rs.<?php echo number_format($customer['spent'], 2); ?></td>
                  <td><?php echo $customer['join_date']; ?></td>
                  <td>
                    <?php if($customer['status'] == 'Active'): ?>
                      <span class="badge badge-success">Active</span>
                    <?php else: ?>
                      <span class="badge badge-warning">Inactive</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                  <td colspan="4"><strong>Grand Total from Top Customers:</strong></td>
                  <td colspan="3"><strong>Rs.<?php echo number_format($totalSpent, 2); ?></strong></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
    </main>
  </div>

</body>
</html>