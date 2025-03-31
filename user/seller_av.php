<?php
session_start();
// check_seller_availability.php
$userid=$_SESSION['userid'];
// Include database connection file
require('../Connect_database.php');

$data = array();
if ($_POST['action'] == 'checkseller') {
    $district;
    $place;
    
    // Check if product ID and address are provided
    if (isset($_POST['product_id']) && isset($_POST['ad'])) {
        // Sanitize and validate input
        $product_id = $_POST['product_id'];
        $address = $_POST['ad'];

        if ($address == 'OD') {
            // Fetch district and place from main address
            $sql = "SELECT city, state FROM tbl_registration WHERE regid='$userid'";
        } else {
            // Fetch district and place from alternative address
            $sql = "SELECT city, state FROM `tbl_alternative_addresses` WHERE id='$address' AND user_id='$userid'";
        }

        // Execute the query
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $district = $row['city'];
            $place = $row['state'];

            $product_name_query = "SELECT product_name FROM tbl_products WHERE product_id = '$product_id'";
            $product_name_result = $conn->query($product_name_query);
            
            if ($product_name_result && $product_name_result->num_rows > 0) {
                $product_name_row = $product_name_result->fetch_assoc();
                $product_name = $product_name_row['product_name'];
                
                // Select a random seller for the given product name, district, and place
                $seller_query = "SELECT p.product_id, p.fk_regid FROM tbl_products p
                                JOIN tbl_pincodes r ON p.fk_regid = r.fk_regid
                                WHERE p.product_name = '$product_name' AND r.district = '$district' AND r.place = '$place' AND p.stock > 0 AND p.p_status = '1'
                                ORDER BY RAND() LIMIT 1";

                $seller_result = $conn->query($seller_query);
                
                if ($seller_result && $seller_result->num_rows > 0) {
                    $seller_row = $seller_result->fetch_assoc();
                    $selected_seller_id = $seller_row['fk_regid'];
                    $selected_product_id = $seller_row['product_id'];

                    // Prepare data for response
                    $data['product_name'] = $product_name;
                    $data['selected_seller_id'] = $selected_seller_id;
                    $data['selected_product_id'] = $selected_product_id;
                    $data['district'] = $district;
                    $data['place'] = $place;
                    $data['msg'] = 'success';
                } else {
                    // No suitable seller found
                    $data['msg'] = 'no_suitable_seller';
                }
            } else {
                // Unable to retrieve product name
                $data['msg'] = 'invalid_product_id';
            }
        } else {
            // Address not found
            $data['msg'] = 'invalid_address';
        }
    } else {
        // Product ID or address is missing
        $data['msg'] = 'missing_data';
    }
}
/* elseif ($_POST['action'] == 'checkproduct') {
    if (isset($_POST['product_id'])) {
        // Sanitize and validate input
        $product_id = $_POST['product_id'];
        $pincode = isset($_POST['pincode']) ? $_POST['pincode'] : '';

        // If pincode is not provided, retrieve it from tbl_registration table
        if (empty($pincode)) {
            // Query to retrieve pincode from tbl_registration table
            $pincode_query = "SELECT postal FROM tbl_registration WHERE regid = '$userid'";
            $pincode_result = $conn->query($pincode_query);

            if ($pincode_result && $pincode_result->num_rows > 0) {
                $pincode_row = $pincode_result->fetch_assoc();
                $pincode = $pincode_row['postal'];
            }
        }

        // Retrieve product name associated with the provided product ID
        $product_name_query = "SELECT product_name FROM tbl_products WHERE product_id = '$product_id'";
        $product_name_result = $conn->query($product_name_query);

        if ($product_name_result && $product_name_result->num_rows > 0) {
            $product_name_row = $product_name_result->fetch_assoc();
            $product_name = $product_name_row['product_name'];

            // Check if there are any sellers offering the same product name and can deliver to the customer's pincode
            $seller_query = "SELECT p.product_id, p.fk_regid FROM tbl_products p
                             JOIN tbl_pincodes r ON p.fk_regid = r.fk_regid
                             WHERE p.product_name = '$product_name' AND r.pincode = '$pincode'";
            $seller_result = $conn->query($seller_query);

            if ($seller_result && $seller_result->num_rows > 0) {
                // Found a suitable seller, return the product ID
                $seller_row = $seller_result->fetch_assoc();
                $data['available'] = true;
                $data['product_id'] = $seller_row['product_id'];
            } else {
                // No suitable seller found for the product in the provided pincode
                $data['available'] = false;
            }
        } else {
            // Unable to retrieve product name
            $data['available'] = false;
        }
    } else {
        // Product ID is missing
        $data['available'] = false;
    }
}
 */


echo json_encode($data);
?>
