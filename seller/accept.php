<?php
session_start();
require('../Connect_database.php');
$userid = $_SESSION['userid'];
// Check if the notification ID is set and valid
if($_POST['action']=='accept'){
if (isset($_POST['notification_id'])) {
    $notification_id = $_POST['notification_id'];
$sql="select  o.id as fk_preid,o.user_id as userid,p.product_name as newname  from tbl_notifications as n JOIN tbl_pre_orders AS o ON o.id = n.from_id join tbl_products as p on p.product_id=o.product_id WHERE n.user_id = '$userid' AND n.notification_id = '$notification_id'; ";
$newpid;
$newusid;
if($re=$conn->query($sql))
{
    $row = $re->fetch_assoc();
    // Output the value of newpid
    echo $row['newname'];
    // Store the value of newpid in a variable for further use
    $newpid = $row['newname'];
    $newusid=$row['userid'];
    $newpreid=$row['fk_preid'];
    $sql="select product_id from tbl_products where product_name='$newpid' and fk_regid='$userid'";
    if($re=$conn->query($sql))
    {
        $row = $re->fetch_assoc();
        echo $row['product_id'];
    // Store the value of newpid in a variable for further use
    $newpid = $row['product_id'];
    }
}    
// Update the status of the notification in the database (e.g., set it as accepted)
    $sql_update_notification = "UPDATE tbl_notifications AS n
    JOIN tbl_pre_orders AS o ON o.id = n.from_id
    SET  o.pre_status = 1, n.status = 'read',o.product_id='$newpid'
    WHERE n.user_id = '$userid' AND n.notification_id = $notification_id;"; // Use the provided notification ID
    if ($conn->query($sql_update_notification) === TRUE) {
        // Notification status updated successfully

$sql="INSERT INTO `tbl_orders`(fk_preid,`user_id`, `order_status`) VALUES($newpreid,$newusid,'1')";
$conn->query($sql);
        // Retrieve the from_id using another SQL query
        $sql_select_from_id = "SELECT o.id AS from_id
                               FROM tbl_notifications AS n
                               JOIN tbl_pre_orders AS o ON o.id = n.from_id
                               WHERE n.notification_id = $notification_id"; // Use the provided notification ID
        $result = $conn->query($sql_select_from_id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $from_id = $row['from_id'];

            // Now, let's delete related rows
           // Now, let's update the related notifications
$sql_update_related = "UPDATE tbl_notifications AS n
JOIN tbl_pre_orders AS o ON o.id = n.from_id
SET n.message = 'The order has been accepted by another seller.',
    n.status = 'unread'
WHERE n.notification_id <> $notification_id
    AND n.status = 'unread'
    AND n.from_id = $from_id";
if ($conn->query($sql_update_related) === TRUE) {
echo "Notification with ID $notification_id has been accepted. from_id: $from_id and related requests have been updated.";
} else {
echo "Error: Failed to update related requests for notification with ID $notification_id.";
}

        } else {
            echo "Error: Unable to retrieve from_id for notification with ID $notification_id.";
        }
    } else {
        // Error updating notification status
        echo "Error: Failed to accept notification with ID $notification_id.";
    }
} else {
    // Notification ID not provided or invalid
    echo "Error: Notification ID is missing or invalid.";
}
}
elseif ($_POST['action'] == "cancel") {
    $msg;
    $from_id;
    $notification_id = $_POST['notification_id'];
    $sql = "SELECT * FROM tbl_notifications WHERE notification_id='$notification_id'";
    if ($result = $conn->query($sql)) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $msg = $row['message'];
                $from_id = $row['from_id'];
            }
            echo $msg . $from_id;
            $sql_update = "UPDATE tbl_notifications AS n
            JOIN tbl_pre_orders AS p ON n.from_id = p.id
            SET n.message = '$msg', n.status = 'unread',
                 p.pre_status = '0'
            WHERE n.from_id = '$from_id'";
            if ($conn->query($sql_update) === TRUE) {
                echo "Records updated successfully";
            } else {
                echo "Error updating records: " . $conn->error;
            }
        }
    }
}


// Close the database connection


$conn->close();
?>
