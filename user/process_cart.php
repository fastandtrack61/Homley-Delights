<?php
session_start();
require('../Connect_database.php');
$userid = $_SESSION['userid'];
function insertOrderDetails($conn, $productId, $quantity, $amount) {
    // Prepare the SQL statement to insert order details
    $sql = "INSERT INTO tbl_order_details (product_id, quantity, price) VALUES ('$productId', '$quantity', '$amount')";
    
    // Execute the SQL query
    if ($conn->query($sql) === TRUE) {
        // Return the last inserted ID
        return $conn->insert_id;
    } else {
        return false;
    }
}

// Function to insert order into tbl_order using fk_preid
function insertOrder($conn, $fk_preid, $userid) {
    // Prepare the SQL statement to insert into tbl_order
    // Assuming you have a table structure where you just need to insert the fk_preid
    $sql = "INSERT INTO tbl_orders (fk_preid, user_id, order_status) VALUES ('$fk_preid', '$userid', '1')";
    
    // Execute the SQL query
    if ($conn->query($sql) === TRUE) {
        // Return the last inserted ID
        return $conn->insert_id;
    } else {
        return false;
    }
}

// Retrieve the JSON data sent from JavaScript
$jsonData = file_get_contents('php://input');

// Check if the data is not empty
if (!empty($jsonData)) {
    // Decode the JSON data to PHP array
    $cartDetails = json_decode($jsonData, true);

    // Check if decoding was successful and data is an array
    if (is_array($cartDetails)) {
        // Assuming you have a database connection established
        require('../Connect_database.php');

        // Get user ID from session
        $userid = $_SESSION['userid'];

        // Initialize an array to store order IDs and their amounts
        $orders = [];

        // Process each item in the cart
        foreach ($cartDetails as $item) {
            $productId = $item['productId'];
            $quantity = $item['quantity'];
            $amount = $item['amount']; // Get the amount from the JSON data

            // Insert order details into tbl_order_details and get the inserted ID
            $orderDetailsId = insertOrderDetails($conn, $productId, $quantity, $amount);
            if ($orderDetailsId !== false) {
                // Insert order into tbl_order using the returned order details ID as fk_preid
                $orderId = insertOrder($conn, $orderDetailsId, $userid);
                if ($orderId !== false) {
                    // Add the order ID and its amount to the array
                    $orders[] = array('orderId' => $orderId, 'amount' => $amount);
                } else {
                    // Handle insertion error if needed
                    echo json_encode(['status' => 'error', 'message' => 'Failed to insert order']);
                    exit; // Exit script if an error occurs
                }
            } else {
                // Handle insertion error if needed
                echo json_encode(['status' => 'error', 'message' => 'Failed to insert order details']);
                exit; // Exit script if an error occurs
            }
        }

        // Close database connection
        $conn->close();

        // Response back to JavaScript with order IDs and their amounts
        echo json_encode(['status' => 'success', 'orders' => $orders]);
    } else {
        // If decoding failed or data is not an array
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
    }
} else {
    // If no data received
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
}
?>
