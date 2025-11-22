<?php 
$db="railway";
$pass="zMNJbSloCLCRSMMQnYcYaNJJJXzQceas";
$host="tramway.proxy.rlwy.net";
$user="root";
$port=44141;

$conn=mysqli_connect($host,$user,$pass,$db,$port);

if(!$conn)
{
echo "<script>alert('not connected')</script>";
}

?>