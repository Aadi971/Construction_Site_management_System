<?php
include("connection.php");
session_start();

$userprofile = $_SESSION['user_name'];  
if (!$userprofile) {
    header('location:login.php');
    exit();
}

$selected_date = $_POST['date'] ?? date('Y-m-d');
$isAdmin = $_POST['isAdmin'] ?? 0;
$site_id = $_POST['site_id'] ?? null;

// Build query based on role
if ($isAdmin) {
    $fetchingQuery = "SELECT labour_page.*, site.site_name, site.id as site_id 
                      FROM labour_page
                      INNER JOIN site ON labour_page.lsite = site.id";
} else {
    $fetchingQuery = "SELECT labour_page.*, site.site_name, site.id as site_id 
                      FROM labour_page
                      INNER JOIN site ON labour_page.lsite = site.id
                      WHERE site.id = '$site_id'";
}

$data = mysqli_query($conn, $fetchingQuery);

while ($row = mysqli_fetch_array($data)) {
    $id = $row['id'];
    $employee = $row['lname'];
    $site = $row['site_name'];
    $site_id = $row['site_id'];

    // Check attendance already submitted
    $checkQuery = "SELECT * FROM labour_attendence WHERE l_id = '$id' AND curr_date = '$selected_date'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        continue;
    }

    ?>
    <tr>
        <td><?php echo $id; ?></td>
        <td><?php echo $employee; ?></td>
        <td><?php echo $site; ?></td>
        <td><input type='radio' name='attendance[<?php echo $id; ?>]' value='present-<?php echo $site_id; ?>'></td>
        <td><input type='radio' name='attendance[<?php echo $id; ?>]' value='absent-<?php echo $site_id; ?>'></td>
        <td><input type='radio' name='attendance[<?php echo $id; ?>]' value='leave-<?php echo $site_id; ?>'></td>
        <td><input type='radio' name='attendance[<?php echo $id; ?>]' value='halfday-<?php echo $site_id; ?>'></td>
   </tr>
<?php

}




?>
