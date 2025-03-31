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
    <title>Document</title>
    <link rel="stylesheet" href="../style2.css">
 

</head>


<body>
    <!-- The sidebar -->
    <div class="sidebar">
        <label for="">DASHBOARD</label>  
        <a href="overview.php">Overview</a>

        
         <a href="Manage_staff.php">Manage Users</a>
        <div id="overviewSubmenu" class="submenu" style="display: none;">
        <a href="Manage_staff.php">List Users</a>
        
    </div>
        <a href="Manage_seller.php">Manage Sellers</a>
        <div id="overviewSubmenu1" class="submenu" style="display: none;">
        <a href="Manage_staff.php">Sellers</a>
        <a href="List_products.php">Products</a>
        
    </div>

        <a href="logout.php">Logout</a>
    </div>
    <!-- Page content -->
    <div style="margin-left: 250px; padding: 20px;" >

    
  

    <div style="display: flex;">
    <div id="over">
        <h3>Total</h3>
      <h1>  <p id="countUsers"></p></h1>
    </div>

    <div id="over1">
        <h3>Total Customers </h3>
       <h1> <p id="countUsers1"></p></h1>
    </div>
    </div>
    <div style="display: flex;">
    <div id="over1">
        <h3>Total Sellers </h3>
       <h1> <p id="countUsers2"></p></h1>
    </div>
    <div id="over1">
        <h3>Total Delivery Persons </h3>
       <h1> <p id="countUsers3"></p></h1>
    </div>
    <div id="over1">
        <h3>Total Blocked </h3>
       <h1> <p id="countUsers4"></p></h1>
    </div>
</div>

    </div>
</body>
<script>
 document.querySelector('.sidebar a[href="Manage_staff.php"]').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior
        var submenu = document.getElementById('overviewSubmenu');
        if (submenu.style.display === 'none') {
            submenu.style.display = 'block';
            submenu.style.maxHeight = submenu.scrollHeight + 'px'; // Expand the submenu height
        } else {
            submenu.style.maxHeight = '0'; // Collapse the submenu height
            setTimeout(function() {
                submenu.style.display = 'none';
            }, 500); // Wait for the transition to complete before hiding the submenu
        }
    });
    document.querySelector('.sidebar a[href="Manage_seller.php"]').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior
        var submenu = document.getElementById('overviewSubmenu1');
        if (submenu.style.display === 'none') {
            submenu.style.display = 'block';
            submenu.style.maxHeight = submenu.scrollHeight + 'px'; // Expand the submenu height
        } else {
            submenu.style.maxHeight = '0'; // Collapse the submenu height
            setTimeout(function() {
                submenu.style.display = 'none';
            }, 500); // Wait for the transition to complete before hiding the submenu
        }
    });

    // Function to animate counting up to a target number
    function animateCount(element, targetCount) {
        let currentCount = 0;
        const increment = Math.ceil(targetCount / 100); // Adjust speed by changing the divisor

        const interval = setInterval(function() {
            currentCount += increment;
            if (currentCount >= targetCount) {
                clearInterval(interval);
                currentCount = targetCount;
            }
            element.textContent = currentCount;
        },60); // Adjust interval as needed (milliseconds)
    }

    // Fetch the total user count from the database and animate counting
    <?php
    $sql = "SELECT count(*) as total FROM tbl_registration r, tbl_login l WHERE r.fk_loginid=l.login_id AND (l.user_roles='0' OR l.user_roles='1' OR l.user_roles='2')";
    if ($result = $conn->query($sql)) {
        if ($result->num_rows > 0) {
            
            $row = $result->fetch_assoc();
            echo "animateCount(document.getElementById('countUsers'), " . $row['total'] . ");";
          
        }
    }
    $sql = "SELECT count(*) as total FROM tbl_registration r, tbl_login l WHERE r.fk_loginid=l.login_id AND user_roles='0'";
    if ($result = $conn->query($sql)) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "animateCount(document.getElementById('countUsers1'), " . $row['total'] . ");";
           
        }
    }
    $sql = "SELECT count(*) as total FROM tbl_registration r, tbl_login l WHERE r.fk_loginid=l.login_id AND user_roles='1'";
    if ($result = $conn->query($sql)) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "animateCount(document.getElementById('countUsers2'), " . $row['total'] . ");";
           
        }
    }
    $sql = "SELECT count(*) as total FROM tbl_registration r, tbl_login l WHERE r.fk_loginid=l.login_id AND user_roles='2'";
    if ($result = $conn->query($sql)) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "animateCount(document.getElementById('countUsers3'), " . $row['total'] . ");";
           
        }
    }
    $sql = "SELECT count(*) as total FROM tbl_registration r, tbl_login l WHERE r.fk_loginid=l.login_id AND (user_roles='0' OR user_roles='1' OR user_roles='2') and l.status='0'";
    if ($result = $conn->query($sql)) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "animateCount(document.getElementById('countUsers4'), " . $row['total'] . ");";
           
        }
    }
    ?>
  
document.getElementById('image').addEventListener('change', function() {
        var input = this;
        var reader = new FileReader();
        var imagePreview = document.getElementById('imagePreview');
        var errorText = document.getElementById('errorText');

        reader.onload = function(e) {
            imagePreview.src = e.target.result;
        };

        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
            document.getElementById('imageLabel').innerText = input.files[0].name; // Update label text
            errorText.style.display = 'none'; // Hide error text if image is selected
        } else {
            imagePreview.src = "default-image.jpg";
            document.getElementById('imageLabel').innerText = 'Select an image'; // Reset label text
            errorText.style.display = 'block'; // Show error text if no image is selected
        }
    });

    function validateImage() {
        var imageInput = document.getElementById('image');
        var errorText = document.getElementById('errorText');

        // Check if an image is selected
        if (imageInput.files.length === 0) {
            errorText.style.display = 'block';
            return false; // Prevent form submission
        } else {
            errorText.style.display = 'none';
            return true; // Allow form submission
        }
    }

    // Trigger file input when clicking on image preview
    document.getElementById('imagePreview').addEventListener('click', function() {
        document.getElementById('image').click();
    });
  
</script>
</html>
