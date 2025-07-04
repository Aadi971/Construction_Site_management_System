<?php
include("connection.php");
error_reporting(0);
session_start();
$userprofile = $_SESSION['user_name'];  
if (!$userprofile) {
    header('location:login.php');
    exit();
}
include("availableFun.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $siteid = $_GET['site'];
    $material = $_GET['available'];

    $from_date = $_GET['from_date'] ?? null;
    $to_date = $_GET['to_date'] ?? null;

    // Get material and site info
    $query = "SELECT 
                site_material.*, 
                site.site_name, 
                material.name AS material_name
              FROM site_material
              JOIN site ON site_material.site_id = site.id
              JOIN material ON site_material.material_id = material.id
              WHERE site_material.site_id = $siteid AND site_material.material_id = $id";

    $result = mysqli_query($conn, $query);

    // Filter data by date if provided
    if ($from_date && $to_date) {
        $q = "SELECT * FROM site_material 
              WHERE site_id = $siteid AND material_id = $id 
              AND date BETWEEN '$from_date' AND '$to_date'";
    } else {
        $q = "SELECT * FROM site_material 
              WHERE site_id = $siteid AND material_id = $id";
    }

    $result1 = mysqli_query($conn, $q);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $available_quantity = getAvailableQuantities($siteid, $conn);
        $available = $available_quantity[$id] ?? 0;

        $response = '
        <div class="modal-header">
            <h2 class="modal-title font-weight" style="text-align: center; font-weight: bold; text-transform: capitalize;">' . htmlspecialchars($row['material_name']) . '</h2>
            <label style="margin: 10px 0px 0px 10px;">Site Name :</label>
            <input type="text" class="form-control form-control-sm" style="width: 90px; margin-left: 10px;" value="' . htmlspecialchars($row['site_name']) . '" readonly>
            <label style="margin:10px 0px 0px 10px;">Available :</label>
            <input type="text" class="form-control form-control-sm" style="width: 50px; margin-left: 10px;" value="' . $available . '" readonly>

            <form method="GET" action="" style="display: flex; align-items: center; margin-left: 10px;">
                <input type="hidden" name="id" value="' . $id . '">
                <input type="hidden" name="site" value="' . $siteid . '">
                <input type="hidden" name="available" value="' . $material . '">
            </form>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left: auto;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Quantity</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>';

        while ($row1 = mysqli_fetch_assoc($result1)) {
            $status = $row1['status'];
            $statusText = $status == 1 ? '+' : '-';
            $statusColor = $status == 1 ? 'green' : 'red';

            $response .= '
                    <tr>
                        <td>' . htmlspecialchars($row1['quantity']) . '</td>
                        <td>' . htmlspecialchars($row1['date']) . '</td>
                        <td style="color: ' . $statusColor . '; font-weight: bold; font-size: 24px;">' . $statusText . '</td>
                    </tr>';
        }

        $response .= '
                </tbody>
            </table>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>';

        echo $response;

    } else {
        echo '<p>No material found for the provided ID.</p>';
    }

} else {
    echo '<p>No ID provided.</p>';
}
?>
