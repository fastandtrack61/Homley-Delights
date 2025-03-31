<?php
session_start();
require('../Connect_database.php');
$userid = $_SESSION['userid'];
$pincode;

if (isset($_POST['edit']) && $_POST['addressId'] == 'OD') {
    // Retrieve form data
    $street = $_POST['street'];

    $city = $_POST['district1'];
    $state = $_POST['places'];
    $postal = $_POST['postal'];
    $country = $_POST['country'];
    $addressId = $_POST['addressId']; // Assuming you have a hidden input field with the address ID
  
    // SQL query to update the alternative address details in the database
    $sql = "UPDATE tbl_registration SET street_address='$street', city='$city', state='$state', postal='$postal', country='$country' WHERE regid='$userid'";

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
}
if (isset($_POST['check'])) {
  // If a new pincode is submitted, use the entered pincode
 
  $dis = $_POST['district'];
      $pl= $_POST['place'];
      $_SESSION['dis']=$dis;
      $_SESSION['pl']=$pl;
 
    
} else {
  // If no new pincode is submitted, use the customer's pincode
  $sql = "SELECT * FROM tbl_registration WHERE regid = $userid";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $dis = $row['city'];
    $pl = $row['state'];

    $_SESSION['dis']=$dis;
    $_SESSION['pl']=$pl;
 
 
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Filter Items</title>
  <style>
    .sidebar {
      width: 200px;
      float: left;
      background-color: #ffffff;
      /* Changing background color to white */
      padding: 10px;
      border-radius: 10px;
      /* Adding border radius */
      border: 1px solid #ddd;
      /* Moved border from padding to here */
      margin: 5px 5px;
      height: 300px;
      /* Setting a height of 300px */
      overflow-y: auto;
      /* Adding overflow-y to enable scrolling if content exceeds height */
     
 
    }
    
    /* Styling for the filter button */
    .sidebar button {
      margin-top: 10px;
      padding: 8px 16px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 5px;
      /* Adding border radius */
    }

    /* Styling for the items container */
    .items-container {
      margin-left: 220px;
      /* Adjust margin to accommodate sidebar width */
      padding-left: 10px;
      padding-top: -1rem;
    }

    /* Styling for individual items */
    .item {
      width: calc(25% - 20px);
      /* Set width to occupy 25% of the available space (minus margins) */
      margin: 10px;
      font-size: 20px;
      text-align: center;
      border: 1px solid #ddd;
      border-radius: 10px;
      /* Adding border radius */
      background-color: #f9f9f9;
      /* Setting background color */
      box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
      /* Adding box shadow */
      display: inline-block;
      /* Display items inline */
      vertical-align: top;
      /* Align items to the top */
      position: relative;
      /* Make position relative for button positioning */
      overflow: hidden;
    }

    /* Styling for product images */
    .item img {
      width: 300px;
      height: 250px;
      max-height: 270px;
      object-fit: cover;
      /* Ensure the image covers the entire container */
      display: block;
      transition: transform 0.3s ease-in-out;
      /* Adding transition effect for smooth zoom */

    }

    /* Zoom effect on hover */
    .item img:hover {
      transform: scale(1.1);
      /* Scale the image by 1.1 on hover (zoom effect) */
    }

    /* Styling for buttons */
    /* Styling for buttons */
    /* Styling for buttons */
    .item button {
      display: inline-block;
      padding: 8px 16px;
      margin: 5px 10px 0 0;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.3s;
      /* Adding transition effects */
      box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
      /* Adding box shadow */
    }

    .item button:hover {
      background-color: #45a049;
      /* Darker background color on hover */
      transform: scale(1.05);
      /* Adding scale effect on hover */
    }

    .item button:active {
      transform: scale(0.95);
      /* Adding scale effect when button is clicked */
    }


    /* Styling for the sidebar headings */
    .sidebar h3 {
      font-family: Arial, sans-serif;
      /* Changing font */
      color: #333;
      /* Changing font color */
    }

    .star {
      font-size: 25px;



    }

    .star.filled {
      color: #45a049;
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

    #bottom-container input[type="text"]:focus {
      outline: none;
      /* Remove outline on focus */
    }

    #bottom-container input {
      width: 150px;
      height: 30px;
      /* Set height to 30px */
      padding: 5px;
      border: 0px;
      border-bottom: 2px solid lightblue;
      /* Light blue bottom border */
      border-radius: 3px;


    }


    #bottom-container {

      text-align: center;
      position: sticky;
      width: 50%;
      background-color: white;
      margin-top: 12px;
      margin-left: 25%;

      /* Ensure it stays above other content */
    }



    .place-order-btn {

      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .place-order-btn:hover {
      background-color: #45a049;
    }

    #err {

      text-align: center;
      margin-left: 12px;

    }

    #err img {
      margin-left: -16rem;

    }
    .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }

        p {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
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

        #add-alternative-address ,#confirmPreOrder{
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
        .cancel-alt:hover,#confirmPreOrder:hover {
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
            top: 45%;
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
  <script src="../sweetalert.js"></script>
  <script src="../sweetalert.min.js"></script>
  <link rel="stylesheet" href="../style.css">
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
        <!-- Cart count display -->


      </ul>
      <label for="menu-btn" class="btn menu-btn"><i class="fas fa-bars"></i></label>
    </div>
  </nav>

 <div>
 <form action="" method="post">
 <select id="districtSelect" name="district" onchange="updatePlaces1('districtSelect', 'placesSelect')" class="In1" >
        <option value="">Select District</option>
      </select> <select id="placesSelect"  name="place" class="In1">
        
        <option value="">Select Place</option>
      </select> </div>
<button id="check" name="check" type="submit">Check</button>
</form> 
  <div class="sidebar">
    <h3>Filter :</h3>
    <form id="filterForm" method="post" action="#">
      <h4>By Category</h4>
      <?php $sql2 = "SELECT * from  `tbl_categories`";


      if ($result1 = $conn->query($sql2)) {
        if ($result1->num_rows > 0) {
          while ($row = $result1->fetch_assoc()) {
      ?>


            <label for="category<?php echo $row['category_id']; ?>">
              <input type="checkbox" id="category<?php echo $row['category_id']; ?>" name="category[]" value="<?php echo $row['category_id']; ?>">
              <?php echo $row['category_name']; ?>
            </label><br>

      <?php
          }
        }
      }
      ?>
 
    <h4>By Price</h4>
<select id="priceSort" name="price-sort">
  <option value="">Select Price</option>
  <option value="high-to-low">High to Low</option>
  <option value="low-to-high">Low to High</option>
  <option value="range">Range</option>
</select>
<div id="price-range" style="display: none;">
  <input type="range" name="min-price" id="minPrice" min="0" max="1000" value="0">
  <input type="range" name="max-price" id="maxPrice" min="0" max="1000" value="1000">
  <div id="price-values">
    <span id="minPriceValue">0</span> - <span id="maxPriceValue">1000</span>
  </div>
</div>


     

      <!-- Add more categories as needed --><br>
      <button type="submit" name="filter" id="applyFilterBtn">Apply Filter</button>
    </form>
  </div>



  <div class="items-container">



    <?php
    // Assuming customer's pincode is 12345
    if (isset($_POST['filter'])) {

      $selectedCategories = isset($_POST['category']) ? $_POST['category'] : [];
      if (!empty($selectedCategories)) {
        $categoryIds = implode(',', $selectedCategories);

        $sql = "SELECT 
                  p.product_id, 
                  p.product_name, 
                  MIN(p.price) AS price, 
                  MIN(p.photo_path) AS photo_path, 
                  COUNT(*) AS seller_count, 
                  MIN(r.full_name) AS full_name 
              FROM 
                  tbl_products p
              JOIN 
                  tbl_registration r ON p.fk_regid = r.regid
              JOIN
                  tbl_pincodes pc ON pc.fk_regid = r.regid
              JOIN
              tbl_categories tc ON tc.category_id = p.category_id
              WHERE 
                  p.p_status = '1' AND
                  tc.category_id IN ($categoryIds) AND
                  pc.district = '$dis' and pc.place='$pl'

              GROUP BY 
                  p.product_name
              ORDER BY 
                  p.product_id DESC";
                  
      }
      elseif (isset($_POST['price-sort'])) {
        $minPrice = isset($_POST['min-price']) ? $_POST['min-price'] : null;
        $maxPrice = isset($_POST['max-price']) ? $_POST['max-price'] : null;
        $priceSort = $_POST['price-sort'];
        $sql = "SELECT 
                  p.product_id, 
                  p.product_name, 
                  MIN(p.price) AS price, 
                  MIN(p.photo_path) AS photo_path, 
                  COUNT(*) AS seller_count, 
                  MIN(r.full_name) AS full_name 
                FROM 
                  tbl_products p
                JOIN 
                  tbl_registration r ON p.fk_regid = r.regid
                JOIN
                  tbl_pincodes pc ON pc.fk_regid = r.regid
                WHERE 
                  p.p_status = '1' AND
                  pc.district = '$dis' and pc.place='$pl'
                GROUP BY 
                  p.product_name";
        if ($priceSort === 'high-to-low') {
          $sql .= " ORDER BY price DESC";
        } elseif ($priceSort === 'low-to-high') {
          $sql .= " ORDER BY price ASC";
        }
       elseif ($minPrice !== null && $maxPrice !== null) {
          $sql .= " HAVING price BETWEEN $minPrice AND $maxPrice";
      }
    }
    
  }


    else {
      $sql = "SELECT 
p.product_id, 
p.product_name, 
MIN(p.price) AS price, 
MIN(p.photo_path) AS photo_path, 
COUNT(*) AS seller_count, 
MIN(r.full_name) AS full_name 
FROM 
tbl_products p
JOIN 
tbl_registration r ON p.fk_regid = r.regid
JOIN
tbl_pincodes pc ON pc.fk_regid = r.regid
WHERE 
p.p_status = '1' AND
pc.district = '$dis' and pc.place='$pl'
GROUP BY 
p.product_name
ORDER BY 
p.product_id DESC;
";
    }


    
    if ($result = $conn->query($sql)) {
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $product_id = $row['product_id'];
$get_rating_sql = "SELECT AVG(rating) AS avg_rating FROM tbl_ratings WHERE product_id = $product_id";
$rating_result = $conn->query($get_rating_sql);
$rating_row = $rating_result->fetch_assoc();
$avg_rating = $rating_row['avg_rating'];
    ?>

          <div class="item">
            <a href="item_details.php?product_id=<?php echo $row['product_id']; ?>">

              <img src="../products-images/<?php echo $row['photo_path']; ?>" alt="Food Product 1">
            </a>
            <h4>
              <?php echo $row['product_name'] ?>
            </h4>

            <p><b style="font-size:25px;">
                <?php echo '&#8377;' . $row['price']; ?>
              </b></p>

            <?php
            // Check if the product exists in the cart
            $sql1 = "SELECT * 
         FROM tbl_cart c
         INNER JOIN tbl_products p ON c.product_id = p.product_id
         WHERE p.product_name = '{$row['product_name']}' 
         AND c.fk_regid = '$userid'";


            $result1 = $conn->query($sql1);
                      // Inside your while loop where you fetch products

        ?>              <button class="add-to-cart-btn" data-product-id="<?php echo $row['product_name']; ?>" onclick="appendProductName(this)">Pre-Book</button>

      
<div class="rating">
    <?php
    $filled_stars = intval($avg_rating); // Get the integer part of the average rating
    $empty_stars = 5 - $filled_stars; // Calculate the number of empty stars

    // Display filled stars
    for ($i = 1; $i <= $filled_stars; $i++) {
        echo '<span class="star filled">★</span>';
    }

    // Display empty stars
    for ($i = 1; $i <= $empty_stars; $i++) {
        echo '<span class="star">★</span>';
    }
    ?>
</div>
          </div>


    <?php
        }
      } else {
        echo "<p id='err'> <img src='../img/product-not-found.jpg' alt=''></p>";
      }
    }
    ?>


    <!-- Add more items as needed -->
  </div>
  <div id="preOrderModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Pre-order Details</h2>
    <p>
        
    <span id="deliveryAddress">

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
                            <select id="districtSelect1"  name="district1" onchange="updatePlaces2('districtSelect1', 'placesSelect1')" class="In1">
                                <option value="">Select District</option>
                            </select>

                            <label for="state">Place</label>
                            <div><input type="text" name="places" id="state" readonly></div>

