<?php
session_start();
$userid = $_SESSION['userid'];
require('../Connect_database.php');
$data = array();

// Check if product ID is provided
if (isset($_GET['product_id'])) {
    // Sanitize and validate product ID
    $product_id = $_GET['product_id'];
    
    // Clear the user's cart
    $clear_cart_query = "DELETE FROM tbl_cart WHERE fk_regid = '$userid'";
    if ($conn->query($clear_cart_query) === true) {
        // Get the district and place from session
        $district = $_GET['dist'];
        $place = $_GET['pla'];
        
        // Retrieve product name associated with the provided product ID
        $product_name_query = "SELECT product_name FROM tbl_products WHERE product_id = '$product_id'";
        $product_name_result = $conn->query($product_name_query);
        
        if ($product_name_result && $product_name_result->num_rows > 0) {
            $product_name_row = $product_name_result->fetch_assoc();
            $product_name = $product_name_row['product_name'];
            
            // Select a random seller for the given product name, district, and place
            $seller_query = "SELECT p.product_id, p.fk_regid FROM tbl_products p
                             JOIN tbl_pincodes r ON p.fk_regid = r.fk_regid
                             WHERE p.product_name = '$product_name' AND r.district = '$district' AND r.place = '$place'
                             ORDER BY RAND() LIMIT 1"; // Randomly select one seller
            $seller_result = $conn->query($seller_query);
            
            if ($seller_result && $seller_result->num_rows > 0) {
                $seller_row = $seller_result->fetch_assoc();
                $selected_seller_id = $seller_row['fk_regid'];
                $selected_product_id = $seller_row['product_id'];
                
                // Add the new item to the cart for the selected seller
                $add_to_cart_query = "INSERT INTO tbl_cart (fk_regid, product_id, quantity) VALUES ('$userid', '$selected_product_id', 1)";
                if ($conn->query($add_to_cart_query) === true) {
                    $data['msg'] = 'success';
                } else {
                    // Error adding the new item to the cart
                    $data['msg'] = 'error_adding_to_cart';
                }
            } else {
                // No suitable seller found
                $data['msg'] = 'no_suitable_seller';
            }
        } else {
            // Unable to retrieve product name
            $data['msg'] = 'invalid_product_id';
        }
    } else {
        // Error clearing the cart
        $data['msg'] = 'error_clearing_cart';
    }
} else {
    // If product ID is not provided, respond with error
    $data['msg'] = 'missing_product_id';
}


$count_cart_query = "SELECT COUNT(*) AS cart_count FROM tbl_cart WHERE fk_regid='$userid'";
$count_cart_result = $conn->query($count_cart_query);
if ($count_cart_result && $count_cart_result->num_rows > 0) {
    $count_row = $count_cart_result->fetch_assoc();
    $data['count'] = $count_row['cart_count'];
} else {
    $data['count'] = 0;
}



// Return data as JSON
echo json_encode($data);
?>
