<?php
session_start();
require_once 'db.php'; // Resolves Line 4 error

// Check if user is logged in; if not, redirect to login
if (!isset($_SESSION['user_id'])) {
    // For now, we'll manually set it to Darell (ID 4) for testing based on your DB
    $_SESSION['user_id'] = 4; 
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM user WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile | CrimsonGate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --bg: #080808;
            --card: #121212;
            --crimson: #ff2e2e;
            --text-dim: #a0a0a0;
            --border: rgba(255, 255, 255, 0.08);
        }

        body {
            background-color: var(--bg);
            color: white;
            font-family: 'Inter', sans-serif;
            display: flex;
            margin: 0;
        }

        /* Re-using your sidebar logic for alignment */
        .sidebar {
            width: 280px;
            background: #0f0f0f;
            height: 100vh;
            position: fixed;
            padding: 2.5rem 1.5rem;
            border-right: 1px solid var(--border);
        }

        .main-content {
            margin-left: 280px;
            padding: 40px 60px;
            width: 100%;
        }

        .profile-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px;
            max-width: 500px;
            margin-top: 30px;
        }

        .info-group { margin-bottom: 25px; }
        .info-group label { color: var(--text-dim); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; }
        .info-group p { font-size: 1.2rem; font-weight: 600; margin-top: 8px; }
        
        .badge {
            background: rgba(255, 46, 46, 0.1);
            color: var(--crimson);
            padding: 4px 12px;
            border-radius: 5px;
            font-size: 0.8rem;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h2 style="color:var(--crimson)">CRIMSONGATE</h2>
        <nav style="margin-top:50px">
            <p style="color:white"><i class="fas fa-user"></i> Profile</p>
            <a href="dash.php" style="color:var(--text-dim); text-decoration:none; display:block; margin-top:20px;">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </nav>
    </aside>

    <main class="main-content">
        <h1>Account Settings</h1>
        <p style="color: var(--text-dim);">Manage your personal information and security.</p>

        <div class="profile-card">
            <div class="info-group">
                <label>Full Name</label>
                <p><?php echo htmlspecialchars($user['name'] . ' ' . $user['lname']); ?></p>
            </div>

            <div class="info-group">
                <label>Email Address</label>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
            </div>

            <div class="info-group">
                <label>Account Status</label>
                <p><span class="badge"><?php echo strtoupper($user['role']); ?></span></p>
            </div>
        </div>
    </main>
</body>
</html>