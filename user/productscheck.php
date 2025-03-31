<?php
// Check if pincode is set
if (isset($_POST['pincode'])) {
    // Retrieve the pincode value
    $pincode = $_POST['pincode'];
    
    // Include database connection
    require('../Connect_database.php');

    // Construct the SQL query
    $sql = "SELECT 
                p.product_id, 
                p.product_name, 
                MIN(p.price) AS price, 
                MIN(p.photo_path) AS photo_path, 
                COUNT(*) AS seller_count, 
                MIN(r.full_name) AS full_name 
            FROM 
                tbl_products p
            JOIN 
                tbl_registration r ON p.fk_regid = r.regid
            JOIN
                tbl_pincodes pc ON pc.fk_regid = r.regid
            WHERE 
                p.p_status = '1' AND
                pc.pincode = '$pincode' -- Replace '12345' with the customer's pincode
            GROUP BY 
                p.product_name
            ORDER BY 
                p.product_id DESC";

    // Execute the SQL query
    $result = $conn->query($sql);

    // Initialize an array to store products
    $products = array();

    // Check if query was successful
    if ($result) {
        // Check if there are any products
        if ($result->num_rows > 0) {
            // Loop through each row in the result set
            while ($row = $result->fetch_assoc()) {
                // Add each product to the array
                $products[] = $row;
            }
        } else {
            // No products found for the provided pincode
            $products['error'] = "No products found for the provided pincode.";
        }
    } else {
        // Query execution failed
        $products['error'] = "Failed to fetch products. Please try again later.";
    }

    // Close the database connection
    $conn->close();

    // Encode the array as JSON and echo it
    echo json_encode($products);
} else {
    // Return an error message if pincode is not set
    echo "Pincode not provided";
}
?>
