<?php
include("connection.php");
error_reporting(0);
session_start();
$userprofile = $_SESSION['user_name'];  
if (!$userprofile) {
    header('location:login.php');
    exit();
}

$current_date = date("Y-m-d");

if(isset($_POST['id']) && isset($_POST['site'])){
    $eid = $_POST['id'];       
    $siteid = $_POST['site'];
    $today = date('Y-m-d');    

    
    $query = "SELECT * FROM update_material WHERE e_id = '$eid' AND curr_date = '$today'";
    $result = mysqli_query($conn, $query);

    if($row = mysqli_fetch_assoc($result)){

        $response= '<div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Remark :</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="testquery.php" >
                        <input type="hidden" name="site" value="'.$siteid.'">
                        <input type="hidden" name="employeeid" value="'.$row['id'].'">
                        <input type="hidden" name="curr_date" value="'.htmlspecialchars($row['curr_date']).'">
                        <input type="hidden" name="attendence_month" value="'.htmlspecialchars($row['attendence_month']).'">
                        <input type="hidden" name="attendance_year" value="'.htmlspecialchars($row['attendance_year']).'">

                        <input type="text" class="form-control" name="attendence" value="'. htmlspecialchars($row['attendence']) .'" placeholder="Attendance (P/A/L)"><br>

                        <input type="text" name="remark" class="form-control" placeholder="Enter Remark" required>

                        <button type="submit" class="btn btn-primary float-right" name="submit">Save changes</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>';

        echo $response;
    } else {
        echo "<div class='modal-body'>No attendance record found for today!</div>";
    }
}
?>