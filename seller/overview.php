<?php
session_start();
require('../Connect_database.php');
$email = $_SESSION['username'];
$userid = $_SESSION['userid'];


$email_verified;
if(empty($_SESSION['username']) && empty($_SESSION['userid'])) {
    header('Location:../login.php');
    exit; // Ensure that script execution stops after redirecting
}








$sql = "SELECT  `email_verify` FROM `tbl_verification` where fk_regid='$userid'";

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $email_verified = $row['email_verify'];
    }
}





$verification_attempts = 0; // Assuming there are initially no verification attempts
$attempts_left = 3;






?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Seller Dashboard</title>

<style>


#verification_div {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 2px solid black;
            border-radius: 5px;
            z-index: 9999;
            /* Increase the z-index value */
        }

        .verification-div {
            margin-top: 20px;
            text-align: center;
        }

        .verification-message {
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .verification-message h2 {
            margin-top: 0;
        }

        .verification-message p {
            margin-bottom: 0;
        }

        .verification-buttons {
            margin-top: 20px;
        }

        .verification-buttons button,.vf {
            padding: 10px 20px;
            margin-right: 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
        }

        .verification-buttons button:hover ,.vf:hover{
            background-color: #0056b3;
        }

        .verification-input {

            margin-top: 20px;
        }

        .verification-input input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Add this CSS to your existing stylesheet */
        .custom-dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 120px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1;
            top: 55px;
            /* Adjust the position as needed */
            border-radius: 5px;
            padding: 10px 0;
            opacity: 0;
            /* Start with 0 opacity */
            transition: opacity 0.5s ease;
            /* Smooth transition */
        }

        .custom-dropdown-content a {
            color: #333;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
            opacity: 0;
            /* Start with 0 opacity */
            transition: opacity 0.5s ease;
            /* Smooth transition */
        }

        .custom-dropdown-content a:hover {
            background-color: #f0f0f0;
        }

        .user-dropdown:hover .custom-dropdown-content {
            display: block;
            opacity: 1;
            /* Set opacity to 1 when displayed */
        }

        .user-dropdown:hover .custom-dropdown-content a {
            opacity: 1;
            /* Fade in each list item */
            transition-delay: 0.1s;
            /* Add a delay for each list item */
        }

        /* Add delay for each subsequent list item */
        .custom-dropdown-content a:nth-child(2) {
            transition-delay: 0.2s;
        }

        .custom-dropdown-content a:nth-child(3) {
            transition-delay: 0.3s;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 2px solid black;
            border-radius: 5px;
            z-index: 9999;
        }

        /* Add more nth-child rules as needed for additional list items */

        /* Styling for the arrow */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* Semi-transparent black */
            z-index: 9998;
            /* Ensure the overlay is below the popup but above other elements */
        }


        .verification-input input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-top: 10px;
    margin-bottom: 5px;
    width: 50%; /* Adjust width as needed */
}

/* CSS for the back to login page link */
.popup p a {
    color: #007bff; /* Link color */
    text-decoration: none; /* Remove underline */
}

.popup p a:hover {
    text-decoration: underline; /* Add underline on hover */
}/* CSS for notifications */
.overview-section {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px auto;
          
        }

        .overview-box {
            border: 2px solid #007bff;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
            margin: 20px;
            width: 20rem;
        }

        .overview-box h3 {
            font-size: 18px;
            color: #007bff;
            margin-top: 0;
        }

        .overview-info {
            margin-bottom: 10px;
        }

        .overview-info span {
            font-weight: bold;
            color: #333;
        }
</style>
<link rel="stylesheet" href="stylesell.css">
<script src="../sweetalert.js"></script>
    <script src="../sweetalert.min.js"></script>
</head>
<body>
<nav>
        <div class="wrapper">
            <div class="logo1" ><a href="#">Homely</a></div>
         
           
            <label for="menu-btn" class="btn menu-btn"><i class="fas fa-bars"></i></label>
        </div>
    </nav>
    <div class="sidenav">
    <a href="./overview.php" class="active">Overview</a>
    <a href="./notification.php" >Notifications</a>
    <a href="#" onclick="toggleSubmenu('product-management')">Product Management</a>
    <div class="submenu" id="product-management">
    <a href="order_management.php" >Pre-Book Orders</a>
        <a href="add_product.php">Add Product</a>
        <a href="./normal_orders.php">Orders</a>
        <a href="./schduled.php">Schduled Deliveries</a>
    </div>
    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
</div>

<div style="margin-left: 250px; padding: 20px;">
    <!-- Content area where dashboard content will be displayed -->
    <?php
// Assuming you have already established a database connection


$productCountQuery = "SELECT COUNT(*) AS total_products FROM tbl_products WHERE fk_regid = '$userid'";
$productCountResult = $conn->query($productCountQuery);
$productCountRow = $productCountResult->fetch_assoc();
$totalProducts = $productCountRow['total_products'];

