<?php

session_start();
require('../Connect_database.php');
$userid = $_SESSION['userid'];
$email = $_SESSION['username'];
// Include necessary files and database connection
$phone;
$sql="SELECT * FROM `tbl_registration` WHERE regid='$userid' ";
if($result=$conn->query($sql))
{
    $row=$result->fetch_assoc();
    $phone=$row['phone'];

}

$productName= $_GET['productName'];
$selectedSellerId  = $_GET['selectedSellerId'];
$selectedProductId = $_GET['selectedProductId'];
$district= $_GET['district'];
$place= $_GET['place'];
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
    <title>Checkout</title>
    <!-- Include any CSS files or stylesheets -->
    <style>

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    box-sizing: border-box;
}

h1 {
    text-align: center;
    margin-bottom: 30px;
}

.section {
    background-color: #fff; /* Set background color to white */
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    position: relative;
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


.edit-address,.cancel-edit,.cancel-alt {
    background-color: #4CAF50;
        color: #fff; /* White text color */
    padding: 10px 20px; /* Add padding */
    border: none; /* Remove border */
    border-radius: 3px; /* Add border radius */
    cursor: pointer; /* Add cursor pointer on hover */
    transition: background-color 0.3s; /* Add transition effect for background color */
}
#add-alternative-address 
{
    background-color: #4CAF50;
        color: #fff; /* White text color */
    padding: 10px 20px; /* Add padding */
    border: none; /* Remove border */
    border-radius: 3px; /* Add border radius */
    cursor: pointer; /* Add cursor pointer on hover */
    transition: background-color 0.3s; /* Add transition effect for background color */ 
}

#add-alternative-address:hover,
.edit-address:hover,.cancel-edit:hover,.cancel-alt:hover {
    background-color: #45a049;
}
#continue,#continue1
{
  
    background-color: #4CAF50;
        color: #fff; /* White text color */
    padding: 10px 20px; /* Add padding */
    border: none; /* Remove border */
    border-radius: 3px; /* Add border radius */
    cursor: pointer; /* Add cursor pointer on hover */
    transition: background-color 0.3s; /* Add transition effect for background color */   
    float: right;
    position: absolute;
    bottom: 0;
    right: 0;
    margin: 1rem;
}
#continue:hover,#continue1:hover
{
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
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
            z-index: 2; /* Higher z-index to make it appear in front */
            display: none;
        }

        .address-form {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            z-index: 3; /* Highest z-index to make it appear even more in front */
            display: none;
            width:25%;
        }

.address-form label {
    display: block;
    margin-bottom: 10px;
}

.address-form input {
    width: calc(100% - 20px); /* Adjust input width */
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
p
{
    text-transform: capitalize;
    padding-left: 9px;
}

.alternative-address {
    display: flex;
    align-items: center;
    margin-bottom: 10px; /* Add margin to create space between addresses */
}

.alternative-address input[type="radio"] {
    margin-right: 10px; /* Add margin to create space between radio buttons and addresses */
}

    .loading-spinner {
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 2s linear infinite;
        margin:auto;
   
   
    
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }


#b
{
    background-color: #4CAF50;
        color: #fff; /* White text color */
    padding: 10px 20px; /* Add padding */
    border: none; /* Remove border */
    border-radius: 3px; /* Add border radius */
    cursor: pointer; /* Add cursor pointer on hover */
    transition: background-color 0.3s; 
}
#b:hover
{
    background-color: #45a049; 
    scale: 1.1;
}

/* HTML: <div class="loader"></div> */
/* Style for the table */
table {
    border-collapse: collapse;

}

/* Style for table cells */
td {
    padding: 10px;
    text-align: left;
}

/* Style for the image */
td img {
    width: 150px;
    height: 150px;
    display: block; /* To remove any extra space around the image */
}

/* Style for the product name */
td.product-name {
    font-weight: bold;
}

/* Style for the quantity controls */
td.quantity-controls {
    text-align: center;
}

.quantity-adjust {
    margin: 0 5px; /* Adjust margin for better spacing */
    padding: 5px 10px; /* Add padding for better click area */
    cursor: pointer;
    background-color: #007bff; /* Blue color */
    color: #fff; /* White text color */
    border: none;
    border-radius: 5px;
}

.quantity-input {
    width: 50px; /* Adjust width as needed */
    text-align: center;
}
.loading-bar {
      position: fixed;
      top: 0;
      left: 0;
      height: 3px;
      width: 0%;
      background-color: #4CAF50; /* Blue color */
      z-index: 1000; /* Ensure it's above other content */
      transition: width 0.4s ease-in-out; /* Smooth animation */
    }

    /* Add some animation to make it more noticeable */
    @keyframes pulse {
      0% {
        opacity: 1;
      }
      50% {
        opacity: 0.5;
      }
      100% {
        opacity: 1;
      }
    }

    .loading-bar::after {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      background-color: #4CAF50; /* Blue color */
      opacity: 1;
      animation: pulse 1.5s infinite;
    }
    </style>
        <script src="../sweetalert.min.js"></script>
        <script src="../sweetalert.js"></script>
</head>
<body>
<div class="loading-bar" id="loadingBar"></div>

<div class="container">
<?php
$row;
$sql="SELECT * FROM tbl_registration where regid=$userid";
$result=$conn->query($sql);
if($result->num_rows>0)
{ $count = 0;
    $row=$result->fetch_assoc();
    $isPrimary = $count === 0 ? true : false;

}
?>    
<div class="section" id="section">


<h3 id="da">Delivery Address</h3>
                    <div id="delivery-address" style="display: flex;">
                        <input type="radio" id="address<?php echo $row['regid']; ?>" name="selected-address" value="<?php echo "OD" ?>" <?php echo $isPrimary ? 'checked' : ''; ?>>

                        <p><?php echo $row['full_name'] ?></p>
                        <p><?php echo '  ' . $row['street_address'] . ', ' . $row['city'] . ', ' . $row['state'] . ', ' . $row['postal'] . ', ' . $row['country']; ?></p>
                        <p><?php echo 'Phone: ' . $row['phone']; ?></p>
                    </div>
                    <h3 id="alt">Alternative Addresses</h3>
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
                            <select id="placesSelect1" name="alt-state" class="In1">
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
                    <button id="continue">Continue</button>
                    <button id="b" style="display:none;">back</button>
                </div>
           
    <div class="section" id="order">
        <h2>Order Summary</h2>
        <div id="order-summary">
    <table >
        <tr id="append">
            <td rowspan="3"><img src="your_product_image_url" alt="Product Image" style="width:150px;height:150px;"></td>
            <td><h3>Your Product Name</h3></td>
        
        </tr>
    
        <tr>
            <td>
                <button id="dec" class="quantity-adjust">-</button>
                <input id="in" type="number" class="quantity-input" value="1" min="1">
                <button id="inc" class="quantity-adjust">+</button>
            </td>
            <td id="total"></td>
        </tr>
    </table>
</div>
<button id="continue1">Pay Now</button>
    </div>

    
</div>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <!-- Include any scripts at the end of the body -->
    <script >
    
        document.getElementById('order-summary').style.display='none'
        document.getElementById('continue1').style.display='none'
document.getElementById('continue').addEventListener('click', handleContinueButtonClick);
function handleContinueButtonClick() {
var selectedAddress = document.querySelector('input[name="selected-address"]:checked');

      document.getElementById('alternative-addresses').style.display="none"
                document.getElementById('alt').style.display="none"
                document.getElementById('delivery-address').style.display="none"
     
               document.querySelector('.edit-address').style.display="none";
document.getElementById('b').style.display="block";




const buttonToCut = document.getElementById('continue');
const sourceDiv = buttonToCut.parentElement;
const destinationDiv = document.getElementById('order');
sourceDiv.removeChild(buttonToCut);
destinationDiv.appendChild(buttonToCut);

document.getElementById('order-summary').style.display='block'
var productName = this.dataset.productName;
        var selectedSellerId = this.dataset.selectedSellerId;
        var selectedProductId = this.dataset.selectedProductId;
var orderSummaryContainer = document.getElementById('order-summary');
    if (this.closest('#order')) {
        
        // Retrieve data attributes from the continueButton
        var productPrice=this.dataset.productPrice
        var productImg=this.dataset.productImg
         productName = this.dataset.productName;
         selectedSellerId = this.dataset.selectedSellerId;
         selectedProductId = this.dataset.selectedProductId;
        var district = this.dataset.district;
        var place = this.dataset.place;
        var msg = this.dataset.msg;
        updatePrice(productPrice,1);
    // Assuming you have another button element with the id 'anotherButton'
var anotherButton = document.getElementById('continue1');
anotherButton.dataset.productPrice = productPrice;
// Append the data to the dataset of the button
anotherButton.dataset.productImg = productImg;
anotherButton.dataset.productName = productName;
anotherButton.dataset.selectedSellerId = selectedSellerId;
anotherButton.dataset.selectedProductId = selectedProductId;
anotherButton.dataset.district = district;
anotherButton.dataset.place = place;
anotherButton.dataset.msg = msg;


fetchMaxQuantity(selectedProductId)

    
        var productDiv = document.getElementById('append');

// Set the HTML content for the product div
productDiv.innerHTML = ' <td rowspan="3"><img src="../products-images/' + productImg + '" alt="Product Image" style="width:150px;height:150px;"></td>' +
                      '<td><h3>' + productName + '</h3></td> <tr><td id="change1"> ₹'+productPrice+'</td></tr>';
                      









  




    }

    document.getElementById('continue').style.display="none"   
   document.getElementById('continue1').style.display="block"   
   document.getElementById("loadingBar").style.display="block"
   document.getElementById("loadingBar").style.width = "100%";
                    setTimeout(function() {
                        document.getElementById("loadingBar").style.display = "none";
                   }, 500);

    // Perform further actions after loading animation is displayed
}
document.getElementById('b').addEventListener('click',function(){
    document.getElementById("loadingBar").style.display="block"
    document.getElementById("loadingBar").style.width = "25%";
    document.getElementById('delivery-address').style.display="flex"
    document.getElementById('alt').style.display="flex"
    document.getElementById('in').value = 1;
    document.getElementById('alternative-addresses').style.display="block"
    document.querySelector('.edit-address').style.display="block";
    document.getElementById('b').style.display="none";
    const buttonToCut = document.getElementById('continue');
const sourceDiv = buttonToCut.parentElement;
const destinationDiv = document.getElementById('section');
sourceDiv.removeChild(buttonToCut);
destinationDiv.appendChild(buttonToCut);
document.getElementById('order-summary').style.display='none'
document.getElementById('continue1').style.display="none"
document.getElementById('continue').style.display="block"
document.getElementById("loadingBar").style.width = "100%";      
setTimeout(function() {
                        document.getElementById("loadingBar").style.display = "none";
                   }, 500);
})




function fetchMaxQuantity(productId) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'last.php');
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    document.getElementById("loadingBar").style.display="block"
    document.getElementById("loadingBar").style.width = "25%";


    xhr.onreadystatechange = function() {
        document.getElementById("loadingBar").style.width = "50%";
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            document.getElementById("loadingBar").style.width = "75%";
            var anotherButton = document.getElementById('continue1');
anotherButton.dataset.productStock = response.stock;
            if(response.stock=='0' ||response.stock=="")
            {
                document.getElementById('order-summary').style.opacity=.5;
                document.getElementById('continue1').style.opacity=.5;
                document.getElementById('continue1').innerHTML="OUT OF STOCK"
                document.getElementById('continue1').disabled=true;
                document.getElementById('inc').disabled=true;
                document.getElementById('dec').disabled=true;
                document.getElementById('in').disabled=true;
                document.getElementById("loadingBar").style.width = "100%";
                    setTimeout(function() {
                        document.getElementById("loadingBar").style.display = "none";
                   }, 500);
// Append the data to the dataset of the button
            }
            else {
                // Product is in stock
                document.getElementById('order-summary').style.opacity = 1;
                document.getElementById('continue1').style.opacity = 1;
                document.getElementById('continue1').innerHTML = "Pay-Now";
                document.getElementById('continue1').disabled = false;
                document.getElementById('inc').disabled = false;
                document.getElementById('dec').disabled = false;
                document.getElementById('in').disabled = false;
                document.getElementById("loadingBar").style.width = "100%";
                    setTimeout(function() {
                        document.getElementById("loadingBar").style.display = "none";
                   }, 500);
            }
            // Update the input value with the fetched maximum quantity
         
        }
    };
    // Send the product ID as data in the POST request
    xhr.send('product_id=' + encodeURIComponent(productId));
}

