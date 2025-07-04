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
error_reporting(0);
session_start();

if (isset($_POST['update'])) {
    $quantity = $_POST['quantity'];
    $site = $_POST['site'];
    $material = $_POST['material'];
    $status = 0;
    // echo $site;
    // die;

    $query = "SELECT material_id, status, SUM(quantity) AS total_quantity 
                FROM site_material 
                WHERE site_id = $site AND status = 1 AND material_id = $material
                GROUP BY material_id, status";

    $result = mysqli_query($conn, $query);
    $total_quantity = mysqli_fetch_all($result,  MYSQLI_ASSOC);


    $q = "SELECT material_id, status, SUM(quantity) AS total_zquantity 
                FROM site_material 
                WHERE site_id = $site AND status = 0 AND material_id = $material
                GROUP BY material_id, status";

    $res = mysqli_query($conn, $q);
    $total = mysqli_fetch_all($res,  MYSQLI_ASSOC);
    // print_r($total);
    // die;


    if (!empty($total_quantity)) {
        foreach ($total_quantity as $row) {
            $material_id = $row['material_id'];
            $added_qty = $row['total_quantity'];
            $used_qty = 0;
            

            foreach ($total as $sub_row) {
                if ($sub_row['material_id'] == $material_id) {
                    $used_qty = $sub_row['total_zquantity'];
                    break;
                }

               
            }
            // echo "added:".$added_qty;
            // echo "used:".$used_qty;
            // exit;
            $available_qty = $added_qty - $used_qty;
//             echo "available:".$available_qty;
//             // die;
// echo "<br>";
//             echo "quantity".$quantity;
//             die;
            
if ($quantity <= $available_qty) {
    $query1 = "INSERT INTO site_material (site_id, material_id, quantity, status) 
                VALUES ('$site', '$material', '$quantity', '$status')";
    $total1 = mysqli_query($conn, $query1);

    if ($total1) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                title: 'Success!',
                text: 'Material subtracted successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'sitematerial.php?id=$site';
                }
            });
        </script>";
        exit();
    }

} else {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            title: 'Oops!',
            text: 'Insufficient quantity available!',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then((result) => {
            window.location.href = 'sitematerial.php?id=$site';
        });
    </script>";
    exit();
}
   
            

              
            }

            
           
        }
        
    }






?>
