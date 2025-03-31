<?php
session_start();
require('../Connect_database.php');
$userid = $_SESSION['userid'];

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve order ID, payment ID, and other necessary data from the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Sanitize and validate input data
    $productId = isset($data['productId']) ? intval($data['productId']) : 0;
    $paymentID = isset($data['paymentID']) ? htmlspecialchars($data['paymentID']) : '';
    $amount   = isset($data['amount']) ? floatval($data['amount']) : 0;
    $q = isset($data['q']) ? floatval($data['q']) : 0;
    $selectedSellerId = isset($data['selectedSellerId']) ? floatval($data['selectedSellerId']) : 0;

    // Fetch the current stock of the product using its ID
    $sqlFetchStock = "SELECT `stock` FROM `tbl_products` WHERE `product_id` = '$productId'";
    $resultFetchStock = $conn->query($sqlFetchStock);

    if ($resultFetchStock->num_rows > 0) {
        $row = $resultFetchStock->fetch_assoc();
        $currentStock = intval($row['stock']);
        
        // Check if the available stock is sufficient for the order
        if ($currentStock >= $q) {
            // Perform database insertion for order details
            $sqlInsertOrderDetails = "INSERT INTO tbl_order_details (product_id, quantity, price) 
                                      VALUES ('$productId', '$q', '$amount')";
            
            if ($conn->query($sqlInsertOrderDetails)) {
                // Insertion of order details successful
                $orderDetailsId = $conn->insert_id; // Get the last inserted ID

                // Perform database insertion for order
                $sqlInsertOrder = "INSERT INTO tbl_orders (fk_preid, user_id, order_status) 
                                   VALUES ('$orderDetailsId', '$userid', '1')";
                
                if ($conn->query($sqlInsertOrder)) {
                    // Insertion of order successful
                    $orderId = $conn->insert_id; // Get the last inserted ID of the order

                    // Perform database insertion for transaction
                    $transactionDate = date('Y-m-d H:i:s');
                    $paymentStatus = isset($paymentID) ? 'completed' : 'pending'; // Check if payment ID is empty

                    $sqlInsertTransaction = "INSERT INTO tbl_transactions (order_id, payment_id, amount, payment_status) 
                                             VALUES ('$orderId', '$paymentID', '$amount' ,'$paymentStatus')";

                    if ($conn->query($sqlInsertTransaction)) {
                        // Insertion of transaction successful
                        
                        // Update the stock of the product after the order is placed
                        $newStock = $currentStock - $q;
                        $sqlUpdateStock = "UPDATE `tbl_products` SET `stock` = '$newStock' WHERE `product_id` = '$productId'";
                        $conn->query($sqlUpdateStock);
                        
                        http_response_code(200);
                        echo json_encode(array('message' => 'Order, order details, and transaction inserted successfully.', 'orderDetailsId' => $orderDetailsId, 'orderId' => $orderId));
                    } else {
                        // Insertion of transaction failed
                        http_response_code(500);
                        echo json_encode(array('error' => 'Failed to insert transaction: ' . $conn->error));
                    }
                } else {
                    // Insertion of order failed
                    http_response_code(500);
                    echo json_encode(array('error' => 'Failed to insert order: ' . $conn->error));
                }
            } else {
                // Insertion of order details failed
                http_response_code(500);
                echo json_encode(array('error' => 'Failed to insert order details: ' . $conn->error));
            }
        } else {
            // Insufficient stock for the order
            http_response_code(400);
            echo json_encode(array('error' => 'Insufficient stock for the order.'));
        }
    } else {
        // Product not found or invalid product ID
        http_response_code(404);
        echo json_encode(array('error' => 'Product not found or invalid product ID.'));
    }
} else {
    // Invalid request method
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
}
?>
