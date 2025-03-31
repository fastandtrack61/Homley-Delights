<?php
session_start();
// Include database connection file
require('../Connect_database.php');

if ($_POST['product_id']) {
    $productId = $_POST['product_id'];

    // Check if stock is available for the given product ID
    $stockQuery = "SELECT stock, product_name FROM tbl_products WHERE product_id = $productId";
    $stockResult = $conn->query($stockQuery);

    if ($stockResult && $stockResult->num_rows > 0) {
        $stockData = $stockResult->fetch_assoc();
        $stock = $stockData['stock'];

        // Construct the response array
        $response = array();
        if ($stock > 0) {
            // Stock is available
            $response['product_name'] = $stockData['product_name'];
            $response['stock'] = $stock;
        } else {
            // No stock available, set stock to 0
            $response['product_name'] = $stockData['product_name'];
            $response['stock'] = 0;
        }
        // Encode the response array into JSON format and echo it back to the client
        echo json_encode($response);
    } else {
        // Error or no stock information found
        echo '';
    }
} else {
    // Product ID not provided
    echo '';
}
?>
