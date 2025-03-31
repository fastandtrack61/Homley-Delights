<?php
session_start();
require('../Connect_database.php');
$userid = $_SESSION['userid'];

$phone;
$sql="SELECT * FROM `tbl_registration` WHERE regid='$userid' ";
if($result=$conn->query($sql))
{
    $row=$result->fetch_assoc();
    $phone=$row['phone'];

}
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<link rel="stylesheet" href="../style.css">
<style>
  #resizable-container {
            background-color: white;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow-y: auto;
            padding: 20px;
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
        .item-container {
            
            text-align: center; /* Center-align items */
            padding: 20px; /* Add padding for spacing */
        }
        
        .item,.items {
            width: 60%; /* Adjust width as needed */
            display: flex; /* Use flexbox for layout */
            margin: 0 auto 10px; /* Add margin for spacing between items */
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
        }
        
        .item img,.items img {
            width: 40%; /* Set width for the image */
            border-radius: 10px 0 0 10px; /* Add border radius only to the left side */
        }
        
        .item-content {
    padding: 16px;
    text-align: left;
    flex-grow: 1; /* Allow content to grow to fill remaining space */
    position: relative; /* Position relative for absolute positioning */
}
        
        .item h4,.items h4 {
            margin-top: 10px;
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .item p,.items p {
            font-size: 14px;
            margin-bottom: 10px;
        }
        
    
        .quantity {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .quantity button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 5px;
            padding: 5px;
            width: 30px;
            font-size: 20px;
            padding:5px;
        }
        
        .quantity button:hover {
            background-color: #45a049;
        }
        .quantity-input
        {
            text-align: center;
            width: 10%;
            border: 2px solid #45a049;
            border-radius: 10px;
            resize: none;
            outline: none;
        }


        .quantity-input::-webkit-outer-spin-button,
        .quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
   
}
.remove-from-cart,
.remove-from-save
{
  margin-left: 1rem;
  margin-top: 2px;
  margin-bottom:1rem;
  border-color: #ddd;
  cursor: pointer;
  border-radius: 10px;
  width:8rem;
  height: 2rem;

}
.save-from-cart,
.move-to-cart
{
    
  margin-top: 2px;
  margin-bottom:1rem;
  border-color: #ddd;
  cursor: pointer;
  border-radius: 10px;
  width:8rem; 
  height: 2rem;
}


button:disabled {
    opacity: 0.5; /* Reduce opacity */
    cursor: not-allowed; /* Change cursor to indicate not clickable */
}

/* Style for disabled input fields */
input:disabled {
    opacity: 0.5; /* Reduce opacity */
    cursor: not-allowed; /* Change cursor to indicate not clickable */
}
.total-price-container {
    position: fixed;
    top: 7rem;
    right: 1rem;
    background-color: #f9f9f9;
    padding: 20px;
    width: 16rem;
    height: 25rem;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px; /* Rounded corners */
}

.order-summary {
    text-align: left;
}

.summary-item {
    margin-bottom: 10px;
    display: flex; /* Add flex display */
    justify-content: space-between; /* Space items evenly */
}

.summary-item span {
    flex-grow: 1; /* Allow text to take available space */
    text-align: left; /* Align text to the left */
}

.summary-label {
    flex-basis: 70%; /* Adjust the width of the label */
    text-align: left; /* Align label to the left */
}

.summary-item.total {
    font-size: 18px;
    color: #ff4500; /* Change color for total */
    font-weight: bold;
}

/* Additional style for total price */
.item-total-price {
    text-align: right; /* Align total price to the right */
    flex-basis: 30%; /* Adjust the width of the total price */
}


/* Hover effect */
.total-price-container:hover {
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    transform: scale(1.02);
    transition: transform 0.2s ease;
}
.subtotal {
    position: absolute;
    top: 12rem;
    right: 10px; /* Adjust right position as needed */
}


#inp:disabled
{
    opacity: 1;
}
#bg
{
    background-color: #e0e0e0;
}







#bottom-container {
    
    position: sticky;
    bottom: 0;
    left: 321px;
    width:57.2%;
    background-color: white;
    padding: 20px;
    box-shadow: 0px -2px 5px rgba(0, 0, 0, 0.1);
    z-index: 1000; 
 
}

