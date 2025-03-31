<?php
session_start();
$userid = $_SESSION['userid'];

// Assuming you have established a database connection
require('../Connect_database.php');

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve product_id, order_id, and rating from the POST data
    $product_id = $_POST["product_id"];
    $order_id = $_POST["order_id"]; // New addition
    $rating = $_POST["rating"];

    // Perform any necessary validation on the input data

    // Insert the rating into the database
    $sql = "INSERT INTO tbl_ratings (user_id, order_id, product_id, rating) VALUES ('$userid', '$order_id', '$product_id', '$rating')";

    if ($conn->query($sql) === TRUE) {
        // Rating inserted successfully
        echo "Rating inserted successfully.";
    } else {
        // Error occurred while inserting the rating
        echo "Error inserting rating: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    // Handle invalid request method
    echo "Invalid request method.";
}
?>
