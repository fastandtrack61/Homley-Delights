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
$phone;
$sql="SELECT * FROM `tbl_registration` WHERE regid='$userid' ";
if($result=$conn->query($sql))
{
    $row=$result->fetch_assoc();
    $phone = $row['phone'];
    $fullname=$row['full_name'];

}

function isPaymentCompleted($orderID)
{
    global $conn;
    $sql = "SELECT * FROM tbl_transactions WHERE order_id = $orderID AND payment_status = 'completed'";
    $result = $conn->query($sql);
    return $result->num_rows > 0;
}
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
   /* Set the maximum width of the container */
    margin: 5px auto; /* Center the container horizontally */
    padding: 20px; /* Add some padding around the content */
    background-color: #f9f9f9; /* Set a background color */
    border: 1px solid #ccc; /* Add a border */
    border-radius: 5px; /* Add border radius for rounded corners */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add a box shadow for depth */
    height: 33rem;
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
    display: flex;
    flex-wrap: wrap; /* Allow items to wrap to the next line */

}

.product-preview {
    flex: 0 0 calc(100% - 20px); /* Adjust the width based on your design */
    margin: 10px;
}

.content-section:hover {
    transform: scale(1.05); /* Scale up on hover */
}


div.content
{
    height: 100%;
    padding: 0px;
}

#dt{
    flex: 0 0 calc(100% - 20px); 
    background-color: #007bff;
}
.item,.items {
            width: 60%; /* Adjust width as needed */
            display: flex; /* Use flexbox for layout */
            margin: 0 auto 10px; /* Add margin for spacing between items */
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
            height: fit-content;
        }
        
        .item img,.items img {
            width: 40%; /* Set width for the image */
            border-radius: 10px 0 0 10px; /* Add border radius only to the left side */
        }
        
        .item-content {
    padding: 10px;
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
        
.status-accepted
{
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
}
.status-pending
{
    background-color: red;
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
}/* CSS styles for the progress bar */
/* CSS styles for the progress tracker */
.progress-tracker {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
}

.point {
    width: 20px;
    height: 20px;
    background-color: #ddd;
    border-radius: 50%;
    text-align: center;
    line-height: 20px;
    font-weight: bold;
    color: #333;
}

.point.active {
    background-color: #4caf50; /* Green color for active points */
    color: white;
}

.bar {
    flex-grow: 1;
    height: 3px;
    background-color: #ddd;
    margin: auto;
}

/* Fill color for bars and points up to the third point when status is 3 */
.bar.active,
.point.active {
    background-color: #4caf50; /* Green color for active bars and points */
}

#mo{
    justify-content: space-between;
}@keyframes fillProgressBar {
    from {
        background-color: #ddd; /* Start color */
    }
    to {
        background-color: #4caf50; /* End color */
    }
}

/* Apply animation to active bars */
.bar.active {
    animation: fillProgressBar 2s ease forwards; /* 2s duration, ease timing function, fill forwards */
}
.pay-now-btn
{
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
}
 #paid
 {
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
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<body>
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
   
    <div class="container">
    <?php       
$sql = "SELECT *
        FROM tbl_orders AS o
        JOIN tbl_pre_orders AS pre ON o.fk_preid = pre.id
        JOIN tbl_products AS p ON pre.product_id = p.product_id
        WHERE pre.user_id = $userid order by pre.created_at desc" ;

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
?>
        <div class="content">
<?php
        while ($row = $result->fetch_assoc()) {
            // Get the status of the order
            $status = $row['order_status'];
            // Define the status labels
            $order_id=$row['order_id'];
            $quantity     =$row['quantity'];
            $product_name  =$row['product_name'];
            $price        =$row['price'];
            $statusLabels = array("Order Accepted", "Order Processed", "Order Shipping", "Order Delivered");
            // Define the status descriptions
            $statusDescriptions = array("Your order has been accepted.", "Your order is being processed.", "Your order is being shipped.", "Your order has been delivered.");
?>
            <div class="item" data-product-id="<?php echo $row['product_id']; ?>">
                <img src="../products-images/<?php echo $row['photo_path']; ?>" alt="Product 1">
                <div class="item-content">
                    <h4><?php echo $row['product_name']; ?></h4>
                    <p>Quantity: <?php echo $row['quantity']; ?></p>
                   <div class="progress-tracker">
    <?php
    // Define status labels for each point
    $statusLabels = array("Order Accepted", "Order Processed", "Order Shipping", "Order Delivered");
    
    // Loop through each status point
    for ($i = 0; $i < 4; $i++) {
        // Determine if the current status point is active
        $activeClass = ($status > $i) ? 'active' : '';
        // Output the status point with label
        echo '<div class="point ' . $activeClass . '" title="' . $statusLabels[$i] . '"></div>';
        
        // Output the progress bar
        if ($i < 3) {
            echo '<div class="bar ' . $activeClass . '"></div>';
        }
    }
    ?><br>

</div>
<div id="mo" style="display:flex;"><span class="label" style="display:block;"> Order Accepted </span><span class="label" style="display:block;"> Order Processed</span><span class="label" style="display:block;"> Order Shipping </span><span class="label" style="display:block;"> Order Delivered</span>

</div><p><?php echo $statusLabels[$status - 1]; ?></p>
<p>Delivery Date: <?php echo date('F j, Y', strtotime($row['delivery_date'])); ?></p>
<p>Delivery Time: <?php echo date('h:i A', strtotime($row['delivery_time'])); ?></p>
<p>Created At: <?php echo date('F j, Y h:i A', strtotime($row['created_at'])); ?></p>

<?php
                            // Check if payment is completed for this order
                            $paymentCompleted = isPaymentCompleted($row['order_id']);
                            if (!$paymentCompleted) {
                                // If payment is not completed, display Pay Now button
                            ?>
                            
                                <button class="pay-now-btn" data-price="<?php echo $row['price']; ?>" data-quantity="<?php echo $row['quantity']; ?>" data-orderid="<?php echo $row['order_id']; ?>">Pay Now</button>
                            <?php }
                            elseif($paymentCompleted)
                            {?>
                             <button id="paid">Paid</button>
                            <?php
                               
                            }
                            ?>
                                                <p id="show"></p>

                                                <?php
                               $transactionSql = "SELECT * FROM tbl_transactions WHERE order_id = '$order_id'";
                               $transactionResult = $conn->query($transactionSql);
               
                               // If there are transactions, display the download button with the amount
                               if ($transactionResult && $transactionResult->num_rows > 0) {
                                   $transactionRow = $transactionResult->fetch_assoc();
                                   $amount = $transactionRow['amount'];
                                   ?>
                                   <button class="downloadBtn"  data-productamount="<?php echo $amount; ?> "data-quantity="<?php echo $quantity ?>" data-productname="<?php echo $product_name; ?>" data-price="<?php echo $price;?>">Download Invoice</button>
                                   <?php
                               }?>

                    <p><b style="font-size:25px;"></b></p>
                </div>
            </div>
<?php
        }
?>
        </div>
<?php
    } else {
        echo "No items found.";
    }
}
?>

