<?php
session_start();
include 'db.php';


$current_page = basename($_SERVER['PHP_SELF']);

if ($current_page == 'index.php' && isset($_SESSION['email'])) {
    session_unset();
    session_destroy();
    // No redirect needed if they are already on index.php
} 

// Standard check for the Dashboard:
if ($current_page != 'index.php' && !isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Proceed only if we have a session email
if (isset($_SESSION['email'])) {
    $user_email = $_SESSION['email'];

    // 1. FETCH: User ID and Points (from your 'user' table)
    $u_query = "SELECT id, points FROM user WHERE email = '$user_email' LIMIT 1";
    $u_res = mysqli_query($conn, $u_query);

    if (mysqli_num_rows($u_res) > 0) {
        $user_data = mysqli_fetch_assoc($u_res);
        $user_id = $user_data['id'];
        $reward_points = number_format($user_data['points']);
    } else {
        $user_id = 0;
        $reward_points = 0;
    }

    $display_name = ucwords(str_replace(['.', '_'], ' ', explode('@', $user_email)[0]));

    // 2. TICKETS: Count from 'bookings' table
    $t_query = "SELECT COUNT(*) as total FROM bookings WHERE user_id = '$user_id'";
    $t_res = mysqli_fetch_assoc(mysqli_query($conn, $t_query));
    $ticket_count = sprintf("%02d", $t_res['total']);

    // 3. INVESTMENT: Sum of prices from 'bookings' table
    $p_query = "SELECT SUM(price) as total_spent FROM bookings WHERE user_id = '$user_id'";
    $p_res = mysqli_fetch_assoc(mysqli_query($conn, $p_query));
    $total_spent = $p_res['total_spent'] ? number_format($p_res['total_spent'], 2) : "0.00";

    // 4. ALERTS: Active events
    $a_query = "SELECT COUNT(*) as alerts FROM events WHERE status = 'active'";
    $a_res = mysqli_fetch_assoc(mysqli_query($conn, $a_query));
    $alerts_count = sprintf("%02d", $a_res['alerts']);
}

// 5. Fetch Events for Slider (Available even if logged out on index)
$event_query = "SELECT * FROM events WHERE status = 'active' ORDER BY event_date ASC LIMIT 5";
$event_result = mysqli_query($conn, $event_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CrimsonGate | Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #050505;
            --sidebar-bg: #0a0a0a;
            --accent-red: #ff2e2e;
            --card-border: rgba(255, 255, 255, 0.1);
            --text-main: #ffffff;
            --text-dim: #888;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body {
            background-color: var(--bg);
            background-image: radial-gradient(circle at 50% -20%, #4b0000 0%, var(--bg) 80%);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px; background: var(--sidebar-bg); border-right: 1px solid var(--card-border);
            padding: 2.5rem 1.2rem; position: fixed; height: 100vh; display: flex; flex-direction: column; z-index: 100;
        }

        .logo { font-size: 1.4rem; font-weight: 900; color: var(--accent-red); text-transform: uppercase; letter-spacing: 3px; text-decoration: none; margin-bottom: 3rem; }
        nav ul { list-style: none; }
        nav ul li a { text-decoration: none; color: var(--text-dim); display: flex; align-items: center; padding: 0.9rem 1.2rem; border-radius: 12px; transition: 0.3s; margin-bottom: 5px; }
        nav ul li.active a, nav ul li a:hover { background: rgba(255, 46, 46, 0.1); color: var(--accent-red); }

        .main-content { margin-left: 260px; flex: 1; padding: 2.5rem 4rem; width: calc(100% - 260px); }
        header { margin-bottom: 3rem; }

        .slider-section { margin-bottom: 4rem; }
        .slider-wrapper { display: flex; gap: 25px; overflow-x: auto; scroll-snap-type: x mandatory; padding: 10px 5px 20px 5px; scrollbar-width: none; scroll-behavior: smooth; }
        .slider-wrapper::-webkit-scrollbar { display: none; }

        .event-card {
            min-width: 100%; height: 400px; background-size: cover; background-position: center;
            border-radius: 28px; position: relative; scroll-snap-align: start; display: flex;
            flex-direction: column; justify-content: flex-end; padding: 3rem;
            border: 2px solid var(--accent-red); box-shadow: 0 0 25px rgba(255, 46, 46, 0.2);
            overflow: hidden; text-decoration: none; color: white; transition: 0.4s ease;
        }

        .event-card::before { content: ''; position: absolute; inset: 0; background: linear-gradient(to top, rgba(0, 0, 0, 0.95) 10%, transparent 60%); z-index: 1; }
        .card-content { position: relative; z-index: 2; }
        .card-content h3 { font-size: 2.8rem; font-weight: 900; margin-bottom: 8px; }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; }
        .card { 
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.05), rgba(0, 0, 0, 0.2)); 
            border: 1px solid var(--card-border); border-radius: 24px; padding: 2.2rem; position: relative; 
            overflow: hidden; transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
        }
        .card:hover { transform: translateY(-10px); border-color: var(--accent-red); box-shadow: 0 15px 35px rgba(255, 46, 46, 0.2); }
        .bg-icon { position: absolute; right: -15px; bottom: -15px; font-size: 5.5rem; color: rgba(255, 255, 255, 0.03); pointer-events: none; }
        .stat-label { color: var(--text-dim); font-size: 0.75rem; font-weight: 800; letter-spacing: 2px; margin-bottom: 18px; }
        .stat-value { font-size: 3.2rem; font-weight: 900; line-height: 1; }
        .stat-value span { font-size: 1rem; color: var(--text-dim); margin-left: 5px; }
    </style>
