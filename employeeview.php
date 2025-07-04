<?php
include("connection.php");
session_start();
include("header/header.php");

if (!isset($_SESSION['user_name'])) {
    header('location:login.php');
    exit();
}

$userprofile = $_SESSION['user_name'];
$is_admin = $_SESSION['is_admin'] ?? 0; // assumes admin flag is stored in session
$site_id = $_SESSION['site_id'] ?? 0;

// Sanitize search input
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Base query
$query = "SELECT employee.*, site.site_name 
          FROM employee 
          INNER JOIN site ON employee.e_location = site.id";

// Apply site filter only for non-admins
if (!$is_admin) {
    $query .= " WHERE employee.e_location = $site_id";
} else {
    // Admin can filter by search globally
    $query .= " WHERE 1"; // Always true condition to append ANDs
}

// Apply search if any
if (!empty($search)) {
    $query .= " AND (employee.id LIKE '%$search%' OR employee.e_name LIKE '%$search%' OR site.site_name LIKE '%$search%' OR employee.e_age LIKE '%$search%')";
}

$query .= " ORDER BY employee.id ASC";

$data = mysqli_query($conn, $query);
$total = mysqli_num_rows($data);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee View Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            flex: 1;
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            background-color: #fff;
            border-collapse: collapse;
            margin: 20px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            word-wrap: break-word;
        }

        th {
            background-color: #f4f4f4;
        }

        td img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
        }

        .form {
            text-align: center;
            margin-bottom: 20px;
        }

        .form input[type="text"] {
            padding: 8px;
            width: 80%;
            max-width: 300px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }

        .form input[type="submit"] {
            padding: 8px 16px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button {
            /* background-color: #007bff; */
            color: #fff;
            border: none;
            /* padding: 8px 10px; */
            /* border-radius: 4px; */
            cursor: pointer;
        }

        .abc {
            text-decoration: none;
        }

        button:hover {
            background-color: #0056b3;
        }

        .delete-btn {
            background-color: #dc3545;
            margin: 3px;
            padding: 8px 12px;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        @media screen and (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
            }

            th, td {
                padding: 8px;
                font-size: 14px;
            }

            td img {
                width: 60px;
                height: 60px;
            }
        }

        
        /* footer {
            background-color: #f4f4f4;
            text-align: center;
            padding: 10px;
            margin-top: auto;
        } */
    </style>
</head>
<body>
    <?php  ?>

    <div class="container">
        <h1>Employee Details</h1>
        <div class="form">
            <form action="#" method="GET">
                <input type="text" placeholder="Search here" name="search">
                <input type="submit" value="Search">
            </form>
        </div>

        <?php if ($total) { ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Site</th>
                <th>Rank</th>
                <th>Joining</th>
                <th>Bank Name</th>
                <th>Account No</th>
                <th>IFSC</th>
                <th>Pan</th>
                <th>Aadhar</th>
                <th>Salary</th>
                <th>More</th>
            </tr>

            <?php while ($result = mysqli_fetch_assoc($data)) { ?>
                <tr>
                    <td><?php echo $result['id']; ?></td>
                    <td><?php echo $result['e_name']; ?></td>
                    <td><?php echo $result['e_age']; ?></td>
                    <td><?php echo $result['site_name']; ?></td>
                    <td><?php echo $result['e_rank']; ?></td>
                    <td><?php echo $result['e_doj']; ?></td>
                    <td><?php echo $result['e_bank_name']; ?></td>
                    <td><?php echo $result['e_account']; ?></td>
                    <td><?php echo $result['e_ifsc']; ?></td>
                    <td><?php echo $result['e_pan']; ?></td>
                    <td><?php echo $result['e_aadhaar']; ?></td>
                    <td><?php echo $result['e_salary']; ?></td>
                    <td>
                        <a href="profile.php?id=<?php echo $result['id']; ?>"><li class="fas fa-play"></li></a>
                        <a href="employeeedit.php?id=<?php echo $result['id']; ?>"><li class="fa fa-edit"></li></a>
                        <a href="employeedelete.php?id=<?php echo $result['id']; ?>"><li class="fa fa-trash" onclick="return confirm('Are you sure want to delete this record');"></li></a>
                    </td>



                    
                </tr>
            <?php } ?>
        </table>
        <?php } else { ?>
            <p style="text-align: center; font-size: 18px; color: red;">No employee records found.</p>
        <?php } ?>
    </div>

    <?php include("header/footer.php"); ?>
</body>
</html>
