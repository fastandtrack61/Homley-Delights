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
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Navigation Bar</title>

    <link rel="stylesheet" href="../style.css">
    
    <style>
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
        .about-content {
            max-width: 1000px;
            margin: 60px auto;
            padding: 0 20px;
            text-align: justify;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transform: translateX(250px);
        }

        .about-content h1 {
            font-size: 32px;
            margin-bottom: 20px;
        }

        .about-content p {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
        }

        .footer p {
            margin: 0;
            font-size: 14px;
        }

    </style>
</head>

<body id="bg1">
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

    <div class="body-text">
        <!-- Content for your About page goes here -->
        <div class="about-content">
            <h1>About Homely Delights</h1>
            <p>Welcome to Homely Delights, your go-to destination for delicious homely-made meals delivered right to your doorstep. Our mission is to provide you with wholesome, nutritious, and flavorful dishes crafted with love and care.</p>
            <p>At Homely Delights, we understand the importance of home-cooked meals in our busy lives. Our team of experienced chefs and cooks is dedicated to bringing you the authentic flavors of homemade food, prepared using the freshest ingredients and traditional recipes.</p>
            <p>Whether you're craving a comforting bowl of soup, a hearty plate of pasta, or a sumptuous dessert, we have something for everyone. Our menu is carefully curated to offer a diverse selection of dishes, catering to different tastes and dietary preferences.</p>
            <p>What sets us apart is our commitment to quality and customer satisfaction. We source our ingredients from local farmers and suppliers, ensuring that each meal is not only delicious but also supports the community. Your satisfaction is our top priority, and we strive to exceed your expectations with every order.</p>
            <p>Join us on a culinary journey filled with flavors, memories, and the joy of sharing a meal with loved ones. Experience the warmth and comfort of homely cooking with Homely Delights.</p>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2024 Homely Delights. All rights reserved.</p>
    </div>

</body>

</html>