// Event listener for the decrement button
document.getElementById('dec').addEventListener('click', function() {
    var inputValue = parseInt(document.getElementById('in').value);
    if (inputValue > 1) {
        document.getElementById("loadingBar").style.width = "100%";
                    setTimeout(function() {
                        document.getElementById("loadingBar").style.display = "none";
                   }, 500);
        var newInputValue = inputValue - 1; // Decrement the value by 1
        document.getElementById('in').value = newInputValue;
        
        // Fetch maximum quantity when value is decremented
        var continueButton = document.getElementById('continue1');
        var productId = continueButton.dataset.selectedProductId;
        fetchMaxQuantity(productId);
        
        // Update the total price
        var productPrice = parseFloat(continueButton.dataset.productPrice);
        updatePrice(productPrice, newInputValue);
    }
});

function updatePrice(price, quantity) {
    var totalPrice = price * quantity;
    document.getElementById('total').innerHTML = "Total: ₹" + totalPrice.toFixed(2);
}


// Event listener for the increment button
// Event listener for the increment button
document.getElementById('inc').addEventListener('click', function() {
    var inputValue = parseInt(document.getElementById('in').value);
    var continueButton = document.getElementById('continue1');
    var maxQuantity = parseInt(continueButton.dataset.productStock);
  

    if (maxQuantity > inputValue) {
        // Increment the value by 1 only if it's less than the maximum quantity
        var newInputValue = inputValue + 1;
        document.getElementById('in').value = newInputValue;
        document.getElementById("loadingBar").style.display="block"
        document.getElementById("loadingBar").style.width = "100%";
                    setTimeout(function() {
                        document.getElementById("loadingBar").style.display = "none";
                   }, 100);

        // Update the total price
        var productPrice = parseFloat(continueButton.dataset.productPrice);
        updatePrice(productPrice, newInputValue);
    } else {
        // Set the input value to the maximum quantity
        document.getElementById('in').value = maxQuantity;

        // Update the total price
        var productPrice = parseFloat(continueButton.dataset.productPrice);
        updatePrice(productPrice, maxQuantity);
    }

});


