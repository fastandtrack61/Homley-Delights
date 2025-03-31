<?php
session_start();
$userid = $_SESSION['userid'];
require('../Connect_database.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$t;
// Initialize an array to store notifications
$notifications = array();
$currentDate = date('Y-m-d');
$scheduledDeliveries = array();
$sql_scheduled_deliveries = "
SELECT *
FROM tbl_orders AS o
JOIN tbl_schedule AS s ON o.order_id = s.order_id 
JOIN tbl_order_details AS od ON od.id = o.fk_preid 
JOIN tbl_products AS p ON p.product_id = od.product_id  
WHERE p.fk_regid='$userid'  
ORDER BY created_at DESC";

$result_scheduled_deliveries = $conn->query($sql_scheduled_deliveries);

// Check if there are any scheduled deliveries
if ($result_scheduled_deliveries->num_rows > 0) {
    while ($row_scheduled_delivery = $result_scheduled_deliveries->fetch_assoc()) {
        $order_id = $row_scheduled_delivery['order_id'];
        $from_date = $row_scheduled_delivery['from_date'];
        $to_date = $row_scheduled_delivery['to_date'];
        $status = ''; // Variable to hold the status to be updated

        if ($currentDate >= $from_date && $currentDate <= $to_date) {
            // If current date is within the delivery period
            $status = 'In Transit';
        } elseif ($currentDate > $to_date) {
            // If current date is past the delivery period
            $status = 'Delivered';
        }

        if (!empty($status)) {
            // Update the status in the database
            $sql_update_status = "UPDATE tbl_schedule SET status = '$status' WHERE order_id = '$order_id'";
            $conn->query($sql_update_status);
        }
    }
}

$sql_scheduled_deliveries = "
SELECT *
FROM tbl_orders AS o
JOIN tbl_schedule AS s ON o.order_id = s.order_id 
JOIN tbl_order_details AS od ON od.id = o.fk_preid 
JOIN tbl_products AS p ON p.product_id = od.product_id  
WHERE p.fk_regid='$userid'  
ORDER BY created_at DESC";

$result_scheduled_deliveries = $conn->query($sql_scheduled_deliveries);

// Check if there are any scheduled deliveries
if ($result_scheduled_deliveries->num_rows > 0) {
    while ($row_scheduled_delivery = $result_scheduled_deliveries->fetch_assoc()) {
        $order_id = $row_scheduled_delivery['order_id'];
        $product_name=$row_scheduled_delivery['product_name'];
        $sid = $row_scheduled_delivery['schedule_id'];
        
        // Check if the product is in transit or delivered and if the current date is less than or equal to the scheduled 'to date'
        if (($row_scheduled_delivery['status'] == 'In Transit' || $row_scheduled_delivery['status'] == 'Delivered') && $currentDate <= $row_scheduled_delivery['to_date']) {
            // Check if a notification has already been inserted for today and this schedule ID
            $sql_check_notification = "SELECT * FROM tbl_notification_dates WHERE schedule_id = '$sid' AND notification_date = CURRENT_DATE";
            $result_check_notification = $conn->query($sql_check_notification);
            
            if ($result_check_notification->num_rows == 0) {
                $status=1;
                // Insert notification for in-transit or delivered product
                $sql = "UPDATE tbl_schedule  SET schedule_status = 1 WHERE schedule_id = $sid";
                $conn->query($sql);
                
            

                $notification_message = "You have scheduled delivery of  $product_name by Today.check it ";
                $notification_message .= $row_scheduled_delivery['status'] == 'In Transit' ? "ongoing." : "final day.";
                
                $sql_insert_notification = "INSERT INTO tbl_notifications (user_id, message, msg_type, status, from_id) VALUES ('$userid', '$notification_message', 'order', 'unread', '$sid')";
                $conn->query($sql_insert_notification);
                
                // Record that a notification has been inserted for this schedule ID and today's date
                $sql_record_notification_date = "INSERT INTO tbl_notification_dates (schedule_id, notification_date) VALUES ('$sid', CURRENT_DATE)";
                $conn->query($sql_record_notification_date);
            }
        } 
    }
}



// Retrieve notifications for the seller
$sql_notifications = "SELECT from_id,created_at,status,notification_id,message,msg_type FROM tbl_notifications WHERE user_id = '$userid' order by created_at DESC ";
$result_notifications = $conn->query($sql_notifications);

// Check if notifications were found
if ($result_notifications->num_rows > 0) {
    // Loop through each notification
    while ($row_notification = $result_notifications->fetch_assoc()) {
        // Add notification message to the array
        $notification = array(
            'message' => $row_notification['message'],
            'msg_type' => $row_notification['msg_type'],
            'notifaction_id'=>$row_notification['notification_id'],
            'status'=>$row_notification['status'],
            'created_at'=>$row_notification['created_at'],
            'from_id' => $row_notification['from_id']

        );
        $notifications[] = $notification;
    }
}

// Convert notifications array to JSON
$notifications_json = json_encode($notifications);

// Output the JSON data
echo $notifications_json;

// Close the database connection
$conn->close();
?>
