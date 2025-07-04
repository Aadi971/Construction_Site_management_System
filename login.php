<?php
session_start();
include("connection.php");

$siteQuery = "SELECT * FROM site";
$siteResult = mysqli_query($conn, $siteQuery);

if (isset($_POST['register'])) {
    $user_id = trim($_POST['email']);
    $user_password = trim($_POST['pwd']);
    // $site_id = trim($_POST['site']);

    if (empty($user_id) || empty($user_password)) {
        echo "<script>alert('Email and Password are required!');</script>";
    } else {
        
        $query = "
                SELECT registration.*, site.site_name 
                FROM registration 
                LEFT JOIN site ON registration.site_id = site.id 
                WHERE registration.email = ? OR registration.name = ?
            ";

    



        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("ss", $user_id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $hashed_password = $row['password'];

                
                if (password_verify($user_password, $hashed_password)) {
                    $_SESSION['user_name'] = $row['name']; 
                    $_SESSION['user_email'] = $row['email'];
                    $_SESSION['is_admin'] = $row['is_admin'];  // 1 or 0
                    $_SESSION['site_id'] = $row['site_id'];    // used if not admin


                    header("Location: home.php");
                    exit();
                } else {
                    echo "<script>alert('Incorrect password. Please try again.');</script>";
                }
            } else {
                echo "<script>alert('User not found. Please check your credentials.');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Database query error. Please try again later.');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url("photo.jpg") no-repeat center center/cover;
        }

        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            width: 380px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .login-container h1 {
            font-size: 24px;
            color: #1c8adb;
            margin-bottom: 20px;
        }

        .input-box {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            transition: 0.3s ease;
        }

        .input-box:focus {
            border-color: #1c8adb;
            outline: none;
        }

        .forgot-password {
            text-align: right;
            margin-bottom: 10px;
        }

        .forgot-password a {
            color: #1c8adb;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .checkbox-container input {
            margin-right: 8px;
        }

        .login-btn {
            width: 100%;
            background: #007bff;
            color: #fff;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            transition: 0.3s ease;
        }

        .login-btn:hover {
            background: #0056b3;
        }

        .social-login {
            margin: 15px 0;
        }

        .social-login button {
            width: 100%;
            background: #e7e7e7;
            color: #333;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            transition: 0.3s ease;
        }

        .social-login button:hover {
            background: #555;
            color: #fff;
        }

        .signup-link {
            margin-top: 15px;
            font-size: 14px;
        }

        .signup-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 400px) {
            .login-container {
                width: 90%;
            }
        }
</style>
</head>
<body>

    <div class="login-container">
        <h1>Login</h1>
        <form action="login.php" method="POST" autocomplete="off">
            
            <div>
                <input type="text" class="input-box" placeholder="Your Email" name="email" id = "username" required>
                <h5 id = "usercheck"> </h5>
            </div>
            <div>
                <input type="password" class="input-box" placeholder="Password" name="pwd" id = "userpass" required>
                <h5 id = "passcheck"> </h5>
            </div>
            
            <div class="forgot-password">
                <a href="#" onclick="forgotPassword()">Forgot Password?</a>
            </div>

            <div class="checkbox-container">
                <input type="checkbox" required>
                <label>I agree to the Terms and Conditions</label>
            </div>

            <button type="submit" class="login-btn" name="register">Login <i class="fa-solid fa-sign-in-alt"></i></button>

            <div class="social-login">
                <button type="button"><i class="fa-solid fa-envelope"></i> Login with Email</button>
            </div>

            <p class="signup-link">Don't have an account? <a href="registration.php">Register here</a></p>
        </form>
    </div>

    <script>
        function forgotPassword() {
            alert("Forgot Password functionality is under development.");
        }

       
        
    </script>

</body>
</html>