#place-order-btn {

    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

#place-order-btn:hover {
    background-color: #45a049;
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


.out-of-stock-banner {
  width: fit-content;
    top: 0;
    left: 0;
    background-color: rgba(255, 0, 0, 0.8); /* Red color with some transparency */
    color: white;
    padding: 5px 10px;
    font-weight: bold;
    font-size: 18px;
    border-radius: 5px;
}
</style>
<script src="../sweetalert.js"></script>
</head>
<body id="bg">
    
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
                        <li><a href="#">Breakfast Items</a></li>
                        <li><a href="#">HomeHarvest Lunch</a></li>
                        <li><a href="#">Evening Eatables</a></li>
                        <li><a href="#">Drop menu 4</a></li>
                    </ul>
                </li>
                <li><a href="deliveryperson.php">For Delivery Partners</a></li>

                
                
                <li><a href="cart.php"><img src="../img/cart.png" alt="" id="userimg">
                <?php 
                $sql = "SELECT COUNT(*) AS cart_count FROM tbl_cart where  fk_regid='$userid'";
                if($result = $conn->query($sql))
                {
                    $row = $result->fetch_assoc();
                    ?>
                                    <span id="cart-count"><sup id="count"><?php echo $row['cart_count'];?></sup></span>

                    <?php
                    
                }
                else
                {
                  ?>
                                                      <span id="cart-count"><sup id="count"><?php echo 0?></sup></span>

                  <?php
                }
                ?>
              </a></li>
                 <!-- Cart count display -->


            </ul>
            <label for="menu-btn" class="btn menu-btn"><i class="fas fa-bars"></i></label>
        </div>
    </nav>


    <div class="item-container">
        <h1>Your Cart Items</h1>
        <p id="show"></p>
  <?php
         $sql="SELECT * from tbl_cart as c  join tbl_products as p on c.product_id=p.product_id  where  c.fk_regid='$userid'";
         if($result=$conn->query($sql))
         {
            if($result->num_rows>0)
            {
                while($row=$result->fetch_assoc())
                {
                    ?>
                    
                    <div class="item" data-product-id="<?php echo $row['product_id']; ?>">
                    <?php if($row['stock']==0){?>

            <img src="../products-images/<?php echo $row['photo_path']?>" alt="Product 1" style="opacity:0.5; ">
            <?php } else{?>
                <img src="../products-images/<?php echo $row['photo_path']?>" alt="Product 1">

                <?php } ?>
            <div class="item-content">
                <h4><?php echo $row['product_name'] ?></h4>
                <?php if($row['stock']==0){?>
                <div class="out-of-stock-banner">Out of Stock</div>
                <h5 style="color:red;">*Remove item to Place other Orders</h5>
                <?php } ?>
                <?php if($row['stock']!=0){?>
                <p><b style="font-size:25px;"> <?php echo '&#8377;' . $row['price']; ?></b></p>
            
                <div class="quantity">

                    <button class="decrement"  >-</button>
                    <input id="inp" disabled  type="number" value="<?php echo $row['quantity']?>" class="quantity-input" max="<?php echo $row['stock']  ?>" >
                    <button  class="increment">+</button>
                </div>
               <?php } ?>
                <button class="remove-from-cart" data-product-id="<?php echo $row['product_id']; ?>">Remove</button>
                <?php if($row['stock']!=0){?>
                <div class="subtotal">
        <span>Subtotal: </span> <!-- Subtotal text -->
        ₹<span class="item-subtotal"><?php echo ($row['price'] * $row['quantity']); ?></span>
    </div>  <?php } ?>
            </div>
        </div>
                    
                    <?php
                }
            }
          
                else {
                    // Display message when cart is empty
                    echo "<p id='remove'>Your cart is empty</p>";
                }
            
         }

  ?>
    <div id="bottom-container">
    <button id="place-order-btn" class="place-order-btn">Place Order</button>
</div>
 <div class="total-price-container">
    <h2>Price Details</h2>
