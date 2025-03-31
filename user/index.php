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

    <div class="body-text">
        <div class="title"> <br>Homely Delights <br>A Homely Produced Food Delivery System <br> <img id="img1"
                src="../img/_961218c4-7082-4a3e-8b7d-1a237a47c943.jpg" alt="">
            <img id="img1" src="../img/_a2c5c8ba-8960-44fa-b997-0f471ce2cdeb.jpg" alt=""> <img id="img1"
                src="../img/_b107c8c2-ca9f-403f-aadc-d478ff794c96.jpg" alt=""> <img id="img1"
                src="../img/_eaa48802-3f30-4799-99f9-95f667a921bf.jpg" alt="">

            <div class="title1">
                <table>
                    <tr>
                        <td>
                            <img id="img1" src="../img/img2.jpg" alt="">
                        </td>
                        <td>Indulge in the comfort of a homely-made meal, where every dish is crafted with love and
                            warmth. Our menu reflects the simplicity and authenticity of home-cooked goodness, offering
                            a delightful experience reminiscent of the familiar flavors of a loving kitchen. Savor the
                            homely touch in every bite, creating a dining experience that feels like a warm embrace.
                        </td>
                        <td> <img id="img1" src="../img/img1.jpg" alt=""></td><td>Indulge in the comfort of a homely-made meal, where every dish is crafted with love and
                            warmth. Our menu reflects the simplicity and authenticity of home-cooked goodness, offering
                            a delightful experience reminiscent of the familiar flavors of a loving kitchen. Savor the
                            homely touch in every bite, creating a dining experience that feels like a warm embrace. </td>
                    </tr>
                    <tr><td>
                        <img id="img1" src="../img/img2.jpg" alt="">
                    </td>
                    <td>Indulge in the comfort of a homely-made meal, where every dish is crafted with love and
                        warmth. Our menu reflects the simplicity and authenticity of home-cooked goodness, offering
                        a delightful experience reminiscent of the familiar flavors of a loving kitchen. Savor the
                        homely touch in every bite, creating a dining experience that feels like a warm embrace.
                    </td><td>
                        <img id="img1" src="../img/img2.jpg" alt="">
                    </td>
                    <td>Indulge in the comfort of a homely-made meal, where every dish is crafted with love and
                        warmth. Our menu reflects the simplicity and authenticity of home-cooked goodness, offering
                        a delightful experience reminiscent of the familiar flavors of a loving kitchen. Savor the
                        homely touch in every bite, creating a dining experience that feels like a warm embrace.
                    </td></tr>
                </table>
                
            </div>
        </div>

  
    <div class="footer">
        <p>&copy; 2024 Homely Delights. All rights reserved.</p>
    </div>

    </div>

</body>

</html>