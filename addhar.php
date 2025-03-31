<?php
require('./Connect_database.php');
$no=$_POST['fname'];
$name=$_POST['name'];
$dob=$_POST['dob'];
$sql = "SELECT * FROM tbl_adharr WHERE adharr_no='$no' AND name='$name' AND adharr_dob = '$dob'";


if($_POST['action']=="adharr"){
if($result=$conn->query($sql))
{
    if($result->num_rows>0)
    {
echo "1";
    }
    else{
        echo '0';
    }
}
}
elseif($_POST['action']=="fassi")
{
    $sql="SELECT * FROM tbl_adharr WHERE fassi_number='$no' AND name='$name' AND adharr_dob = '$dob'";
    if($result=$conn->query($sql))
{
    if($result->num_rows>0)
    {
echo "1";
    }
    else
    {
        echo"0";
    }
}
}

?>