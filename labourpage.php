<?php
session_start();

include("connection.php");
error_reporting(0);
include("header/header.php");

$userprofile = $_SESSION['user_name'];
if($userprofile == true){

}
else{
    header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Labour Page</title>
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

    .btn1 {
        width: 100%; 
        padding: 10px; 
        background: var(--primary-color); 
        color: #fff;
        border: none; 
        border-radius: var(--border-radius); 
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    .btn1:hover {
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
    <?php ?>
    <div class="container">
        <h2>Add New Labour</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input_field">
                <label for="name">Name</label>
                <input type="text" name="name" placeholder="Enter name here" value="<?php echo htmlspecialchars($name); ?>">
                <span class="error"><?php echo $name_error; ?></span>
            </div>
            <div class="input_field">
                <label for="age">Age</label>
                <input type="text" name="age" placeholder="Enter your age" value="<?php echo htmlspecialchars($age); ?>">
                <span class="error"><?php echo $age_error; ?></span>
            </div>
            <div class="input_field">
                <label for="rank">Rank</label>
                <div class="custome_select">
                    <select name="rank">
                        <option value="">Select</option>
                        <option value="worker/labour" <?php echo $rank == 'worker/labour' ? 'selected' : ''; ?>>Worker/Labour</option>
                    </select>
                </div>
                <span class="error"><?php echo $rank_error; ?></span>
            </div>
            <div class="input_field">
                <label for="shift">Shift</label>
                <div class="custome_select">
                    <select name="shift">
                        <option value="">Select</option>
                        <option value="morning" <?php echo $shift == 'morning' ? 'selected' : ''; ?>>Morning</option>
                        <option value="afternoon" <?php echo $shift == 'afternoon' ? 'selected' : ''; ?>>Afternoon</option>
                        <option value="night" <?php echo $shift == 'night' ? 'selected' : ''; ?>>Night</option>
                    </select>
                </div>
                <span class="error"><?php echo $shift_error; ?></span>
            </div>
            <div class="input_field">
                <label for="salary">Salary</label>
                <input type="number" name="salary" placeholder="Enter your salary" value="<?php echo htmlspecialchars($salary); ?>">
                <span class="error"><?php echo $salary_error; ?></span>
            </div>
            <div class="input_field">
                <label for="location">Location</label>
                <div class="custome_select">
                    
                <select name="location" required>
                    <option value="">Select Location</option>
                    <?php
                    $is_admin = $_SESSION['is_admin'] ?? 0;
                    $user_site_id = $_SESSION['site_id'] ?? 0;
                    

                    $query = $is_admin
                        ? "SELECT * FROM site"
                        : "SELECT * FROM site WHERE id = $user_site_id";

                    $data = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($data)) { ?>
                        <option value=<?php echo $row['id']; ?>><?php echo $row['site_name']; ?></option>
                 <?php   }
                    ?>
                </select>

                    
                </div>
            </div>
            <div class="input_field">
                <label for="joining">Date of Joining</label>
                <input type="date" name="joining" value="<?php echo htmlspecialchars($joining); ?>">
                <span class="error"><?php echo $joining_error; ?></span>
            </div>
            <input type="submit" value="Add" name="submit" class="btn1">
        </form>
    </div>
    <?php include("header/footer.php");?>
</body>
</html>



<?php

$name_error = $age_error = $rank_error = $shift_error = $salary_error = $site_error = $joining_error = "";
$name = $age = $rank = $shift = $salary = $site = $joining = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $age = trim($_POST['age']);
    $rank = trim($_POST['rank']);
    $shift = trim($_POST['shift']);
    $salary = trim($_POST['salary']);
    $site = trim($_POST['location']);
    $joining = trim($_POST['joining']);

    
    if (empty($name)) {
        $name_error = "Name is required";
    } elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        $name_error = "Name should contain only letters and spaces";
    }

    
    if (empty($age) || !is_numeric($age) || $age < 18 || $age > 65) {
        $age_error = "Enter a valid age (18-65)";
    }

    
    if (empty($rank)) {
        $rank_error = "Rank is required";
    }

    
    if (empty($shift)) {
        $shift_error = "Shift is required";
    }

    
    if (empty($salary) || !is_numeric($salary) || $salary <= 0) {
        $salary_error = "Enter a valid salary";
    }

    
    if (empty($site)) {
        $site_error = "Site location is required";
    }

    
    if (empty($joining)) {
        $joining_error = "Joining date is required";
    }

    
    if (empty($name_error) && empty($age_error) && empty($rank_error) && empty($shift_error) && empty($salary_error) && empty($site_error) && empty($joining_error)) {
        $stmt = $conn->prepare("INSERT INTO labour_page (lname, lage, lrank, lshift, lsalary, lsite, joining) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisssss", $name, $age, $rank, $shift, $salary, $site, $joining);

        if ($stmt->execute()) {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>
                    Swal.fire({
                        title: "Thank You!",
                        text: "Form submitted successfully!",
                        icon: "success"
                    }).then(() => {
                        window.location.href = "display.php";
                    });
                </script>';
        } else {
            echo '<script>
                    Swal.fire({
                        title: "Error!",
                        text: "There was an issue submitting your form.",
                        icon: "error"
                    });
                </script>';
        }
        $stmt->close();
    }
}


// ?>