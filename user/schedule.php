<?php

session_start();
require('../Connect_database.php');
$email = $_SESSION['username'];
$userid = $_SESSION['userid'];
$email_verified;
if (empty($_SESSION['username']) && empty($_SESSION['userid'])) {
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
$regid = "";
$full_name = "";
$dob = "";
$gender = "";
$street_address = "";

$city = "";
$state = "";
$postal = "";
$country = "";
$phone = "";
$age = "";
$filepath = "";
if ($email == "google" && isset($_SESSION["userid"])) {
    $sql = "SELECT * FROM tbl_googleusers where oauth_uid='$userid'";
    if ($result = $conn->query($sql)) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $full_name = $row['first_name'] . " " . $row['last_name'];
            $email = $row['email'];
            $filepath = $row['picture'];
            $created = $row['created'];
            $modified = $row['modified'];
        }
    }
} else {
    $sql = "SELECT * FROM tbl_login l,tbl_registration r,tbl_images i where l.username='$email' and r.regid='$userid' and r.fk_loginid=l.login_id and i.fk_regid=r.regid";
    if ($result = $conn->query($sql)) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $regid = $row['regid'];
            $full_name = $row['full_name'];
            $dob = $row['dob'];
            $gender = $row['gender'];
            $street_address = $row['street_address'];
            $city = $row['city'];
            $state = $row['state'];
            $postal = $row['postal'];
            $country = $row['country'];
            $phone = $row['phone'];
            $age = $row['age'];
            $filepath = $row['filepath'];
            $_SESSION['photo_path'] = $row['filepath'];
        }
    }
}



if (isset($_POST['upload'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (adjust the limit as needed)
    if ($_FILES["image"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    $allowedFormats = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedFormats)) {
        echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Insert image information into the database
            $filename = basename($_FILES["image"]["name"]);
            $filepath = $target_file;





            $sql = "UPDATE tbl_images SET filename='$filename', filepath='$filepath' WHERE fk_regid='$userid'";
            if ($conn->query($sql) === true) {
                echo "<script>window.location.replace('user.php');</script>";
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
} else



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style1.css">
    <link rel="stylesheet" href="../style.css">
    <script src="../sweetalert.js"></script>
    <script src="../sweetalert.min.js"></script>
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
   
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1;
            top: 55px;
            /* Adjust the position as needed */
            border-radius: 5px;
            padding: 10px 0;
            opacity: 0;
            /* Start with 0 opacity */
            transition: opacity 0.5s ease;
            line-height: 2;
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
}
/* CSS for the container div */
.container {
    top:2rem;
    max-width: 1200px; /* Set the maximum width of the container */
    margin: 5px auto; /* Center the container horizontally */
    padding: 20px; /* Add some padding around the content */
    background-color: #f9f9f9; /* Set a background color */
    border: 1px solid #ccc; /* Add a border */
    border-radius: 5px; /* Add border radius for rounded corners */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add a box shadow for depth */
}

/* Optional: Add more styles as needed */
/* CSS for the upload button */
#upload {
    margin-top: 10px; /* Add some top margin */
    padding: 10px 20px; /* Add padding */
    border: none; /* Remove the default border */
    border-radius: 5px; /* Add border radius for rounded corners */
    background-color: #007bff; /* Set the background color */
    color: #fff; /* Set the text color */
    cursor: pointer; /* Add cursor pointer on hover */
    margin-left: 1rem;
}

/* Style for the upload button on hover */
#upload[type="submit"]:hover {
    background-color: #0056b3; /* Change background color on hover */
}

.p-center{
    text-align: center;
}/* CSS for labels and their associated content */
.a-super {
    font-weight: bold; /* Make the labels bold */
    margin-bottom: 5px; /* Add some bottom margin */
}

/* Optional: Add more styles as needed */
/* CSS for info sections */
.info-section {
    margin-bottom: 20px; /* Add some bottom margin between sections */
}

/* CSS for input group */
.input-group {
    display: flex; /* Use flexbox for layout */
    align-items: center; /* Center items vertically */
}

/* CSS for labels */
.a-super {
    font-weight: bold; /* Make the labels bold */
    margin-right: 10px; /* Add some right margin */
    width: 100%; /* Set a fixed width for the labels */
    
}

/* Optional: Adjust input box styles as needed */
#email,#phone,#fullname{
    padding: 3px; /* Add padding */
    border: 1px solid #ccc; /* Add a border */
    border-radius: 5px; /* Add border radius */
    margin-right: 40%;
    width: 200px;
}
#cart-count {
      position: absolute;
      top: -12px;
      right: 40px;
      color: white;
      font-size: 12px;

    }

    #cart-count sup {
      background-color: red;
      color: white;
      border-radius: 50%;
      padding: 3px 6px;
    }
