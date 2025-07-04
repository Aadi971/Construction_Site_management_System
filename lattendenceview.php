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

$isAdmin = $_SESSION['is_admin'];
$site_user_id = $_SESSION['site_id'];
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$currentdate = date('Y-m-d');

$whereClauses = [];

if (!$isAdmin) {
    $whereClauses[] = "labour_attendence.site_id = '$site_user_id'";
}

if (!empty($search)) {
    $searchFilter = "(labour_page.id LIKE '%$search%' 
                      OR labour_page.lname LIKE '%$search%' 
                      OR site.site_name LIKE '%$search%')";
    $whereClauses[] = $searchFilter;
}

$whereSQL = "";
if (count($whereClauses) > 0) {
    $whereSQL = " WHERE " . implode(" AND ", $whereClauses);
}

$query = "SELECT labour_page.id as l_id, site.site_name, labour_page.lname,
                 COUNT(labour_attendence.id) as total_status
          FROM labour_attendence 
          INNER JOIN site ON labour_attendence.site_id = site.id
          LEFT JOIN labour_page ON labour_attendence.l_id = labour_page.id
          $whereSQL
          GROUP BY labour_page.id, labour_page.lname, site.site_name
          ORDER BY labour_page.id ASC";


$data = mysqli_query($conn, $query);
$total = mysqli_num_rows($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Labour Attendance</title>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #f0f2f5;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .container {
        flex: 1;
        max-width: 1200px;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    h1 {
        text-align: center;
        color: #333;
        margin-bottom: 25px;
        font-size: 2rem;
        font-weight: 600;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    }

    th, td {
        padding: 14px;
        text-align: center;
        border-bottom: 1px solid #eee;
        word-break: break-word;
    }

    th {
        background-color: #f7f9fc;
        color: #444;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 14px;
    }

    td {
        background-color: #fff;
        color: #333;
        font-size: 15px;
    }

    td img {
        width: 75px;
        height: 75px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    td img:hover {
        transform: scale(1.05);
    }

    .form {
        text-align: center;
        margin-bottom: 30px;
    }

    .form input[type="text"] {
        padding: 10px;
        width: 80%;
        max-width: 300px;
        font-size: 15px;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-right: 10px;
    }

    .form input[type="submit"] {
        padding: 10px 20px;
        background-color: #28a745;
        color: #fff;
        font-size: 15px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form input[type="submit"]:hover {
        background-color: #218838;
    }

    button {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 14px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease-in-out;
    }

    button:hover {
        background-color: #0056b3;
    }

    .view {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .view a {
        text-decoration: none;
    }

    .view button {
        background-color: #007bff;
        padding: 12px 24px;
        font-size: 15px;
        border-radius: 8px;
        font-weight: bold;
        text-transform: uppercase;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: 0.3s ease;
    }

    .view button:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    #ef {
        background-color: #6c757d;
    }

    .delete-btn {
        background-color: #dc3545;
        padding: 10px 14px;
        font-size: 14px;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .delete-btn:hover {
        background-color: #c82333;
    }

    @media screen and (max-width: 768px) {
        .container {
            padding: 20px;
        }

        table {
            display: block;
            overflow-x: auto;
        }

        th, td {
            padding: 10px;
            font-size: 13px;
        }

        td img {
            width: 60px;
            height: 60px;
        }

        .form input[type="text"],
        .form input[type="submit"] {
            width: 100%;
            margin-bottom: 10px;
        }

        .view {
            flex-direction: column;
            align-items: center;
        }
    }
</style>

</head>
<body>
    
    <div class="container">
        <h1>Labour Attendance Details</h1>
        <div class="form">
            <div class="view">
                <a href="attendanceview.php"><button>Go to employee attendance</button></a>
                <a href="markatt.php"><button id="ef">Mark labour attendance</button></a>
            </div>
            <form action="#" method="GET">
                <input type="text" placeholder="Search here" name="search">
                <input type="submit" value="Search">
            </form>

            <?php
            if (isset($_SESSION['message'])) {
                echo "<div class='success'>" . $_SESSION['message'] . "</div>";
                unset($_SESSION['message']);
            }
            
            ?>
        </div>

        <?php if ($total) { ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Site name</th>
                <th>Total Status (Present)</th>
                <th>More</th>
            </tr>

            <?php while ($result = mysqli_fetch_assoc($data)) { ?>
                <tr>
                    <td><?php echo $result['l_id']; ?></td>
                    <td><?php echo $result['lname']; ?></td>
                    <td><?php echo $result['site_name']; ?></td>
                    <td><?php echo $result['total_status']; ?></td>
                    <td><a href="lprofile.php?id=<?php echo $result['l_id']; ?>"><button>More details</button></a></td>
                </tr>
            <?php } ?>
        </table>
        <?php } else { ?>
            <p style="text-align: center; font-size: 18px; color: red;">No attendance records found.</p>
        <?php } ?>
    </div>

    <?php include("header/footer.php"); ?>
</body>
</html>