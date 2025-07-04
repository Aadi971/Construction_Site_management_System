<?php
include("connection.php");
session_start();
include("header/header.php");

if (!isset($_SESSION['user_name'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

   

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #007bff;
        }
        .input_field {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        .btn1 {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: #fff;
            border: none;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn1:hover {
            background: #0056b3;
        }

        @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
       }
    </style>
</head>
<body>

<div class="container">
    <h2>Employee Registration</h2>
    <form action="" method="post">
        <div class="form-grid">
            <div class="input_field">
                <label for="name">Full Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="input_field">
                <label for="age">Age</label>
                <input type="number" name="age" required>
            </div>
            <div class="input_field">
                <label for="location">Location</label>
                <select name="location" required>
                    <option value="">Select Location</option>
                    <?php
                    $is_admin = $_SESSION['is_admin'] ?? 0;
                    $user_site_id = $_SESSION['site_id'] ?? 0;

                    $query = $is_admin
                        ? "SELECT * FROM site"
                        : "SELECT * FROM site WHERE id = $user_site_id";

                    $data = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($data)) {
                        echo "<option value='{$row['id']}'>{$row['site_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="input_field">
                <label for="rank">Rank</label>
                <select name="rank" required>
                    <option value="">Select Rank</option>
                    <option value="Manager">Manager</option>
                    <option value="Engineer">Engineer</option>
                    <option value="Worker">Worker</option>
                </select>
            </div>
            <div class="input_field">
                <label for="mobile">Mobile Number</label>
                <input type="text" name="mobile" pattern="[0-9]{10}" required>
            </div>
            <div class="input_field">
                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" required>
            </div>
            <div class="input_field">
                <label for="doj">Date of Joining</label>
                <input type="date" name="doj" required>
            </div>
            <div class="input_field">
                <label for="bank_name">Bank Name</label>
                <input type="text" name="bank_name" required>
            </div>
            <div class="input_field">
                <label for="account_number">Account Number</label>
                <input type="text" name="account_number" required>
            </div>
            <div class="input_field">
                <label for="ifsc">IFSC Code</label>
                <input type="text" name="ifsc" required>
            </div>
            <div class="input_field">
                <label for="pan">PAN Details</label>
                <input type="text" name="pan" required>
            </div>
            <div class="input_field">
                <label for="aadhaar">Aadhaar Card</label>
                <input type="text" name="aadhaar" pattern="[0-9]{12}" required>
            </div>
            <div class="input_field">
                <label for="salary">Salary</label>
                <input type="number" name="salary" required>
            </div>
        </div>
        <input type="submit" value="Register" name="submit" class="btn1">
    </form>
</div>


<?php
include("insertFun.php");

if (isset($_POST['submit'])){
    $array = array(
        'e_name'         => $_POST['name'],
        'e_age'          => $_POST['age'],
        'e_location'     => $_POST['location'],
        'e_rank'         => $_POST['rank'],
        'e_mobile'       => $_POST['mobile'],
        'e_dob'          => $_POST['dob'],
        'e_doj'          => $_POST['doj'],
        'e_bank_name'    => $_POST['bank_name'],
        'e_account'      => $_POST['account_number'],
        'e_ifsc'         => $_POST['ifsc'],
        'e_pan'          => $_POST['pan'],
        'e_aadhaar'      => $_POST['aadhaar'],
        'e_salary'       => $_POST['salary']
    );

    $q = Insert('employee', $array);

    if ($q) {
        echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Employee registered successfully!',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'employeeview.php';
        });
        </script>";
    } else {
        echo "<script>
        Swal.fire({
            title: 'Oops!',
            text: 'Something went wrong!',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'employeeview.php';
        });
        </script>";
    }
}
?>
<?php include("header/footer.php"); ?>
</body>
</html>
