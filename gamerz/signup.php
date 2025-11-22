<?php  
include "connection.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$Uname=$_POST["uname"];
$mail=$_POST["mail"];
$pass=$_POST["pass"];
$role=$_POST["role"];

echo "$Uname,  $mail  ,$pass ,$role";

$sql=$conn->prepare("INSERT INTO signup (uname,mail,pass,role) VALUES (?,?,?,?)");
$sql->bind_param("ssss",$Uname,$mail,$pass,$role);
try{
if($sql->execute()){
    echo "<script>alert('signed up succesfully')</script>";
    setcookie("name",$Uname,time()+(360*12),"/");
    header("Location:create_profile.html");
}

else 
    echo "<script>alert('error in siging up')</script>";
}catch(mysqli_sql_exception $e)
{
    echo " <script>alert('error in siging up')</script>";
}

?>