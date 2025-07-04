<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "construction";

$conn = mysqli_connect($servername,$username,$password,$dbname);

if($conn){
    // echo "ok";
}else{
    echo "failed".mysqli_connect_error();
}

?>