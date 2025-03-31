<?php
session_start();
// check_seller_availability.php
$userid = $_SESSION['userid'];
// Include database connection file
require('../Connect_database.php');

$data = array();
if ($_POST['action'] == 'checkseller') {
    // Check if product ID, address, and other necessary variables are provided
    if (isset($_POST['product_id']) && isset($_POST['ad']) && isset($_POST['pname']) && isset($_POST['psid']) && isset($_POST['spid']) && isset($_POST['dis']) && isset($_POST['pl'])) {
        // Sanitize and validate input
        $product_id = $_POST['product_id'];
        $address = $_POST['ad'];
        $productName = $_POST['pname'];
        $selectedSellerId = $_POST['psid'];
        $selectedProductId = $_POST['spid'];
        $district = $_POST['dis'];
        $place = $_POST['pl'];

        // Fetch district and place based on the address provided
        if ($address == 'OD') {
            $data['uad']='OD';
            // Fetch district and place from main address
            $address_query = "SELECT city, state FROM tbl_registration WHERE regid='$userid'";
        } else {
            // Fetch district and place from alternative address
            $address_query = "SELECT city, state FROM `tbl_alternative_addresses` WHERE id='$address' AND user_id='$userid'";
            $data['alt']=$address;
        }

        // Execute the query to fetch district and place
        $address_result = $conn->query($address_query);

        if ($address_result && $address_result->num_rows > 0) {
            $row = $address_result->fetch_assoc();
            $district = $row['city'];
            $place = $row['state'];
            
            // Check if the assigned seller has sufficient stock
            $seller_query = "SELECT * FROM tbl_products p
                            JOIN tbl_pincodes r ON p.fk_regid = '$selectedSellerId'
                            WHERE p.product_id = '$selectedProductId' AND r.district = '$district' AND r.place = '$place' AND p.stock > 0 AND p.p_status = '1'";
            $seller_result = $conn->query($seller_query);

            if ($seller_result && $seller_result->num_rows > 0) {
                // Assigned seller has sufficient stock, proceed with the current assignment
                $seller_row = $seller_result->fetch_assoc();
                $data['price']=$seller_row['price'];
                $data['product_img']=$seller_row['photo_path'];
                $data['product_name'] = $productName;
                $data['selected_seller_id'] = $selectedSellerId;
                $data['selected_product_id'] = $selectedProductId;
                $data['district'] = $district;
                $data['place'] = $place;
                $data['msg'] = 'success';
            } else {
                // Assigned seller does not have sufficient stock, find a new seller
                $new_seller_query = "SELECT  p.product_id, p.fk_regid FROM tbl_products p
                                    JOIN tbl_pincodes r ON p.fk_regid = r.fk_regid
                                    WHERE p.product_name = '$productName' AND r.district = '$district' AND r.place = '$place' AND p.stock > 0 AND p.p_status = '1'
                                    ORDER BY RAND() LIMIT 1";
                $new_seller_result = $conn->query($new_seller_query);

                if ($new_seller_result && $new_seller_result->num_rows > 0) {
                    // Found a new seller who can deliver to the customer's location
                    $new_seller_row = $new_seller_result->fetch_assoc();
                    $new_seller_id = $new_seller_row['fk_regid'];
                    $new_product_id = $new_seller_row['product_id'];
                    $data['price']=$new_seller_row['price'];
                    $data['product_img']=$$new_seller_row['photo_path'];
                  
                    $data['product_name'] = $productName;
                    $data['selected_seller_id'] = $new_seller_id;
                    $data['selected_product_id'] = $new_product_id;
                    $data['district'] = $district;
                    $data['place'] = $place;
                    $data['msg'] = 'success';
                } else {
                    // No suitable seller found
                    $data['msg'] = 'no_suitable_seller';
                }
            }
        } else {
            // Unable to retrieve user's location
            $data['msg'] = 'invalid_address';
        }
    } else {
        // Product ID, address, or other necessary variables are missing
        $data['msg'] = 'missing_data';
    }
} else {
    // Invalid action
    $data['msg'] = 'invalid_action';
}

echo json_encode($data);
?>
