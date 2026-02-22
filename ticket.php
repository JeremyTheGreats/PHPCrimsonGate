<?php
session_start();
include "db.php";

$events = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets | CrimsonGate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --bg-dark: #080808;
            --sidebar-bg: rgba(15, 15, 15, 0.95);
            --crimson: #ff2e2e;
            --crimson-dim: #b30000;
            --glass: rgba(255, 255, 255, 0.03);
            --border: rgba(255, 255, 255, 0.08);
            --text-dim: #a0a0a0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            background-image: radial-gradient(circle at 50% -20%, #4b0000 0%, #080808 80%);
            background-repeat: no-repeat;
            color: white;
            display: flex;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }
        html::before{
            content:"";
            position: fixed;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;                 /* start hidden */
            transition: opacity 0.6s ease;
            z-index: 0;
            pointer-events: none;
        }
        html.bg-on::before{
            background-image:
                linear-gradient(rgba(0,0,0,0.85), rgba(0,0,0,0.5)),
                var(--page-bg);
            opacity: 1;
        }
        html, body { height: 100%; }

        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            backdrop-filter: blur(15px);
            padding: 2.5rem 1.2rem;
            border-right: 1px solid var(--border);
            position: fixed;
            height: 100vh;
            z-index: 100;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 900;
            letter-spacing: 2px;
            color: var(--crimson);
            margin-bottom: 3rem;
            text-align: center;
        }

        .logo span {
            color: white;
        }

        nav ul {
            list-style: none;
        }

        nav ul li {
            margin-bottom: 0.8rem;
        }

        nav ul li a {
            text-decoration: none;
            color: var(--text-dim);
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 0.9rem 1.2rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        nav ul li.active a {
            background: rgba(255, 46, 46, 0.1);
            color: var(--crimson);
            box-shadow: inset 0 0 10px rgba(255, 46, 46, 0.05);
        }

        nav ul li a:hover {
            color: white;
            background: rgba(255, 255, 255, 0.05);
        }

        .content {
            margin-left: 260px;
            flex: 1;
            padding: 2.5rem 4rem;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3.5rem;
        }

        .top-bar h1 {
            font-size: 2rem;
            font-weight: 800;
        }

        .search-box {
            background: var(--glass);
            border: 1px solid var(--border);
            padding: 12px 25px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            width: 400px;
            transition: 0.3s;
        }

        .search-box:focus-within {
            border-color: var(--crimson);
            box-shadow: 0 0 15px rgba(255, 46, 46, 0.2);
        }

        .search-box input {
            background: none;
            border: none;
            color: white;
            margin-left: 12px;
            width: 100%;
            outline: none;
            font-size: 0.9rem;
        }

        .ticket-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(480px, 1fr));
            gap: 30px;
        }

        .ticket-card {
            background: rgba(255, 255, 255, 0.02);
            display: flex;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            border: 1px solid var(--border);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* hover effect */
        .ticket-card:hover {
            border-color: var(--crimson);
            transform: translateY(-8px) scale(1.02);
            background: rgba(255, 255, 255, 0.05);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        /* TOP punch hole */
        .ticket-card::before {
            content: '';
            position: absolute;
            width: 30px;
            height: 30px;
            background: #080808;
            border-radius: 50%;
            left: 72%;
            top: -15px;
            z-index: 3;
            border: 1px solid var(--border);
        }


        /* show background on hover */
        .ticket-card:hover::after {
            opacity: 0.25;
        }

        /* keep content above background */
        .ticket-card > * {
            position: relative;
            z-index: 2;
        }
        
        .event-info {
            padding: 2rem;
            flex: 2.5;
            border-right: 2px dashed rgba(255, 255, 255, 0.1);
        }

        .artist-tag {
            font-size: 0.7rem;
            font-weight: 800;
            color: var(--crimson);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: block;
            margin-bottom: 8px;
        }

        .artist-name {
            font-size: 1.8rem;
            margin-bottom: 8px;
            font-weight: 900;
            letter-spacing: -0.5px;
        }

        .venue {
            color: var(--text-dim);
            font-size: 0.9rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .event-meta {
            display: flex;
            gap: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .event-meta i {
            color: var(--crimson);
        }

        .booking-action {
            flex: 1.2;
            background: rgba(255, 46, 46, 0.03);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            text-align: center;
        }

        .price {
            font-size: 1.4rem;
            font-weight: 900;
            margin-bottom: 15px;
            color: #fff;
        }

        .price span {
            font-size: 0.75rem;
            color: var(--text-dim);
            display: block;
            font-weight: 400;
            margin-bottom: 4px;
        }

        .book-btn {
            background: var(--crimson);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 0.75rem;
            width: 100%;
        }

        .book-btn:hover {
            background: white;
            color: black;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
        }

        @media (max-width: 1200px) {
            .ticket-grid {
                grid-template-columns: 1fr;
            }

            .content {
                padding: 2rem;
            }
        }
    </style>

    

</head>

<body>
    

    <aside class="sidebar">
        <div class="logo">CRIMSON<span>GATE</span></div>
        <nav>
            <ul>
                <li><a href="dash.php"><i class="fas fa-th-large"></i> Dashboard</a></li>
                <li class="active"><a href="#"><i class="fas fa-ticket-alt"></i> Tickets</a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            </ul>
        </nav>
    </aside>

    <main class="content">
        <div class="top-bar">
            <h1>Find Your Next Experience</h1>
            <div class="search-box">
                <i class="fas fa-search" style="color: var(--crimson);"></i>
                <input type="text" placeholder="Search by Artist, Venue, or City...">
            </div>
        </div>

        <div class="ticket-grid">

            <?php while($row = $events->fetch_assoc()): ?>
                <div class="ticket-card"
                    style="--card-bg: url('<?php echo $row['poster']; ?>');">

                    <div class="event-info">
                        <span class="artist-tag"><?php echo $row['title']; ?></span>
                        <h2 class="artist-name"><?php echo $row['artist']; ?></h2>

                        <span class="venue">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo $row['venue']; ?>
                        </span>

                        <div class="event-meta">
                            <span><i class="fas fa-calendar"></i>
                                <?php echo date("M d, Y", strtotime($row['event_date'])); ?>
                            </span>
                            <span><i class="fas fa-clock"></i>
                                <?php echo date("h:i A", strtotime($row['event_time'])); ?>
                            </span>
                        </div>
                    </div>

                    <div class="booking-action">
                        <div class="price">
                            <span>Tickets from</span>
                            ₱<?php echo number_format($row['price']); ?>
                        </div>

                        <a href="seatno.php?event_id=<?php echo $row['id']; ?>" style="width:100%;">
                            <button class="book-btn">Book Seat</button>
                        </a>
                    </div>

                </div>
            <?php endwhile; ?>

            </div>

    </main>
        <script>
        const htmlEl = document.documentElement;

        document.querySelectorAll(".ticket-card").forEach(card => {
        card.addEventListener("mouseenter", () => {
            const bg = getComputedStyle(card).getPropertyValue("--card-bg").trim();
            htmlEl.style.setProperty("--page-bg", bg);
            htmlEl.classList.add("bg-on");
        });

        card.addEventListener("mouseleave", () => {
            htmlEl.classList.remove("bg-on");
        });
        });

        </script>

</body>

</html>