<select id="placesSelect1" name="alt-state" class="In1" onchange="updatePlaceInput()">
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
                    

                   
                </div>

    </span></p>
    Quantity: <input type="number" id="quantity" min="1" max="200" value="1">

    <p>

    Date: <input type="date" id="orderDate" min="<?php echo date('Y-m-d'); ?>">
    Time: <input type="time" id="orderTime" min="14:00" max="17:30">
</p>
<input type="hidden" id="productName" value="<?php echo $product_name; ?>">
<span id="error"></span>
<button id="confirmPreOrder" style="display: none;">Confirm Pre-order</button>
  </div>


</div>

</body>
<script>

function appendProductName(button) {
    var productId = button.getAttribute('data-product-id'); // Get the product ID from the button's data attribute
    var productName = button.previousElementSibling.textContent.trim(); // Get the product name from the previous element (assuming it's a <h4> tag)
    var productNameInput = document.getElementById('productName'); // Get the input element by its ID

    // Append the product name to the input value
    productNameInput.value = productId 
  }

  document.getElementById('confirmPreOrder').addEventListener('click', function() {
    // Retrieve pre-order details
    var quantity = document.getElementById('quantity').value;
    var productName = document.getElementById('productName').value;
    var orderDate = document.getElementById('orderDate').value;
    var orderTime = document.getElementById('orderTime').value;

    var currentDate = new Date(); // Get the current date and time
    var currentHour = currentDate.getHours(); // Get the current hour

    // Check if the order date is set to the next day and the time is between 2 PM to 5:30 PM
    if (orderDate > currentDate.toISOString().split('T')[0] && (orderTime >= '14:00' && orderTime <= '17:30')) {
        // Accept the order immediately
        sendPreOrderRequest(quantity, productName, orderDate, orderTime);
    } else if (currentHour < 13) {
        // Pre-booked orders can be accepted before 1 PM
        sendPreOrderRequest(quantity, productName, orderDate, orderTime);
    } else {
        // Pre-booked orders after 1 PM are scheduled for the next day
        var nextDay = new Date(currentDate);
        nextDay.setDate(nextDay.getDate() + 1); // Increment current date by 1 day

        // Ensure that the order time is set to 14:00 (2 PM) for the next day
        orderTime = '14:00';

        var nextDayFormatted = nextDay.toISOString().split('T')[0]; // Format as YYYY-MM-DD

        // Update the order date input field with the next day's date and the order time to 14:00
        document.getElementById('orderDate').value = nextDayFormatted;
        document.getElementById('orderTime').value = orderTime;
        document.getElementById('error').style.color="red";
        document.getElementById('error').innerHTML='Pre-booked orders after 1 PM are scheduled for the next day.';
    }
});

