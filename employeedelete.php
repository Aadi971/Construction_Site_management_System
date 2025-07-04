<?php
include("connection.php");
error_reporting(0);

$id = $_GET['id'];
$query = "DELETE from employee where id='$id'";
$data = mysqli_query($conn,$query);

if($data>0){
    echo "<script>alert('Record deleted successfully');</script>";
    header("location:http://localhost/construction/display.php");
} 
else {
    echo "<script>alert('Something went wrong?');</script>";
}

?>