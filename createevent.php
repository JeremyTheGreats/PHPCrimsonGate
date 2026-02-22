<!DOCTYPE html>
<html>
<head>
    <title>Create Event</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --bg-dark: #070707;
            --sidebar-bg: #0f0f0f;
            --crimson: #ff2e2e;
            --crimson-glow: rgba(255, 46, 46, 0.4);
            --glass: rgba(255, 255, 255, 0.03);
            --border: rgba(255, 255, 255, 0.07);
            --text-main: #ffffff;
            --text-dim: #8e8e8e;
            --success: #00ff88;
            --warning: #ffcc00;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            background-image: radial-gradient(circle at 260px 0%, #1a0505 0%, #070707 50%);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            padding: 2.5rem 1.5rem;
            position: fixed;
            height: 100vh;
            z-index: 10;
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: var(--glass);
            color: var(--crimson);
            transform: translateX(5px);
            border-left: 3px solid var(--crimson);
        }

        .create-event-wrapper {
            max-width: 650px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 22px;
            padding: 35px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .create-event-wrapper h2 {
            margin-bottom: 25px;
            font-size: 1.6rem;
            font-weight: 800;
            color: #ff2e2e;
            text-align: center;
        }

        .create-event-form label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #a0a0a0;
        }

        .create-event-form input {
            width: 100%;
            padding: 14px 16px;
            margin-bottom: 18px;
            border-radius: 12px;
            background: #0b0b0b;
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: white;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .create-event-form input[type="file"] {
            padding: 10px;
            background: #0f0f0f;
        }

        .create-event-form input:focus {
            border-color: #ff2e2e;
            box-shadow: 0 0 12px rgba(255, 46, 46, 0.25);
        }

        .create-event-form button {
            width: 100%;
            padding: 16px;
            margin-top: 10px;
            background: linear-gradient(135deg, #ff2e2e, #b30000);
            border: none;
            border-radius: 14px;
            color: white;
            font-size: 0.85rem;
            font-weight: 900;
            letter-spacing: 1.2px;
            cursor: pointer;
            transition: 0.3s ease;
            box-shadow: 0 10px 25px rgba(255, 46, 46, 0.35);
        }

        .create-event-form button:hover {
            transform: translateY(-2px);
            filter: brightness(1.2);
            box-shadow: 0 15px 35px rgba(255, 46, 46, 0.45);
        }
        .toast{
            position: fixed;
            top: 25px;
            right: 25px;
            background: rgba(0,255,136,0.12);
            border: 1px solid rgba(0,255,136,0.25);
            color: #00ff88;
            padding: 14px 18px;
            border-radius: 12px;
            font-weight: 800;
            z-index: 9999;
            backdrop-filter: blur(10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.4);
        }

 
    </style>

    <?php

        include 'db.php';

        $error = "";
        $success = "";

        if (isset($_POST['create_event'])) {

            $title = $_POST['title'];
            $artist = $_POST['artist'];
            $event_date = $_POST['event_date'];
            $event_time = $_POST['event_time'];
            $venue = $_POST['venue'];
            $price = $_POST['price'];

            // simple upload
            $filename = time() . "_" . $_FILES['poster']['name'];
            $path = "images/" . $filename;

            if (move_uploaded_file($_FILES['poster']['tmp_name'], $path)) {

                $stmt = $conn->prepare("INSERT INTO events (title, artist, event_date, event_time, venue, price, poster)
                                        VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssis", $title, $artist, $event_date, $event_time, $venue, $price, $path);

                if ($stmt->execute()) {
                    $success = "Event created successfully!";

                } else {
                    $error = "Database error!";
                }

                $stmt->close();

            } else {
                $error = "Upload failed!";
            }
        }
        ?>
    <script>
    const toast = document.getElementById("toast");
    if (toast) setTimeout(() => toast.style.opacity = "0", 1500);
    </script>


</head>
<body>

    <aside class="sidebar">
        <div class="logo">CRIMSON<span>ADMIN</span></div>
        <ul class="nav-links">
            <li><a href="#" class="active"><i class="fas fa-chart-pie"></i> Overview</a></li>
            <li><a href="#"><i class="fas fa-calendar-check"></i> Events</a></li>
            <li><a href="#"><i class="fas fa-ticket-alt"></i> Bookings</a></li>
            <li><a href="#"><i class="fas fa-users"></i> Staff</a></li>
            <li style="margin-top: 50px;"><a href="index.php"><i class="fas fa-power-off"></i> Logout</a></li>
        </ul>
    </aside>

    <div class="create-event-wrapper">
        <h2>Create New Event</h2>

        <?php if ($error): ?>
            <div class="toast" style="background: rgba(255,46,46,0.12); border-color: rgba(255,46,46,0.25); color: #ff2e2e;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div id="toast" class="toast">
                <?php echo $success; ?> Redirecting...
            </div>

            <script>
                setTimeout(() => {
                    window.location.href = "admindash.php";
                }, 2000);
            </script>
        <?php endif; ?>


        <form method="POST" enctype="multipart/form-data" class="create-event-form">

            <label>Event Title</label>
            <input type="text" name="title" required>

            <label>Artist / Singer</label>
            <input type="text" name="artist" required>

            <label>Event Date</label>
            <input type="date" name="event_date" required>

            <label>Event Time</label>
            <input type="time" name="event_time" required>

            <label>Venue</label>
            <input type="text" name="venue" required>

            <label>Base Ticket Price</label>
            <input type="number" name="price" required>

            <label>Event Poster</label>
            <input type="file" name="poster" accept="image/*" required>

            <button type="submit" name="create_event">Create Event</button>

        </form>
    </div>


</body>
</html>