function sendPreOrderRequest(quantity, productName, orderDate, orderTime) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'pre.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Request successful
                alert(xhr.responseText);
                var response = JSON.parse(xhr.responseText);
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Order Request Placed. Please wait for 15 minutes to accept the order!',
                    timer: 2000,
                    didOpen: () => {
                        document.querySelector('.swal2-container').style.zIndex = 999999;
                        modal.style.display = "none";
                    }
                });
            } else {
                // Error handling if request fails
                document.getElementById('error').innerHTML=      'Failed to retrieve pre-order details. Please try again later.'
            }
        }
    };

    // Prepare data to send
    var data = 'productName=' + encodeURIComponent(productName) +
               '&orderDate=' + encodeURIComponent(orderDate) +
               '&orderTime=' + encodeURIComponent(orderTime) +
               '&quantity=' + encodeURIComponent(quantity);

    // Send the request
    xhr.send(data);
}









     function isValidTime(time) {
        // Parse the time string to get hours and minutes
        var parts = time.split(':');
        var hours = parseInt(parts[0], 10);
        var minutes = parseInt(parts[1], 10);

        // Check if the time is between 2:00 PM and 5:30 PM
        if ((hours === 14 && minutes >= 0) || (hours > 14 && hours < 17) || (hours === 17 && minutes <= 30)) {
        document.getElementById('confirmPreOrder').style.display="block"
            return true;
        } else {
            return false;
        }
    }

    // Event listener to validate the time input
    document.getElementById('orderTime').addEventListener('change', function() {
        var timeInput = this.value;
        if (!isValidTime(timeInput)) {
            alert('Please select a time between 2:00 PM and 5:30 PM.');
            // Clear the input value
            this.value = '';
        }
    });
