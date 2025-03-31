<?php
// item_details.php
session_start();
require('../Connect_database.php');
$userid = $_SESSION['userid'];
// Include necessary files and database connection

$product_id;
// Check if product_id is provided in the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
}
// Fetch details of the specific item from the database using $product_id



if (isset($_POST['edit']) && $_POST['addressId'] != 'OD') {
    // Retrieve form data
    $street = $_POST['street'];
    $city = $_POST['district'];
    $state = $_POST['places'];
    $postal = $_POST['postal'];
    $country = $_POST['country'];
    $addressId = $_POST['addressId']; // Assuming you have a hidden input field with the address ID

    // SQL query to update the alternative address details in the database
    $sql = "UPDATE tbl_alternative_addresses SET street_address='$street', city='$city', state='$state', postal='$postal', country='$country' WHERE id='$addressId'";

    if ($conn->query($sql) === TRUE) {
        // Update successful
        echo "<script>
            window.onload = function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Alternative address updated successfully',
                    timer: 2000, // Display for 2 seconds
                    showConfirmButton: false
                });
            };
         </script>";
    } else {
        // Error updating alternative address
        echo "Error updating alternative address: " . $conn->error;
    }
} elseif (isset($_POST['edit']) && $_POST['addressId'] == 'OD') {
    $street = $_POST['street'];
    $city = $_POST['district'];
    $state = $_POST['places'];
    $postal = $_POST['postal'];
    $country = $_POST['country'];
    // Assuming you have a hidden input field with the address ID

    // SQL query to update the address details in the database
    $sql = "UPDATE tbl_registration SET street_address='$street', city='$city', state='$state', postal='$postal', country='$country' WHERE regid='$userid'";

    if ($conn->query($sql) === TRUE) {
        // Update successful
        echo "<script>
                    window.onload = function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Address updated successfully',
                            timer: 2000, // Display for 2 seconds
                            showConfirmButton: false
                        });
                    };
                 </script>";
    } else {
        // Error updating address
        echo "Error updating address: " . $conn->error;
    }
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    // Check if all required fields are set
    if (!empty($_POST['alt-street']) && !empty($_POST['alt-city']) && !empty($_POST['alt-state']) && !empty($_POST['alt-postal']) && !empty($_POST['alt-country'])) {
        // Sanitize input data
        $altStreet = $_POST['alt-street'];
        $altCity = $_POST['alt-city'];
        $altState = $_POST['alt-state'];
        $altPostal = $_POST['alt-postal'];
        $altCountry = $_POST['alt-country'];

        // Check if the address already exists for the user
        $sql_check = "SELECT COUNT(*) AS count FROM tbl_alternative_addresses WHERE user_id = $userid AND street_address = '$altStreet' AND city = '$altCity' AND state = '$altState' AND postal = '$altPostal' AND country = '$altCountry'";
        $result_check = $conn->query($sql_check);
        $row_check = $result_check->fetch_assoc();
        $count = $row_check['count'];

        if ($count == 0) {
            // Insert the address into the database
            $sql_insert = "INSERT INTO tbl_alternative_addresses (user_id, street_address, city, state, postal, country) VALUES ($userid, '$altStreet', '$altCity', '$altState', '$altPostal', '$altCountry')";
            if ($conn->query($sql_insert) === TRUE) {
                echo "<script>
                Swal.fire(
                  'Success!',
                  'New alternative address added successfully',
                  'success'
                ).then(function() {
                    location.reload();
                });
            </script>";
            } else {
                echo "Error: " . $sql_insert . "<br>" . $conn->error;
            }
        } else {

            echo "<script>
            window.onload = function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'This alternative address already exists for the user.',
                    timer: 2000, // Display for 2 seconds
                    showConfirmButton: false
                });
            };
         </script>";
        }
    } else {
        echo "Please fill in all the required fields.";
    }
}


?>


























