<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
</body>
</html>
<?php
include("connection.php");
error_reporting(0);
session_start();
$userprofile = $_SESSION['user_name'];  
if (!$userprofile) {
    header('location:login.php');
    exit();
}


if(isset($_POST['submit'])){
    $id = $_POST['employeeid'];
    $siteid = $_POST['site'];
    $remark = $_POST['remark'];
    $current_date = $_POST['curr_date'];
    $attendence = $_POST['attendence'];
    // $current_date = $_POST['curr_date'];
    // $attendancemonth = $_POST['attendence_month'];
    // $attendanceyear = $_POST['attendance_year'];
    // $current_date = date("Y-m-d");
    

   
    $query = "UPDATE update_material 
              SET remark = '$remark', attendence = '$attendence' 
              WHERE id = '$id' 
              AND site_id = '$siteid' 
              AND curr_date = '$current_date'";

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                title: 'Success!',
                text: 'Updated successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'tt.php?id=$site';
                }
            });
        </script>";
        exit();
    }
    else {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                title: 'Oops!',
                text: 'Something went wrong!',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                window.location.href = 'tt.php?id=$site';
            });
        </script>";
        exit();
    }
    
}
?>