document.getElementById('minPrice').addEventListener('input', function() {
  document.getElementById('minPriceValue').textContent = this.value;
});

document.getElementById('maxPrice').addEventListener('input', function() {
  document.getElementById('maxPriceValue').textContent = this.value;
});


  var data

 

  document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('applyFilterBtn').addEventListener('click', function(event) {
    // Check if any category is selected
    var categoriesSelected = document.querySelectorAll('input[name="category[]"]:checked').length > 0;
    // Check if price option is selected
    var priceSortSelected = document.getElementById('priceSort').value !== "";
    // If no category is selected and no price option is selected, prevent form submission
    if (!categoriesSelected && !priceSortSelected) {
      alert("Please select at least one filter option.");
      event.preventDefault(); // Prevent form submission
    }
    // If either category or price option is selected, proceed with form submission
  });
});



document.getElementById('priceSort').addEventListener('change', function() {
    var priceRange = document.getElementById('price-range');
    if (this.value === 'range') {
        priceRange.style.display = 'block';
    } else {
        priceRange.style.display = 'none';
    }
});



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







var modal = document.getElementById('preOrderModal');

// Get the button that opens the modal
var btns = document.querySelectorAll('.add-to-cart-btn');

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btns.forEach(function (button) {
    button.addEventListener('click', function () {
        modal.style.display = "block";
        // Retrieve and display delivery address and date-time
        var deliveryAddress = ''; // Get the delivery address from your form or data source
        var deliveryDateTime = ''; // Get the delivery date-time from your form or data source
        document.getElementById('deliveryAddress').textContent = deliveryAddress;
   
    });
});

// When the user clicks on <span> (x), close the modal
span.onclick = function () {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}


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
alert(xhr.responseText)
                        const addressDetails = JSON.parse(xhr.responseText);

                        document.getElementById('street').value = addressDetails.street_address;
                        // document.getElementById('city').value = addressDetails.city;
                        document.getElementById('state').value = addressDetails.state;
                        document.getElementById('postal').value = addressDetails.postal;
                        document.getElementById('country').value = addressDetails.country;


                        document.getElementById('districtSelect1').value = addressDetails.city
                        updatePlaces1('districtSelect1', 'placesSelect1');

                    



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
  
    function updatePlaceInput() {
        var selectedPlace = document.getElementById("placesSelect1").value;
        document.getElementById("state").value = selectedPlace;
    }

</script>


</html>