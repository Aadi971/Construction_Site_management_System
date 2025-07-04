<?php
include("connection.php");
function Insert($tableName, $data){
    global $conn;
    $table = $tableName;
    $column = array_keys($data);
    $value = array_values($data);
    $finalColumn = implode(",", $column);
    $finalValue = "'". implode("','", $value)."'";

    $query = "INSERT INTO $table($finalColumn) VALUES($finalValue)";
    $result = mysqli_query($conn, $query);
    return $result;
}

?>