// Event listener for manual input
document.getElementById('in').addEventListener('input', function() {
    var inputValue = parseInt(this.value);
    var continueButton = document.getElementById('continue1');
    var maxQuantity = parseInt(continueButton.dataset.productStock);
    
    // Prevent negative numbers, set to 1 if entered value is less than 1
    if (inputValue < 1) {
        this.value = 1;
    } else if (inputValue > maxQuantity) {
        // If the entered value exceeds the maximum quantity, set it to the maximum quantity
        this.value = maxQuantity;
    }

    var newInputValue = this.value;

    // Fetch maximum quantity when value is incremented
    var productId = continueButton.dataset.selectedProductId;
    fetchMaxQuantity(productId);

    // Update the total price
    var productPrice = parseFloat(continueButton.dataset.productPrice);
    updatePrice(productPrice, newInputValue);
});



document.getElementById('continue1').addEventListener('click',function(){
    var productId= this.dataset.selectedProductId
    fetchMaxQuantity(productId)

 var q=document.getElementById('in').value
var price=this.dataset.productPrice
var selectedSellerId=this.dataset.selectedSellerId;
var total=q*price;
initializeRazorpay(total, productId,q,selectedSellerId);
    
})  

function initializeRazorpay(amount, productId,q,selectedSellerId) {
    alert('hi');
    var options = {
        "key": "rzp_test_ABwuiljxkPyEfS",
        "amount": amount * 100,
        "currency": "INR",
        "name": "Your Company Name",
        "description": "Payment for your order",
        "image": "https://example.com/your_logo.png",
        "handler": function(response) {
            var paymentID = response.razorpay_payment_id;
            // Send payment details to server using XMLHttpRequest
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_payment.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data=JSON.parse(xhr.responseText);
                        Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Your order has been placed!',
                confirmButtonText: 'OK'
            }).then((result) => {
                // Redirect to a new page after clicking "OK"
                if (result.isConfirmed) {
                    window.location.href = 'order_purchase.php';
                }
            });
        
                        
                      
                        // Handle success response from server
                    } else {
                        console.error('Error:', xhr.statusText);
                        // Handle error response from server
                    }
                }
            };
            var data = JSON.stringify({
                productId: productId,
                paymentID: paymentID,
                amount: amount, 
                q :q,
                selectedSellerId:selectedSellerId
            });
            xhr.send(data);
        },
        "prefill": {
            "email": "<?php echo $_SESSION['username']; ?>",
            "contact": "<?php echo $phone;?>"
        },
        "theme": {
            "color": "#F37254"
        }
    };
    var rzp = new Razorpay(options);
    rzp.open();
}