<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row['product_name']; ?> Details</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        @font-face {
            font-family: 'Raleway';
            src: url('https://fonts.googleapis.com/css2?family=Raleway&display=swap');
            /* Optionally, you can specify font weights and styles */
            /* font-weight: normal; */
            /* font-style: normal; */
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


        .item-details-container {
            display: flex;
            align-items: center;
            margin: 29px;
            /* Adjust margin as needed */
            font-family: 'Roboto', sans-serif;


        }

        .item-photo {
            position: relative;
            border: 1px solid #ddd;
            /* Add border to the photo container */
            padding: 10px;
            /* Add padding for spacing */
            width: 28rem;
            height: 30rem;
            display: flex;
            justify-content: center;

        }

        .item-photo img {

            max-width: 100%;
            /* Ensure the image does not exceed the container */
            max-height: 100%;
        }

        .button-container {
            position: absolute;
            bottom: 22px;
            left: 50%;
            margin-left: -50%;
            display: flex;
        }

        .buy-now-button,
        .add-to-cart-btn {
            margin: 0 10px;
            /* Add spacing between buttons */
            padding: 10px 20px;
            /* Add padding to buttons */
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 13.4rem;
            height: 3rem;
            font-size: 20px;

        }

        .goto-to-cart-btn {
            margin: 0 10px;
            /* Add spacing between buttons */
            padding: 10px 20px;
            /* Add padding to buttons */
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 13.4rem;
            height: 3rem;
            font-size: 20px;
        }

        /* Additional styling for buttons as needed */


        .item-details {
            margin-left: 2rem;
            /* Add margin for spacing between photo and details */
            margin-top: -4rem;
            /* Add margin to move the details towards the top */

            transform: translateY(-59px);

        }

        .item-details h2 {
            font-size: 35px;
            /* Adjust font size as needed */
            margin-top: 0;
            /* Remove default margin for heading */
        }

        .item-details p {
            margin: 10px 0;
            /* Adjust spacing between paragraphs */
        }

        .itim img {
            width: 28rem;
            height: 25rem;
        }

        /* Add additional styling for buttons, seller name, rating, etc. */
        .add-to-cart-btn:hover,
        .buy-now-button:hover {
            background-color: #45a049;
            /* Change background color on hover */
            transform: scale(1.05);
            /* Scale the button on hover */
            transition: background-color 0.3s, transform 0.3s;
            /* Add smooth transition */
            opacity: .9;
        }

        .goto-to-cart-btn:hover {
            background-color: #45a049;
            /* Change background color on hover */
            transform: scale(1.05);
            /* Scale the button on hover */
            transition: background-color 0.3s, transform 0.3s;
            /* Add smooth transition */
            opacity: .9;
        }

        .rating {
            display: inline-block;
            margin-top: 11px;
            /* Adjust margin as needed */
            margin-left: .3rem;

        }

        .rating-value {
            display: inline-block;
            padding: 3px 6px;
            /* Adjust padding as needed */
            border-radius: 3px;
            /* Adjust border radius */
            background-color: #4CAF50;
            /* Background color */
            color: white;
            /* Text color */
            font-size: 12px;
            /* Adjust font size */

            width: 2rem;
            text-align: center;
        }

        .inline {
            display: flex;
            margin-top: 9rem;
        }

        .star {
            font-size: 13px;
        }

        .price {
            font-size: 29px;
            font-weight: bold;
        }

        .delivery-pincode-container {
            margin-top: 20px;
            display: flex;
            align-items: center;
        }

        .delivery-pincode-container label {
            margin-right: 10px;
        }

        .delivery-pincode-container .map-symbol {
            margin-right: 5px;
            font-size: 20px;
            /* Adjust size as needed */
        }

        .delivery-pincode-container input[type="text"],
        #postOfficeSelect {
            width: 150px;
            height: 30px;
            /* Set height to 30px */
            padding: 5px;
            border: 0px;
            border-bottom: 2px solid lightblue;
            /* Light blue bottom border */
            border-radius: 3px;

        }

        #postOfficeSelect {
            margin-left: 1.5rem;
        }

        .delivery-pincode-container button {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .delivery-pincode-container input[type="text"]:focus {
            outline: none;
            /* Remove outline on focus */
        }

        .seller-details {
            display: flex;
            align-items: center;
            margin-top: 20px;
        }

        .seller-details p {
            margin-right: 10px;
        }

        .seller-details .rating {
            display: inline-block;
            margin-top: -1px;
        }

        .seller-details .rating-value {
            padding: 3px 6px;
            border-radius: 3px;
            background-color: blue;
            color: white;
            font-size: 12px;
            width: 2rem;
            text-align: center;

        }

        .padd {
            justify-content: flex-start;
        }


        .similar-product img {
            width: 150px;
            height: 150px;
        }

        .similar-product h4 {
            font-size: 18px;
            margin-top: 10px;
        }

        .similar-product .price {
            font-size: 16px;
            font-weight: bold;
            margin-top: -28px;
        }

        .similar-products-container {
            border: 1px solid #ddd;
            /* Add border */
            padding: 10px;
            /* Add padding */
            border-radius: 10px;
        }

        .similar-product {
            width: calc(20% - 20px);
            /* Adjust width as needed */
            margin-bottom: 10px;
            border: 1px solid #ccc;
            /* Add border around each product */
            padding: 10px;
            /* Add padding */
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 180px;
            border-radius: 10px;
        }

        .product-image {
            margin-bottom: 10px;
        }

        .product-image img {
            max-width: 100%;
            height: auto;
        }

        .product-details {
            text-align: center;
        }

        .price {
            font-weight: bold;
            margin-top: 28px;
        }

        .rate {
            /* Add your rating CSS here */
            display: inline-block;
            margin-left: 8rem;

            transform: translate(4px, -47px);

        }

        .rating-values {
            display: inline-block;
            padding: 3px 6px;
            /* Adjust padding as needed */
            border-radius: 3px;
            /* Adjust border radius */
            background-color: #4CAF50;
            /* Background color */
            color: white;
            /* Text color */
            font-size: 12px;
            /* Adjust font size */

            width: 2rem;
            text-align: center;
        }

        .Check {

            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .Check {
            background-color: #45a049;
        }








        .section {
            background-color: #fff;
            /* Set background color to white */



        }

        .section h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }


        .edit-address,
        .cancel-edit,
        .cancel-alt {
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
            /* Add transition effect for background color */
        }

        #add-alternative-address {
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
            /* Add transition effect for background color */
        }

        #add-alternative-address:hover,
        .edit-address:hover,
        .cancel-edit:hover,
        .cancel-alt:hover {
            background-color: #45a049;
        }

        @media screen and (max-width: 600px) {
            .container {
                padding: 10px;
            }

            .section {
                padding: 15px;
            }
        }


        .blur-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* Semi-transparent black */
            z-index: 2;
            /* Higher z-index to make it appear in front */
            display: none;
        }

        .address-form {
            position: fixed;
            top: 70%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 21px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            z-index: 3;
            /* Highest z-index to make it appear even more in front */
            display: none;
            width: 33%;
        }

        .address-form label {
            display: block;
            margin-bottom: 10px;
        }

        .address-form input {
            width: calc(100% - 20px);
            /* Adjust input width */
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        .address-form button[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .address-form button[type="submit"]:hover {
            background-color: #45a049;
        }

        p {
            text-transform: capitalize;
            padding-left: 9px;
        }

        .alternative-address {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            /* Add margin to create space between addresses */
        }

        .alternative-address input[type="radio"] {
            margin-right: 10px;
            /* Add margin to create space between radio buttons and addresses */
        }

        .In1 {
            padding: 6px;
            margin-bottom: 6px;
        }
        .green-tick::before {
    content: '\2714'; /* Unicode character for checkmark */
    color: green;
    margin-right: 10px; 
    margin: -1rem;
    transform: translate(12px,50px);
    /* Adjust as needed */
}
.red-cross::before {
    content: '\2718'; /* Unicode character for cross mark */
    color: red;
    margin-right: 5px; /* Adjust as needed */
}
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


    </style>
    <!-- Link to your CSS file -->
    <script src="../sweetalert.js"></script>
    <script src="../sweetalert.min.js"></script>
</head>

<body>
    <nav>
        <div class="wrapper">
            <div class="logo"><a href="#">Homely</a></div>
            <input type="radio" name="slider" id="menu-btn">
            <input type="radio" name="slider" id="close-btn">
            <ul class="nav-links">

                <label for="close-btn" class="btn close-btn"><i class="fas fa-times"></i></label>
                <li><a href="index.php">Home</a></li>
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
                            <span id="cart-count"><sup id="count"><?php echo $row['cart_count']; ?></sup></span>

                        <?php

                        } else {
                        ?>
                            <span id="cart-count"><sup id="count"><?php echo 0 ?></sup></span>

                        <?php
                        }
                        ?>
                    </a></li>
                <!-- Cart count display -->


            </ul>
            <label for="menu-btn" class="btn menu-btn"><i class="fas fa-bars"></i></label>
        </div>
    </nav>
    <?php
    $sql = "SELECT * FROM `tbl_products` WHERE product_id = $product_id";
    $result = $conn->query($sql);

    // Check if item exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

    ?><div class="item-details-container">
            <div class="item-photo">
                <div class="itim">
                    <img src="../products-images/<?php echo $row['photo_path']; ?>" alt="<?php echo $row['product_name']; ?>">
                </div>
                <div class="button-container">
                    <?php
                    // Check if the product exists in the cart
                    $sql1 = "SELECT * 
        FROM tbl_cart c
        INNER JOIN tbl_products p ON c.product_id = p.product_id
        WHERE p.product_name = '{$row['product_name']}' 
        AND c.fk_regid = '$userid'";
                    $result1 = $conn->query($sql1);
                    if ($result1->num_rows > 0) { ?>
                        <!-- Display "Go to Cart" button -->
                        <a href="cart.php"><button id="goto_cart" class="goto-to-cart-btn" data-product-id="<?php echo $row['product_id']; ?>">Go to Cart</button></a>
                    <?php
                    } else { ?>
                        <!-- Display "Add to Cart" button -->
                        <button class="add-to-cart-btn" data-product-id="<?php echo $row['product_id']; ?>">Add to Cart</button>
                    <?php
                    }
                    ?>



                    <button class="buy-now-button" data-product-id="<?php echo $row['product_id']; ?>">Buy Now</button>
                </div>
            </div>
            <div class="item-details">
                <div class="inline">
                    <h2><?php echo $row['product_name']; ?></h2>
                    <div class="rating">
                        <div class="rating-value" style="background-color: #4CAF50;">3.5<span class="star">★</span></div>
                    </div>
                </div>
                <!-- Add seller name and other details here -->
                <p class="price"> ₹<?php echo number_format($row['price'], 2, '.', ','); ?></p>

                <!--      <div class="delivery-pincode-container">
    <label for="delivery-pincode">Delivery</label>
    <span class="map-symbol"><img src="../img/118703_map_pin_icon.png" height="14px" width="14px"></span> 
    <input type="text" id="delivery-pincode" name="delivery-pincode" placeholder="Enter Pincode">
  <button id="check" class="Check">Check</button>


</div> -->
                <span style="margin-left:89px;" id="error"></span>
                <div class="section">
                    <?php
                    $row;
                    $sql = "SELECT * FROM tbl_registration where regid=$userid";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $count = 0;
                        $row = $result->fetch_assoc();
                        $isPrimary = $count === 0 ? true : false;
                    }
                    ?>
                    <h3>Delivery Address</h3>
                    <div id="delivery-address" style="display: flex;">
                        <input type="radio" id="address<?php echo $row['regid']; ?>" name="selected-address" value="<?php echo "OD" ?>" <?php echo $isPrimary ? 'checked' : ''; ?>>

                        <p><?php echo $row['full_name'] ?></p>
                        <p><?php echo '  ' . $row['street_address'] . ', ' . $row['city'] . ', ' . $row['state'] . ', ' . $row['postal'] . ', ' . $row['country']; ?></p>
                        <p><?php echo 'Phone: ' . $row['phone']; ?></p>
                    </div>
                    <h3>Alternative Addresses</h3>
                    <div id="alternative-addresses">
                        <?php
                        // Fetch all alternative addresses for the user
                        $sql_alternative = "SELECT * FROM tbl_alternative_addresses WHERE user_id = $userid";
                        $result_alternative = $conn->query($sql_alternative);

                        if ($result_alternative->num_rows > 0) {
                            // Display the first 5 alternative addresses
                            $count = 0;
                            while ($row_alternative = $result_alternative->fetch_assoc()) {
                                $isFirst = $count === 0;

                                if ($count < 1) {
                                    echo '<div class="alternative-address">';
                                    echo '<input type="radio" id="alt-address-' . $row_alternative['id'] . '" name="selected-address" value="' . $row_alternative['id'] . '" '  . '>';

                                    echo '<p>' . $row_alternative['street_address'] . ', ' . $row_alternative['city'] . ', ' . $row_alternative['state'] . ', ' . $row_alternative['postal'] . ', ' . $row_alternative['country'] . '</p>';
                                    echo '</div>';
                                } else {
                                    // Additional addresses will be hidden initially
                                    echo '<div class="alternative-address" style="display: none;">';
                                    echo '<input type="radio" id="alt-address-' . $row_alternative['id'] . '" name="selected-address" value="' . $row_alternative['id'] . '" ' . ($isFirst ? 'checked' : '') . '>';

                                    echo '<p>' . $row_alternative['street_address'] . ', ' . $row_alternative['city'] . ', ' . $row_alternative['state'] . ', ' . $row_alternative['postal'] . ', ' . $row_alternative['country'] . '</p>';
                                    echo '</div>';
                                }
                                $count++;
                            }
                            // Show more link if there are more than 5 addresses
                            if ($result_alternative->num_rows > 1) {
                                echo '<a href="#" id="show-more">Show More</a>';
                                echo '<a href="#" id="show-less" style="display:none;">Show Less</a>';
                            }
                        }
                        ?>
                    </div>

                    <button class="edit-address">Edit</button>
                    <div class="blur-background"></div>
                    <div id="edit-address-form" class="address-form" style="display: none;">
                        <!-- Edit address form will be displayed here -->
                        <!-- Add input fields for editing the address -->
                        <form id="edit-address-form" method="post">
                            <label for="street">Street Address:</label>
                            <input type="text" id="street" name="street" value="">
                            <input type="hidden" id="id" name="addressId" value="">

                            <label for="city">District</label>
                            <select id="districtSelect" name="district" onchange="updatePlaces1('districtSelect', 'placesSelect')" class="In1">
                                <option value="">Select District</option>
                            </select>

                            <label for="state">Place</label>
                            <div><input type="text" name="places" id="state" readonly></div>
                            <select id="placesSelect" name="place" class="In1" onchange="updatePlaceInput()">
                                <option value="">Select Place</option>
                            </select>
                            <label for="postal">Postal Code:</label>
                            <input type="text" id="postal" name="postal" value="">

                            <label for="country">Country:</label>
                            <input type="text" id="country" name="country" value="">

                            <button type="submit" name="edit">Save</button>
                            <button type="button" class="cancel-edit">Cancel</button>

                        </form>
                    </div>
                    <form id="pincode-form" method="post" action="check_pincode.php">
                        <input type="hidden" name="pincode">
                    </form>

                    <button id="add-alternative-address" <?php echo $result_alternative->num_rows >= 2 ? 'style="display: none;"' : ''; ?>>+ Add Alternative Address</button>
                    <div id="alternative-address-form" class="address-form" style="display: none;">
                        <!-- Alternative address form will be displayed here -->
                        <!-- Add input fields for alternative address -->
                        <form id="add-alt-address-form" method="post">
                            <label for="alt-street">Street Address:</label>
                            <input type="text" id="alt-street" name="alt-street">
                            <label id="error1"></label>
                            <label for="alt-city">City:</label>
                            <select id="districtSelect1" name="alt-city" onchange="updatePlaces2('districtSelect1', 'placesSelect1')" class="In1">
                                <option value="">Select District</option>
                            </select>

                            <label id="error2"></label>
                            <label for="alt-state">State:</label>
                            <div><input type="text" name="places" id="state" readonly></div>

                            <select id="placesSelect1" name="alt-state" class="In1" onchange="updatePlaceInput()">
                                <option value="">Select Place</option>
                            </select>
                            <label id="error3"></label>
                            <label for="alt-postal">Postal Code:</label>
                            <input type="text" id="alt-postal" name="alt-postal">
                            <label id="error4"></label>
                            <label for="alt-country">Country:</label>
                            <input type="text" id="alt-country" name="alt-country">
                            <label id="error5"></label>
                            <button type="submit" name="add">Save</button>
                            <button type="button" class="cancel-alt">Cancel</button>
                        </form>
                    </div>
                </div>


                <div class="seller-details">
                    <p class="padd">Seller &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Seller Name</p>
                    <div class="rating">
                        <div class="rating-value" style="background-color: blue;">3.5<span class="star">★</span></div>
                    </div>
                </div>



            </div>
        <?php
    } else {
        // Item not found
        echo "Item not found.";
    }

        ?>

        </div>

        <!-- After the item details container -->

        <div class="similar-products-container">
            <h3>Similar Products</h3>
            <div class="similar-products">
                <!-- Fetch and display similar products here -->
                <?php
                // Example code to fetch similar products from the database
                $sql_similar = "SELECT * FROM `tbl_products` WHERE category_id = 2 AND product_id != $product_id LIMIT 4";
                $result_similar = $conn->query($sql_similar);
                if ($result_similar->num_rows > 0) {
                    while ($row_similar = $result_similar->fetch_assoc()) {
                ?>
                        <div class="similar-product">
                            <div class="product-image">
                                <a href="item_details.php?product_id=<?php echo $row_similar['product_id']; ?>"> <img src="../products-images/<?php echo $row_similar['photo_path']; ?>" alt="<?php echo $row_similar['product_name']; ?>"></a>
                            </div>
                            <div class="product-details">
                                <h4><?php echo $row_similar['product_name']; ?></h4>
                                <div class="rate">
                                    <div class="rating-values" style="background-color: blue;">3.5<span class="star">★</span></div>

                                </div>
                                <p class="price">₹<?php echo number_format($row_similar['price'], 2, '.', ','); ?></p>

                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "No similar products found.";
                }
                ?>
            </div>
        </div>

</body>
<script>
// Function to handle radio button change event
function handleRadioChange(event) {
    var selectedAddress = event.target.value;


    var xhr = new XMLHttpRequest();
        xhr.open('POST', 'seller_av.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                if (xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.msg === 'success') {
    // Clear all existing green ticks
    var allRadios = document.querySelectorAll('input[type="radio"]');
    allRadios.forEach(function(radio) {
        radio.classList.remove('green-tick');
        radio.classList.remove('red-cross');
        radio.style.opacity = 1;
    });

    // Add green tick to selected radio button
    event.target.classList.add('green-tick');
    
    // Update the Buy Now button with the details
    var buyNowButton = document.querySelector('.buy-now-button');
    buyNowButton.dataset.productName = response.product_name;
    buyNowButton.dataset.sellerId = response.selected_seller_id;
    buyNowButton.dataset.productId = response.selected_product_id;
    buyNowButton.dataset.district = response.district;
    buyNowButton.dataset.place = response.place;
    buyNowButton.dataset.msg = response.msg;
    buyNowButton.disabled = false;
    // Optionally, you can update the button text or any other attribute as needed
    buyNowButton.innerText = 'Buy Now - ' + response.product_name; // Example of updating button text
    buyNowButton.style.opacity=1
} 
else {
    // Clear all existing green ticks and red crosses
    var allRadios = document.querySelectorAll('input[type="radio"]');
    allRadios.forEach(function(radio) {
        radio.classList.remove('green-tick');
        radio.classList.remove('red-cross');
        radio.style.opacity = .5;
    });
    event.target.classList.add('red-cross');
    // Update the Buy Now button with the "No suitable seller" message
    var buyNowButton = document.querySelector('.buy-now-button');
    buyNowButton.innerText = 'Not Available';
    buyNowButton.dataset.msg = response.msg;
    buyNowButton.style.opacity=.7
    buyNowButton.disabled = true;
}

                } else {
                    // Error occurred while processing the request
                    console.error('Error: ' + xhr.responseText);
                }
            }
        };
        xhr.send('product_id=' + <?php echo $product_id ?> + '&ad=' + encodeURIComponent(selectedAddress) +'&action=checkseller');
    // You can add further logic here to handle the address
}

