<?php

session_start();
include 'db.php';


// Check if admin is logged in
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 2. FETCH LIVE STATS
// Total Revenue: Summing confirmed booking prices
$rev_query = mysqli_query($conn, "SELECT SUM(price) as total FROM bookings WHERE status = 'confirmed'");
$rev_data = mysqli_fetch_assoc($rev_query);
$total_revenue = $rev_data['total'] ?? 0;

// Tickets Sold: Count total entries in bookings table
$ticket_query = mysqli_query($conn, "SELECT COUNT(*) as ticket_count FROM bookings");
$ticket_data = mysqli_fetch_assoc($ticket_query);
$tickets_sold = $ticket_data['ticket_count'] ?? 0;

// Active Events: Count events with 'active' status
$active_query = mysqli_query($conn, "SELECT COUNT(*) as active_count FROM events WHERE status = 'active'");
$active_data = mysqli_fetch_assoc($active_query);
$active_events = $active_data['active_count'] ?? 0;

// Total Users: Count registered users (excluding admins)
$user_count_query = mysqli_query($conn, "SELECT COUNT(*) as u_count FROM user WHERE role = 'user'");
$user_data = mysqli_fetch_assoc($user_count_query);
$total_users = $user_data['u_count'] ?? 0;

// 3. FETCH ALL EVENTS FOR THE TABLE
$events_result = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | CrimsonGate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script type="text/javascript">
        function preventBack() { window.history.forward(); }
        setTimeout("preventBack()", 0);
        window.onunload = function () { null };
    </script>

    <style>
        :root {
            --crimson: #ff2e2e;
            --crimson-glow: rgba(255, 46, 46, 0.4);
            --bg-dark: #070707;
            --sidebar-bg: #0f0f0f;
            --glass: rgba(255, 255, 255, 0.03);
            --border: rgba(255, 255, 255, 0.07);
            --success: #00ff88;
            --text-main: #ffffff;
            --text-dim: #8e8e8e;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            padding: 2.5rem 1.5rem;
        }

        .logo {
            text-align: center;
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--crimson);
            text-shadow: 0 0 15px var(--crimson-glow);
            margin-bottom: 3.5rem;
        }

        .logo span {
            color: white;
        }

        .nav-links {
            list-style: none;
        }

        .nav-links a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 18px;
            color: var(--text-dim);
            text-decoration: none;
            border-radius: 12px;
            transition: 0.3s;
        }

        .nav-links a.active,
        .nav-links a:hover {
            background: var(--glass);
            color: var(--crimson);
            border-left: 3px solid var(--crimson);
        }

        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 3rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            margin-bottom: 3.5rem;
        }

        .stat-card {
            padding: 25px;
            background: var(--glass);
            border: 1px solid var(--border);
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }

        .stat-card h4 {
            color: var(--text-dim);
            font-size: 0.75rem;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .stat-card p {
            font-size: 1.8rem;
            font-weight: 900;
        }

        /* Table */
        .table-container {
            background: var(--sidebar-bg);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            padding: 20px;
            background: rgba(255, 255, 255, 0.02);
            color: var(--text-dim);
            font-size: 0.75rem;
            text-transform: uppercase;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 22px 20px;
            border-bottom: 1px solid var(--border);
            font-size: 0.95rem;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .status-active {
            background: rgba(0, 255, 136, 0.1);
            color: var(--success);
            border: 1px solid rgba(0, 255, 136, 0.2);
        }

        .status-soldout {
            background: rgba(255, 46, 46, 0.1);
            color: var(--crimson);
            border: 1px solid rgba(255, 46, 46, 0.2);
        }

        .actions {
            display: flex;
            gap: 12px;
        }

        .action-btn {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--glass);
            color: var(--text-dim);
            border: 1px solid var(--border);
            border-radius: 10px;
            text-decoration: none;
        }

        .delete-btn:hover {
            background: var(--crimson);
            color: white;
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="logo">CRIMSON<span>ADMIN</span></div>
        <ul class="nav-links">
            <li><a href="admindash.php" class="active"><i class="fas fa-chart-pie"></i> Overview</a></li>
            <li><a href="event.php"><i class="fas fa-calendar-check"></i> Events</a></li>
            <li><a href="bookings.php"><i class="fas fa-ticket-alt"></i> Bookings</a></li>
            <li><a href="user.php"><i class="fas fa-users"></i> Users</a></li>
            <li style="margin-top: 50px;"><a href="index.php" style="color:var(--crimson)"><i
                        class="fas fa-power-off"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="header"
            style="display:flex; justify-content:space-between; align-items:center; margin-bottom:3rem;">
            <div>
                <h2>Dashboard Overview</h2>
                <p style="color: var(--text-dim); font-size: 0.9rem;">Live analytics from your system.</p>
            </div>
            <a href="createevent.php" style="text-decoration: none;">
                <button
                    style="padding: 12px 24px; background: var(--crimson); color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 700;">
                    <i class="fas fa-plus-circle"></i> Create Event
                </button>
            </a>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h4>Total Revenue</h4>
                <p>₱<?php echo number_format($total_revenue, 0); ?></p>
            </div>
            <div class="stat-card">
                <h4>Tickets Sold</h4>
                <p><?php echo number_format($tickets_sold); ?></p>
            </div>
            <div class="stat-card">
                <h4>Active Events</h4>
                <p><?php echo $active_events; ?></p>
            </div>
            <div class="stat-card">
                <h4>Total Users</h4>
                <p><?php echo $total_users; ?></p>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Event Artist</th>
                        <th>Venue Location</th>
                        <th>Show Date</th>
                        <th>Gate Status</th>
                        <th>Base Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($events_result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($events_result)): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['artist']); ?></strong><br>
                                    <small style="color: var(--text-dim)"><?php echo htmlspecialchars($row['title']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($row['venue']); ?></td>
                                <td>
                                    <?php echo date('M d, Y', strtotime($row['event_date'])); ?><br>
                                    <small
                                        style="color: var(--crimson)"><?php echo date('h:i A', strtotime($row['event_time'])); ?></small>
                                </td>
                                <td>
                                    <span
                                        class="status-badge <?php echo ($row['status'] == 'active') ? 'status-active' : 'status-soldout'; ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                                <td>₱<?php echo number_format($row['price'], 2); ?></td>
                                <td class="actions">
                                    <a href="editevent.php?id=<?php echo $row['id']; ?>" class="action-btn"><i
                                            class="fas fa-pen-nib"></i></a>
                                    <a href="delete_event.php?id=<?php echo $row['id']; ?>" class="action-btn delete-btn"
                                        onclick="return confirm('Delete this event?')"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align: center; padding: 40px;">No events in database.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>