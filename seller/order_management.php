<?php
session_start();
require('../Connect_database.php');
$email = $_SESSION['username'];
$userid = $_SESSION['userid'];
if(empty($_SESSION['username']) && empty($_SESSION['userid'])) {
    header('Location:../login.php');
    exit; // Ensure that script execution stops after redirecting
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
<title>Seller Dashboard</title>
<link rel="stylesheet" href="stylesell.css">

<style>

.item,.items {
            width: 90%; /* Adjust width as needed */
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
        .pay-now-btn{
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
</style>
</head>
<body>
<nav>
        <div class="wrapper">
            <div class="logo1" ><a href="#">Homely</a></div>
         
           
            <label for="menu-btn" class="btn menu-btn"><i class="fas fa-bars"></i></label>
        </div>
    </nav>
<div class="sidenav">
    <a href="overview.php" >Overview</a>
    <a href="./notification.php" >Notifications</a>
    <a href="#" onclick="toggleSubmenu('product-management')" class="active">Product Management</a>
    <div class="submenu" id="product-management">
        <a href="order_management.php" >Pre-Book Orders</a>
        <a href="add_product.php">Add Product</a>
        <a href="./normal_orders.php">Orders</a>
        <a href="./schduled.php">Schduled Deliveries</a>
    </div>
    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
</div>

<div style="margin-left: 250px; padding: 20px;">
<div class="container">
        <?php       
$sql="SELECT *
FROM tbl_orders AS o
JOIN tbl_pre_orders AS pre ON o.fk_preid = pre.id
JOIN tbl_products AS p ON pre.product_id = p.product_id

WHERE p.fk_regid = $userid order by created_at desc;
 ";

        if($result = $conn->query($sql)) {
            if($result->num_rows > 0) {
                ?>
                <div class="content">
                    <?php
                    while($row = $result->fetch_assoc()) {
                        ?>
                           <div class="item" data-product-id="<?php echo $row['product_id']; ?>">
            <img src="../products-images/<?php echo $row['photo_path']?>" alt="Product 1">
            <div class="item-content">
                <h4><?php echo $row['product_name'] ?></h4>
                <p>Quantity: <?php echo $row['quantity']; ?></p>
            <?php
            if($row['order_status']==1)
            {
                ?>
                <button id="orderAccepted" class="status-accepted"  disabled>Order Accepted</button>
                <button id="orderProcessing" class="status-processing" value="2" data-order-id="<?php echo $row['order_id']; ?>">Click to Process Order</button>
                            <button id="orderShipped" class="status-shipped" value="3" style="display: none;" data-order-id="<?php echo $row['order_id']; ?>">Click to Ship Order</button>
                            <button id="orderDelivered" class="status-delivered" value="4" style="display: none;" data-order-id="<?php echo $row['order_id']; ?>">Click to Confirm Delivery</button>

                <?php
            }
            elseif($row['order_status']==2)
            {
            ?>
            <button id="orderAccepted" class="status-accepted"  disabled>Order Accepted</button>
                <button id="orderProcessing" class="status-processing" value="2" disabled data-order-id="<?php echo $row['order_id']; ?>">Order Processed</button>
                            <button id="orderShipped" class="status-shipped" value="3"  data-order-id="<?php echo $row['order_id']; ?>">Click to Ship Order</button>
                            <button id="orderDelivered" class="status-delivered" value="4" style="display: none;" data-order-id="<?php echo $row['order_id']; ?>">Click to Confirm Delivery</button>

            
            <?php
            }
            elseif($row['order_status']==3)
            {
                ?>
                <button id="orderAccepted" class="status-accepted"  disabled>Order Accepted</button>
                <button id="orderProcessing" class="status-processing" disabled value="2" data-order-id="<?php echo $row['order_id']; ?>">Order Processed</button>
                            <button id="orderShipped" class="status-shipped" value="3" disabled data-order-id="<?php echo $row['order_id']; ?>">Order Shipped</button>
                            <button id="orderDelivered" class="status-delivered" value="4"  data-order-id="<?php echo $row['order_id']; ?>">Click to Confirm Delivery</button>

                
                
                <?php
            }
            elseif($row['order_status']==4)
            {
                ?>
                
                <button id="orderAccepted" class="status-accepted" disabled >Order Accepted</button>
                <button id="orderProcessing" class="status-processing" disabled value="2" data-order-id="<?php echo $row['order_id']; ?>">Order Processed</button>
                            <button id="orderShipped" class="status-shipped"  disabled value="3"  data-order-id="<?php echo $row['order_id']; ?>">Order Shipped</button>
                            <button id="orderDelivered" class="status-delivered"  disabled  value="4"  data-order-id="<?php echo $row['order_id']; ?>">Order Delivered</button>

                <?php
            }
            ?>
               
                
<p>Delivery Date: <?php echo $row['delivery_date']; ?></p>
<p>Delivery Time: <?php echo date("h:i A", strtotime($row['delivery_time'])); ?></p>
<p>Order Placed: <?php echo date("F j, Y h:i A", strtotime($row['created_at'])); ?></p>
<?php
                            // Check if payment is completed for this order
                            $paymentCompleted = isPaymentCompleted($row['order_id']);
                            if (!$paymentCompleted) {
                                // If payment is not completed, display Pay Now button
                            ?>
                                <button class="pay-now-btn">Not yet Paid</button>
                            <?php }
                                                        elseif($paymentCompleted)
                                                        {?>
                                                         <button id="paid">Paid</button>
                                                        <?php
                                                           
                                                        }
                                                        ?>
                        

                <p id="show"></p>
                <p><b style="font-size:25px;"></b></p>

                
             

         
            </div>
        </div><?php
                        
                    }
                    ?>
                </div>
                <?php
            }
        }
        ?>


    <!-- Details section -->
  
    </div>
</div>

<script>



// JavaScript function to toggle submenu visibility
function toggleSubmenu(submenuId) {
    var submenu = document.getElementById(submenuId);
    if (submenu.style.display === "block") {
        submenu.style.display = "none";
    } else {
        submenu.style.display = "block";
    }
}


const orderProcessingBtns = document.querySelectorAll('.status-processing');
const orderShippedBtns = document.querySelectorAll('.status-shipped');
const orderDeliveredBtns = document.querySelectorAll('.status-delivered');

// Function to update status
function updateStatus(orderId, status) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_del.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // If the status is updated successfully, you can perform additional actions here
                console.log('Status updated successfully');
            }
        }
    };
    xhr.send('orderId=' + orderId + '&status=' + status);
}

