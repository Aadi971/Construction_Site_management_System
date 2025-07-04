<?php
include("connection.php");
error_reporting(0);
session_start();

function getAvailableQuantities($site_id, $conn) {
    // Fetch total added materials
    $query = "SELECT material_id, SUM(quantity) AS total_quantity 
              FROM site_material 
              WHERE site_id = $site_id AND status = 1 
              GROUP BY material_id";
    
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query Error (Added Materials): " . mysqli_error($conn));
    }
    $total_quantity = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Fetch total used materials
    $q = "SELECT material_id, SUM(quantity) AS total_zquantity 
          FROM site_material 
          WHERE site_id = $site_id AND status = 0 
          GROUP BY material_id";

    $res = mysqli_query($conn, $q);
    if (!$res) {
        die("Query Error (Used Materials): " . mysqli_error($conn));
    }
    $total_used = mysqli_fetch_all($res, MYSQLI_ASSOC);

    // Prepare available quantity array
    $available_quantities = [];

    foreach ($total_quantity as $row) {
        $material_id = $row['material_id'];
        $added_qty = $row['total_quantity'];
        $used_qty = 0;

        // Find the used quantity
        foreach ($total_used as $sub_row) {
            if ($sub_row['material_id'] == $material_id) {
                $used_qty = $sub_row['total_zquantity'];
                break;
            }
        }

        $available_quantities[$material_id] = $added_qty - $used_qty;
    }

    return $available_quantities;
}