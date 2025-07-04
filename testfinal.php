<?php
include("connection.php");
error_reporting(0);
session_start();
$userprofile = $_SESSION['user_name'];  
if (!$userprofile) {
    header('location:login.php');
    exit();
}
$shown_count = 0;

if (isset($_POST['attendenceBtn'])) {
    date_default_timezone_set('Asia/Kolkata');

    $selected_date = $_POST['selected_date'] ?? date('Y-m-d');
    $today = date('Y-m-d');
    

    if ($selected_date != $today) {
        $message = "<div class='message error'>Please select the current date only for attendance.</div>";
    } else {
        $attendence_month = date('M', strtotime($selected_date));
        $attendence_year = date('Y', strtotime($selected_date));

        $types = [
            'employeePresent' => 'P',
            'employeeAbsent' => 'A',
            'employeeLeave' => 'L',
            'employeeHalfDay' => 'H'
        ];

        foreach ($types as $field => $attendence) {
            if (isset($_POST[$field])) {
                foreach ($_POST[$field] as $data) {
                    list($e_id, $site_id) = explode("-", $data);

                    // Prevent duplicate attendance
                    $check = mysqli_query($conn, "SELECT * FROM update_material WHERE e_id = '$e_id' AND curr_date = '$selected_date'");
                    if (mysqli_num_rows($check) == 0) {
                        mysqli_query($conn, "INSERT INTO update_material(site_id, e_id, curr_date, attendence_month, attendance_year, attendence) 
                                             VALUES('$site_id', '$e_id', '$selected_date', '$attendence_month', '$attendence_year', '$attendence')");
                    }
                }
            }
        }

        
        if ($shown_count == 0) {
            $_SESSION['attendance_message'] = "<div class='message error'>All employees have already submitted attendance for this date.</div>";
        } else {
            $_SESSION['attendance_message'] = "<div class='message success'>Attendance submitted successfully!</div>";
        }
        header("Location: testing.php");
        exit();

        

       
    }
}
?>