// Function to alert order ID
function alertOrderId(orderId) {
    if (orderId) {
        alert('Order ID: ' + orderId);
    }
}

// Add event listeners to the buttons
orderProcessingBtns.forEach(function(button) {
    button.addEventListener('click', function() {
        const orderId = button.dataset.orderId;
        alertOrderId(orderId);

        // Update button text content
        button.textContent = 'Order Processed';
        button.disabled = true;
        // Find the corresponding orderShippedBtn
        const item = button.closest('.item');
        const orderShippedBtn = item.querySelector('.status-shipped');

        // Display the orderShippedBtn
        orderShippedBtn.style.display = 'inline-block';

        // Update status if needed
         updateStatus(orderId, '2');
    });
});

orderShippedBtns.forEach(function(button) {
    button.addEventListener('click', function() {
        const orderId = button.dataset.orderId;
        alertOrderId(orderId);
        button.disabled = true;
        // Update button text content
        button.textContent = 'Order Shipped';

        // Find the corresponding orderDeliveredBtn
        const item = button.closest('.item');
        const orderDeliveredBtn = item.querySelector('.status-delivered');

        // Display the orderDeliveredBtn
        orderDeliveredBtn.style.display = 'inline-block';

        // Update status if needed
         updateStatus(orderId, '3');
    });
});

orderDeliveredBtns.forEach(function(button) {
    button.addEventListener('click', function() {
        const orderId = button.dataset.orderId;
        alertOrderId(orderId);
        button.disabled = true;
        // Update button text content
        button.textContent = 'Order Delivered';

        // Update status if needed
        updateStatus(orderId, '4');
    });
});


</script>

</body>
</html>
