<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Homely Delights</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    .about-content {
            max-width: 1000px;
            margin: 60px auto;
            padding: 0 20px;
            text-align: justify;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transform: translateX(113px);
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
<body>
<nav>
        <div class="wrapper">
            <div class="logo"><a href="#">Homely</a></div>
            <input type="radio" name="slider" id="menu-btn">
            <input type="radio" name="slider" id="close-btn">
            <ul class="nav-links">
                <label for="close-btn" class="btn close-btn"><i class="fas fa-times"></i></label>
                <li><a href="index.html">Home</a></li>
                <li><a href="about.php">About</a></li>
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
