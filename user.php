<?php
include "db.php";
// Fetch users including the 'status' column
$query = "SELECT id, name, lname, email, role, status FROM user ORDER BY role ASC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Directory | CrimsonAdmin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --bg: #080808;
            --sidebar-bg: #0f0f0f;
            --card: #121212;
            --crimson: #ff2e2e;
            --border: rgba(255, 255, 255, 0.08);
            --text-dim: #a0a0a0;
            --glass: rgba(255, 255, 255, 0.03);
            --success: #00ff78;
            --warning: #ffc107;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg);
            color: white;
            font-family: 'Inter', sans-serif;
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            padding: 2.5rem 1.5rem;
            position: fixed;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .logo {
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--crimson);
            margin-bottom: 3.5rem;
            text-align: center;
            letter-spacing: 2px;
        }

        .logo span {
            color: white;
        }

        .nav-links {
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-dim);
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-radius: 12px;
            transition: 0.3s;
        }

        .nav-links a.active {
            background: var(--glass);
            color: var(--crimson);
            border-left: 3px solid var(--crimson);
        }

        /* CONTENT */
        .main-content {
            margin-left: 280px;
            padding: 60px;
            width: calc(100% - 280px);
        }

        header h1 {
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: 10px;
        }

        .table-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            margin-top: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #181818;
            padding: 20px;
            text-align: left;
            font-size: 0.75rem;
            color: var(--text-dim);
            text-transform: uppercase;
        }

        td {
            padding: 20px;
            border-bottom: 1px solid var(--border);
        }

        /* USER UI */
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            background: #222;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            color: var(--crimson);
            border: 1px solid var(--border);
        }

        /* BADGES */
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            display: inline-block;
        }

        .badge-admin {
            background: rgba(255, 46, 46, 0.1);
            color: var(--crimson);
        }

        .badge-user {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .status-active {
            color: var(--success);
        }

        .status-inactive {
            color: var(--text-dim);
            text-decoration: line-through;
        }

        .status-pending {
            color: var(--warning);
        }

        /* BUTTONS */
        .btn-action {
            padding: 6px 14px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: bold;
            transition: 0.3s;
            border: 1px solid transparent;
        }

        .btn-approve {
            border-color: var(--success);
            color: var(--success);
        }

        .btn-approve:hover {
            background: var(--success);
            color: black;
        }

        .btn-inactivate {
            border-color: var(--crimson);
            color: var(--crimson);
        }

        .btn-inactivate:hover {
            background: var(--crimson);
            color: white;
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="logo">CRIMSON<span>ADMIN</span></div>
        <ul class="nav-links">
            <li><a href="admindash.php"><i class="fas fa-chart-pie"></i> Overview</a></li>
            <li><a href="event.php"><i class="fas fa-calendar-check"></i> Events</a></li>
            <li><a href="bookings.php"><i class="fas fa-ticket-alt"></i> Bookings</a></li>
            <li><a href="user.php" class="active"><i class="fas fa-users"></i> Users</a></li>
            <li style="margin-top: auto; padding-top: 50px;">
                <a href="login.php" style="color: #ff4d4d;"><i class="fas fa-power-off"></i> Logout</a>
            </li>
        </ul>
    </aside>

    <main class="main-content">
        <header>
            <h1>Staff Directory</h1>
            <p style="color: var(--text-dim);">Manage team accounts and verify user access levels.</p>
        </header>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>User Profile</th>
                        <th>Email Address</th>
                        <th>Role</th>
                        <th>Management</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()):
                        $current_status = $row['status'] ?? 'pending';
                        ?>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="avatar">
                                        <?php echo substr($row['name'], 0, 1) . substr($row['lname'], 0, 1); ?></div>
                                    <div>
                                        <strong class="status-<?php echo $current_status; ?>">
                                            <?php echo $row['name'] . " " . $row['lname']; ?>
                                        </strong><br>
                                        <small
                                            style="color: var(--text-dim); text-transform: uppercase; font-size: 0.6rem;">
                                            <?php echo $current_status; ?>
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td style="color: var(--text-dim);"><?php echo $row['email']; ?></td>
                            <td>
                                <span class="badge <?php echo ($row['role'] == 'admin') ? 'badge-admin' : 'badge-user'; ?>">
                                    <?php echo $row['role']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($current_status !== 'active'): ?>
                                    <a href="update_user_status.php?id=<?php echo $row['id']; ?>&status=active"
                                        class="btn-action btn-approve">
                                        <i class="fas fa-check"></i> Approve
                                    </a>
                                <?php else: ?>
                                    <a href="update_user_status.php?id=<?php echo $row['id']; ?>&status=inactive"
                                        class="btn-action btn-inactivate" onclick="return confirm('Suspend this account?')">
                                        <i class="fas fa-ban"></i> Inactivate
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>