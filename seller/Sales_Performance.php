<?php
session_start();
require('../Connect_database.php');
$email = $_SESSION['username'];
$userid = $_SESSION['userid'];
if(empty($_SESSION['username']) && empty($_SESSION['userid'])) {
    header('Location:../login.php');
    exit; // Ensure that script execution stops after redirecting
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Seller Dashboard</title>
<link rel="stylesheet" href="stylesell.css">

</head>
<body>
<nav>
        <div class="wrapper">
            <div class="logo1" ><a href="#">Homely</a></div>
         
           
            <label for="menu-btn" class="btn menu-btn"><i class="fas fa-bars"></i></label>
        </div>
    </nav>
<div class="sidenav">
    <a href="overview.php" >Overview</a>
    <a href="./notification.php" >Notifications</a>
    <a href="Sales_Performance.php" class="active">Sales Performance</a>
    <a href="#" onclick="toggleSubmenu('product-management')">Product Management</a>
    <div class="submenu" id="product-management">
    <a href="order_management.php" >Pre-Book Orders</a>
        <a href="add_product.php">Add Product</a>
        <a href="./normal_orders.php">Orders</a>
        <a href="./schduled.php">Schduled Deliveries</a>
    </div>
    <a href="customer_insights.php">Customer Insights</a>
    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
</div>

<div style="margin-left: 250px; padding: 20px;">
    <!-- Content area where dashboard content will be displayed -->
    <h2>Welcome to Your Seller Dashboard</h2>
    <p>This is where you can view important metrics and manage your business.</p>
</div>

<script>
// JavaScript function to toggle submenu visibility
function toggleSubmenu(submenuId) {
    var submenu = document.getElementById(submenuId);
    if (submenu.style.display === "block") {
        submenu.style.display = "none";
    } else {
        submenu.style.display = "block";
    }
}
</script>

</body>
</html>
