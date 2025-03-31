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
.notification {
    width: 500px;
    height:fit-content;
}

.button-container {
    display: flex;
    gap: 10px; /* Adjust the gap between buttons as needed */
}

.reject-btn {
    background-color: red;
            color: #fff;
            /* White text color */
            padding: 10px 20px;
            /* Add padding */
            border: none;
            /* Remove border */
            border-radius: 3px;
            /* Add border radius */
            cursor: pointer;
            /* Add cursor pointer on hover */
            transition: background-color 0.3s;
}

.accept-btn
{
    background-color: #4CAF50;
            color: #fff;
            /* White text color */
            padding: 10px 20px;
            /* Add padding */
            border: none;
            /* Remove border */
            border-radius: 3px;
            /* Add border radius */
            cursor: pointer;
            /* Add cursor pointer on hover */
            transition: background-color 0.3s;
}
.oa{
    background-color: #4CAF50;
            color: #fff;
            /* White text color */
            margin-top: 12px;
            padding: 10px 20px;
            /* Add padding */
            border: none;
            /* Remove border */
            border-radius: 3px;
            /* Add border radius */
            cursor: pointer;
            /* Add cursor pointer on hover */
            transition: background-color 0.3s; 
}
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
}.notification {
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    border-radius: 5px;
    margin-bottom: 10px;
    padding: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
 #notifications{
    height: fit-content;
    overflow:auto;
    
 }
 #notifications::-webkit-scrollbar { /* For Chrome, Safari, and Opera */
    width: 8px;
}

#notifications::-webkit-scrollbar-thumb { /* For Chrome, Safari, and Opera */
    background-color: green;
    border-radius: 4px;
}

#notifications::-webkit-scrollbar-track { /* For Chrome, Safari, and Opera */
    background: #f1f1f1;
}
.notification p {
    margin: 0;
    font-size: 14px;
}

.notification.success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.notification.error {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}
#not{
    transform: translate(320px, 15px);
}
.notification-dot {
  position: absolute;
  top: 17%;
  left: 60%;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: red;
}

.bounce-animation {
  animation: bounce 0.5s infinite alternate;
}

@keyframes bounce {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(5px);
  }
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
            <div id="not"><img src="../img/notification.png" alt="" width="30px" height="30px">   <div class="notification-dot" id="notificationDot"></div></div>
         

            <label for="menu-btn" class="btn menu-btn"><i class="fas fa-bars"></i></label>
        </div>
    </nav>
<div class="sidenav">
    <a href="./overview.php">Overview</a>
    <a href="./notification.php" class="active">Notifications</a>
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
   

    <div class="overlay" id="overlay"></div>
    <input type="hidden" id="email" value="<?php echo $_SESSION["username"] ?>">
    <div id="notifications">
        <h3>Notifications:</h3>
        <ul id="notificationsList">
            <!-- Notifications will be dynamically added here -->
        </ul>
    </div>
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
updateMessageCount(0)
var messageCount = 0;
// Function to update message count
function updateMessageCount(count) {
  messageCount = count;
  
  // Add or remove bouncing animation based on message count
  var notificationDot = document.getElementById('notificationDot');
  if (messageCount >= 1) {
    notificationDot.style.display = 'block'; // Show the notification dot
    notificationDot.classList.add('bounce-animation');
  } else {
    notificationDot.style.display = 'none'; // Hide the notification dot
    notificationDot.classList.remove('bounce-animation');
  }
}

// Example: Updating message count (call this function when message count changes)
// Call with message count equal to 0




// Function to handle the Reject button click
function handleRejectButtonClick(notificationId) {
    alert('Rejected notification with ID: ' + notificationId);


    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'accept.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {

fetchNotifications();
location.reload();
            }}}