// Add event listener to all radio buttons with name "selected-address"
var radioButtons = document.querySelectorAll('input[name="selected-address"]');
radioButtons.forEach(function(radioButton) {
    radioButton.addEventListener('change', handleRadioChange);

    // Check if the radio button is initially checked by default
    if (radioButton.checked) {
        handleRadioChange({target: radioButton}); // Trigger the event manually
    }
});



document.addEventListener("DOMContentLoaded", function() {
    // Add event listener to the Buy Now button
    var buyNowButton = document.querySelector('.buy-now-button');
    buyNowButton.addEventListener('click', function() {
        // Get the response data stored in the Buy Now button's dataset
        var msg = buyNowButton.dataset.msg;

        // Check the message from the response data
        if (msg === 'success') {
            // Get other dataset details if message is success
            var productName = buyNowButton.dataset.productName;
            var selectedSellerId = buyNowButton.dataset.sellerId;
            var selectedProductId = buyNowButton.dataset.productId; ;
            var district = buyNowButton.dataset.district
            var place = buyNowButton.dataset.place;

            // Redirect to checkout.php with dataset details
            var url = 'checkout.php?' +
                'productName=' + encodeURIComponent(productName) + '&' +
                'selectedSellerId=' + encodeURIComponent(selectedSellerId) + '&' +
                'selectedProductId=' + encodeURIComponent(selectedProductId) + '&' +
                'district=' + encodeURIComponent(district) + '&' +
                'place=' + encodeURIComponent(place);

            window.location.href = url;
        } else if (msg === 'no_suitable_seller') {
            // Show alert if message is no suitable seller
            alert("No suitable seller found.");
        }
    });
});







    function updatePlaceInput() {
        var selectedPlace = document.getElementById("placesSelect").value;
        document.getElementById("state").value = selectedPlace;
    }


    document.querySelectorAll('.add-to-cart-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var productId = this.getAttribute('data-product-id');
            var addToCartBtn = this; // Store a reference to the button

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'add_to_cart.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == XMLHttpRequest.DONE) {
                    if (xhr.status == 200) {
                        var data = JSON.parse(xhr.responseText);
                        document.getElementById('count').innerHTML = data.count;
                        if (data.msg === 'success') {
                            // Update the button text and behavior
                            addToCartBtn.textContent = 'Go to Cart';
                            addToCartBtn.classList.add('goto-to-cart-btn');
                            addToCartBtn.classList.remove('add-to-cart-btn');

                            // Remove all event listeners from the button
                            var clonedBtn = addToCartBtn.cloneNode(true);
                            addToCartBtn.parentNode.replaceChild(clonedBtn, addToCartBtn);

                            clonedBtn.addEventListener('click', function() {
                                window.location.href = 'cart.php';
                            });
                        } else if (data.msg === 'different_location') {
                            // Handle different location scenario
                            swal({
                                title: "Items already in cart",
                                text: "Your cart contains items from different location. Would you like to reset your cart for adding items from this location?",
                                icon: "warning",
                                buttons: {
                                    cancel: "No",
                                    startafresh: "Start afresh"
                                }
                            }).then((value) => {
                                if (value === "startafresh") {
                                    // Redirect to a page to reset the cart
                                    var resetCartRequest = new XMLHttpRequest();
                                    resetCartRequest.open('GET', 're.php?product_id=' + encodeURIComponent(productId), true);

                                    resetCartRequest.onreadystatechange = function() {
                                        if (resetCartRequest.readyState === XMLHttpRequest.DONE) {
                                            if (resetCartRequest.status === 200) {
                                                var resetCartResponse = JSON.parse(resetCartRequest.responseText);
                                                if (resetCartResponse.msg === 'success') {
                                                    // Show a notification that the item has been added to the cart after resetting
                                                    document.getElementById('count').innerHTML = resetCartResponse.count;

                                                    // Update the button text and behavior
                                                    addToCartBtn.textContent = 'Go to Cart';
                                                    addToCartBtn.classList.add('goto-to-cart-btn');
                                                    addToCartBtn.classList.remove('add-to-cart-btn');

                                                    // Remove all event listeners from the button
                                                    var clonedBtn = addToCartBtn.cloneNode(true);
                                                    addToCartBtn.parentNode.replaceChild(clonedBtn, addToCartBtn);

                                                    clonedBtn.addEventListener('click', function() {
                                                        window.location.href = 'cart.php';
                                                    });
                                                } else {
                                                    // Show an error message if resetting the cart failed
                                                    alert('Failed to reset cart: ' + resetCartResponse.msg);
                                                }
                                            } else {
                                                // Show an error message if the request encounters an error
                                                alert('Error resetting cart: ' + resetCartRequest.statusText);
                                            }
                                        }
                                    };

                                    resetCartRequest.send();
                                } else {
                                    // User chose not to reset the cart
                                    alert('You chose not to reset the cart.');
                                }
                            });
                        } else if (data.msg === 'conflicting_items') {
                            // Handle conflicting items scenario
                            swal({
                                title: "Items already in cart",
                                text: "Your cart contains items from different location. Would you like to reset your cart for adding items from this location?",
                                icon: "warning",
                                buttons: {
                                    cancel: "No",
                                    startafresh: "Start afresh"
                                }
                            }).then((value) => {
                                if (value === "startafresh") {
                                    // Redirect to a page to reset the cart
                                    var resetCartRequest = new XMLHttpRequest();
                                    resetCartRequest.open('GET', 're.php?product_id=' + encodeURIComponent(productId), true);

                                    resetCartRequest.onreadystatechange = function() {
                                        if (resetCartRequest.readyState === XMLHttpRequest.DONE) {
                                            if (resetCartRequest.status === 200) {
                                                var resetCartResponse = JSON.parse(resetCartRequest.responseText);
                                                if (resetCartResponse.msg === 'success') {
                                                    // Show a notification that the item has been added to the cart after resetting
                                                    addToCartBtn.textContent = 'Go to Cart';
                                                    addToCartBtn.classList.add('goto-to-cart-btn');
                                                    addToCartBtn.classList.remove('add-to-cart-btn');

                                                    // Remove all event listeners from the button
                                                    var clonedBtn = addToCartBtn.cloneNode(true);
                                                    addToCartBtn.parentNode.replaceChild(clonedBtn, addToCartBtn);

                                                    clonedBtn.addEventListener('click', function() {
                                                        window.location.href = 'cart.php';
                                                    });
                                                } else {
                                                    // Show an error message if resetting the cart failed
                                                    alert('Failed to reset cart: ' + resetCartResponse.msg);
                                                }
                                            } else {
                                                // Show an error message if the request encounters an error
                                                alert('Error resetting cart: ' + resetCartRequest.statusText);
                                            }
                                        }
                                    };

                                    resetCartRequest.send();
                                } else {
                                    // User chose not to reset the cart
                                    alert('You chose not to reset the cart.');
                                }
                            });
                        } else {
                            // Show an error message if adding to the cart failed
                            alert('Failed to add item to cart');
                        }
                    } else {
                        // Show an error message if there was a problem with the request
                        alert('Error: ' + xhr.responseText);
                    }
                }
            };
            // Include additional parameters for district and place
            xhr.send('product_id=' + encodeURIComponent(productId) + "&dis=<?php echo $_SESSION['dis'] ?>" + "&pl=<?php echo $_SESSION['pl'] ?>");
        });
    });

    // Declare xhr globally
    var xhr;
   













  

    // Function to check availability using the provided pincode
    /* function checkAvailability(productId, pincode) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'seller_av.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                if (xhr.status == 200) {
                    alert(xhr.responseText)
                    var response = JSON.parse(xhr.responseText);
                    if (response.available) {
                        // If the product is deliverable, redirect the user to the checkout page
                        window.location.href = 'checkout.php?product_id=' + encodeURIComponent(response.product_id);
                    } else {
                        // If the product is not deliverable, display a message
                        alert('Sorry, this product is not deliverable to your pincode.');
                    }
                } else {
                    // Error occurred while processing the request
                    console.error('Error: ' + xhr.responseText);
                }
            }
        };
        xhr.send('product_id=' + encodeURIComponent(productId) + '&pincode=' + encodeURIComponent(pincode)+'&action=checkproduct');
    } */





    document.addEventListener('DOMContentLoaded', function() {
        const editAddressButton = document.querySelector('.edit-address');
        const editAddressForm = document.getElementById('edit-address-form');
        const cancelEditButton = document.querySelector('.cancel-edit');

        editAddressButton.addEventListener('click', function() {
            const selectedAddress = document.querySelector('input[name="selected-address"]:checked');

            if (selectedAddress) {
                const addressId = selectedAddress.value;

                const addressDetails = getAddressDetails(addressId); // Function to retrieve address details from the server
                // Populate the edit form fields with the address details

            } else {
                alert('Please select an address to edit.');
            }
        });





        function getAddressDetails(addressId) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'get_address.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');


            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {

                        const addressDetails = JSON.parse(xhr.responseText);

                        document.getElementById('street').value = addressDetails.street_address;
                        // document.getElementById('city').value = addressDetails.city;
                        document.getElementById('state').value = addressDetails.state;
                        document.getElementById('postal').value = addressDetails.postal;
                        document.getElementById('country').value = addressDetails.country;


                        document.getElementById('districtSelect').value = addressDetails.city
                        updatePlaces1('districtSelect', 'placesSelect');

                        document.getElementById('placesSelect').value = addressDetails.state



                        if (addressId == 'OD') {
                            document.getElementById('id').value = 'OD'
                        } else {
                            document.getElementById('id').value = addressDetails.id
                        } // Display the edit address form
                        editAddressForm.style.display = 'block';
                    } else {
                        console.error('Request failed with status', xhr.status);
                    }
                }
            };


            var data = 'addressId=' + encodeURIComponent(addressId);


            xhr.send(data);
        }




        editAddressForm.addEventListener('submit', function(event) {

            // Here you can add code to handle form submission, such as AJAX request to update the address in the database
            editAddressForm.style.display = 'none'; // Hide the edit address form after submission
            document.body.classList.remove('dim-background'); // Remove dim background effect
        });

        cancelEditButton.addEventListener('click', function() {
            editAddressForm.style.display = 'none'; // Hide the edit address form when cancel button is clicked
            document.body.classList.remove('dim-background'); // Remove dim background effect
        });

        const addAltAddressButton = document.getElementById('add-alternative-address');
        const altAddressForm = document.getElementById('alternative-address-form');
        const cancelAltButton = document.querySelector('.cancel-alt');

        addAltAddressButton.addEventListener('click', function() {
            altAddressForm.style.display = 'block'; // Show the alternative address form
            document.body.classList.add('dim-background'); // Add dim background effect
        });

        altAddressForm.addEventListener('submit', function(event) {
            // Prevent form submission






            const altStreet = document.getElementById('alt-street').value.trim();
            const altCity = document.getElementById('alt-city').value.trim();
            const altState = document.getElementById('alt-state').value.trim();
            const altPostal = document.getElementById('alt-postal').value.trim();
            const altCountry = document.getElementById('alt-country').value.trim();
            let hasError = false; // Track if any field has error




            // Postal code validation
            const postalCodeRegex = /^\d{6}$/; // Regex to match exactly 6 digits
            if (!postalCodeRegex.test(altPostal)) {
                document.getElementById('error4').textContent = 'Postal code must be exactly 6 digits';
                document.getElementById('error4').style.display = 'block';
                hasError = true;
            }

            // Check if any field is empty
            if (altStreet === '') {
                document.getElementById('error1').textContent = 'Please fill out street address';
                document.getElementById('error1').style.display = 'block';
                hasError = true;
            }

            if (altCity === '') {
                document.getElementById('error2').textContent = 'Please fill out city';
                document.getElementById('error2').style.display = 'block';
                hasError = true;
            }

            if (altState === '') {
                document.getElementById('error3').textContent = 'Please fill out state';
                document.getElementById('error3').style.display = 'block';
                hasError = true;
            }

            if (altCountry === '') {
                document.getElementById('error5').textContent = 'Please fill out country';
                document.getElementById('error5').style.display = 'block';
                hasError = true;
            }

            // If there's any error, return without submitting the form
            if (hasError) {
                event.preventDefault();
            }

            // 



            // Here you can add code to handle form submission, such as AJAX request to add the alternative address to the database
            altAddressForm.style.display = 'block'; // Hide the alternative address form after submission
            document.body.classList.remove('dim-background'); // Remove dim background effect
        });

        cancelAltButton.addEventListener('click', function() {
            altAddressForm.style.display = 'none'; // Hide the alternative address form when cancel button is clicked
            document.body.classList.remove('dim-background'); // Remove dim background effect
        });
    });







    function updateDistrictsAndPlaces1() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '../dis.json', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    populateSelect1('districtSelect', 'placesSelect', data);
                } else {
                    console.error('Error fetching data:', xhr.status);
                }
            }
        };
        xhr.send();
    }

    function populateSelect1(districtSelectId, placesSelectId, data) {
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

    function updatePlaces1(districtSelectId, placesSelectId) {
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
    updateDistrictsAndPlaces1();


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
        const alternativeAddresses = document.querySelectorAll('.alternative-address');
        const showMoreLink = document.getElementById('show-more');
        const showLessLink = document.getElementById('show-less');

        showMoreLink.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default link behavior
            // Loop through all alternative addresses and display them
            alternativeAddresses.forEach(function(address) {
                address.style.display = 'flex'; // Set display to flex

            });
            // Toggle between "Show More" and "Show Less" links
            showMoreLink.style.display = 'none';
            showLessLink.style.display = 'block';
        });

        showLessLink.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default link behavior
            // Loop through all alternative addresses and hide them
            for (let i = 1; i < alternativeAddresses.length; i++) {
                alternativeAddresses[i].style.display = 'none';
            }
            // Toggle between "Show More" and "Show Less" links
            showMoreLink.style.display = 'block';
            showLessLink.style.display = 'none';
        });
    });
</script>

</html>