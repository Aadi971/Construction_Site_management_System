<?php
include("connection.php");
error_reporting(0);

session_start();
$userprofile = $_SESSION['user_name'];
if (!$userprofile) {
    header('location:login.php');
    exit();
}
include("header/header.php");
include("insertFun.php");

$isAdmin = $_SESSION['is_admin']; // true or 1 if admin
$site_user_id = $_SESSION['site_id'];


if ($isAdmin) {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
} else {
    $id = $site_user_id;
}


// echo $id;
// die;
$query = ($isAdmin)
    ? "SELECT * FROM site"
    : "SELECT * FROM site WHERE id = $site_user_id";

$siteResult = mysqli_query($conn, $query);


if ($id > 0) {
    
    $query_add = "SELECT material.id AS material_id,
                         material.name AS material_name,
                         site_material.site_id,
                         SUM(site_material.quantity) AS total_quantity
                  FROM material
                  LEFT JOIN site_material 
                    ON material.id = site_material.material_id 
                    AND site_material.status = 1
                  WHERE site_material.site_id = $id
                  GROUP BY material.id";

    $add_result = mysqli_query($conn, $query_add);
    $add_data = ($add_result) ? mysqli_fetch_all($add_result, MYSQLI_ASSOC) : [];

    
    $query_subtract = "SELECT material.id AS material_id,
                              material.name AS material_name,
                              site_material.site_id,
                              SUM(site_material.quantity) AS total_quantity
                       FROM material
                       LEFT JOIN site_material 
                         ON material.id = site_material.material_id 
                         AND site_material.status = 0
                       WHERE site_material.site_id = $id
                       GROUP BY material.id";

    $subtract_result = mysqli_query($conn, $query_subtract);
    $subtract_data = ($subtract_result) ? mysqli_fetch_all($subtract_result, MYSQLI_ASSOC) : [];
} else {
    echo "Invalid Site ID.";
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Material Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            /* background: #f4f4f4; */
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        .action-buttons a {
            padding: 5px 10px;
            margin: 5px;
            text-decoration: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 3px;
        }
        .action-buttons a.delete {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Site Material Details</h2>
        <?php if (isset($_SESSION['message'])) {
             echo $_SESSION['message'];
             unset($_SESSION['message']);
            } ?>
        <?php if ($isAdmin) { ?>
    <select id="site" name="site" class="form-control" required>
        <option value="">Select</option>
        <?php while ($row1 = mysqli_fetch_assoc($siteResult)) { ?>
            <option value="<?php echo $row1['id']; ?>" 
                <?php if ($id == $row1['id']) echo 'selected'; ?>>
                <?php echo $row1['site_name']; ?>
            </option>
        <?php } ?>
    </select>
<?php } else { ?>
    <h4>Your Site: 
        <?php
            $site_name_query = mysqli_query($conn, "SELECT site_name FROM site WHERE id = $site_user_id");
            $site_name_row = mysqli_fetch_assoc($site_name_query);
            echo $site_name_row['site_name'];
        ?>
    </h4>
<?php } ?>


        <table id="table">
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Availabe</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($add_data) || !empty($subtract_data)) {
                    foreach ($add_data as $row) {
                        $material_id = $row['material_id'];
                        $added_qty = $row['total_quantity'];
                        $used_qty = 0;
                        

                        foreach ($subtract_data as $sub_row) {
                            if ($sub_row['material_id'] == $material_id) {
                                $used_qty = $sub_row['total_quantity'];
                                break;
                            }

                           
                        }
                        $available_qty = $added_qty - $used_qty;
                        ?>









                        <tr>
                            <td><?php echo $row['material_name']; ?></td>
                            <td><?php echo $available_qty; ?></td>
                            <td class="action-buttons">
                                <a href="#exampleModal" class="btn btn-success abc" data-id="<?php echo $row['material_id']; ?>" data-siteid="<?php echo $row['site_id']; ?>" data-toggle="modal"><i class="fa fa-plus"></i></a>
                                <a href="#exampleModalCenter" class="btn btn-danger def" data-subtract="<?php echo $row['material_id']; ?>" data-siteid="<?php echo $row['site_id']; ?>" data-toggle="modal"><i class="fa fa-minus"></i></a>
                                <a href="#ModalCenter" class="btn btn-secondary view" data-table="<?php echo $row['material_id']; ?>" data-siteid="<?php echo $row['site_id']; ?>" data-toggle="modal"><i class="fa-regular fa-eye"></i></a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='4'>No materials found for this site.</td></tr>";
                }
                ?>
            </tbody>
        </table>


 

            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                
                </div>
            </div>
            </div>




            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-subtract">
                

                </div>
            </div>
            </div>

            <div class="modal fade bd-example-modal-lg" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content modal-table">
                

                </div>
            </div>
            </div>
    </div>

<script>
    $(document).ready(function() {


 
        $('#table').hide();

        
        var site_id = new URLSearchParams(window.location.search).get('id');

        
        <?php if ($isAdmin): ?>
            if (site_id) {
                $('#table').show();
            }

            
            $('#site').on('change', function () {
                var selectedId = $(this).val();
                if (selectedId) {
                    // Show the table if a site is selected
                    window.location.href = 'sitematerial.php?id=' + selectedId;
                } else {
                    $('#table').hide();
                }
            });
        <?php else: ?>
            // If not admin, show the table directly
            $('#table').show();
        <?php endif; ?>




        $(document).on("click", ".abc", function(event){
            event.preventDefault();
            var id = $(this).data("id");
            var site_id= $(this).data("siteid");
            // alert("ID: " + id + ", Site ID: " + site_id);
            // $(".def").prop("disabled", true);
            $.ajax({
                url: "query.php",
                type: "POST",
                data: {id:id, site:site_id},
                success: function(data){
                    $(".modal-content").html(data);
                }
            });
        });


        $(document).on("click", ".def", function(event){
            event.preventDefault();
            var id = $(this).data("subtract");
            var site_id= $(this).data("siteid");
            // alert("ID: " + id + ", Site ID: " + site_id);
            // $(".abc").prop("disabled", true);
            $.ajax({
                url: "subtract.php",
                type: "POST",
                data: {id:id, site:site_id},
                success: function(data){
                    $(".modal-subtract").html(data);
                }
            });
        });


        $(document).on("click", ".view", function(event){
            event.preventDefault();
            var id = $(this).data("table");
            var site_id= $(this).data("siteid");
            // alert("ID: " + id + ", Site ID: " + site_id);
            $.ajax({
                url: "sitedetail.php",
                type: "GET",
                data: {id:id, site:site_id},
                success: function(data){
                    $(".modal-table").html(data);
                }
            });
        });

       


        

    });
</script>



<?php include("header/footer.php"); ?>
</body>
</html>
