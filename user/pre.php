<?php
session_start();
$userid = $_SESSION['userid'];
require('../Connect_database.php');
$data = array();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize an array to store messages
$messages = array();

// Retrieve the product name from the AJAX request
$product_name = $_POST['productName'];
$pdate=$_POST['orderDate'];
$ptime=$_POST['orderTime'];
$quantity=$_POST['quantity'];
// Sanitize the input to prevent SQL injection
$product_name = mysqli_real_escape_string($conn, $product_name);
$product_id="";
// Retrieve user's state and city
$sql_address = "SELECT state, city,full_name FROM tbl_registration WHERE regid = '$userid'";
$result_address = $conn->query($sql_address);
if ($result_address->num_rows > 0) {
    $row_address = $result_address->fetch_assoc();
    $user_state = $row_address['state'];
    $user_city = $row_address['city'];
    $user_name = $row_address['full_name'];
}
$sql_sellers1 = "SELECT  r.regid AS seller_id,p.product_id,r.full_name, pc.district AS seller_district, pc.place AS seller_place, p.product_name
               FROM tbl_registration r
               JOIN tbl_pincodes pc ON pc.fk_regid = r.regid
               JOIN tbl_products p ON p.fk_regid = r.regid
               WHERE pc.district = '$user_city' 
                 AND pc.place = '$user_state' 
                 AND p.product_name = '$product_name'";
$result_sellers1 = $conn->query($sql_sellers1);
if ($result_sellers1->num_rows > 0) {
    $row_seller1 = $result_sellers1->fetch_assoc();
    $product_id = $row_seller1['product_id'];
}
$sql_insert_preorder = "INSERT INTO tbl_pre_orders (user_id, product_id, delivery_date, delivery_time,quantity) 
VALUES ('$userid', '$product_id', '$pdate', '$ptime','$quantity')";
$result_insert = $conn->query($sql_insert_preorder);
$pre_order_id = $conn->insert_id; // Get the ID of the inserted pre-order

if (!$result_insert) {
// If insertion fails, add a message to the array
$messages[] = "Error: Failed to insert pre-order details for product $product_name.";
}
// Prepare SQL query to select sellers matching customer's state, city, and product name
$sql_sellers = "SELECT  r.regid AS seller_id,p.product_id,r.full_name, pc.district AS seller_district, pc.place AS seller_place, p.product_name
               FROM tbl_registration r
               JOIN tbl_pincodes pc ON pc.fk_regid = r.regid
               JOIN tbl_products p ON p.fk_regid = r.regid
               WHERE pc.district = '$user_city' 
                 AND pc.place = '$user_state' 
                 AND p.product_name = '$product_name'";

// Execute the query
$result_sellers = $conn->query($sql_sellers);

// Check if sellers were found
if ($result_sellers->num_rows > 0) {
    // Loop through each seller
    while ($row_seller = $result_sellers->fetch_assoc()) {
        $seller_id = $row_seller['seller_id'];

        $seller_name = $row_seller['full_name'];
        $seller_district = $row_seller['seller_district'];
        $seller_place = $row_seller['seller_place'];
        $product_name = $row_seller['product_name'];
        
        $product_id = $row_seller['product_id'];
        // Construct message for the seller
        $message = "Dear $seller_name, you have a pre-booking request for $product_name from $user_city, $user_state. by $user_name with $quantity";
        
        // Store the message in the array
        $messages[] = $message;

        // Insert the message into the notification table
        $sql_insert_notification = "INSERT INTO tbl_notifications (user_id, message,msg_type, from_id) VALUES ('$seller_id', '$message','request','$pre_order_id')";
        if ($conn->query($sql_insert_notification) === TRUE) {
            // Notification inserted successfully
            // You can handle success here if needed
        } else {
            // Error inserting notification
            // You can handle error here if needed
        }
    }


 

} else {
    // No sellers found matching the criteria
    $messages[] = "No sellers found matching the criteria.";
}

// Convert messages array to JSON
$messages_json = json_encode($messages);

// Output the JSON data
echo $messages_json;

// Close the database connection
$conn->close();
?>
