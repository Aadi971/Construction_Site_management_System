<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
</body>
</html>
<?php
include("connection.php");

session_start();

if (isset($_POST['submit'])) {
    // $date = $_POST['date'];
    $quantity = $_POST['quantity'];
    $site = $_POST['site'];
    $material = $_POST['material'];
    $addition = isset($_POST['1']) ? $_POST['1'] : 1;

    $sql = "INSERT INTO site_material (site_id, material_id, quantity, status) VALUES ('$site', '$material', '$quantity', '$addition')";

    // if (mysqli_query($conn, $sql)) {
    //     $_SESSION['message'] = "Record added successfully!";
    // } else {
    //     $_SESSION['message'] = "Error: " . mysqli_error($conn);
    // }

    if (mysqli_query($conn, $sql)) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                title: 'Success!',
                text: 'Material added successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'sitematerial.php?id=$site';
                }
            });
        </script>";
        exit();
    } else {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                title: 'Oops!',
                text: 'Something went wrong!',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                window.location.href = 'sitematerial.php?id=$site';
            });
        </script>";
        exit();
    }

} 


//     header('Location: sitematerial.php?id=' . $site); // Adjust to your actual page
//     exit();
// }





?>