</head>

<body>
    <aside class="sidebar">
        <a href="#" class="logo">CRIMSON<span>GATE</span></a>
        <nav>
            <ul>
                <li class="active"><a href="#"><i class="fas fa-th-large"></i>&nbsp;&nbsp;Dashboard</a></li>
                <li><a href="ticket.php"><i class="fas fa-ticket-alt"></i>&nbsp;&nbsp;Tickets</a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i>&nbsp;&nbsp;Profile</a></li>
            </ul>
        </nav>
        <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid var(--card-border);">
            <a href="index.php" style="color:var(--accent-red); text-decoration:none;">
                <i class="fas fa-sign-out-alt"></i> Sign Out
            </a>
        </div>
    </aside>

    <main class="main-content">
        <header>
            <p style="color:var(--accent-red); font-size:0.75rem; font-weight:800; letter-spacing:2px; margin-bottom: 5px;">SECURE ACCESS GRANTED</p>
            <h1 style="font-weight:900; font-size: 2.2rem;">Welcome, <?php echo isset($display_name) ? $display_name : 'Guest'; ?></h1>
        </header>

        <section class="slider-section">
            <div class="slider-wrapper" id="autoSlider">
                <?php while ($event = mysqli_fetch_assoc($event_result)): ?>
                    <a href="seatno.php?event_id=<?php echo $event['id']; ?>" class="event-card"
                        style="background-image: url('<?php echo $event['poster']; ?>');">
                        <div class="card-content">
                            <h3><?php echo $event['title']; ?></h3>
                            <p style="color: rgba(255,255,255,0.7);"><i class="far fa-calendar-alt"></i>
                                <?php echo date('M d, Y', strtotime($event['event_date'])); ?></p>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        </section>

        <section class="stats-grid">
            <div class="card">
                <i class="fas fa-ticket-alt bg-icon"></i>
                <div class="stat-label">MY TICKETS</div>
                <div class="stat-value"><?php echo isset($ticket_count) ? $ticket_count : '00'; ?></div>
            </div>

            <div class="card">
                <i class="fas fa-wallet bg-icon"></i>
                <div class="stat-label">TOTAL INVESTMENT</div>
                <div class="stat-value"><span style="font-size: 1.5rem;">₱</span><?php echo isset($total_spent) ? $total_spent : '0.00'; ?></div>
            </div>

            <div class="card">
                <i class="fas fa-coins bg-icon"></i>
                <div class="stat-label">REWARD POINTS</div>
                <div class="stat-value" style="color: var(--accent-red);"><?php echo isset($reward_points) ? $reward_points : '0'; ?><span>pts</span></div>
            </div>
        </section>
    </main>

    <script>
        const slider = document.getElementById('autoSlider');
        function autoPlay() {
            if (!slider) return;
            const cardWidth = slider.querySelector('.event-card').offsetWidth + 25;
            if (slider.scrollLeft >= (slider.scrollWidth - slider.clientWidth) - 5) {
                slider.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                slider.scrollBy({ left: cardWidth, behavior: 'smooth' });
            }
        }
        setInterval(autoPlay, 5000);
    </script>
</body>
</html>