/* Optional: Add more styles as needed */

    </style>
</head>

<body>
    <nav>
        <div class="wrapper">
            <div class="logo"><a href="#">Homely</a></div>
            <input type="radio" name="slider" id="menu-btn">
            <input type="radio" name="slider" id="close-btn">
            <ul class="nav-links">
                <label for="close-btn" class="btn close-btn"><i class="fas fa-times"></i></label>
                <li><a href="./index.php">Home</a></li>
                <li><a href="about.php">About</a></li>

                <li>
                    <a href="food-items.php" class="desktop-item">Food-Items</a>
                    <input type="checkbox" id="showDrop">
                    <label for="showDrop" class="mobile-item">Dropdown Menu</label>
                    <ul class="drop-menu">
                    <li><a href="./breakfast.php">Breakfast Items</a></li>
                        <li><a href="./HomeHarvest.php">HomeHarvest Lunch</a></li>
                        <li><a href="./Evening_Eatables.php">Evening Eatables</a></li>
                        <li><a href="pre_book.php">Pre-Book</a></li>
                    </ul>
                </li>
                <li><a href="deliveryperson.php">For Delivery Partners</a></li>


                <!-- Inside the <nav> element -->
                <li class="user-dropdown">
                    <div class="user-photo-container">
                    <a href="user.php">
                            <img src="<?php echo $_SESSION['photo_path']; ?>" alt="" id="cart">

                        </a>



                    </div>
                    <div class="custom-dropdown-content">
                        <!-- Add your dropdown menu items here -->
                        <a href="user.php">Profile</a>
                        <a href="order_histroy.php">Orders</a>
                        <a href="schedule.php">schedule</a>
                        <a href="logout.php">Logout</a>

                    </div>
                </li>
                <li><a href="cart.php"><img src="../img/cart.png" alt="" id="userimg">
            <?php
            $sql = "SELECT COUNT(*) AS cart_count FROM tbl_cart where  fk_regid='$userid'";
            if ($result = $conn->query($sql)) {
              $row = $result->fetch_assoc();
            ?>
              <span id="cart-count"><sup id="count">
                  <?php echo $row['cart_count']; ?>
                </sup></span>

            <?php

            } else {
            ?>
              <span id="cart-count"><sup id="count">
                  <?php echo 0 ?>
                </sup></span>

            <?php
            }
            ?>
          </a></li>

            </ul>
            <label for="menu-btn" class="btn menu-btn"><i class="fas fa-bars"></i></label>
        </div>
    </nav>
   
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
  <div class="container">
    <!-- Page content -->
    <div class="content">
        <!-- Form for scheduling delivery -->
        <form id="deliveryForm">
            <label for="deliveryDate">Select Delivery Date:</label>
            <input type="date" id="deliveryDate" name="deliveryDate">

            <label for="orderDetails">Order Details:</label>
            <textarea id="orderDetails" name="orderDetails" rows="4" cols="50"></textarea>

            <button type="submit">Schedule Delivery</button>
        </form>
    </div>
</div>

<script>
    // JavaScript code to handle form submission
    document.getElementById("deliveryForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent default form submission
        // You can add code here to handle form data submission
        // For example, you might use AJAX to send data to a backend server for processing
        alert("Delivery Scheduled!"); // Placeholder alert, replace with actual logic
    });
</script>


</body>
<script>
    var data;
    var email = document.getElementById('email').value;
    // Trigger file input when clicking on the default image
    document.getElementById('imagePreview').addEventListener('click', function () {
        document.getElementById('image').click();
    });

    // Display selected image filename on file input change
    document.getElementById('image').addEventListener('change', function () {
        var input = this;
        var reader = new FileReader();

        reader.onload = function (e) {
            document.getElementById('imagePreview').src = e.target.result;
        };

        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        } else {
            document.getElementById('imagePreview').src = "default-image.jpg";
        }
    });



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

</html>


<?php




if (isset($_POST['upload'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (adjust the limit as needed)
    if ($_FILES["image"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    $allowedFormats = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedFormats)) {
        echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Insert image information into the database
            $filename = basename($_FILES["image"]["name"]);
            $filepath = $target_file;





            $sql = "UPDATE tbl_images SET filename='$filename', filepath='$filepath' WHERE fk_regid='$userid'";
            if ($conn->query($sql) === true) {
                echo "<script>window.location.replace('user.php');</script>";
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
} else

?>