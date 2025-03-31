<?php

session_start();
require('../Connect_database.php');
$email = $_SESSION['username'];
$userid = $_SESSION['userid'];

if (empty($_SESSION['username']) && empty($_SESSION['userid'])) {
    header('Location:../login.php');
    exit; // Ensure that script execution stops after redirecting
}


$sql = "SELECT filepath FROM tbl_images WHERE fk_regid = $userid"; // Modify this query as per your table structure
$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    // Check if there is at least one row returned
    if ($result->num_rows > 0) {
        // Fetch the row
        $row = $result->fetch_assoc();
        
        $_SESSION['photo_path']=$row['filepath'];
    } else {
        echo "No image found with the specified ID.";
    }
} else {
    // Error in the query
    echo "Error: " . $conn->error;
}

$email_verified = 0;
$sql = "SELECT `email_verify` FROM `tbl_verification` WHERE fk_regid='$userid'";
if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email_verified = $row['email_verify']; // Email is considered verified if email_verify is not 1
    }
}

// Check if email exists in tbl_googleusers table
$email_verified_from_google = false;
$sql = "SELECT * FROM `tbl_googleusers` WHERE email='$email'";
if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        $email_verified_from_google = true;
    }
}
$password = false;

// Query to fetch the password field from tbl_login
$sql = "SELECT password FROM tbl_login WHERE username = '$email'";
$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    // Check if there are any rows returned
    if ($result->num_rows > 0) {
        // Fetch the row
        $row = $result->fetch_assoc();
        // Check if the password field is not empty
        if (!empty($row['password'])) {
            // Password is not empty, set $password to true
            $password = true;
        }
    } else {
        // No rows returned, handle the case accordingly
        echo "No records found!";
    }
} else {
    // Error in the query, handle the error accordingly
    echo "Error: " . $conn->error;
}
if (isset($_POST['Submit1'])) {
    function calculateAge($dob) {
        $dobObject = new DateTime($dob);
        $currentDate = new DateTime();
        $diff = $currentDate->diff($dobObject);
        return $diff->y;
    }

$dob = $_POST['dob'];
$gender = $_POST['gender'];
$address = $_POST['address'];
$city = $_POST['district1'];
$state = $_POST['places'];
$postcode = $_POST['postcode'];
$country = $_POST['country'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$age = calculateAge($dob);
$sql = "UPDATE tbl_registration 
SET dob='$dob', age='$age', gender='$gender', street_address='$address', city='$city', state='$state', 
    postal='$postcode', country='$country', phone='$phone' 
WHERE regid='$userid'";

    // Execute the SQL statement
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
        $sql = "UPDATE tbl_login SET password='$password' WHERE username='$email'";
        if ($conn->query($sql) === TRUE) {
            echo "<script>
                swal({
                    title: 'Success!',
                    text: 'Password updated successfully',
                    icon: 'success',
                    button: 'OK'
                }).then(() => {
                    window.location.href = 'index.php'; // Redirect to desired page
                });
              </script>";
        } else {
            echo "Error inserting password: " . $conn->error;
        }
    } else {
        echo "Error updating record: " . $conn->error;
    }

}
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nupdate'])) {
    // Retrieve new name and phone number from the form
    $newName = $_POST["newName"];
    $newPhone = $_POST["newPhone"];

    // Validate the new name and phone number (you can add more validation as needed)
    if (empty($newName)) {
        // Handle empty name
        echo "Please enter your new name.";
        exit; // Stop further execution
    }

    if (empty($newPhone)) {
        // Handle empty phone number
        echo "Please enter your new phone number.";
        exit; // Stop further execution
    }

    // Now you can update the details in your database
    // Connect to your database


    // Assuming $userid contains the user's ID
    // Update the user's details in the database
    $sql = "UPDATE tbl_registration SET full_name = '$newName', phone = '$newPhone' WHERE regid = $userid";

    if ($conn->query($sql) === TRUE) {
      
        echo '<script>';
        echo 'document.addEventListener("DOMContentLoaded", function() {';
        echo '    Swal.fire({';
        echo '        title: "Details Updated Successful!",';
        echo '        text: "Updated Successful",';
        echo '        icon: "success",';
        echo '        showConfirmButton: false';
        echo '    });';

        echo '});';
        echo '</script>';
    } else {
        echo "Error updating details: " . $conn->error;
    }

    // Close the database connection
 
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
.a-super1 {
    font-weight: bold; /* Make the labels bold */
    margin-right: 10px; /* Add some right margin */
   
    margin-left: 35rem;
}

