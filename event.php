<?php

include 'db.php';


$query = "SELECT * FROM events ORDER BY event_date ASC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Console | CrimsonAdmin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        
        :root {
            --bg: #080808;
            --sidebar-bg: #0f0f0f;
            --card: #121212;
            --crimson: #ff2e2e;
            --crimson-glow: rgba(255, 46, 46, 0.4);
            --border: rgba(255, 255, 255, 0.08);
            --text-dim: #a0a0a0;
            --glass: rgba(255, 255, 255, 0.03);
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

        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            padding: 2.5rem 1.5rem;
            position: fixed;
            height: 100vh;
            z-index: 100;
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
            text-shadow: 0 0 15px var(--crimson-glow);
        }

        .logo span {
            color: white;
        }

        .nav-links {
            list-style: none;
        }

        .nav-links li {
            margin-bottom: 12px;
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

        .nav-links a:hover,
        .nav-links a.active {
            background: var(--glass);
            color: var(--crimson);
            transform: translateX(5px);
            border-left: 3px solid var(--crimson);
        }

        .main-content {
            margin-left: 280px;
            padding: 40px 60px;
            width: calc(100% - 280px);
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .create-btn {
            background: var(--crimson);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
            box-shadow: 0 4px 15px var(--crimson-glow);
        }

        .event-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
        }

        .event-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 24px;
            overflow: hidden;
            transition: 0.4s;
        }

        .event-card:hover {
            border-color: var(--crimson);
            transform: translateY(-10px);
        }

        .poster-area {
            height: 200px;
            background-color: #1a1a1a;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .status-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 900;
            backdrop-filter: blur(10px);
        }

        .details-area {
            padding: 25px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .info-item span {
            display: block;
            color: var(--text-dim);
            font-size: 0.75rem;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="logo">CRIMSON<span>ADMIN</span></div>
        <ul class="nav-links">
            <li><a href="admindash.php"><i class="fas fa-chart-pie"></i> Overview</a></li>
            <li><a href="event.php" class="active"><i class="fas fa-calendar-check"></i> Events</a></li>
            <li><a href="bookings.php"><i class="fas fa-ticket-alt"></i> Bookings</a></li>
            <li><a href="user.php"><i class="fas fa-users"></i> Users</a></li>
            <li style="margin-top: auto; padding-bottom: 20px;">
                <a href="index.php" style="color: var(--crimson);"><i class="fas fa-power-off"></i> Logout</a>
            </li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="header-section">
            <div>
                <h1 style="font-size: 2.5rem; font-weight: 900; margin: 0;">Event Console</h1>
                <p style="color: var(--text-dim); margin: 5px 0 0 0;">Manage and monitor your live gate entries.</p>
            </div>
            <a href="createevent.php" style="text-decoration: none;">
                <button class="create-btn">
                    <i class="fas fa-plus"></i> Create Event
                </button>
            </a>
        </header>

        <section class="event-grid">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Logic for status color
                    $isActive = (strtolower($row['status']) == 'active');
                    $badgeStyle = $isActive ? 'background: rgba(0, 255, 120, 0.2); color: #00ff78;' : 'background: rgba(255, 46, 46, 0.2); color: var(--crimson);';

                    // Display each event card
                    ?>
                    <div class="event-card">
                        <div class="poster-area"
                            style="background-image: url('<?php echo htmlspecialchars($row['poster']); ?>');">
                            <div class="status-badge" style="<?php echo $badgeStyle; ?>">
                                <?php echo strtoupper($row['status']); ?>
                            </div>
                        </div>
                        <div class="details-area">
                            <h3><?php echo htmlspecialchars($row['artist']); ?></h3>
                            <p style="color: var(--text-dim); font-size: 0.9rem; margin-top: 5px;">
                                <i class="fas fa-music" style="color: var(--crimson); margin-right: 5px;"></i>
                                <?php echo htmlspecialchars($row['title']); ?>
                            </p>
                            <p style="color: var(--text-dim); font-size: 0.9rem; margin-top: 5px;">
                                <i class="fas fa-map-marker-alt" style="color: var(--crimson); margin-right: 5px;"></i>
                                <?php echo htmlspecialchars($row['venue']); ?>
                            </p>

                            <div class="info-row">
                                <div class="info-item">
                                    <span>Date</span>
                                    <b><?php echo date('M d, Y', strtotime($row['event_date'])); ?></b>
                                </div>
                                <div class="info-item">
                                    <span>Price</span>
                                    <b>₱<?php echo number_format($row['price'], 0); ?></b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p style='color: var(--text-dim); grid-column: 1/-1; text-align: center; padding: 50px;'>No events found in database.</p>";
            }
            ?>
        </section>
    </main>
</body>

</html>