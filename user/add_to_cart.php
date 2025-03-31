<?php
session_start();
$userid = $_SESSION['userid'];
require('../Connect_database.php');
$data = array();

// Check if product ID is provided
if (isset($_POST['product_id'])) {
    // Sanitize and validate product ID
    $product_id = $_POST['product_id'];
    
    // Retrieve product name associated with the provided product ID
    $product_name_query = "SELECT product_name FROM tbl_products WHERE product_id = '$product_id'";
    $product_name_result = $conn->query($product_name_query);
    
    if ($product_name_result && $product_name_result->num_rows > 0) {
        $product_name_row = $product_name_result->fetch_assoc();
        $product_name = $product_name_row['product_name'];
        
        // Check if there are any sellers offering the same product name and can deliver to the customer's district and place
        $district = $_POST['dis'];
        $place    = $_POST['pl'];
        
        $sql = "SELECT * FROM tbl_registration WHERE regid = $userid";
        $result = $conn->query($sql);
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_district = $row['city'];
            $user_place    = $row['state'];
        
       
             
                
             
                    // No conflicting items found, proceed to add the product to the cart
                    
                    $seller_query = "SELECT p.product_id, p.fk_regid FROM tbl_products p
                                     JOIN tbl_pincodes r ON p.fk_regid = r.fk_regid
                                     WHERE p.product_name = '$product_name' AND r.district = '$district' AND r.place = '$place'
                                     ORDER BY RAND() LIMIT 1"; // Randomly select one seller
                    $seller_result = $conn->query($seller_query);
                    
                    if ($seller_result && $seller_result->num_rows > 0) {
                        // Found a random seller, add the product to the cart using the seller's product ID
                        $seller_row = $seller_result->fetch_assoc();
                        $seller_product_id = $seller_row['product_id'];
                        
                        // Add the product to the cart
                        $add_to_cart_query = "INSERT INTO tbl_cart (fk_regid, product_id, quantity) VALUES ('$userid', '$seller_product_id', 1)";
                        if ($conn->query($add_to_cart_query) === true) {
                            $data['msg'] = 'success';
                        } else {
                            // Error adding the product to the cart
                            $data['msg'] = 'error';
                        }
                    } else {
                        // No suitable seller found
                        $data['msg'] = 'no_suitable_seller';
                    }
                
            
        } else {
            // Unable to retrieve user information
            $data['msg'] = 'error_retrieving_user_info';
        }
    } else {
        // Unable to retrieve product name
        $data['msg'] = 'invalid_product_id';
    }
} else {
    // If product ID is not provided, respond with error
    $data['msg'] = 'missing_product_id';
}

// Count cart items
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
