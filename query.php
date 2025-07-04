<?php
include("connection.php");
error_reporting(0);

$current_date = date("Y-m-d");

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $siteid = $_POST['site'];

    $query = "SELECT * FROM material WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Prepare the data for the modal
        $response = '<div class="modal-header">
                        <h2 class="modal-title font-weight" id="exampleModalLabel" style="text-align: center; font-weight: bold; text-transform: capitalize;">' . htmlspecialchars($row['name']) . '</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="ajaxform.php" class="form-horizontal">
                            <input type="hidden" class="form-control" value="' . $siteid . '" name="site" placeholder="Site" required>
                            <input type="hidden" class="form-control" value="' . htmlspecialchars($row['id']) . '" name="material" placeholder="Material" required>
                            <label for="date">Today</label>
                            <input type="text" value="' . $current_date . '" class="form-control" name="date" placeholder="Date" required><br>
                            <input type="number" class="form-control" name="quantity" placeholder="Quantity" required>
                            <button type="submit" class="btn btn-success float-right" name="submit">Add</button>
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

// Handle form submission


?>