<hr id="cl">
    <div class="order-summary">
        <div class="summary-item">
        <span class="summary-label" id="price-label">Price (0 Item)</span> <span id="subtotal">₹0.00</span>

        </div>
        <div class="summary-item">
            <span class="summary-label">Tax (5%) </span> <span id="tax">₹0.00</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Delivery Charges</span> <span id="shipping">₹0.00</span>
        </div>
        <hr id="cl">
        <div class="summary-item total">
            <span class="summary-label">Grand Total:</span> <span id="grand-total">₹0.00</span>
        </div>
        <hr id="cl">
    </div>
</div>


       

        <!-- Add more items for additional products -->
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
               
                    <div class="blur-background"></div>
                  
                    

                   
                </div>

    </span></p>

    <p>

   
</p>
<input type="hidden" id="productName" value="<?php echo $product_name; ?>">
<span id="error"></span>
<button id="confirmPreOrder" class="pay-now" >Pay</button>
<input type="text" id="amount">
  </div>


</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>


      <script>





function handleCartCountChange(count) {
    // Display alert with the new count
    if(count == 0) {
        document.getElementById('show').innerHTML = "Your cart is empty";
        document.getElementById('price-label').textContent = `Price (${count} Item)`;
        document.getElementById('bottom-container').style.display = 'none'; // Hide the place order button if cart is empty

    } else {
        document.getElementById('price-label').textContent = `Price (${count} Item)`;
        document.getElementById('bottom-container').style.display = 'block'; // Show the place order button if cart is not empty

    }
    // You can also perform any other actions based on the new count
}

// Call handleCartCountChange when the page loads to initialize the count
handleCartCountChange(parseInt(document.getElementById('count').innerText));
if (parseInt(document.getElementById('count').innerText) > 0) {
    document.getElementById('bottom-container').style.display = 'block';
}
    document.querySelectorAll('.item').forEach(function(item) {









        const quantityInput = item.querySelector('.quantity-input');
        const decrementButton = item.querySelector('.decrement');
        const incrementButton = item.querySelector('.increment');
        const productId = item.dataset.productId; // Get the product ID from data attribute
        const removeFromCartButton = item.querySelector('.remove-from-cart');

        removeFromCartButton.addEventListener('click', function() {
            removeFromCart(productId)
                item.remove();
            });

         

        decrementButton.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            if (value > 1) {
                value--;
                quantityInput.value = value;
                updateQuantity(productId, value);
                updateOrderSummary(); // Call function to update order summary
 // Call function to update quantity
            }
        });

        incrementButton.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            let max = parseInt(quantityInput.getAttribute('max'));
            if (value < max) {
                value++;
                quantityInput.value = value;
                updateQuantity(productId, value); // Call function to update quantity
                updateOrderSummary(); // Call function to update order summary

            }
        });

        quantityInput.addEventListener('input', function() {
    let value = parseInt(quantityInput.value);
    let max = parseInt(quantityInput.getAttribute('max'));
    if (value > max) {
        // Show SweetAlert notification
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Sorry, the quantity you entered exceeds the available stock!',
        });
        // Set the input value to the maximum available stock
        quantityInput.value = max;
    } 
});


function updateOrderSummary() {
    var subtotal = 0;
    document.querySelectorAll('.item').forEach(function(item) {
        var price = parseFloat(item.querySelector('.item-content').querySelector('b').innerText.replace('₹', ''));
        var quantity = parseInt(item.querySelector('.quantity-input').value);
        var itemSubtotal = price * quantity; // Calculate subtotal for the item
        subtotal += itemSubtotal; // Add subtotal to total
        item.querySelector('.item-subtotal').textContent = itemSubtotal.toFixed(2); // Update subtotal in the item
    });

    var count = document.getElementById('count').innerText;
    var tax = 0;
    var shipping = 0;
    var grandTotal = 0;
    handleCartCountChange(parseInt(document.getElementById('count').innerText));

    if (count > 0) {
        tax = 0.05 * subtotal;
        shipping = 10;
        grandTotal = subtotal + tax + shipping;
    }

    document.getElementById('subtotal').innerText = '₹' + subtotal.toFixed(2);
    document.getElementById('tax').innerText = '₹' + tax.toFixed(2);
    document.getElementById('shipping').innerText = '₹' + shipping.toFixed(2);
    document.getElementById('grand-total').innerText = '₹' + grandTotal.toFixed(2);
    document.getElementById('amount').value=grandTotal.toFixed(2)
}


