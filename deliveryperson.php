<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Person Signup</title>
    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }

        .content {
            margin-bottom: 20px;
        }

        .signup-button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        /* CSS for flex container */
        .fruite-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            /* Align items to the left */
        }

        /* Styling for each item */
        .fruite-item {
            width: calc(20% - 20px); /* Adjust card width to fit three cards in a row */
    margin: 40px; 
            /* Adjust margin as needed */
            box-sizing: border-box;
            /* Include padding and border in width calculation */
            overflow: hidden;
 
        }


        /* Styling for the image */
        .fruite-img img {
            max-width: 100%; /* Ensures the image does not exceed the width of its container */
    max-height: 100%; /* Ensures the image does not exceed the height of its container */
    object-fit: cover;
          
            border-top-right-radius: 5px;
            border-top-left-radius: 5px;
            transition: transform .5s ease;
            /* Adding transition for smooth zoom effect */
        }

        /* Zoom effect on hover */
        .fruite-item:hover .fruite-img img {
    transform: scale(1.1); /* Adjust the scale factor as needed */
}
        /* Styling for the category label */
        .category-label {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #6c757d;
            /* Adjust color as needed */
            color: #fff;
            /* Adjust color as needed */
            padding: 5px 10px;
            border-radius: 5px;
        }

        /* Styling for the content section */
        .content-section {
            padding: 20px;
            border: 1px solid #6c757d;
            /* Adjust color as needed */
            border-top: 0;
            border-radius: 0 0 5px 5px;
            background-color: #fff;
            /* Adjust color as needed */
            
        }

        /* Styling for the title */
        .item-title {
            font-size: 1.25rem;
            /* Adjust as needed */
            margin-bottom: 10px;
        }

        /* Styling for the description */
        .item-description {
            margin-bottom: 10px;
        }

        /* Styling for the price */
        .item-price {
            font-size: 1.2rem;
            /* Adjust as needed */
            font-weight: bold;
        }

        /* Styling for the add to cart button */
        .add-to-cart-btn {
            display: inline-block;
            padding: 8px 20px;
            border: 1px solid #6c757d;
            /* Adjust color as needed */
            border-radius: 20px;
            text-decoration: none;
            color: #6c757d;
            /* Adjust color as needed */
            transition: background-color 0.3s, color 0.3s;
        }

        .add-to-cart-btn:hover {
            background-color: #6c757d;
            /* Adjust color as needed */
            color: #fff;
            /* Adjust color as needed */
        }
    </style>
     <link rel="stylesheet" href="style.css">
   
</head>

<body>
<nav>
        <div class="wrapper">
            <div class="logo"><a href="#">Homely</a></div>
            <input type="radio" name="slider" id="menu-btn">
            <input type="radio" name="slider" id="close-btn">
            <ul class="nav-links">
                <label for="close-btn" class="btn close-btn"><i class="fas fa-times"></i></label>
                <li><a href="index.html">Home</a></li>
                <li><a href="#">About</a></li>
                <li>
                    <a href="./food-items.php" class="desktop-item">Food-Items</a>
                    <input type="checkbox" id="showDrop">
                    <label for="showDrop" class="mobile-item">Dropdown Menu</label>
                    <ul class="drop-menu">
                    <li><a href="./breakfast.php">Breakfast Items</a></li>
                        <li><a href="./lunch.php">HomeHarvest Lunch</a></li>
                        <li><a href="./evening.php">Evening Eatables</a></li>
                    </ul>
                </li>

                <li><a href="login.php"><button id="but">Sign-up</button></a></li>
                <li><a href="become_a_seller.php"><button id="but">Become a Seller</button></a></li>


            </ul>
            <label for="menu-btn" class="btn menu-btn"><i class="fas fa-bars"></i></label>
        </div>
    </nav>


    <div class="container">
        <div class="content">
            <h2>Join as Delivery Person</h2>
            <p>Become a part of our delivery team and start delivering happiness!</p>
        </div>
       <a href="deliverpersonreg.php"> <button class="signup-button">Sign Up</button></a>
    </div>

    <div class="fruite-container">
        <div class="fruite-item">
            <div class="fruite-img">
                <img src="./img/del3.jpg" alt="">
            </div>

            <div class="content-section">
                <h4 class="item-title">Effort based earning</h4>
                <p class="item-description">
                    Get paid for every parcel delivered by you. Earn incentives on the basis of your performance,
                    availability and through various other schemes.</p>


            </div>
        </div>
        <div class="fruite-item">
            <div class="fruite-img">
                <img src="./img/del2.jpg" alt="">
            </div>

            <div class="content-section">
                <h4 class="item-title">Weekly Payouts</h4>
                <p class="item-description">
                    Get assured payment every week and earn extra money by referring Ecom Express to your other friends. <p>


            </div>
        </div>
        <div class="fruite-item">
            <div class="fruite-img">
                <img src="./img/del1.jpg" alt="">
            </div>

            <div class="content-section">
                <h4 class="item-title">Flexible time-slot</h4>
                <p class="item-description">
                    Choose to work with us as a part-time or a full-time delivery partner. Work for 4 hours or 8 hours as per your suitable time slot.</p>

            </div>
        </div>
        <div class="fruite-item">
            <div class="fruite-img">
                <img src="./img/del3.jpg" alt="">
            </div>

            <div class="content-section">
                <h4 class="item-title">Effort based earning</h4>
                <p class="item-description">
                    Get paid for every parcel delivered by you. Earn incentives on the basis of your performance,
                    availability and through various other schemes.</p>


            </div>
        </div>
    </div>
  


</body>

</html>