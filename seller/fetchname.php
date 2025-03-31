<?php
require('../Connect_database.php');
$sql = "SELECT DISTINCT product_name FROM tbl_products";
$result = $conn->query($sql);

$productNames = array();

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        // Store product names in an array
        $productNames[] = $row['product_name'];
    }
}

// Remove duplicate product names
$productNames = array_unique($productNames);

echo json_encode($productNames);
?>
