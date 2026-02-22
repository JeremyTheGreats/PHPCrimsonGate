<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Success | CrimsonGate</title>
    <style>
        body {
            background: #050505;
            color: white;
            font-family: 'Outfit', sans-serif;
            text-align: center;
            padding: 100px;
        }

        .icon {
            color: #00ffaa;
            font-size: 80px;
            margin-bottom: 20px;
        }

        .btn {
            background: #ff2e2e;
            color: white;
            padding: 15px 30px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="icon">✔</div>
    <h1>Payment Successful!</h1>
    <p>Your seats have been reserved. Check your email for the digital ticket.</p>
    <a href="dash.php" class="btn">Return Home</a>
</body>

</html>