// Retrieve today's earnings
$today = date("Y-m-d");
$todayEarningsQuery = "SELECT sum(t.amount) AS today_earnings
FROM tbl_transactions AS t 
JOIN tbl_orders AS o ON t.order_id = o.order_id 
JOIN tbl_order_details AS od ON od.id = o.fk_preid 
JOIN tbl_products AS p ON p.product_id = od.product_id 
WHERE p.fk_regid = '$userid' 
AND t.payment_status = 'completed' 
AND DATE(t.transaction_date) = '$today'";
$todayEarningsResult = $conn->query($todayEarningsQuery);
$todayEarningsRow = $todayEarningsResult->fetch_assoc();
$todayEarnings = $todayEarningsRow['today_earnings'];
$today_normal1=$todayEarningsRow['today_earnings'];
$today = date("Y-m-d");
$todayEarningsQuery = "SELECT sum(t.amount) AS today_earnings1 FROM tbl_transactions as t JOIN tbl_orders as o on t.order_id=o.order_id JOIN tbl_pre_orders as pre on pre.id=o.fk_preid JOIN tbl_products as p on p.product_id=pre.product_id WHERE p.fk_regid='$userid' and t.payment_status='completed' 
AND DATE(t.transaction_date) = '$today'";
$todayEarningsResult = $conn->query($todayEarningsQuery);
$todayEarningsRow = $todayEarningsResult->fetch_assoc();
$todayEarnings += $todayEarningsRow['today_earnings1'];
$today_prebook1=$todayEarningsRow['today_earnings1'];

$totalEarningsQuery = "SELECT sum(t.amount) as total_earnings FROM tbl_transactions as t JOIN tbl_orders as o on t.order_id=o.order_id JOIN tbl_order_details as od on od.id=o.fk_preid JOIN tbl_products as p on p.product_id=od.product_id WHERE p.fk_regid='$userid' and t.payment_status='completed'";
$totalEarningsResult = $conn->query($totalEarningsQuery);
$totalEarningsRow = $totalEarningsResult->fetch_assoc();
$totalEarnings = $totalEarningsRow['total_earnings'];
$total_normal2=$totalEarningsRow['total_earnings'];
$totalEarningsQuery = "SELECT sum(t.amount) as total_earnings1 FROM tbl_transactions as t JOIN tbl_orders as o on t.order_id=o.order_id JOIN tbl_pre_orders as pre on pre.id=o.fk_preid JOIN tbl_products as p on p.product_id=pre.product_id WHERE p.fk_regid='$userid' and t.payment_status='completed'";
$totalEarningsResult = $conn->query($totalEarningsQuery);
$totalEarningsRow = $totalEarningsResult->fetch_assoc();

$totalEarnings += $totalEarningsRow['total_earnings1'];
$total_prebook2=$totalEarningsRow['total_earnings1'];
?>

<div class="overview-section">
    <h2>Seller Overview</h2>
    <div style="display:flex;">
    <div class="overview-box">
        <h3>Total Products</h3>
        <div class="overview-info">
            <span>Total Products:</span> <?php echo $totalProducts; ?>
        </div>
    </div>
    <div class="overview-box">
        <h3>Today's Earnings</h3>
        <div class="overview-info">
            <span>Today's Earnings:</span> $<?php echo $todayEarnings; ?>
        </div>
    </div>
    <div class="overview-box">
        <h3>Total Earned Amount</h3>
        <div class="overview-info">
            <span>Total Earned Amount:</span> $<?php echo $totalEarnings; ?>
        </div>
    </div>
    </div>
    <div style="display:flex;">
    <div class="overview-box">
        <h3>Todays Total Normal Order Amount</h3>
        <div class="overview-info">
            <span>Todays:</span> <?php echo $today_normal1; ?>
        </div>
    </div>
    <div class="overview-box">
        <h3>Todays Total Pre-Book Order Amount</h3>
        <div class="overview-info">
            <span>Today's Earnings:</span> $<?php echo $today_prebook1; ?>
        </div>
    </div>
    <div class="overview-box">
        <h3>Total Normal Order Earnings</h3>
        <div class="overview-info">
            <span>Total Earned Amount:</span> $<?php echo $total_normal2; ?>
        </div>
    </div>
    <div class="overview-box">
        <h3>Total Pre-Book Order Earnings</h3>
        <div class="overview-info">
            <span>Total Earned Amount:</span> $<?php echo $total_prebook2; ?>
        </div>
    </div>
    </div>
