<?php
session_start();
require('../Connect_database.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve order ID, payment ID, and other necessary data from the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Sanitize and validate input data
    $orderID = isset($data['orderID']) ? intval($data['orderID']) : 0;
    $paymentID = isset($data['paymentID']) ? htmlspecialchars($data['paymentID']) : '';
    $amount = isset($data['amount']) ? floatval($data['amount']) : 0;

    if ($orderID && $paymentID && $amount > 0) {
        // Insert new transaction record into the database
        $sql_insert = "INSERT INTO tbl_transactions (order_id, payment_id, amount, payment_status) 
                       VALUES ($orderID, '$paymentID', $amount, 'Pending')";

        if ($conn->query($sql_insert)) {
            // Transaction record inserted successfully

            // Check if payment ID is received, if yes, update payment status to 'Paid'
            $sql_update = "UPDATE tbl_transactions 
                           SET payment_status = 'completed' 
                           WHERE order_id = $orderID AND payment_id = '$paymentID'";

            if ($conn->query($sql_update)) {
                // Payment status updated successfully
                http_response_code(200);
                echo json_encode(array('message' => 'Transaction record inserted and payment status updated successfully.'));
            } else {
                // Failed to update payment status
                http_response_code(500);
                echo json_encode(array('error' => 'Failed to update payment status.'));
            }
        } else {
            // Failed to insert transaction record
            http_response_code(500);
            echo json_encode(array('error' => 'Failed to insert transaction record.'));
        }
    } else {
        // Invalid input data or missing required fields
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data or missing required fields.'));
    }
} else {
    // Invalid request method
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
}
?>
