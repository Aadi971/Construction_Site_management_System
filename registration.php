<?php
include("connection.php");
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
     <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: url("photo-r.jpg"); background-repeat: no-repeat; background-size: cover;}
        header {
            background: #007bff;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header .flex {
            list-style: none;
            display: flex;
        }
        header .flex li {
            margin: 0 15px;
        }
        header .flex a {
            text-decoration: none;
            color: white;
            font-size: 16px;
        }
        header .btn a {
            text-decoration: none;
            color: white;
        }
        header .btn {
            padding: 10px 20px;
            background: #0056b3;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        header .btn:hover {
            background: #003f8a;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container h2 { text-align: center; margin-bottom: 20px; }
        .input_field { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px; }
        .error { color: red; font-size: 14px; margin-top: 5px; }
        .btn {
            width: 100px; padding: 10px; background: #007bff; color: #fff;
            border: none; border-radius: 5px; cursor: pointer;
        }
        .btn:hover { background: #0056b3; }

        

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }
            header .flex {
                flex-direction: column;
                width: 100%;
            }
            header .flex li {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
<header>
    <ul class="flex">
        <li><a href="index.php">Home</a></li>
        <li><a href="services.php">Services</a></li>
        <li><a href="registration.php">Registration</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
    <button class="btn"><a href="login.php">Sign Up</a></button>
</header>
<div class="container">
    <h2>Registration Form</h2>
    <form method="POST" action="#">
            
        <div class="input_field">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo $name; ?>">
            <span class="error"><?php echo $name_error; ?></span>
        </div>

        <div class="input_field">
            <label>Password</label>
            <input type="password" name="password">
            <span class="error"><?php echo $password_error; ?></span>
        </div>

        <div class="input_field">
            <label>Re-enter Password</label>
            <input type="password" name="cnf">
        </div>

        <div class="input_field">
            <label>Site ID</label>
            <input type="text" name="site_id">
        </div>

        <div class="input_field">
            <label>Contact</label>
            <input type="text" name="contact" value="<?php echo $mobile; ?>">
            <span class="error"><?php echo $mobile_error; ?></span>
        </div>

        <div class="input_field">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $email; ?>">
            <span class="error"><?php echo $email_error; ?></span>
        </div>

        <div>
            <button type="submit" class="btn" name="register">Register</button>
        </div>
    </form>
</div>



</body>
</html>
<?php 

$name_error = $password_error = $mobile_error = $email_error = "";
$name = $password = $con_password = $mobile = $email = "";

if (isset($_POST['register'])) {
    // $siteno = trim($_POST['siteno']);
    $name = trim($_POST['name']);
    $password = $_POST['password'];
    $con_password = $_POST['cnf'];
    $site_id = trim($_POST['site_id']);
    $mobile = trim($_POST['contact']);
    $email = trim($_POST['email']);

    
    if (empty($name)) {
        $name_error = "Name is required";
    } elseif (!preg_match("/^[a-zA-Z-' ]+$/", $name)) {
        $name_error = "Name should contain only letters, spaces, hyphens, or apostrophes";
    }
    
    
    if (empty($password)) {
        $password_error = "Password is required";
    } elseif (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters";
    } elseif (!preg_match("#[0-9]+#", $password)) {
        $password_error = "Password must include at least one number";
    } elseif (!preg_match("#[a-z]+#", $password)) {
        $password_error = "Password must include at least one lowercase letter";
    } elseif (!preg_match("#[A-Z]+#", $password)) {
        $password_error = "Password must include at least one uppercase letter";
    } elseif ($password !== $con_password) {
        $password_error = "Passwords do not match";
    } else {
        
        $secure_pass = password_hash($password, PASSWORD_DEFAULT);
    }
    
    // Mobile Number Validation
    if (empty($mobile)) {
        $mobile_error = "Contact number is required";
    } elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $mobile_error = "Contact number must be 10 digits";
    }
    
    // Email Validation
    if (empty($email)) {
        $email_error = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format";
    }
    
    if (empty($name_error) && empty($password_error) && empty($mobile_error) && empty($email_error)) {
        
        $check_query = "SELECT * FROM registration WHERE email = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            
            echo '<script>
                    alert("Email already registered. Please use another email.");
                    window.location.href = "registration.php";
                </script>';
        } else {
            
            $query = "INSERT INTO registration (name, password, site_id, contact, email) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssss", $name, $secure_pass, $site_id, $mobile, $email);
            
            
            if ($stmt->execute()) {
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>
                        Swal.fire({
                            title: "Thank You!",
                            text: "Form submitted successfully!",
                            icon: "success"
                        }).then(() => {
                            window.location.href = "index.php";
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
        }
    }
}
?>
