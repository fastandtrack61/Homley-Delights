<?php
session_start();
require('../Connect_database.php');

// Check if the user is logged in


// Check if orderId and status are set in the request
if(isset($_POST['orderId']) && isset($_POST['status'])) {
    $orderId = $_POST['orderId'];
    $status = $_POST['status'];

    // Prepare update statement
    $sql = "UPDATE tbl_schedule  SET schedule_status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $orderId);

    // Execute the update statement
    if ($stmt->execute()) {
        echo "Status updated successfully";
    } else {
        echo "Error updating status: " . $conn->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Handle the case when orderId or status is not set
    echo "Error: orderId or status not set";
}
?>
