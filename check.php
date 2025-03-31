<?php
require './Connect_database.php';
$sql="SELECT * FROM tbl_login where status=1";
$data=array();
if($result=$conn->query($sql))
{
if($result->num_rows>0)
{
    while($row=$result->fetch_assoc())
    {
     $data[]=$row;

}}
else
{
    echo "not exsit";
}
}
echo json_encode($data);
?>