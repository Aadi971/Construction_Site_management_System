<?php
include("connection.php");
error_reporting(0);
session_start();
$userprofile = $_SESSION['user_name'];  
if (!$userprofile) {
    header('location:login.php');
    exit();
}

$id = $_GET['id'] ?? null;
$siteid = $_GET['site'] ?? null;
$selectedMonth = $_GET['month'] ?? date('m');
$selectedYear = $_GET['year'] ?? date('Y');

if ($id && $siteid) {
    $query = "SELECT 
                update_material.*,
                employee.e_name AS employee_name, 
                site.site_name
              FROM update_material
              JOIN site ON update_material.site_id = site.id
              JOIN employee ON update_material.e_id = employee.id
              WHERE update_material.site_id = $siteid AND update_material.e_id = $id
              LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);

        $att_sql = "SELECT curr_date, attendence FROM update_material 
                    WHERE e_id = '$id' AND site_id = '$siteid' 
                    AND MONTH(curr_date) = '$selectedMonth' AND YEAR(curr_date) = '$selectedYear'";
        $att_result = mysqli_query($conn, $att_sql);

        $attendanceMap = [];
        while ($att_row = mysqli_fetch_assoc($att_result)) {
            $day = date('j', strtotime($att_row['curr_date']));
            $attendanceMap[$day] = $att_row['attendence'];
        }

        $response = '
        <style>
            table td, table th {
                border: 1px solid #ccc;
                padding: 8px;
                min-width: 30px;
                font-size: 14px;
            }
            th {
                background-color: #3f51b5;
                color: white;
            }
            td {
                text-align: center;
                font-weight: bold;
            }
        </style>

        <div class="modal-header" style="flex-direction: column; align-items: flex-start; padding: 20px; gap: 15px;">
            <div style="width: 100%; text-align: center;">
                <h2 class="modal-title font-weight" style="font-weight: bold; text-transform: capitalize; margin: 0;">
                    ' . htmlspecialchars($row['employee_name']) . '
                </h2>
            </div>

            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 10px;">
                <label>Site Name:</label>
                <input type="text" class="form-control form-control-sm" style="width: 150px;" value="' . htmlspecialchars($row['site_name']) . '" readonly>

                <label>Status:</label>
                <input type="text" class="form-control form-control-sm" style="width: 100px;" value="' . htmlspecialchars($row['attendence']) . '" readonly>
            </div>

            <!-- Filter Form -->
            <form id="monthYearForm" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 15px;">
                <input type="hidden" name="id" value="' . $id . '">
                <input type="hidden" name="site" value="' . $siteid . '">
                <label>Month:</label>
                <select name="month" class="form-control form-control-sm" style="width: 100px;">';

        for ($m = 1; $m <= 12; $m++) {
            $selected = ($selectedMonth == $m) ? 'selected' : '';
            $monthName = date('F', mktime(0, 0, 0, $m, 1));
            $response .= "<option value='$m' $selected>$monthName</option>";
        }

        $response .= '
                </select>
                <label>Year:</label>
                <select name="year" class="form-control form-control-sm" style="width: 100px;">';

        $currentYear = date('Y');
        for ($y = $currentYear - 5; $y <= $currentYear + 2; $y++) {
            $selected = ($selectedYear == $y) ? 'selected' : '';
            $response .= "<option value='$y' $selected>$y</option>";
        }

        $response .= '
                </select>
                <button type="submit" class="btn btn-sm btn-primary">View</button>
            </form>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; top: 10px; right: 10px;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>';

        $response .= '<div style="overflow-x: auto; margin-top: 20px;">
        <table style="border-collapse: collapse; width: 100%; text-align: center;">
            <tr>';
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $response .= '<th>' . $d . '</th>';
        }
        $response .= '</tr><tr>';
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dateStr = "$selectedYear-$selectedMonth-$d";
            $dayName = date('D', strtotime($dateStr));
            $response .= '<td style="background-color: #3f51b5; color: white;">' . $dayName . '</td>';
        }
        $response .= '</tr><tr>';
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $status = $attendanceMap[$d] ?? '-';
            switch ($status) {
                case 'P': $bg = 'green'; break;
                case 'A': $bg = 'red'; break;
                case 'L': $bg = 'yellow'; break;
                case 'H': $bg = 'orange'; break;
                default: $bg = 'white';
            }

            $response .= '<td style="background-color:' . $bg . '; color:' . ($bg != 'white' ? 'black' : '#333') . ';">' . $status . '</td>';
        }

        $response .= '</tr></table></div>';

        // AJAX Reload Script
        $response .= '
        <script>
            $("#monthYearForm").on("submit", function(e) {
                e.preventDefault();
                let url = "' . $_SERVER['PHP_SELF'] . '?' . 'id=' . $id . '&site=' . $siteid . '&" + $(this).serialize();
                $.get(url, function(data) {
                    $(".modal-view").html(data);
                });
            });
        </script>';

        echo $response;

    } else {
        echo '<p>No data found for the provided ID.</p>';
    }
} else {
    echo '<p>No ID or site provided.</p>';
}
?>
