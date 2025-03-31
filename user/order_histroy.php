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

$sql="select "




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

.content {
    display: flex; /* Use flexbox layout */
}

.content-section {
    flex: 1; /* Each section takes up equal space */
    /* Add padding for spacing */
    width: fit-content;
    height: fit-content;
    cursor: pointer; /* Change cursor to pointer */
    transition: transform 0.3s ease; /* Add transition for animation */
}

.content-section:hover {
    transform: scale(1.05); /* Scale up on hover */
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
                <li><a href="#">About</a></li>

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
                        <a href="deliveryperson.php">
                            <img src="<?php echo $_SESSION['photo_path']; ?>" alt="" id="cart">

                        </a>



                    </div>
                    <div class="custom-dropdown-content">
                        <!-- Add your dropdown menu items here -->
                        <a href="user.php">Profile</a>
                        <a href="order_histroy.php">Orders</a>
                        <a href="logout.php">Logout</a>
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
   
        <div class="container">

    <!-- Page content -->
    <div class="content">
    <!-- Pre-book section -->
    <div id="prebook-section" class="content-section">
        <!-- Content for pre-book section goes here -->
        <!-- For example, you can add a heading and any other relevant content -->
        <h2>Pre-Book</h2>
        <p>This section contains content related to pre-booking.</p>
        <!-- Add more content as needed -->
    </div>

    <!-- Order purchase history section -->
    <div id="order-history-section" class="content-section">
        <!-- Content for order purchase history section goes here -->
        <!-- For example, you can add a heading and any other relevant content -->
        <h2>Order Purchase History</h2>
        <p>This section contains content related to order purchase history.</p>
        <!-- Add more content as needed -->
    </div>
</div>


        </div>
</body>
<script>
  

  document.addEventListener("DOMContentLoaded", function () {
    // Get the pre-book section element
    const prebookSection = document.getElementById("prebook-section");
    
    // Add click event listener to the pre-book section
    prebookSection.addEventListener("click", function () {
        // Redirect to prebook.php
        window.location.href = "prebook_histroy.php";
    });
    const order_history_section= document.getElementById("order-history-section");
    
    // Add click event listener to the pre-book section
    order_history_section.addEventListener("click", function () {
        // Redirect to prebook.php
        window.location.href = "order_purchase.php";
    });
});


</script>

</html>

