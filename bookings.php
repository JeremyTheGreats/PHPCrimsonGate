<?php
// 1. SECURITY & CACHE HEADERS
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

session_start();
include 'db.php';


// 2. ADMIN ACCESS CHECK
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 3. FETCH DATA
$sql = "SELECT 
            b.id AS ref_id, 
            CONCAT(u.name, ' ', u.lname) AS customer_name, 
            e.title AS event_name, 
            b.seat_number, 
            b.price, 
            b.payment_method, 
            b.status 
        FROM bookings b
        LEFT JOIN user u ON b.user_id = u.id
        LEFT JOIN events e ON b.event_id = e.id
        ORDER BY b.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Management | CrimsonAdmin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --bg: #080808;
            --sidebar-bg: #0f0f0f;
            --card: #121212;
            --crimson: #ff2e2e;
            --border: rgba(255, 255, 255, 0.08);
            --text-dim: #a0a0a0;
            --success: #00ff78;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg); color: white; display: flex; min-height: 100vh; }
        .sidebar { width: 280px; background: var(--sidebar-bg); border-right: 1px solid var(--border); padding: 2.5rem 1.5rem; position: fixed; height: 100vh; display: flex; flex-direction: column; }
        .main { margin-left: 280px; padding: 40px 60px; width: calc(100% - 280px); }
        .table-container { background: var(--card); border: 1px solid var(--border); border-radius: 20px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { padding: 20px; font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase; background: #181818; border-bottom: 1px solid var(--border); }
        td { padding: 20px; border-bottom: 1px solid var(--border); font-size: 0.9rem; }
        .seat-tag { color: var(--crimson); font-weight: 800; background: rgba(255, 46, 46, 0.1); padding: 4px 8px; border-radius: 5px; }
        .status-pill { padding: 6px 12px; border-radius: 50px; font-size: 0.7rem; font-weight: 700; }
        .status-pending { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
        .status-confirmed { background: rgba(0, 255, 120, 0.1); color: var(--success); }
        
        /* New Action Button Style */
        .btn-confirm {
            background: rgba(0, 255, 120, 0.1);
            color: var(--success);
            border: 1px solid var(--success);
            padding: 5px 10px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.8rem;
            transition: 0.3s;
        }
        .btn-confirm:hover {
            background: var(--success);
            color: black;
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo" style="color:var(--crimson); font-weight:900; font-size:1.6rem; margin-bottom:3.5rem; text-align:center;">CRIMSON<span>ADMIN</span></div>
        <ul class="nav-links" style="list-style:none;">
            <li><a href="admindash.php" style="text-decoration:none; color:var(--text-dim); padding:14px; display:block;"><i class="fas fa-chart-pie"></i> Overview</a></li>
            <li><a href="event.php" style="text-decoration:none; color:var(--text-dim); padding:14px; display:block;"><i class="fas fa-calendar-check"></i> Events</a></li>
            <li><a href="bookings.php" style="text-decoration:none; color:var(--crimson); background:rgba(255,255,255,0.03); padding:14px; display:block; border-left:3px solid var(--crimson);"><i class="fas fa-ticket-alt"></i> Bookings</a></li>
            <li><a href="user.php" style="text-decoration:none; color:var(--text-dim); padding:14px; display:block;"><i class="fas fa-users"></i> Users</a></li>
            <li style="margin-top: auto; padding-bottom: 20px;">
                <a href="index.php" style="color: var(--crimson); text-decoration:none; padding:14px; display:block;"><i class="fas fa-power-off"></i> Logout</a>
            </li>
        </ul>
    </aside>

    <div class="main">
        <div class="header" style="margin-bottom:40px;">
            <h1 style="font-size: 2.5rem; font-weight: 900; margin:0;">Booking Management</h1>
            <p style="color: var(--text-dim);">Verify payments and confirm seat reservations.</p>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Ref ID</th>
                        <th>Customer</th>
                        <th>Event</th>
                        <th>Seats</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $status = strtolower($row['status']);
                            $statusClass = ($status == 'pending') ? 'status-pending' : 'status-confirmed';
                            ?>
                            <tr>
                                <td style="color: var(--text-dim);">#CG-<?php echo $row['ref_id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['customer_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['event_name']); ?></td>
                                <td><span class="seat-tag"><?php echo htmlspecialchars($row['seat_number']); ?></span></td>
                                <td>₱<?php echo number_format($row['price'], 2); ?></td>
                                <td><span class="status-pill <?php echo $statusClass; ?>"><?php echo strtoupper($status); ?></span></td>
                                <td>
                                    <?php if ($status == 'pending'): ?>
                                        <a href="confirm_booking.php?id=<?php echo $row['ref_id']; ?>" 
                                           class="btn-confirm" 
                                           onclick="return confirm('Confirm payment for this booking?')">
                                           <i class="fas fa-check"></i> Confirm
                                        </a>
                                    <?php else: ?>
                                        <span style="color: var(--text-dim); font-size: 0.8rem;">Verified <i class="fas fa-check-double"></i></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align:center; padding:30px; color:var(--text-dim);'>No data found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>