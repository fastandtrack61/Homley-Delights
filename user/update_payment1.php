<?php
session_start();
require('../Connect_database.php');
$userid = $_SESSION['userid'];
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve payment ID and order data from the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Sanitize and validate input data
    $paymentID = isset($data['paymentID']) ? htmlspecialchars($data['paymentID']) : '';
    $orders = isset($data['orders']) ? $data['orders'] : [];

    // Initialize an array to store the result of each transaction insertion
    $results = [];

    // Perform database insertion for each order
    foreach ($orders as $order) {
        $orderId = isset($order['orderId']) ? intval($order['orderId']) : 0;
        $amount = isset($order['amount']) ? floatval($order['amount']) : 0;

        // Perform database insertion for transaction
        $transactionDate = date('Y-m-d H:i:s');
        $paymentStatus = !empty($paymentID) ? 'completed' : 'pending'; // Check if payment ID is empty

        $sqlInsertTransaction = "INSERT INTO tbl_transactions (order_id, payment_id, amount, payment_status) 
                                 VALUES ('$orderId', '$paymentID', '$amount', '$paymentStatus')";

        if ($conn->query($sqlInsertTransaction)) {
            // Insertion of transaction successful
            $results[] = array('orderId' => $orderId, 'status' => 'success');

            // Fetch seller ID associated with the order
            $sqlFetchSellerId = "SELECT fk_regid,stock,od.quantity as q,od.product_id as product_id FROM tbl_order_details od 
                                 INNER JOIN tbl_orders o ON od.id=o.fk_preid 
                                 JOIN tbl_products p ON p.product_id=od.product_id 
                                 WHERE o.order_id='$orderId'";

            $sellerResult = $conn->query($sqlFetchSellerId);
            if ($sellerResult->num_rows > 0) {
               
                $sellerRow = $sellerResult->fetch_assoc();
                $sellerId = $sellerRow['fk_regid'];
                $quantity=$sellerRow['q'];
                $currentStock=$sellerRow['stock'];
                $product_id=$sellerRow['product_id'];
                // Construct message for the seller
                $message = "You have a new order. Please check your dashboard.";

                // Insert the notification into the notifications table
                $sqlInsertNotification = "INSERT INTO tbl_notifications (user_id, message, msg_type, from_id) 
                                           VALUES ('$sellerId', '$message', 'normal', '$orderId')";

                if ($conn->query($sqlInsertNotification) === TRUE) {
                    $clear_cart_query = "DELETE FROM tbl_cart WHERE fk_regid = '$userid'";
                    $conn->query($clear_cart_query);
                    $newStock = $currentStock - $quantity;
                    $sqlUpdateStock = "UPDATE tbl_products SET stock = '$newStock' WHERE product_id = '$product_id'";
                    $conn->query($sqlUpdateStock);
                } else {
                    // Notification insertion failed
                    // Handle the error if needed
                }
            }
        } else {
            // Insertion of transaction failed
            $results[] = array('orderId' => $orderId, 'status' => 'error', 'message' => 'Failed to insert transaction: ' . $conn->error);
        }
    }

    // Send response back to the client
    http_response_code(200);
    echo json_encode($results);
} else {
    // Invalid request method
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
}
?>
