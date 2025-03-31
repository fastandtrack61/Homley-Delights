<?php
require('../Connect_database.php');
$fk_logid=$_POST['fk_logid'];
$data=array();


$sql="SELECT * FROM tbl_registration AS r Join tbl_login AS l on r.fk_loginid=l.login_id Join tbl_images as i on i.fk_regid=r.regid where r.regid='$fk_logid'";
if($result=$conn->query($sql))
{
    if($result->num_rows>0)
    {
        $data[]=$result->fetch_assoc();
    }
    else
    {
        echo "User Not Found";
    }
}
echo json_encode($data)
?>