// Add click event listener to each button




function handleRadioChange(event) {
    var selectedAddress = event.target.value;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'recheck.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    document.getElementById("loadingBar").style.width = "50%";
    

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

                    var continueButton = document.getElementById('continue');
                    continueButton.dataset.productPrice = response.price;
                    continueButton.dataset.productImg = response.product_img;
                    continueButton.dataset.productName = response.product_name;
                    continueButton.dataset.selectedSellerId = response.selected_seller_id;
                    continueButton.dataset.selectedProductId = response.selected_product_id;
                    continueButton.dataset.district = response.district;
                    continueButton.dataset.place = response.place;
                    continueButton.dataset.msg = response.msg
                    continueButton.disabled=false;
                    continueButton.style.opacity=1;
                    // Update the Buy Now button with the details
                    document.getElementById('continue').innerHTML = "Continue with " + response.product_name
                    document.getElementById("loadingBar").style.width = "100%";
                    setTimeout(function() {
                        document.getElementById("loadingBar").style.display = "none";
                   }, 500);

                } else {
                    // Clear all existing green ticks and red crosses
                    var allRadios = document.querySelectorAll('input[type="radio"]');
                    allRadios.forEach(function(radio) {
                        radio.classList.remove('green-tick');
                        radio.classList.remove('red-cross');
                        radio.style.opacity = .5;
                    });
                    event.target.classList.add('red-cross');
                    // Update the Buy Now button with the "No suitable seller" message
                    document.getElementById('continue').innerHTML = "OUT Stock For This Location.";
                    var continueButton = document.getElementById('continue');
                    continueButton.style.opacity=.8;
                    continueButton.disabled=true;
                  
                }
            } else {
                // Error occurred while processing the request
                console.error('Error: ' + xhr.responseText);
            }
        }
    };
    xhr.send('product_id=<?php echo $selectedProductId ?>&ad=' + encodeURIComponent(selectedAddress) + '&action=checkseller&pname=<?php echo $productName ?>&psid=<?php echo $selectedSellerId ?>&spid=<?php echo $selectedProductId ?>&dis=<?php echo $district ?>&pl=<?php echo $place ?>');
   
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




