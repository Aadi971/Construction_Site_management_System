<?php
include("connection.php");
session_start();
include("header/header.php");
error_reporting(0);

$userprofile = $_SESSION['user_name'] ?? null;
$is_admin = $_SESSION['is_admin'] ?? 0;

if (!$userprofile) {
    header('location:login.php');
    exit();
}

$site_id = $_SESSION['site_id'] ?? 0;

$query = "
SELECT material.name AS material_name, site.site_name, site_material.*
FROM site_material
JOIN material ON site_material.material_id = material.id
JOIN site ON site_material.site_id = site.id
";

if (!$is_admin) {
    $query .= " WHERE site_material.site_id = $site_id";
}

$data = mysqli_query($conn, $query);
$total = mysqli_num_rows($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    
    <!-- <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet"> -->

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #000;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: white;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        .container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        h1 {
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        p {
            font-size: 18px;
            opacity: 0.9;
        }

        .welcome-box {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
        }

        /* .btn {
            display: inline-block;
            padding: 12px 24px;
            margin-top: 20px;
            color: white;
            background: #007bff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            text-decoration: none;
            transition: 0.3s ease;
        } */

        /* .btn:hover {
            background: #0056b3;
        } */

        @media (max-width: 600px) {
            h1 {
                font-size: 28px;
            }

            p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
<?php ?>

<div class="overlay"></div>

<div class="container">
    <div class="welcome-box">
    <h1>Welcome, <?php echo htmlspecialchars($userprofile); ?>!</h1>
        <?php if ($is_admin): ?>
            <p style="color: #00ff99;">(Maalik aa gye)</p>
        <?php endif; ?>

        <p>Enjoy your time on our platform.</p>
        <a href="#" class="btn">Go to Profile</a>
    </div>
    
</div>

<?php include("header/footer.php");?>

</body>
</html>
