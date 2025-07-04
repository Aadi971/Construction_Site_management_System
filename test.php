<?php
ob_start(); // Start output buffering
session_start();
include("connection.php");
error_reporting(0);



$userprofile = $_SESSION['user_name'];  
if (!$userprofile) {
    header('location:login.php');
    exit();
}



$isAdmin = $_SESSION['is_admin']; 
$site_user_id = $_SESSION['site_id'];

$message = "";

if (isset($_POST['attendenceBtn'])) {
    date_default_timezone_set('Asia/Kolkata');
    $selected_date = $_POST['selected_date'] ?? date('Y-m-d');
    $today = date('Y-m-d');

    if ($selected_date != $today) {
        $_SESSION['attendance_message'] = "<div class='message error'>Please select the current date only for attendance.</div>";
    } else {
        $attendence_month = date('M', strtotime($selected_date));
        $attendence_year = date('Y', strtotime($selected_date));

        $shown_count = 0;

        if (isset($_POST['attendance'])) {
            foreach ($_POST['attendance'] as $e_id => $value) {
                list($status, $site_id) = explode("-", $value);

                
                $status_map = [
                    'present' => 'P',
                    'absent' => 'A',
                    'leave' => 'L',
                    'halfday' => 'H'
                ];

                $attendence = $status_map[$status] ?? '';

                if ($attendence) {
                    // duplicate attendance
                    $check = mysqli_query($conn, "SELECT * FROM update_material WHERE e_id = '$e_id' AND curr_date = '$selected_date'");
                    if (mysqli_num_rows($check) == 0) {
                        mysqli_query($conn, "INSERT INTO update_material(site_id, e_id, curr_date, attendence_month, attendance_year, attendence) 
                                             VALUES('$site_id', '$e_id', '$selected_date', '$attendence_month', '$attendence_year', '$attendence')");
                        $shown_count++;
                    }
                }
            }
        }

        if ($shown_count == 0) {
            $_SESSION['attendance_message'] = "<div class='message error'>All employees have already submitted attendance for this date.</div>";
        } else {
            $_SESSION['attendance_message'] = "<div class='message success bg-success'>Attendance submitted successfully!</div>";
        }
    }

    header("Location: tt.php");
    exit();
}

include("header/header.php");
ob_end_flush(); // End output buffering

?>

<!DOCTYPE html>
<html>
<head>
    <title>Mark Attendance</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f4f4f4;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background: #3f51b5;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .message {
            margin-bottom: 20px;
            padding: 10px;
            font-weight: bold;
            border-radius: 4px;
        }
        .message.success {
            color: green;
            background: #e0ffe0;
            border: 1px solid green;
        }
        .message.error {
            color: red;
            background: #ffe0e0;
            border: 1px solid red;
        }
        .submit-btn {
            padding: 10px 20px;
            background: #3f51b5;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        .submit-btn:hover {
            background: #303f9f;
        }
        input[type="date"] {
            padding: 8px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<?php echo $message; ?>
<h2>Today Attendance</h2>
<form action="" method="POST">
    <table>
        <tr>
            <th>ID</th>
            <th>Employee Name</th>
            <th>Site</th>
            <th>P</th>
            <th>A</th>
            <th>L</th>
            <th>H</th>
        </tr>

        <tbody id="employeeTableBody"></tbody>

        <input type="hidden" name="selected_date" id="selected_date" value="<?php echo date('Y-m-d'); ?>">

        <tr>
            <td colspan="7"><input type="submit" name="attendenceBtn" class="submit-btn" value="Submit Attendance"></td>
        </tr>
    </table>
</form>

<script>
$(document).ready(function() {
    function loadEmployees(date) {
        $.ajax({
            url: 'testing.php',
            type: 'POST',
            data: { 
                date: date,
                isAdmin: <?php echo json_encode($isAdmin); ?>,
                site_id: <?php echo json_encode($site_user_id); ?>
            },
            success: function(response) {
                $('#employeeTableBody').html(response);
            }
        });
    }

    let initialDate = $('input[name="selected_date"]').val();
    loadEmployees(initialDate);
});
</script>

</body>
</html>
