<?php
include("connection.php");
error_reporting(0);
session_start();
include("header/header.php");

$userprofile = $_SESSION['user_name'];
if($userprofile == true){

}
else{
    header('location:login.php');
}

$query1 = "SELECT * FROM site";
$data1 = mysqli_query($conn,$query1);
$total1 = mysqli_num_rows($data1);

// $result1 = mysqli_fetch_assoc($data1);
// print_r($result1);
// while ($result1 = mysqli_fetch_assoc($data1)) {
//     echo $result1['id'] . "<br>"; 
// }

// exit;
$id = $_GET['id'];
// echo $id;
// die;

$query = "SELECT * FROM labour_page where id = '$id'";
$data = mysqli_query($conn,$query);

$total = mysqli_num_rows($data);
$result = mysqli_fetch_assoc($data);




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Labour update</title>
    <style>
    :root {
        --primary-color: #007bff;
        --hover-color: #0056b3;
        --error-color: #dc3545;
        --border-radius: 5px;
        --input-padding: 8px;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: sans-serif, system-ui;
    }

    body{
        min-height: 100vh;
    }

    .container {
        max-width: 500px;
        margin: 50px auto;
        background: #fff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .input_field {
        margin-bottom: 15px;
    }

    form label {
        display: block; 
        margin-bottom: 5px;
    }

    form input, .custome_select select {
        width: 100%;
        padding: var(--input-padding); 
        border: 1px solid #ccc; 
        border-radius: var(--border-radius);
        outline: none;
        transition: all 0.3s ease-in-out;
    }

    form input:hover, .custome_select select:hover {
        border-color: var(--primary-color);
    }

    form input:focus, .custome_select select:focus, .btn:focus {
        outline: 2px solid var(--primary-color);
        /* outline-offset: 2px; */
    }

    .custome_select {
        position: relative;
        width: 100%;
        height: 37px;
    }

    .custome_select:before {
        content: "";
        position: absolute;
        top: 14px;
        right: 2px;
        border: 8px solid black;
        border-color: grey transparent transparent transparent;
        pointer-events: none;
    }    

    .error {
        color: var(--error-color);
        font-size: 13px;
        font-weight: bold;
        margin-top: 5px;
    }

    .btn {
        width: 100%; 
        padding: 10px; 
        background: var(--primary-color); 
        color: #fff;
        border: none; 
        border-radius: var(--border-radius); 
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    .btn:hover {
        background: var(--hover-color);
    }

    @media (max-width: 600px) {
        .container {
            padding: 15px;
        }
        .btn {
            padding: 12px;
        }
    }
</style>
</head>
<body>

    <div class="container">
        <h2>Update Labour Details</h2>
        <form action="#" method="post" autocomplete= "off">
            <div class="input_field">
                <input type="hidden" name="id" value="<?php echo $result['id']; ?>">
                <label for="">Name</label>
                <input type="text" name="name" placeholder="Enter name here" value="<?php echo $result['lname']; ?>">
            </div>
            <div class="input_field">
                <label for="">Age</label>
                <input type="number" name="age" placeholder="Enter your age" value="<?php echo $result['lage'];?>">
            </div>
            <div class="input_field">
                <label for="">Rank</label>
                <div class="custome_select">
                    <select name="rank">
                        <option value="">Select</option>
                        <option value="worker/labour"
                        <?php
                        if($result['lrank'] == 'worker/labour'){
                            echo "selected";
                        }
                        ?>
                        >Worker/Labour</option>
                    </select>
                </div>
            </div>
            <div class="input_field">
                <label for="">Shift</label>
                <div class="custome_select">
                    <select name="shift" required>
                        <option value="">Select</option>
                        <option value="morning"<?php if($result['lshift'] == 'morning'){echo "selected";}?>>Morning</option>
                        <option value="afternoon"<?php if($result['lshift'] == 'afternoon'){echo "selected";}?>>Afternoon</option>
                        <option value="night"<?php if($result['lshift'] == 'night'){echo "selected";}?>>Night</option>
                    </select>
                </div>
            </div>
            <div class="input_field">
                <label for="">Salary</label>
                <input type="number" name="salary" placeholder="Enter your salary" value="<?php echo $result['lsalary'];?>">
            </div>
            <div class="input_field">
                <label for="">Location</label>
                <div class="custome_select">
                    <select name="lsite" required>
                        <option value="">Select</option>
                        
                        <?php
                        while ($result1 = mysqli_fetch_assoc($data1)) {
                            $selected = ($result1['id'] == $result['lsite']) ? 'selected': '';

                        ?>
                        <option value="<?php echo $result1['id'];?>" <?php echo $selected; ?>><?php echo $result1['site_name'];?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="input_field">
                <label for="">Date of Joining</label>
                <input type="date" name="joining" value="<?php echo $result['joining'];?>">
            </div>
            <input type="submit" value="Update" name="update" class="btn">
        </form>
    </div>
    <?php include("header/footer.php");?>
</body>
</html>

<?php


if(isset($_POST['update'])){
    // echo "fgh";
    // exit;
    $id = $_POST['id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $rank = $_POST['rank'];
    $shift = $_POST['shift'];
    $salary = $_POST['salary'];
    $site = $_POST['lsite'];
    $joining = $_POST['joining'];

    // echo $name;
    // die;

    $query = "UPDATE labour_page 
    SET lname='$name', lage='$age', lrank='$rank', lshift='$shift', lsalary='$salary', lsite='$site', joining='$joining' 
    WHERE id='$id'";
    $datas = mysqli_query($conn,$query);


    if ($datas) {
        ?>
        <meta http-equiv = "refresh" content = "0; url = http://localhost/construction/display.php" />
        <?php
        exit();  // Ensures the script stops executing after redirection
    } else {
        echo "Failed to update: " . mysqli_error($conn);
    }
    
}
?>