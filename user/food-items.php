<?php
session_start();
require('../Connect_database.php');
$userid = $_SESSION['userid'];
$pincode;

if (isset($_POST['check'])) {
  // If a new pincode is submitted, use the entered pincode
 
  $dis = $_POST['district'];
      $pl= $_POST['place'];
      $_SESSION['dis']=$dis;
      $_SESSION['pl']=$pl;
      $dist=$dis;
      $pla=$pl;
 
    
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
    $dist=$dis;
    $pla=$pl;
 
 
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
p.p_status = '1' AND p.stock > 0 AND
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
            if ($result1->num_rows > 0) { ?>
              <!-- Display "Go to Cart" button -->
              <a href="cart.php"><button id="goto_cart" class="goto-cart-bt" data-product-id="<?php echo $row['product_id']; ?>">Go to Cart</button></a>
            <?php
            } else { ?>
              <!-- Display "Add to Cart" button -->
              <button class="add-to-cart-btn" data-product-id="<?php echo $row['product_id']; ?>">Add to Cart</button>
            <?php
            }

            // Inside your while loop where you fetch products
$product_id = $row['product_id'];
$get_rating_sql = "SELECT AVG(rating) AS avg_rating FROM tbl_ratings WHERE product_id = $product_id";
$rating_result = $conn->query($get_rating_sql);
$rating_row = $rating_result->fetch_assoc();
$avg_rating = $rating_row['avg_rating'];

            ?>
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
</body>
<script>
document.getElementById('minPrice').addEventListener('input', function() {
  document.getElementById('minPriceValue').textContent = this.value;
});

document.getElementById('maxPrice').addEventListener('input', function() {
  document.getElementById('maxPriceValue').textContent = this.value;
});


  var data

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
              addToCartBtn.classList.add('goto-cart-btn');
              addToCartBtn.classList.remove('add-to-cart-btn');

              // Remove all event listeners from the button
              var clonedBtn = addToCartBtn.cloneNode(true);
              addToCartBtn.parentNode.replaceChild(clonedBtn, addToCartBtn);

              clonedBtn.addEventListener('click', function() {
                window.location.href = 'cart.php';
              });
                    }  else {
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
        xhr.send('product_id=' + encodeURIComponent(productId) + "&dis=<?php echo $dist?>" + "&pl=<?php echo $pla?>");
    });
});


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
        option.textContent = item.District+',Kerala';
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




  
</script>


</html>