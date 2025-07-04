<?php
include("connection.php");
error_reporting(0);

session_start();
include("header/header.php");

$userprofile = $_SESSION['user_name'];
if (!$userprofile) {
    header('location:login.php');
}

$isAdmin = $_SESSION['is_admin'];
$site_user_id = $_SESSION['site_id'];

$siteQuery = "SELECT * FROM site"
    . ($isAdmin ? "" : " WHERE id = '$site_user_id'");
$siteResult = mysqli_query($conn, $siteQuery);

date_default_timezone_set("Asia/Kolkata");
$currentDate = date('Y-m-d');

$selected_site = isset($_POST['site']) ? $_POST['site'] : '';
$selected_date = isset($_POST['date']) ? $_POST['date'] : $currentDate;
// echo $selected_date;
// die;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Filter Attendance</h4>
        </div>
        <div class="card-body">
            <form method="post" action="">
                <div class="row">
                    <div class="col-md-5 mt-3">
                        <label for="site" class="form-label">Select Site:</label>
                        <select name="site" id="site" class="form-select" required>
                            <option value="">Select</option>
                            <?php while ($row = mysqli_fetch_assoc($siteResult)) { ?>
                                <option value="<?php echo $row['id']; ?>" <?php if ($selected_site == $row['id']) echo 'selected'; ?>>
                                    <?php echo $row['site_name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-5 mt-3">
                        <label for="date" class="form-label">Select Date:</label>
                        <input type="date" name="date" id="date" value="<?php echo $selected_date; ?>" class="form-control" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end mt-3">
                        <button type="submit" name="fetch_labours" class="btn btn-success w-100">Fetch Labour</button>
                    </div>
                    <div class="col-md-2 d-flex align-items-end mt-3">
                        <button type="submit" name="fetch_employee" class="btn btn-success w-100">Fetch Employee</button>
                    </div>
                </div>
            </form>
        </div>


    </div>

    <div class="card mt-4">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Attendance List</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    
                    <tbody>
                        <?php
                        //labour details
                        if (isset($_POST['fetch_labours'])) {
                            $_SESSION['success_message'] = 'Labour attendance was fetch successful!';
                            $_SESSION['error_message'] = 'Something went wrongl!';
                            if (isset($_SESSION['success_message'])) {?>
                                
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Labour Name</th>
                                    <th>Site</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Total %</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <?php
                            $query = "SELECT labour_attendence.*, site.site_name, labour_page.lname
                                      FROM labour_attendence 
                                      INNER JOIN site ON labour_attendence.site_id = site.id
                                      INNER JOIN labour_page ON labour_attendence.l_id = labour_page.id 
                                      WHERE labour_attendence.date = '$selected_date' 
                                      AND labour_attendence.site_id = '$selected_site'";

                                      

                            $result = mysqli_query($conn, $query);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>

                                        <tr>
                                            <td><?php echo $row['l_id']; ?></td>
                                            <td><?php echo $row['lname']; ?></td>
                                            <td><?php echo $row['site_name']; ?></td>
                                            <td><?php echo $row['date']; ?></td>
                                            <td><?php echo $row['status']; ?></td>
                                            <td>
                                                <?php 
                                                    // $totalDays = 30;
                                                    $presentDays = ($row['status'] == 'Present') ? 1 : 0;
                                                    $absentDays = ($row['status'] == 'Absent') ? 1 : 0;
                                                    $totalDays = ($presentDays + $absentDays);
                                            
                                                    $attendancePercentage = ($presentDays / $totalDays) * 100;
                                                    echo number_format($attendancePercentage, 2) . "%"; 
                                                ?>
                                            </td>

                                            <td><a href="lprofile.php?id=<?php echo $row['l_id']; ?>"><button>More details</button></a></td>
                                        </tr>

                                          <?php
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";
                            }
                            echo '<div class="success-message">'
                                    . $_SESSION['success_message'] . '</div>';
                              
                                unset($_SESSION['success_message']); 
                            }else{
                                echo '<div class="error_message">'
                                . $_SESSION['error_message'] . '</div>';
                            }

                           
                        }
                        ?>

                        

                        <?php
                        //employee details 
                        if (isset($_POST['fetch_employee'])) {

                            $_SESSION['success_message'] = 'Employee attendence was fetch successful!';
                            $_SESSION['error_message'] = 'Something went wrongl!';
                                    if (isset($_SESSION['success_message'])) {?>


                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Employee Name</th>
                                                <th>Site</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Total %</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                            <?php
                            $query = "SELECT attendence.*, site.site_name, employee.e_name
                                      FROM attendence 
                                      LEFT JOIN site ON attendence.site_id = site.id
                                      LEFT JOIN employee ON attendence.l_id = employee.id 
                                      WHERE attendence.cur_date = '$selected_date' 
                                      AND attendence.site_id = '$selected_site'";

                                      

                                      $result1 = mysqli_query($conn, $query);
                            
                                        if (mysqli_num_rows($result1) > 0) {
                                            while ($row1 = mysqli_fetch_assoc($result1)) {
                                       
                                    ?>
                                        <tr>
                                            <td><?php echo $row1['l_id']; ?></td>
                                            <td><?php echo $row1['e_name']; ?></td>
                                            <td><?php echo $row1['site_name']; ?></td>
                                            <td><?php echo $row1['date']; ?></td>
                                            <td><?php echo $row1['status']; ?></td>
                                            <td>
                                                <?php 
                                                    // $totalDays = 30;
                                                    $presentDays = ($row1['status'] == 'Present') ? 1 : 0;
                                                    $absentDays = ($row1['status'] == 'Absent') ? 1 : 0;
                                                    $halfDay = ($row1['status'] == 'Half-Day') ? 1: 0;
                                                    $totalDays = ($presentDays + $absentDays + $halfDay);
                                                    $finalPresent = ($presentDays + $halfDay);
                                                
                                                    $attendancePercentage = ($finalPresent / $totalDays) * 100;
                                                    echo number_format($attendancePercentage, 2) . "%"; 
                                                ?>
                                            </td>
                                            <td><a href="profile.php?id=<?php echo $row1['l_id']; ?>"><button>More details</button></a></td>
                                        </tr>

                                        <?php
                                }
                        } else {
                                echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";
                            }

                            echo '<div class="success-message">'
                                . $_SESSION['success_message'] . '</div>';
                            
                            unset($_SESSION['success_message']); 
                            }else{
                            echo '<div class="error_message">'
                            . $_SESSION['error_message'] . '</div>';
                            }
                        }
                        ?>
                    
                    </tbody>
                </table>
            </div>
        </div>

    </div>


      
                    

                      
</div>
<?php include("header/footer.php");?>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>



