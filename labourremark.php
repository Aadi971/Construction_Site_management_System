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

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $siteid = $_POST['site'];
    $today = date('Y-m-d');
    

    $query = "SELECT * FROM labour_attendence WHERE l_id = '$id' AND curr_date = '$today'";
    $result = mysqli_query($conn, $query);

    if($row = mysqli_fetch_assoc($result)){

        $response= '<div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Remark :</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="labourremarkquery.php" >
                        <input type="hidden" class="form-control" value="'.$siteid.'" name="site" placeholder="Site" required>
                        <input type="hidden" class="form-control" value="'.$id.'" name="id" placeholder="ID" required>
                        <input type="hidden" class="form-control" value="'.htmlspecialchars($row['curr_date']).'" name="curr_date" placeholder="ID" required>
                        <input type="hidden" class="form-control" value="'.htmlspecialchars($row['attendence_month']).'" name="attendence_month" placeholder="ID" required>
                        <input type="hidden" class="form-control" value="'.htmlspecialchars($row['attendance_year']).'" name="attendance_year" placeholder="ID" required>
                        <input type="texr" class="form-control" value="'. htmlspecialchars($row['attendence']) .'" name="attendence" placeholder="ID"><br>
                        <input type="text" name="remark" class="form-control" placeholder="Enter Remark" required>
                        <button type="submit" class="btn btn-primary float-right" name="submit">Save changes</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    
                </div>';

        echo $response;

    }

}
?>