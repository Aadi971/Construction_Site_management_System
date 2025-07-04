<?php
include("connection.php");
session_start();
include("header/header.php");

$isAdmin = $_SESSION['is_admin'];
$site_user_id = $_SESSION['site_id'];

$where = $isAdmin ? "" : "WHERE e.e_location = '$site_user_id'";


$query = "
    SELECT e.id, e.e_name, e.e_rank, e.e_location, site.site_name, 
        COUNT(a.id) as total_days,
        SUM(CASE WHEN a.status = 'Present' OR a.status = 'Half-Day' THEN 1 ELSE 0 END) as present_days
    FROM employee e
    LEFT JOIN site ON e.e_location = site.id
    LEFT JOIN attendence a ON e.id = a.l_id
    $where
    GROUP BY e.id
    ORDER BY e.e_name ASC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f4f8;
            margin: 0;
        }

        .container {
            width: 95%;
            max-width: 1100px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background: #007bff;
            color: white;
        }

        .red { background: #ffcccc; color: #b30000; font-weight: bold; }
        .yellow { background: #fff3cd; color: #856404; font-weight: bold; }
        .green { background: #d4edda; color: #155724; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h2>Attendance Summary</h2>
    <table>
        <tr>
            <th>S.No</th>
            <th>ID</th>
            <th>Employee Name</th>
            <th>Rank</th>
            <th>Site</th>
            <th>Total Days</th>
            <th>Present Days</th>
            <th>Attendance %</th>
        </tr>
        <?php 
        $sn = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $total = (int)$row['total_days'];
            $present = (int)$row['present_days'];
            $percentage = $total > 0 ? round(($present / $total) * 100) : 0;

            $class = $percentage >= 75 ? 'green' : ($percentage >= 50 ? 'yellow' : 'red');
        ?>
        <tr>
            <td><?= $sn++; ?></td>
            <td><?= $row['id']; ?></td>
            <td><?= $row['e_name']; ?></td>
            <td><?= $row['e_rank']; ?></td>
            <td><?= $row['site_name']; ?></td>
            <td><?= $total; ?></td>
            <td><?= $present; ?></td>
            <td class="<?= $class; ?>"><?= $percentage; ?>%</td>
        </tr>
        <?php } ?>
    </table>
</div>
<?php include("header/footer.php"); ?>
</body>
</html>
