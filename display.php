<?php
include("connection.php");
error_reporting(0);
session_start();
include("header/header.php");

$userprofile = $_SESSION['user_name'];
if (!$userprofile) {
    header('location:login.php');
    exit();
}

$is_admin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : 0;
$site_id = $_SESSION['site_id'];
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';



$query = "SELECT labour_page.*, site.site_name 
          FROM labour_page 
          LEFT JOIN site ON labour_page.lsite = site.id 
          WHERE 1";

if (!$is_admin) {
    $query .= " AND labour_page.lsite = $site_id";
}

if (!empty($search)) {
    $query .= " AND (labour_page.id LIKE '%$search%' OR labour_page.lname LIKE '%$search%' OR site.site_name LIKE '%$search%' OR labour_page.lage LIKE '%$search%')";
}



$query .= " ORDER BY labour_page.id ASC";

$data = mysqli_query($conn, $query);
$total = mysqli_num_rows($data);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Labour View Page</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
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
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .abc{
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

        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            padding-top: 50px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
        }

        .modal-content {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 90%;
        }

        #caption {
            margin: 15px auto;
            text-align: center;
            color: #ccc;
            font-size: 18px;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }
        td a{
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <?php  ?>
    <div class="container">
        <h1>Labour Details</h1>

        <div class="form">
            <form action="#" method="GET">
                <input type="text" placeholder="Search here" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <input type="submit" value="Search">
            </form>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Rank</th>
                <th>Shift</th>
                <th>Salary</th>
                <th>Site</th>
                <th>Edit</th>
            </tr>

            <?php
            if ($total > 0) {
                while ($result = mysqli_fetch_assoc($data)) { ?>
                    <tr>
                        <td class="p-3"><?php echo $result['id']; ?></td>
                        <td class="p-3"><?php echo $result['lname']; ?></td>
                        <td class="p-3"><?php echo $result['lage']; ?></td>
                        <td class="p-3"><?php echo $result['lrank']; ?></td>
                        <td class="p-3"><?php echo $result['lshift']; ?></td>
                        <td class="p-3"><?php echo $result['lsalary']; ?></td>
                        <td class="p-3"><?php echo $result['site_name']; ?></td>
                        <td >
                            <a href="update.php?id=<?php echo $result['id']; ?>">
                                <li class="fa fa-edit"></li>
                            </a>
                            <a href="delete.php?id=<?php echo $result['id']; ?>">
                                <li class="fa fa-trash" onclick="return confirm('Are you sure want to delete this record');"></li>
                            </a>
                            <a href="lprofile.php?id=<?php echo $result['id']; ?>"><li class="fa fa-play"></li></a>
                        </td>
                        
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="8">No record found</td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php include("footer.php"); ?>
</body>
</html>
