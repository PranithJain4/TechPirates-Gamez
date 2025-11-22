<?php
include "connection.php";

$bio=$_POST['bio'];
$gt=$_POST['gt'];
$headline=$_POST['headline'];
$bio=$_POST['bio'];
$game=$_POST['games'];
$skills=$_POST['skills'];
$prole=$_POST['prole'];
$uname=$_COOKIE["name"];
$f=$_FILES['imag'];
$targetd="uploads/";
$profpath=$targetd.$f["name"];

if(!file_exists($targetd))
    mkdir($targetd,0777,true);

if(isset($f)){
    $fileNam=$f["name"];
    $filetm=$f["tmp_name"];
    $fileer=$f["error"];

    if($fileer===0){
$sql=$conn->prepare("UPDATE signup
SET gametag=?, bio=?, headline=?, games=?, skills=?,profile_pic=?
WHERE uname=?;");
$sql->bind_param("sssssss",$gt,$bio,$headline,$game,$skills,$profpath,$uname);
if($sql->execute())
{
    header("Location:home1.php");
    echo "<script>alert('profile saved succesfully')</script>";
}
else
{
    echo "<script>alert('error in saving profile');</script>";
    // header("Location:create_profile.html");
    
}
}
else{
      echo "<script>alert('error in file!!!')</script>";
    // header("Location:create_profile.html");
  
}
}else
{
    echo "<script>alert('please select the profile pic !!!')</script>";
    // header("Location:create_profile.html");
     
}


?>