</div>

</body>
<script src="../jspdf.min.js"></script>

<script>

 // Add event listener to each download button
 document.addEventListener("DOMContentLoaded", function() {
    const downloadBtns = document.querySelectorAll('.downloadBtn');
    downloadBtns.forEach(function(downloadBtn) {
        downloadBtn.addEventListener('click', function() {
            // Get the data attributes from the button
            const productAmount = parseFloat(this.getAttribute('data-productamount'));
            const quantity = parseInt(this.getAttribute('data-quantity'));
            const productName = this.getAttribute('data-productname');
            const price = parseFloat(this.getAttribute('data-price'));
const to=price*quantity;
            // Create a new jsPDF instance
            const doc = new jsPDF();

            // Set font styles
            doc.setFont("helvetica");

            // Define colors
            const primaryColor = "#007bff"; // Primary color (blue)
            const secondaryColor = "#343a40"; // Secondary color (dark gray)
            const lightColor = "#f8f9fa"; // Light color (light gray)

            // Add company name
            const companyName = 'Homley Delights';
            doc.setTextColor(primaryColor);
            doc.setFontSize(30);
            doc.text(companyName, 20, 30);

            // Add customer details
            doc.setFontSize(14);
            doc.setTextColor(secondaryColor);
            doc.text('Customer Name:', 20, 50);
            doc.setTextColor(primaryColor);
            doc.text('<?php echo $fullname; ?>', 70, 50); // Ensure $fullname is properly formatted as a string

            doc.setTextColor(secondaryColor);
            doc.text('Phone Number:', 20, 65);
            doc.setTextColor(primaryColor);
            doc.text('<?php echo $phone; ?>', 70, 65); // Ensure $phone is properly formatted as a string

            // Add invoice title
            doc.setFontSize(24);
            doc.setTextColor(secondaryColor);
            doc.text('Invoice', 20, 90);

            // Add horizontal line
            doc.setLineWidth(0.5);
            doc.setDrawColor(secondaryColor);
            doc.line(20, 95, 190, 95);

            // Add order details
            doc.setFontSize(14);
            doc.setTextColor(secondaryColor);
            doc.text('Product Name:', 20, 110);
            doc.setTextColor(primaryColor);
            doc.text(productName, 70, 110);

            doc.setTextColor(secondaryColor);
            doc.text('Quantity:', 20, 125);
            doc.setTextColor(primaryColor);
            doc.text(quantity.toString(), 70, 125);

            doc.setTextColor(secondaryColor);
            doc.text('Price per unit:', 20, 140);
            doc.setTextColor(primaryColor);
            doc.text('$' + price.toFixed(2), 70, 140);

            doc.setTextColor(secondaryColor);
            doc.text('Total Amount:', 20, 155);
            doc.setTextColor(primaryColor);
            doc.text('$' + to.toFixed(2), 70, 155);

            // Add signature section
            doc.setTextColor(secondaryColor);
            doc.setFontSize(18);
            doc.text('Authorized Signature', 20, 190);

            // Save the PDF
            doc.save('invoice.pdf');
        });
    });
});


 function initializeRazorpay(amount, orderID) {
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
            xhr.open('POST', 'update_payment_status.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        alert('Payment successful: ' + paymentID);
                        location.reload();
                        // Handle success response from server
                    } else {
                        console.error('Error:', xhr.statusText);
                        // Handle error response from server
                    }
                }
            };
            var data = JSON.stringify({
                orderID: orderID,
                paymentID: paymentID,
                amount: amount 
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

  const payNowButtons = document.querySelectorAll('.pay-now-btn');

// Add click event listener to each button

document.addEventListener("DOMContentLoaded", function() {
    const payNowButtons = document.querySelectorAll('.pay-now-btn');

    payNowButtons.forEach(button => {
        button.addEventListener('click', function() {
            const price = parseFloat(button.getAttribute('data-price'));
            const quantity = parseInt(button.getAttribute('data-quantity'));
            const orderID = parseInt(button.getAttribute('data-orderid')); // Retrieve the order ID

            const totalAmount = price * quantity;

            initializeRazorpay(totalAmount, orderID); // Pass the order ID to the payment processing function
        });
    });
});



</script>

</html>

