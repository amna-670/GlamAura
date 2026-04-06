<?php
session_start();
include("connect.php");

// Redirect to login if not authenticated
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);

    // First get the image name to delete the file
    $query = "SELECT image FROM product WHERE id='$id'";
    $result = mysqli_query($con, $query);
    $product = mysqli_fetch_assoc($result);
    $image = $product['image'];

    // Delete the category
    $delete_query = "DELETE FROM product WHERE id='$id'";
    $delete_result = mysqli_query($con, $delete_query);

    if ($delete_result) {
        // Delete the image file if it exists
        if (!empty($image) && file_exists("uploads/$image")) {
            unlink("uploads/$image");
        }
        $_SESSION['message'] = "Products deleted successfully";
    } else {
        $_SESSION['message'] = "Error deleting product: " . mysqli_error($con);
    }
}

header('location: products.php');
exit();
?>