function updatePlaceInput() {
        var selectedPlace = document.getElementById("placesSelect").value;
        document.getElementById("state").value = selectedPlace;
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
            document.getElementById("loadingBar").style.display="block"
        document.getElementById("loadingBar").style.width = "25%";

            xhr.onreadystatechange = function() {
                document.getElementById("loadingBar").style.width = "40%";
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
                        document.getElementById("loadingBar").style.width = "100%";
                        setTimeout(function() {
                        document.getElementById("loadingBar").style.display = "none";
                   }, 500);
                    } else {
                        console.error('Request failed with status', xhr.status);
                    }
                }
                
            };


            var data = 'addressId=' + encodeURIComponent(addressId);


            xhr.send(data);
        }




        editAddressForm.addEventListener('submit', function(event) {
                      
            document.getElementById("loadingBar").style.display="block"
           

            // Here you can add code to handle form submission, such as AJAX request to update the address in the database
            editAddressForm.style.display = 'none'; // Hide the edit address form after submission
            document.body.classList.remove('dim-background'); // Remove dim background effect
            document.getElementById("loadingBar").style.width = "100%";
                        setTimeout(function() {
                        document.getElementById("loadingBar").style.display = "none";
                   }, 500);
        });

        cancelEditButton.addEventListener('click', function() {
            document.getElementById("loadingBar").style.display="block"
            editAddressForm.style.display = 'none'; // Hide the edit address form when cancel button is clicked
            document.body.classList.remove('dim-background'); // Remove dim background effect
            document.getElementById("loadingBar").style.width = "100%";
                        setTimeout(function() {
                        document.getElementById("loadingBar").style.display = "none";
                   }, 500);
        });

        const addAltAddressButton = document.getElementById('add-alternative-address');
        const altAddressForm = document.getElementById('alternative-address-form');
        const cancelAltButton = document.querySelector('.cancel-alt');

        addAltAddressButton.addEventListener('click', function() {
            document.getElementById("loadingBar").style.display="block"
            altAddressForm.style.display = 'block'; // Show the alternative address form
            document.body.classList.add('dim-background'); // Add dim background effect
            document.getElementById("loadingBar").style.width = "100%";
                        setTimeout(function() {
                        document.getElementById("loadingBar").style.display = "none";
                   }, 500);
        });

        altAddressForm.addEventListener('submit', function(event) {
            // Prevent form submission
            document.getElementById("loadingBar").style.display="block"

       


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
            document.getElementById("loadingBar").style.width = "100%";
                        setTimeout(function() {
                        document.getElementById("loadingBar").style.display = "none";
                   }, 500);

        });

        cancelAltButton.addEventListener('click', function() {
            document.getElementById("loadingBar").style.display="block"
            altAddressForm.style.display = 'none'; // Hide the alternative address form when cancel button is clicked
            document.body.classList.remove('dim-background'); // Remove dim background effect
            document.getElementById("loadingBar").style.width = "100%";
                        setTimeout(function() {
                        document.getElementById("loadingBar").style.display = "none";
                   }, 500);

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
        document.getElementById("loadingBar").style.display="block"
        document.getElementById("loadingBar").style.width = "20%";
    
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '../dis.json', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                document.getElementById("loadingBar").style.width = "40%";
                if (xhr.status === 200) {
                    document.getElementById("loadingBar").style.width = "60%";
                    const data = JSON.parse(xhr.responseText);
                    const districtData = data.find(item => item.District === selectedDistrict);
                    if (districtData) {
                        document.getElementById("loadingBar").style.width = "80%";
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
                    document.getElementById("loadingBar").style.width = "100%";
                    setTimeout(function() {
                        document.getElementById("loadingBar").style.display = "none";
                   }, 500);
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
</body>
</html>
