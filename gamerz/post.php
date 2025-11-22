<?php 
include "connection.php";

$d=$_POST["desc"];
$file=$_FILES["img"];
$n=$_COOKIE["name"];

$targetdir="profile_ims/";
$filepath=$targetdir.$file["name"];

if(!file_exists($targetdir))
    mkdir($targetdir,0777,true);

if(isset($file)){
    $fileName=$file["name"];
    $filetmp=$file["tmp_name"];
    $fileerr=$file["error"];
   
    if($fileerr===0)
    {
        print_r($fileName,$filetmp);
        if(move_uploaded_file($filetmp,$filepath))
        {  try{
            echo "<script>alert('uploded')</script>";     
            $sql=$conn->prepare("INSERT INTO post (uname,imgpath,content) values (?,?,?)");
            $sql->bind_param("sss",$n,$filepath,$d);
            $result=$sql->execute();
            header("Location:home1.php");
            if(!$result)
                echo "<script>alert('error in storing  ')</script>";
        }catch(error)
        {
            echo "<script>alert('error in storing  ')</script>";
        }
        }
        else
             echo "<script>alert('error in uploding ')</script>";

    }
    else
        echo "<script>alert('error in file  ')</script>";

}



?>