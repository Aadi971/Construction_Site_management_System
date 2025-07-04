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

$current_date = date("Y-m-d");

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $siteid = $_POST['site'];
    $material = $_POST['available'];


    

    $query = "SELECT * FROM material WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $available_quantity = getAvailableQuantities($siteid, $conn);
        $available = isset($available_quantity[$id]) ? $available_quantity[$id] : 0;

        $response = '<div class="modal-header">
                        <h2 class="modal-title font-weight" id="exampleModalCenter" style="text-align: center; font-weight: bold; text-transform: capitalize;">' . htmlspecialchars($row['name']) . '</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form method="POST" action="abc.php" class="form-horizontal">
                            <input type="hidden" class="form-control" value="' . $siteid . '" name="site" placeholder="Site" required>
                            <input type="hidden" class="form-control" value="' . htmlspecialchars($row['id']) . '" name="material" placeholder="Material" required>
                            <label for="date">Today</label>
                            <input type="text" value="' . $current_date . '" class="form-control" name="date" placeholder="Date" required><br>
                            <label for="quantity">Available Quantity</label>
                            <input type="number" class="form-control" value="' . $available . '" name="available" placeholder="Available Quantity" readonly><br>
                           
                            <label for="quantity">Quantity</label>
                            <input type="number" class="form-control" name="quantity" placeholder="Quantity" required>
                            <button type="submit" class="btn btn-success float-right" name="update">Today Update</button>
                        </form>
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