xhr.send('notification_id=' + notificationId +'&action=reject');
}
// Function to fetch notifications
// Function to fetch notifications
function fetchNotifications() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'fetch_notify.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Notifications fetched successfully
                console.log(xhr.responseText);
                var notifications = JSON.parse(xhr.responseText);
                
                // Group notifications by their creation date
                var groupedNotifications = {};
                notifications.forEach(function(notification) {
                    var date = new Date(notification.created_at).toDateString();
                    if (!groupedNotifications[date]) {
                        groupedNotifications[date] = [];
                    }
                    groupedNotifications[date].push(notification);
                });
                
                var notificationsList = document.getElementById('notificationsList');
                notificationsList.innerHTML = ''; // Clear previous notifications
                
                // Iterate over each group of notifications
                for (var date in groupedNotifications) {
                    if (groupedNotifications.hasOwnProperty(date)) {
                        // Create date heading
                        var dateHeading = document.createElement('h3');
                        dateHeading.textContent = date;
                        notificationsList.appendChild(dateHeading);
                        
                        // Iterate over notifications in this group
                        groupedNotifications[date].forEach(function(notification) {
                            var div = document.createElement('div');
                            div.classList.add('notification');
                            div.textContent = notification.message;
                            notificationsList.appendChild(div);
                            
                            // Check if the notification type is 'request' and status is 'unread'
                            if (notification.msg_type === 'request' && notification.status === 'unread' && notification.message != 'The order has been accepted by another seller.') {
                                // Create div to hold buttons and apply flexbox
                                var buttonContainer = document.createElement('div');
                                buttonContainer.classList.add('button-container');
                                updateMessageCount(1);
                                
                                // Create 'Accept' button
                                var acceptButton = document.createElement('button');
                                acceptButton.textContent = 'Accept';
                                acceptButton.classList.add('accept-btn');
                                acceptButton.dataset.notificationId = notification.notifaction_id; // Set notification ID as a data attribute

                                // Add click event listener to Accept button
                                acceptButton.addEventListener('click', function () {
                                    acceptOrder(notification.notifaction_id);
                                });

                                // Append 'Accept' button to the button container
                                buttonContainer.appendChild(acceptButton);

                                // Append button container to the notification div
                                div.appendChild(buttonContainer);
                            } else if (notification.msg_type === 'request' && notification.status === 'read') {
                                // If status is 'read', show 'Order Accepted' message
                                var acceptedMessage = document.createElement('span');
                                acceptedMessage.innerHTML = '<br><button class="oa">Order Accepted</button>';
                                div.appendChild(acceptedMessage);

                                // Create cancel button only for accepted orders created within the last 15 minutes
                                var createdTime = new Date(notification.created_at).getTime();
                                var currentTime = new Date().getTime();
                                var elapsedTime = currentTime - createdTime;
                                if (elapsedTime <= 15 * 60 * 1000) { // 15 minutes in milliseconds
                                    var cancelButton = document.createElement('button');
                                    cancelButton.textContent = 'Cancel';
                                    cancelButton.classList.add('cancel-btn');
                                    cancelButton.dataset.notificationId = notification.notifaction_id; // Set notification ID as a data attribute
                                    div.appendChild(cancelButton);

                                    // Add event listener to the cancel button
                                    cancelButton.addEventListener('click', function () {
                                        cancelOrder(notification.notifaction_id);
                                    });
                                }
                            }
                        });
                    }
                }
            } else {
                // Error fetching notifications
                console.error('Failed to fetch notifications. Status code: ' + xhr.status);
            }
            // Schedule next fetch after a delay
            setTimeout(fetchNotifications, 5000); // Fetch every 5 seconds
        }
    };
    xhr.send();
}

// Start fetching notifications
fetchNotifications();


// Function to cancel the order
function cancelOrder(notificationId) {
    // Send an AJAX request to cancel the order using the notification ID
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'accept.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Order canceled successfully
                console.log(xhr.responseText)
                console.log('Order canceled successfully.');
            } else {
                // Error canceling order
                console.error('Failed to cancel order. Status code: ' + xhr.status);
            }
        }
    };
    // Send notification ID as POST data
    xhr.send('notification_id=' + notificationId +'&action=cancel');
}


// Add event listener to handle accept button click
// Add event listener to handle accept button click
document.addEventListener('click', function(event) {
    if (event.target && event.target.classList.contains('accept-btn')) {
        // If the clicked element is the accept button
        var notificationId = event.target.dataset.notificationId; // Get the notification ID

        // Send AJAX request to handle the accept action
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'accept.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Handle response from the server if needed
                    fetchNotifications();
                    updateMessageCount(0)
                    console.log(xhr.responseText); // Log the response for debugging
                } else {
                    // Error handling if the request fails
                    console.error('Failed to send accept request. Status code: ' + xhr.status);
                }
            }
        };
        xhr.send('notification_id=' + notificationId +'&action=accept'); // Send notification ID as POST data
    }
});



// JavaScript function to toggle submenu visibility
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
