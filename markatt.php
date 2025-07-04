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

$query = "SELECT * from site"
    . ($isAdmin ? "" : " WHERE id = '$site_user_id'");
$siteResult = mysqli_query($conn, $query);

date_default_timezone_set("Asia/Kolkata");
$currentDate = date('Y-m-d');


if (isset($_POST['submit_attendance'])) {
    $site_id = mysqli_real_escape_string($conn, $_POST['site']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);

    if (!empty($_POST['attendance'])) {
        foreach ($_POST['attendance'] as $labour_id => $status) {
            $labour_id = mysqli_real_escape_string($conn, $labour_id);
            $status = mysqli_real_escape_string($conn, $status);
            $in_time = !empty($_POST['in_time'][$labour_id]) ? "'".mysqli_real_escape_string($conn, $_POST['in_time'][$labour_id])."'" : "NULL";
            $out_time = !empty($_POST['out_time'][$labour_id]) ? "'".mysqli_real_escape_string($conn, $_POST['out_time'][$labour_id])."'" : "NULL";

            $remarks = mysqli_real_escape_string($conn, $_POST['remarks'][$labour_id]);

            // Insert attendance data
            $insertQuery = "INSERT INTO labour_attendence (l_id, site_id, date, status, in_time, out_time, remarks) 
                            VALUES ('$labour_id', '$site_id', '$date', '$status', $in_time, $out_time, '$remarks')";
            mysqli_query($conn, $insertQuery);
        }
        $_SESSION['message'] = "Attendance recorded successfully!";
        ?>
        <meta http-equiv="refresh" content="0; url=http://localhost/construction/lattendenceview.php">
        <?php
    exit();
    } else {
        echo "<div class='error'>Please mark attendance for at least one laborer.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            /* display: inline-block; */
            /* flex-direction: column; */
            /* min-height: 100vh; */
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            /* flex: 1; */
            max-width: 90%;
            margin: 50px auto;
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        h2 {
            text-align: center;
        }
        select, input[type="date"], input[type="time"], input[type="text"] {
            width: 100%;
            padding: 3px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }

        
        .view {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .view a {
            text-decoration: none;
        }

        .view button {
            background: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            display: inline-block;
            text-transform: uppercase;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .view button:hover {
            background: #0056b3;
            transform: scale(1.05);
        }

        #ef{
            background-color: gray;
        }
        .btn1 {
            background: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }
        .btn1:hover {
            background: #0056b3;
        }

        button {
            /* position: absolute; */
            /* right: 100px; */
            /* top: 0; */
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .view{
            display: flex;
            justify-content: space-between;
        }

        .abc{
            text-decoration: none;
            margin-right: 5px;
        }
        .success {
            color: green;
            text-align: center;
            font-weight: bold;
        }
        .error {
            color: red;
            text-align: center;
            font-weight: bold;
        }

        .footer {
            /* background-color: #f4f4f4; */
            width: 100%;
            height: 100%;
            text-align: center;
            padding: 5px;
            margin-top: auto;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <h2>Mark Labour Attendance</h2>
        <div class="view">
            <a href="lattendenceview.php" class="abc"><button>View labour attendence</button></a>
            <a href="empattendence.php" class="abc"><button id="ef">Mark employee attendance</button></a>
        </div>
        
        <form method="post" action="">
        <label for="site">Select Site:</label>
        <select name="site" id="site" required>
            <option value="">Select</option>
            <?php while ($row = mysqli_fetch_assoc($siteResult)) { ?>
                <option value="<?php echo $row['id']; ?>" <?php if (isset($_POST['site']) && $_POST['site'] == $row['id']) echo 'selected'; ?>>
                    <?php echo $row['site_name']; ?>
                </option>
            <?php } ?>
        </select>

        <label for="date">Select Date:</label>
        <input type="date" name="date" value="<?php echo isset($_POST['date']) ? $_POST['date'] : $currentDate; ?>" required>
        
        <input type="submit" name="fetch_labours" value="Fetch Labour" class="btn1">
        </form>


        <?php
        if (isset($_POST['fetch_labours']) && !empty($_POST['site'])) {
            $selectedSite = $_POST['site'];
            $labourQuery = "SELECT id, lname, lrank FROM labour_page WHERE lsite = '$selectedSite'";



            $labourResult = mysqli_query($conn, $labourQuery);
            ?>

        <form method="post" action="">
            <input type="hidden" name="site" value="<?php echo $selectedSite; ?>">
            <input type="hidden" name="date" value="<?php echo $_POST['date']; ?>">
            <table>
                <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Rank</th>
                    <!-- <th>Shift</th> -->
                    <th>In Time</th>
                    <th>Out Time</th>
                    <th>Attendance Status</th>
                    <th>Remarks</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($labourResult)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['lname']; ?></td>
                        <td><?php echo $row['lrank']; ?></td>
                        <td><input type="time" name="in_time[<?php echo $row['id']; ?>]"></td>
                        <td><input type="time" name="out_time[<?php echo $row['id']; ?>]"></td>
                        <td>
                            <input type="radio" name="attendance[<?php echo $row['id']; ?>]" value="Present" required> Present
                            <input type="radio" name="attendance[<?php echo $row['id']; ?>]" value="Absent"> Absent
                            <input type="radio" name="attendance[<?php echo $row['id']; ?>]" value="Half-Day"> Half-Day
                        </td>
                        <td><input type="text" name="remarks[<?php echo $row['id']; ?>]"></td>
                    </tr>
                <?php } ?>
            </table>
            <input type="submit" name="submit_attendance" value="Submit Attendance" class="btn1">
        </form>



        <?php
        }
        ?>
    </div>
    <footer>
        <div class="footer">
            <?php include("footer.php");?>
        </div>
    </footer>
</body>
</html>