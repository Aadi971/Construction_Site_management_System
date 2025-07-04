<?php
include("connection.php");
error_reporting(0);

session_start();
include("header/header.php");

$userprofile = $_SESSION['user_name'];
if (!$userprofile) {
    header('location:login.php');
}

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger text-center'>No Labour ID provided.</div>";
    exit;
}

$labour_id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch Labour
$labourQuery = "SELECT labour_page.*, site.site_name 
FROM labour_page 
INNER JOIN site ON labour_page.lsite = site.id 
WHERE labour_page.id = '$labour_id'";

$labourResult = mysqli_query($conn, $labourQuery);
$labour = mysqli_fetch_assoc($labourResult);

if (!$labour) {
    echo "<div class='alert alert-danger text-center'>Labour record not found.</div>";
    exit;
}

// Fetch Attendance Record
$attendanceQuery = "SELECT * FROM labour_attendence WHERE l_id = '$labour_id' ORDER BY date DESC";
$attendanceResult = mysqli_query($conn, $attendanceQuery);

// Attendance summary count
$totalPresent = 0;
$totalAbsent = 0;
$totalHalfDay = 0;

while ($row = mysqli_fetch_assoc($attendanceResult)) {
    if ($row['attendence'] == "P") {
        $totalPresent++;
    } elseif ($row['attendence'] == "A") {
        $totalAbsent++;
    } elseif ($row['attendence'] == "H") {
        $totalHalfDay++;
    }
}
mysqli_data_seek($attendanceResult, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Labour Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">  
    <style>
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #007bff;
        }

        .text-center1{
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>



<div class="container my-5">
    <h2 class="text-center mb-4">Labour Profile</h2>
            <div class="text-center1">
                <a href="display.php" class="btn btn-primary">Back to labour List</a>
                <a href="lattendenceview.php" class="btn btn-secondary">Back to attendence List</a>
            </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                
                <div class="col-md-3 text-center">
                    <?php if (!empty($labour['profile_picture'])) { ?>
                        <img src="<?php echo $labour['profile_picture']; ?>" class="profile-img" alt="Profile Picture">
                    <?php } else { ?>
                        <img src="photo3.jpg" class="profile-img" alt="Default Profile">
                    <?php } ?>
                </div>

                
                <div class="col-md-9">
                    <h4 class="card-title"><?php echo $labour['lname']; ?></h4>
                    <p><strong>Rank:</strong> <?php echo $labour['lrank']; ?></p>
                    <p><strong>Site:</strong> <?php echo $labour['site_name']; ?></p>
                    <p><strong>Contact:</strong> <?php echo $labour['contact']; ?></p>
                    <p><strong>Address:</strong> <?php echo $labour['address']; ?></p>
                    <p><strong>Joining Date:</strong> <?php echo $labour['joining']; ?></p>
                    <p><strong>Salary:</strong> ₹<?php echo number_format($labour['lsalary'], 2); ?> per month</p>
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
            <div class="alert alert-warning"><strong>Half-Day:</strong> <?php echo $totalHalfDay; ?> Days</div>
        </div>
        <div class="col-md-4">
            <?php
              $totalDays = ($totalPresent + $totalAbsent);
              $attendencePercentage = ($totalPresent / $totalDays) * 100;
            ?>
            <div class="alert alert-primary"><strong>Total:</strong> <?php echo number_format($attendencePercentage); ?> %</div>
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
                <?php while ($row = mysqli_fetch_assoc($attendanceResult)) { ?>
                    <tr>
                        <td><?php echo $row['date']; ?></td>
                        <td>
                            <?php
                            if ($row['attendence'] == "P") {
                                echo "<span class='badge bg-success'>Present</span>";
                            } elseif ($row['attendence'] == "A") {
                                echo "<span class='badge bg-danger'>Absent</span>";
                            } else {
                                echo "<span class='badge bg-warning'>Half-Day</span>";
                            }
                            ?>
                        </td>
                        <td><?php echo $row['in_time'] ? $row['in_time'] : "--"; ?></td>
                        <td><?php echo $row['out_time'] ? $row['out_time'] : "--"; ?></td>
                        <td><?php echo $row['remarks']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<?php include("header/footer.php");?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