.popup-overlay1 {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: none;
    justify-content: center;
    align-items: center;
}

.popup1 {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    max-width: 400px;
    width: 100%;
}

.popup1h2 {
    margin-top: 0;
}

.label {
    display: block;
    margin-bottom: 5px;
}

.input {
    width: 100%;
    padding: 8px;
    border-radius: 3px;
    border: 1px solid #ccc;
}

.button-group {
    display: flex;
    justify-content: space-between;
}

.button-group button {
    padding: 10px 20px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

#saveUpdateButton {
    background-color: #007bff;
    color: #fff;
}

#saveUpdateButton:hover {
    background-color: #0056b3;
}

#cancelUpdateButton {
    background-color: #ccc;
    color: #333;
}

#cancelUpdateButton:hover {
    background-color: #bbb;
}
.receive-details {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #ffffff;
        padding: 20px;
        border: 2px solid #000000;
        border-radius: 5px;
        z-index: 9999;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        width: 30rem;
    }

    /* Popup header */
    .receive-details h2 {
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 24px;
    }

    /* Form input fields */
    .receive-details input[type="text"],
    .receive-details input[type="password"] {
        width: calc(100% - 40px);
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #cccccc;
        border-radius: 5px;
        font-size: 16px;
    }

    /* Submit button */
    .receive-details input[type="submit"] {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #ffffff;
        font-size: 16px;
        cursor: pointer;
    }

    /* Submit button hover effect */
    .receive-details input[type="submit"]:hover {
        background-color: #0056b3;
    }
    textarea[name="address"] {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 100%;
    height: 100px; /* Adjust the height as needed */
    resize: vertical; /* Allow vertical resizing */
}

input[type="date"] {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 100%;
}

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
    
    if ($email_verified!=1 && !$email_verified_from_google) {
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
            document.addEventListener('DOMContentLoaded', function() {
            document.getElementById("overlay").style.display = "block";
            document.getElementById("verificationPopup").style.display = "block";
            })
        </script>
        <?php
    } 
    elseif(empty($password)) 
    {
?>

<div class="receive-details" id="receiveDetailsPopup">
    <h2>Provide Your Details</h2>
    <form action="#" method="post" id="receiveDetailsForm">
    <textarea name="address" placeholder="Address" required></textarea><br>
<input type="date" name="dob" placeholder="Date of Birth" required><br>
<input type="radio" name="gender" value="male" placeholder="Gender" required>Male<input type="radio" name="gender" value="female" placeholder="Gender" required>Female <br>
<select id="districtSelect1"  name="district1" onchange="updatePlaces2('districtSelect1', 'placesSelect1')" class="In1">
                                <option value="">Select District</option>
                            </select>

                            <label for="state">Place</label>
                            <div><input type="text" name="places" id="state" readonly></div>

<select id="placesSelect1" name="alt-state" class="In1" onchange="updatePlaceInput()">
    <option value="">Select Place</option>
</select>
            
<input type="text" name="postcode" placeholder="Postcode" required><br>
<input type="text" name="country" placeholder="Country" required><br>
<input type="text" name="phone" placeholder="Phone" required><br>
<input type="password" name="password" placeholder="Password" required><br>
<input type="password" name="confirm_password" placeholder="Confirm Password" required><br>

        <input type="submit" value="Submit1" name="Submit1">
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
           document.getElementById("overlay").style.display = "block";
        document.getElementById("receiveDetailsPopup").style.display = "block";

    })
        </script>
