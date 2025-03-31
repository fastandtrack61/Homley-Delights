
<?php
$data=array();
require('./Connect_database.php');
$regid=$_POST['id'];

$updateVerificationQuery = "UPDATE tbl_verification SET email_verify = '1' WHERE fk_regid = '$regid'";
if ($conn->query($updateVerificationQuery) === TRUE) {
$data['resp']='success';
}
echo json_encode($data);
?>