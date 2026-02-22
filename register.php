<!DOCTYPE html>
<head>

    <title>Join CrimsonGate | Experience the Sound</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <?php 
    include "db.php";
    $error = "";

    if (isset($_POST['register'])){
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $pass = $_POST['password'];

        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $check = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'");

        if (mysqli_num_rows($check) > 0){
            $error = "Email is already registered!";
        } else {
            $insert = mysqli_query($conn, "INSERT INTO user (name, lname, email, password, role) VALUES ('$name', '$lname', '$email', '$hash', 'user')");
            if ($insert){
                header("Location: login.php");
                exit;
            } else {
                $error = "Registration Failed! Please try again.";
            }
        }
    }
    ?>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        body {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.8)), url("style/back.jpg");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #ffffff;
            padding: 20px;
        }

        .register-container {
            background: rgba(15, 15, 15, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 40px;
            border-radius: 24px;
            width: 100%;
            max-width: 480px;
            border: 1px solid rgba(255, 46, 46, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }

        .register-container:hover {
            border-color: rgba(255, 46, 46, 0.4);
            transform: translateY(-5px);
        }

        .header-area {
            text-align: center;
            margin-bottom: 30px;
        }

        .header-area h2 {
            font-size: 2.2rem;
            font-weight: 800;
            background: linear-gradient(to right, #fff, #ff4d4d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }

        .header-area p {
            color: #888;
            font-size: 0.95rem;
        }

        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 0;
        }

        .form-group {
            flex: 1;
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #bbb;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding-left: 2px;
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
            transition: 0.3s;
        }

        input {
            width: 100%;
            padding: 13px 15px 13px 42px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            outline: none;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        input:focus {
            border-color: #ff2e2e;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 15px rgba(255, 46, 46, 0.2);
        }

        input:focus + i {
            color: #ff2e2e;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            font-size: 0.85rem;
            color: #aaa;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #ff2e2e;
            cursor: pointer;
        }

        .register-btn {
            width: 100%;
            padding: 16px;
            background: #b30000;
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 8px 20px rgba(179, 0, 0, 0.3);
        }

        .register-btn:hover {
            background: #ff2e2e;
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(255, 46, 46, 0.5);
        }

        .error-msg {
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid rgba(255, 0, 0, 0.2);
            color: #ff4d4d;
            padding: 12px;
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            text-align: center;
        }

        .footer-links {
            text-align: center;
            margin-top: 25px;
            font-size: 0.9rem;
            color: #777;
        }

        .footer-links a {
            color: #ff4d4d;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .footer-links a:hover {
            color: #fff;
            text-shadow: 0 0 10px rgba(255, 77, 77, 0.5);
        }

        .back-link {
            display: inline-block;
            margin-top: 25px;
            font-size: 0.85rem;
            color: #555;
            text-decoration: none;
            transition: 0.3s;
        }

        .back-link:hover {
            color: #bbb;
            transform: translateX(-5px);
        }
    </style>
</head>
<body>

    <div class="register-container">
        <div class="header-area">
            <h2>Join CrimsonGate</h2>
            <p>Your portal to unforgettable live experiences.</p>
        </div>

        <?php if ($error): ?>
            <div class="error-msg">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="#" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="fname">First Name</label>
                    <div class="input-wrapper">
                        <input type="text" id="fname" name="name" placeholder="John" required>
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lname">Last Name</label>
                    <div class="input-wrapper">
                        <input type="text" id="lname" name="lname" placeholder="Doe" required>
                        <i class="fas fa-id-card"></i>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" placeholder="john@example.com" required>
                    <i class="fas fa-envelope"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                    <i class="fas fa-lock"></i>
                </div>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="terms" required>
                <label for="terms">I agree to the <a href="#">Terms & Conditions</a></label>
            </div>

            <button type="submit" class="register-btn" name="register">Create Account</button>
        </form>

        <div class="footer-links">
            Already have an account? <a href="login.php">Login here</a>
        </div>

        <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Return to Landing Page</a>
    </div>

</body>
</html>