<?php 
include("connection.php");
error_reporting(0);
include("header/header.php");
include("insertFun.php");
if(isset($_POST['submit'])){
    $name = array(
        'site_name' => $_POST['name']
    );

    $q = Insert('site', $name);

    if($q){
        echo "Site name was Inserted successfully!";
    }else{
        echo "Failed to Insert";
    }

    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="jumbotron">
        <h1 align="center">Add New Site</h1>
    </div>
    <div class="container">
        <form action="#" method="POST" class="border border-success ml-5 pl-5">
            <div class="row">
                <div class="col-8">
                    <div class="form-group">
                        <label for="addsite">Site Name</label>
                        <input type="text" class="form-control" placeholder="Enter new site" name="name">
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" name="submit" value="Add">
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>