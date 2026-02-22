<?php
session_start();
include "db.php";

// 1. SESSION & LOGOUT HANDLING
if (isset($_SESSION['email'])) {
    session_unset();
    session_destroy();
    header("Location: login.php?logged_out=1");
    exit;
}

$error = "";

// 2. LOGIN PROCESSING
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Fetch user details including the 'status' column
    $query = "SELECT * FROM user WHERE email = '$email'";
    $check = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($check);

    // Verify Password
    if ($user && password_verify($password, $user['password'])) {

        // --- STATUS VALIDATION ---
        // Check if the account is approved by admin
        if ($user['status'] === 'pending') {
            $error = "Your account is pending. Please wait for admin approval.";
        } elseif ($user['status'] === 'inactive') {
            $error = "Your account has been deactivated. Please contact the administrator.";
        } else {
            // Status is 'active', proceed with session creation
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] == 'admin') {
                header("Location: admindash.php");
            } else {
                header("Location: dash.php");
            }
            exit;
        }
        // -------------------------

    } else {
        $error = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | CrimsonGate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script type="text/javascript">
        function preventBack() { window.history.forward(); }
        setTimeout("preventBack()", 0);
        window.onunload = function () { null };
    </script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.8)), url("style/back.jpg");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #ffffff;
            overflow: hidden;
        }

        .login-container {
            background: rgba(15, 15, 15, 0.7);
            backdrop-filter: blur(20px);
            padding: 50px 40px;
            border-radius: 24px;
            width: 90%;
            max-width: 420px;
            border: 1px solid rgba(255, 46, 46, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .login-container h2 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 8px;
            background: linear-gradient(to right, #fff, #ff4d4d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle {
            color: #888;
            margin-bottom: 35px;
            font-size: 0.95rem;
        }

        .form-group {
            text-align: left;
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #bbb;
            text-transform: uppercase;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #555;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px 15px 14px 45px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            outline: none;
            font-size: 1rem;
        }

        input:focus {
            border-color: #ff2e2e;
            box-shadow: 0 0 15px rgba(255, 46, 46, 0.2);
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background: #b30000;
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 800;
            text-transform: uppercase;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-btn:hover {
            background: #ff2e2e;
            transform: translateY(-2px);
        }

        .error-msg {
            background: rgba(255, 0, 0, 0.1);
            color: #ff4d4d;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .options {
            margin-top: 25px;
            color: #888;
        }

        .options a {
            color: #ff4d4d;
            text-decoration: none;
            font-weight: 600;
        }

        .back-home {
            display: block;
            margin-top: 30px;
            color: #555;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Welcome Back</h2>
        <p class="subtitle">Enter your credentials to access your tickets</p>

        <?php if ($error): ?>
            <div class="error-msg">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrapper">
                    <input type="text" name="username" placeholder="name@example.com" required>
                    <i class="fas fa-envelope"></i>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <input type="password" name="password" placeholder="••••••••" required>
                    <i class="fas fa-lock"></i>
                </div>
            </div>

            <button type="submit" name="login" class="login-btn">Sign In</button>
        </form>

        <div class="options">
            <span>New to CrimsonGate?</span> <a href="register.php">Create Account</a>
        </div>
        <a href="index.php" class="back-home"><i class="fas fa-arrow-left"></i> Back to Home</a>
    </div>
</body>

</html>