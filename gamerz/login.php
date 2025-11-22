<?php
include "connection.php";

$pass=$_POST["pass"];
$uname=$_POST["uname"];
$sql=$conn->prepare("SELECT * from signup where uname=?");
$sql->bind_param("s",$uname);
$sql->execute();
$result=$sql->get_result();
$res=$result->fetch_assoc();
if($res)
{  
    if($res["pass"]==$pass){
        setcookie("name",$res['uname'],time()+(360*12),"/");
        header("Location:home1.php");
    }

    else
        {
        echo"
         <script>
            alert('Your password is wrong please enter password again');
            window.location.href='game.html';
            </script>";
          

    }
}
else
{
    echo "<script>
            alert('You havenot sign up yet please sign up for login ');
            window.location.href='REG_GAME.html'
        </script>";
}

?>