<?php
        echo "<script>window.location.href = 'index.php';</script>";

    }
    
    else {

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

        <div class="p-center">
            <form id="uploadForm" action="#" method="post" enctype="multipart/form-data">
                <input type="file" name="image" id="image" accept="image/*" style="display: none;">
                <img src="<?php echo $filepath ?>" id="imagePreview">
                <br>
                <input type="submit" value="Upload" name="upload" id="upload">
            </form>



        </div>
        <div id="d6">

        <div class="info-section">
    <div class="input-group">
        <label for="fullname" class="a-super">Name:</label>
        <input type="text" id="fullname" name="fullname" value="<?php echo $full_name; ?>">
        
    </div>
</div>

<div class="info-section">
    <div class="input-group">
        <label for="email" class="a-super">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" readonly>
    </div>
</div>

<div class="info-section">
    <div class="input-group">
        <label for="phone" class="a-super">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>">
    </div>
</div>

<div class="info-section">
<div class="input-group">
<button class="a-super1" id="updates">Update Details</button>
    </div>
  
</div>



        </div>
<!-- Add this HTML code inside the <div class="content"> element -->



    </div>

    </div>
    <form action="#" method="post">
    <div class="popup-overlay1" id="updatePopupOverlay1" >
    <div class="popup1" id="updatePopup1">
        <h2>Update Details</h2>
        <div class="input-group">
            <label for="newName">New Name:</label>
            <input type="text" id="newName" name="newName" placeholder="Enter your new name" value="<?php echo $full_name; ?>">
        </div>
        <div class="input-group">
            <label for="newPhone">New Phone Number:</label>
            <input type="text" id="newPhone" name="newPhone" placeholder="Enter your new phone number"  value="<?php echo $phone; ?>">
        </div>
        <div class="button-group">
            <button id="saveUpdateButton" type="submit" name="nupdate">Save</button>
            <button id="cancelUpdateButton">Cancel</button>
        </div>
    </div>
</div>

</form>
</body>
<script>





function updateDistrictsAndPlaces2() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '../dis.json', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    populateSelect2('districtSelect1', 'placesSelect1', data); // Corrected IDs
                } else {
                    console.error('Error fetching data:', xhr.status);
                }
            }
        };
        xhr.send();
    }

    function populateSelect2(districtSelectId, placesSelectId, data) {
        const districtSelect = document.getElementById(districtSelectId);
        const placesSelect = document.getElementById(placesSelectId);

        // Clear previous options
        placesSelect.innerHTML = '<option value="">Select Place</option>';

        // Populating the districts
        const districtNames = new Set(); // Using Set to avoid repetition
        data.sort((a, b) => a.District.localeCompare(b.District));
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.District;
            option.textContent = item.District + ',Kerala';
            if (!districtNames.has(item.District)) { // Check if district name already exists
                districtSelect.appendChild(option);
                districtNames.add(item.District); // Add district name to Set
            }
        });
    }

    function updatePlaces2(districtSelectId, placesSelectId) {
        const districtSelect = document.getElementById(districtSelectId);
        const placesSelect = document.getElementById(placesSelectId);
        const selectedDistrict = districtSelect.value;

        // Clear previous options
        placesSelect.innerHTML = '<option value="">Select Place</option>';

        const xhr = new XMLHttpRequest();
        xhr.open('GET', '../dis.json', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    const districtData = data.find(item => item.District === selectedDistrict);
                    if (districtData) {
                        const places = districtData.Places;
                        const placeNames = new Set(); // Using Set to avoid repetition
                        places.forEach(place => {
                            if (!placeNames.has(place)) { // Check if place name already exists
                                const placeOption = document.createElement('option');
                                placeOption.value = place;
                                placeOption.textContent = place;
                                placesSelect.appendChild(placeOption);
                                placeNames.add(place); // Add place name to Set
                            }
                        });
                    } else {
                        console.log('No places found for the selected district.');
                    }
                } else {
                    console.error('Error fetching data:', xhr.status);
                }
            }
        };
        xhr.send();
    }

    // Call function to populate districts and places initially
    updateDistrictsAndPlaces2();






document.addEventListener('DOMContentLoaded', function() {
    var updateButton = document.getElementById('updates');
    var popupOverlay = document.getElementById('updatePopupOverlay1');
    var popup = document.getElementById('updatePopup1');
    var newNameInput = document.getElementById('newName');
    var newPhoneInput = document.getElementById('newPhone');

    if (updateButton && popupOverlay && popup && newNameInput && newPhoneInput) {
        updateButton.addEventListener('click', function() {
            popupOverlay.style.display = 'flex';
        });

        document.getElementById('cancelUpdateButton').addEventListener('click', function() {
            popupOverlay.style.display = 'none';
        });

        document.getElementById('saveUpdateButton').addEventListener('click', function() {
            var newName = newNameInput.value.trim();
            var newPhone = newPhoneInput.value.trim();

            // Validate input fields
            if (newName === '') {
                alert('Please enter your new name.');
                newNameInput.focus();
                return;
            }

            if (newPhone === '') {
                alert('Please enter your new phone number.');
                newPhoneInput.focus();
                return;
            }

            // Here you can perform actions like sending the updated data to the server
            console.log('New Name:', newName);
            console.log('New Phone:', newPhone);
            
            popupOverlay.style.display = 'none';
        });
    } else {
        console.error('Error: One or more elements not found.');
    }
});


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





function updatePlaceInput() {
        var selectedPlace = document.getElementById("placesSelect1").value;
        document.getElementById("state").value = selectedPlace;
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