</div>

    <div class="overlay" id="overlay"></div>
    <input type="hidden" id="email" value="<?php echo $_SESSION["username"] ?>">
    <?php


    // Your PHP logic goes here...
    
    if ($email_verified != 1 && $verification_attempts < 3) {
        ?>
        <div class="popup" id="verificationPopup">
            <h2>Email Not Verified</h2>
            <p>Your email address is not verified. Please verify your email to continue.</p>
            <div class="verification-buttons">
                <button id="send-code-btn">Resend Verification Code</button>
            </div>
            <div class="verification-input">
        <input id="otp" type="text" name="verification_code" placeholder="Enter Verification Code" required>

    </div>
                <input type="button" id="verify" value="Verify" class="vf">
                <p id="error-message" style="color: red; display: none;">Incorrect OTP. Please try again.</p> <!-- New element for error message -->
   
                <div id="sweetAlertContainer"></div>

                <p>Back to <a href="../login.php">Login Page</a></p> <!-- New element for back to login page -->

        </div>
        <script>
            document.getElementById("overlay").style.display = "block";
            document.getElementById("verificationPopup").style.display = "block";
        </script>
        <?php
    } else {
        ?>
        <script>
            document.getElementById("overlay").style.display = "none"; // Hide the overlay if email is verified
        </script>
        <?php
    }


    ?>
   
</div>

<script>
// Function to fetch notifications

function toggleSubmenu(submenuId) {
    var submenu = document.getElementById(submenuId);
    if (submenu.style.display === "block") {
        submenu.style.display = "none";
    } else {
        submenu.style.display = "block";
    }
}
var data;
    var email = document.getElementById('email').value;


document.addEventListener('DOMContentLoaded', function() {


var resendTimeout; // Variable to store the setTimeout function
var timeLeft = 60; 
// Your JavaScript code here
var verifyButton = document.getElementById('verify');
if (verifyButton) {
verifyButton.addEventListener('click', function () {
    var otp = document.getElementById('otp').value;
    if (otp == data['vcode']) {
        document.getElementById("verificationPopup").style.display = "none";
        document.getElementById("overlay").style.display = "none";
        updatestatus();
    }
    else
    {
        document.getElementById("error-message").style.display = "block";

    }
});
}



var sendCodeBtn = document.getElementById('send-code-btn');
if (sendCodeBtn) {
sendCodeBtn.addEventListener('click', function () {
    if (timeLeft === 60) { // Only allow sending code if it's the first click in the last 60 seconds
        sendCodeBtn.disabled = true; // Disable the button
        sendVerificationRequest(); // Send the request to resend OTP

        resendTimeout = setInterval(function () {
            timeLeft--; // Decrease remaining time
            if (timeLeft === 0) {
                clearInterval(resendTimeout); // Stop the countdown
                sendCodeBtn.disabled = false; // Enable the button after 60 seconds
                timeLeft = 60; // Reset remaining time
                sendCodeBtn.textContent = "Resend Verification Code"; // Reset button text
            } else {
                sendCodeBtn.textContent = "Resend Verification Code (" + timeLeft + "s)"; // Update button text with remaining time
            }
        }, 1000); // Update the timer every second
    }
});
}


function sendVerificationRequest() {
var xhr = new XMLHttpRequest();
xhr.open('POST', '../ttt.php');
xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
        data = JSON.parse(xhr.responseText);
    
        if (data.vcode) {
// Show SweetAlert for successful resend
Swal.fire({
icon: 'success',
title: 'Success!',
text: 'Email verification code sent successfully!',
timer: 2000, // Set the timer to automatically close the alert after 2 seconds
didOpen: () => {
    // Set a higher z-index for the SweetAlert
    document.querySelector('.swal2-container').style.zIndex = 999999;
}
});
} else {
// Show error message if resend failed
Swal.fire({
icon: 'error',
title: 'Error!',
text: 'Failed to resend verification code. Please try again.',
didOpen: () => {
    // Set a higher z-index for the SweetAlert
    document.querySelector('.swal2-container').style.zIndex = 999999;
}
});
}


    }
};
xhr.send("email=" + email);
}




});



function updatestatus() {

var xhr = new XMLHttpRequest();
xhr.open('POST', '../updatestatus.php');
xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");


xhr.send("id=" + <?php echo $userid ?>);

xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
        data1 = JSON.parse(xhr.responseText);
     
        if (data1.resp == "success") {
            Swal.fire({
                icon: 'success',
                title: 'Verification Successful!',
                text: 'Your email has been successfully verified.',
                confirmButtonText: 'OK'
            }).then((result) => {
                // Reload the page
                location.reload();
            });


        }

    }
}
}




function showVerificationPopup() {
document.getElementById("verificationPopup").style.display = "block";

var xhr = new XMLHttpRequest();
xhr.open('POST', '../ttt.php');
xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");


xhr.send("email=" + email);

xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
        data = JSON.parse(xhr.responseText);
       

    }
};


}

// Function to hide the verification popup
function hideVerificationPopup() {
document.getElementById("verificationPopup").style.display = "none";
}





if(<?php echo $email_verified ?> != 1) {
showVerificationPopup();
}
</script>

</body>
</html>
