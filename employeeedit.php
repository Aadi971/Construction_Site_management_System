<?php
include("connection.php");
error_reporting(0);
session_start();
include("header/header.php");

if (!isset($_SESSION['user_name'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];
$query1 = "SELECT * from employee where id='$id'";
$result1 = mysqli_query($conn, $query1);
$data = mysqli_fetch_assoc($result1);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Update</title>
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
                <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                <label for="name">Full Name</label>
                <input type="text" name="name" value="<?php echo $data['e_name']; ?>" required>
            </div>
            <div class="input_field">
                <label for="age">Age</label>
                <input type="number" name="age" value="<?php echo $data['e_age']; ?>" required>
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

                    $sites = mysqli_query($conn, $query);
                    while ($site = mysqli_fetch_assoc($sites)) {
                        $selected = $site['id'] == $data['e_location'] ? 'selected' : '';
                        echo "<option value='{$site['id']}' $selected>{$site['site_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="input_field">
                <label for="rank">Rank</label>
                <select name="rank" required>
                    <option value="">Select Rank</option>
                    <option value="Manager" <?php if ($data['e_rank'] == 'Manager') echo 'selected'; ?>>Manager</option>
                    <option value="Engineer" <?php if ($data['e_rank'] == 'Engineer') echo 'selected'; ?>>Engineer</option>
                    <option value="Worker" <?php if ($data['e_rank'] == 'Worker') echo 'selected'; ?>>Worker</option>
                </select>
            </div>
            <div class="input_field">
                <label for="mobile">Mobile Number</label>
                <input type="text" name="mobile" pattern="[0-9]{10}" value="<?php echo $data['e_mobile']; ?>" required>
            </div>
            <div class="input_field">
                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" value="<?php echo $data['e_dob']; ?>" required>
            </div>
            <div class="input_field">
                <label for="doj">Date of Joining</label>
                <input type="date" name="doj" value="<?php echo $data['e_doj']; ?>" required>
            </div>
            <div class="input_field">
                <label for="bank_name">Bank Name</label>
                <input type="text" name="bank_name" value="<?php echo $data['e_bank_name']; ?>" required>
            </div>
            <div class="input_field">
                <label for="account_number">Account Number</label>
                <input type="text" name="account_number" value="<?php echo $data['e_account']; ?>" required>
            </div>
            <div class="input_field">
                <label for="ifsc">IFSC Code</label>
                <input type="text" name="ifsc" value="<?php echo $data['e_ifsc']; ?>" required>
            </div>
            <div class="input_field">
                <label for="pan">PAN Details</label>
                <input type="text" name="pan" value="<?php echo $data['e_pan']; ?>" required>
            </div>
            <div class="input_field">
                <label for="aadhaar">Aadhaar Card</label>
                <input type="text" name="aadhaar" pattern="[0-9]{12}" value="<?php echo $data['e_aadhaar']; ?>" required>
            </div>
            <div class="input_field">
                <label for="salary">Salary</label>
                <input type="number" name="salary" value="<?php echo $data['e_salary']; ?>" required>
            </div>
        </div>
        <input type="submit" value="Update" name="submit" class="btn1">
    </form>
</div>

<?php
if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $age = trim($_POST['age']);
    $site = trim($_POST['location']);
    $rank = trim($_POST['rank']);
    $mobile = trim($_POST['mobile']);
    $dob = trim($_POST['dob']);
    $joining = trim($_POST['doj']);
    $bank_name = trim($_POST['bank_name']);
    $account_no = trim($_POST['account_number']);
    $ifsc = trim($_POST['ifsc']);
    $pan = trim($_POST['pan']);
    $aadhaar = trim($_POST['aadhaar']);
    $salary = trim($_POST['salary']);

    $query = "UPDATE employee SET 
                e_name='$name',
                e_age='$age',
                e_location='$site',
                e_rank='$rank',
                e_mobile='$mobile',
                e_dob='$dob',
                e_doj='$joining',
                e_bank_name='$bank_name',
                e_account='$account_no',
                e_ifsc='$ifsc',
                e_pan='$pan',
                e_aadhaar='$aadhaar',
                e_salary='$salary'
              WHERE id='$id'";

    $q = mysqli_query($conn, $query);

    if ($q) {
        echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Employee updated successfully!',
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
        });
        </script>";
    }
}
?>

<?php include("header/footer.php"); ?>
</body>
</html>
