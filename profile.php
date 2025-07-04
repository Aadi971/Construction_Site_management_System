<?php
include("connection.php");
error_reporting(0);
session_start();
include("header/header.php");

$userprofile = $_SESSION['user_name'];
if (!$userprofile) {
    header('location:login.php');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // echo $id;
    // die;

    
    $query = "SELECT employee.*, site.site_name 
              FROM employee 
              INNER JOIN site ON employee.e_location = site.id
              WHERE employee.id = '$id'";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $labour = mysqli_fetch_assoc($result);
    } else {
        echo "<p class='text-center text-danger mt-5'>No details found for this labour.</p>";
        exit;
    }

    // Fetch
    $attendanceQuery = "SELECT * FROM update_material WHERE e_id = '$id' ORDER BY curr_date DESC";
    $attendanceResult = mysqli_query($conn, $attendanceQuery);

    // Count
    $totalPresent = $totalAbsent = $totalHalfDay = $totalLeave = 0;
    while ($row = mysqli_fetch_assoc($attendanceResult)) {
        if ($row['attendence'] == "P") {
            $totalPresent++;
        } elseif ($row['attendence'] == "A") {
            $totalAbsent++;
        }
         elseif ($row['attendence'] == "L") {
            $totalLeave++;
        } else {
            $totalHalfDay++;
        }
    }
    
    // attendance history display
    mysqli_data_seek($attendanceResult, 0);
} else {
    echo "<p class='text-center text-danger mt-5'>Invalid request.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Labour Profile - <?php echo $labour['lname']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 2px solid #007bff;
            object-fit: cover;
        }

        .text-center1{
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body class="bg-light">
    <?php ?>

<div class="container my-5">
    <h2 class="text-center mb-4">Employee Profile</h2>
    <div class="text-center1">
        <a href="employeeview.php" class="btn btn-primary">Back to employee List</a>
        <a href="attendanceview.php" class="btn btn-secondary">Back to Attendance List</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                
                <div class="col-md-3 text-center">
                    <?php if (!empty($labour['profile_picture'])) { ?>
                        <img src="<?php echo $labour['profile_picture']; ?>" class="profile-img" alt="Profile Picture">
                    <?php } else { ?>
                        <img src="photo4.jpg" class="profile-img" alt="Default Profile">
                    <?php } ?>
                </div>

                
                <div class="col-md-4">
                    <h4 class="card-title"><?php echo $labour['e_name']; ?></h4>
                    <p><strong>Age:</strong> <?php echo $labour['e_age']; ?></p>
                    <p><strong>DOB:</strong> <?php echo $labour['e_dob']; ?></p>
                    <p><strong>Site:</strong> <?php echo $labour['site_name']; ?></p>
                    <p><strong>Rank:</strong> <?php echo $labour['e_rank']; ?></p>
                    <p><strong>Contact:</strong> <?php echo $labour['e_mobile']; ?></p>
                    <p><strong>Address:</strong> <?php echo $labour['address']; ?></p>
                    
                </div>
                <div class="col-md-4">
                    <p><strong>Joining Date:</strong> <?php echo $labour['e_doj']; ?></p>
                    <p><strong>Bank:</strong> <?php echo $labour['e_bank_name']; ?></p>
                    <p><strong>Accound No:</strong> <?php echo $labour['e_account']; ?></p>
                    <p><strong>IFSC:</strong> <?php echo $labour['e_ifsc']; ?></p>
                    <p><strong>Pan No:</strong> <?php echo $labour['e_pan']; ?></p>
                    <p><strong>Aadhaar No:</strong> <?php echo $labour['e_aadhaar']; ?></p>
                    <p><strong>Salary:</strong> ₹<?php echo number_format($labour['e_salary'], 2); ?> per month</p>
                </div>
            </div>
        </div>
    </div>
    
    <h3 class="mt-4">Attendance Summary</h3>
    <div class="row text-center">
        <div class="col-md-4">
            <div class="alert alert-success"><strong>Present:</strong> <?php echo $totalPresent; ?> Days</div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-danger"><strong>Absent:</strong> <?php echo $totalAbsent; ?> Days</div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-warning"><strong>Leave-Day:</strong> <?php echo $totalLeave; ?> Days</div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-warning"><strong>Half-Day:</strong> <?php echo $totalHalfDay; ?> Days</div>
        </div>
        <div class="col-md-4">
                <?php 
                    $totalDays = ($totalPresent + $totalAbsent);
                    $attendancePercentage = ($totalPresent / $totalDays) * 100; 
                ?>
            <div class="alert alert-primary"><strong>Total:</strong> <?php echo number_format($attendancePercentage, 2) . "%"; ?></div>
        </div>
    </div>

    <h3 class="mt-4">Attendance History</h3>
    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                    <th>In Time</th>
                    <th>Out Time</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($attendanceResult) > 0) { ?>
                    <?php while ($row = mysqli_fetch_assoc($attendanceResult)) { ?>
                        <tr>
                            <td><?php echo $row['curr_date']; ?></td>
                            <td>
                                <?php
                                if ($row['attendence'] == "P") {
                                    echo "<span class='badge bg-success'>Present</span>";
                                } elseif ($row['attendence'] == "A") {
                                    echo "<span class='badge bg-danger'>Absent</span>";
                                } elseif ($row['attendence'] == "L") {
                                    echo "<span class='badge bg-warning'>Leave-Day</span>";
                                }else{
                                    echo "<span class='badge bg-secondary'>Half-Day</span>";
                                }
                                ?>
                            </td>
                            <td><?php echo $row['in_time'] ? $row['in_time'] : "--"; ?></td>
                            <td><?php echo $row['out_time'] ? $row['out_time'] : "--"; ?></td>
                            <td><?php echo $row['remark']; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr><td colspan='5' class='text-muted'>No attendance records found.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<?php include("header/footer.php");?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
