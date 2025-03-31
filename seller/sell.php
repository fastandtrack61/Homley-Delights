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
    <a href="#" class="active">Overview</a>
    <a href="#">Sales Performance</a>
    <a href="#" onclick="toggleSubmenu('product-management')">Product Management</a>
    <div class="submenu" id="product-management">
        <a href="order_management.php">Order Management</a>
        <a href="#">Add Product</a>
    </div>
    <a href="#">Customer Insights</a>
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
