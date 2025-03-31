<?php
// Retrieve form data
$orderId = $_POST['orderId'];
$fromDate = $_POST['fromDate'];
$toDate = $_POST['toDate'];

session_start();
require('../Connect_database.php');
$email = $_SESSION['username'];
$userid = $_SESSION['userid'];

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Escape special characters to prevent SQL injection (if necessary)
$orderId = mysqli_real_escape_string($conn, $orderId);
$fromDate = mysqli_real_escape_string($conn, $fromDate);
$toDate = mysqli_real_escape_string($conn, $toDate);

// Construct the SQL query
$sql = "INSERT INTO tbl_schedule (user_id,order_id, from_date, to_date,schedule_status) VALUES ('$userid','$orderId', '$fromDate', '$toDate',1)";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "Delivery scheduled successfully!";
} else {
    echo "Error scheduling delivery: " . $conn->error;
}

// Close connection
$conn->close();
?>