updateOrderSummary()
// Call updateOrderSummary function whenever quantity of an item changes
document.querySelectorAll('.quantity-input').forEach(function(input) {
    input.addEventListener('input', updateOrderSummary);
});




        function updateQuantity(productId, quantity) {
            // Send AJAX request to update quantity in the database
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == XMLHttpRequest.DONE) {
                    if (xhr.status == 200) {
                        // Handle successful response
                  var data=JSON.parse(xhr.responseText)
                  if (data.status === 'success') {
                    // Show SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Quantity Updated Successfully!',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
                  
                    } else {
                        // Handle error
                        console.error('Error: ' + xhr.responseText);
                    }
                }
            };
            xhr.send('product_id=' + encodeURIComponent(productId) + '&quantity=' + encodeURIComponent(quantity) +'&from=update');
        }


        function removeFromCart(productId) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            if (xhr.status == 200) {
                var data = JSON.parse(xhr.responseText);
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Product Removed from Cart Successfully!',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    document.getElementById('count').innerHTML = data.count;
                    handleCartCountChange(data.count);
                    updateOrderSummary(); // Call the function to update order summary after removing an item
                }
            } else {
                console.error('Error: ' + xhr.responseText);
            }
        }
    };
    xhr.send('product_id=' + encodeURIComponent(productId) +'&from=remove');
}



    });

var modal = document.getElementById('preOrderModal');

// Get the button that opens the modal
var btns = document.querySelectorAll('.place-order-btn');

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
                        updatePlaces2('districtSelect1', 'placesSelect1');

                    



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




     
    


    });
   


    function initializeRazorpay(amount, data) {
    var options = {
        "key": "rzp_test_ABwuiljxkPyEfS",
        "amount": amount * 100,
        "currency": "INR",
        "name": "Your Company Name",
        "description": "Payment for your order",
        "image": "https://example.com/your_logo.png",
        "handler": function(response) {
            var paymentID = response.razorpay_payment_id;
            // Convert amount back to rupees
            // Send payment details, order IDs, and amounts to server using XMLHttpRequest
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_payment1.php', true);
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
                    } else {
                        console.error('Error:', xhr.statusText);
                        // Handle error response from server
                    }
                }
            };
            // Prepare data to send
            var requestData = JSON.stringify({
                paymentID: paymentID,
                orders: data.orders  // Sending the order IDs and amounts
            });
            // Send data
            xhr.send(requestData);
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


// Function to alert product IDs and quantities in the cart
// Function to send product IDs and quantities to PHP via AJAX
// Function to send product details to PHP via AJAX
function sendCartDetailsToPHP() {
    var cartItems = document.querySelectorAll('.item');
    var cartDetails = [];

    // Iterate over each item in the cart
    cartItems.forEach(function(item) {
        var productId = item.dataset.productId;
        var quantity = item.querySelector('.quantity-input').value;
        var price = parseFloat(item.querySelector('.item-content b').innerText.replace('₹', ''));
        var amount = price * parseInt(quantity); // Calculate the amount for each product
        cartDetails.push({ productId: productId, quantity: quantity, amount: amount }); // Push product details to the array
    });

    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();
    var url = 'process_cart.php'; // Replace 'process_cart.php' with the actual URL of your PHP script

    // Set up the request
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    // Define what happens on successful data submission
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Response from PHP script
            alert(xhr.responseText)
            var data= JSON.parse(xhr.responseText);
            var amount=document.getElementById('amount').value
    initializeRazorpay(amount,data)
        } else {
            // Handle error
            console.error('Error:', xhr.statusText);
        }
    };

    // Handle network errors
    xhr.onerror = function() {
        console.error('Network Error');
    };

    // Convert JavaScript object to JSON string and send it
    xhr.send(JSON.stringify(cartDetails));
}


// Add an event listener to the "Pay" button
document.getElementById('confirmPreOrder').addEventListener('click', function() {
    // Call the function to send cart details to PHP
    sendCartDetailsToPHP();
 
    // Additional logic for processing payment can be added here
});

      </script> 
</body>
</html>