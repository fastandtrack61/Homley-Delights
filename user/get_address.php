<?php
session_start();
include ('../Connect_database.php');
$userid = $_SESSION['userid'];
if(isset($_POST['addressId']) && $_POST['addressId']!='OD') {
    $addressId = $_POST['addressId'];
 
    $sql = "SELECT * FROM tbl_alternative_addresses WHERE user_id = $userid AND id='$addressId'";
    $result = $conn->query($sql);
    
    // Check if the query was successful
    if ($result->num_rows > 0) {
        // Fetch address details from the result
        $row = $result->fetch_assoc();
        
        echo json_encode($row);
    } else {
        // Return an empty array if no address found
        echo json_encode([]);
    }
    // Stop further execution of the script
    exit();
}
else
{
    $sql="SELECT * FROM tbl_registration where regid=$userid";

    $result = $conn->query($sql);
    
    // Check if the query was successful
    if ($result->num_rows > 0) {
        // Fetch address details from the result
        $row = $result->fetch_assoc();
        
        echo json_encode($row);
    } else {
        // Return an empty array if no address found
        echo json_encode([]);
    }
    // Stop further execution of the script
    exit();   
}
?>
