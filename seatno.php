<?php
include 'db.php'; // Ensure your DB connection is correct

// 1. Get the dynamic Event ID from the URL (e.g., seatno.php?event_id=2)
// If no ID is passed, it defaults to 1 so the page doesn't crash
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 1;

// 2. Fetch occupied seats ONLY for this specific event
$occupied_seats = [];
$query = "SELECT seat_number FROM bookings WHERE event_id = ? AND (status = 'paid' OR status = 'pending')";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $occupied_seats[] = $row['seat_number'];
}

// 3. (Optional) Fetch Event Name to display to the user
$event_info = $conn->prepare("SELECT title FROM events WHERE id = ?");
$event_info->bind_param("i", $event_id);
$event_info->execute();
$event_res = $event_info->get_result()->fetch_assoc();
$display_name = $event_res ? $event_res['title'] : "Select Your Seats";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $display_name; ?> | CrimsonGate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #050505;
            --crimson: #ff2e2e;
            --vip: #ffd700;
            --available: #1a1a1a;
            --occupied: #0a0a0a;
            --text-dim: #666;
            --seat-size: 42px;
        }

        body {
            background-color: var(--bg);
            color: white;
            font-family: 'Outfit', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px 150px 20px;
            margin: 0;
        }

        .event-title {
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 30px;
            font-weight: 900;
        }

        .stage-container {
            width: 100%;
            max-width: 600px;
            text-align: center;
            margin-bottom: 60px;
        }

        .stage-glow {
            height: 4px;
            background: white;
            box-shadow: 0 10px 40px var(--crimson), 0 0 20px white;
            border-radius: 50%;
        }

        .seat-map {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 15px;
            padding: 20px;
        }

        .seat-map input {
            display: none;
        }

        .seat {
            width: var(--seat-size);
            height: var(--seat-size);
            background: var(--available);
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-bottom: 4px solid rgba(0, 0, 0, 0.5);
            color: #555;
        }

        .seat.vip-row {
            border: 1px solid rgba(255, 215, 0, 0.2);
            color: var(--vip);
        }

        .seat.occupied {
            background: var(--occupied) !important;
            color: #222 !important;
            border: 1px dashed #333 !important;
            cursor: not-allowed;
            opacity: 0.5;
            transform: none !important;
        }

        .seat:hover:not(.occupied) {
            transform: translateY(-5px);
            background: #333;
            color: white;
        }

        input:checked+.seat {
            background: var(--crimson) !important;
            color: white !important;
            box-shadow: 0 0 20px var(--crimson);
            border-color: white;
            transform: scale(1.1);
        }

        .legend {
            display: flex;
            gap: 25px;
            margin-bottom: 40px;
            background: rgba(255, 255, 255, 0.03);
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 0.85rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dot {
            width: 14px;
            height: 14px;
            border-radius: 4px;
        }

        .payment-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(20px);
            padding: 20px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            box-sizing: border-box;
            z-index: 9999;
        }

        .proceed-btn {
            background: var(--crimson);
            color: white;
            border: none;
            padding: 18px 60px;
            border-radius: 15px;
            font-weight: 900;
            cursor: pointer;
            transition: 0.3s;
        }

        .proceed-btn:hover {
            background: white;
            color: black;
            transform: translateY(-3px);
        }
    </style>
</head>

<body>

    <h2 class="event-title"><?php echo $display_name; ?></h2>

    <div class="stage-container">
        <div class="stage-glow"></div>
        <p style="margin-top: 20px; letter-spacing: 10px; color: var(--text-dim); font-size: 0.7rem;">STAGE</p>
    </div>

    <div class="legend">
        <div class="legend-item">
            <div class="dot" style="border: 1px solid var(--vip);"></div> VIP
        </div>
        <div class="legend-item">
            <div class="dot" style="background: var(--available);"></div> Available
        </div>
        <div class="legend-item">
            <div class="dot" style="background: var(--crimson);"></div> Selected
        </div>
        <div class="legend-item">
            <div class="dot" style="background: var(--occupied); border: 1px dashed #333;"></div> Occupied
        </div>
    </div>

    <form action="payment.php" method="POST">
        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

        <div class="seat-map">
            <?php
            $rows = range('A', 'T');
            foreach ($rows as $r) {
                $isVIP = in_array($r, ['A', 'B', 'C', 'D', 'E']);
                $price = $isVIP ? 5000 : 2000;

                for ($i = 1; $i <= 10; $i++) {
                    $seatID = $r . $i;
                    $isOccupied = in_array($seatID, $occupied_seats);

                    $class = $isVIP ? 'seat vip-row' : 'seat';
                    if ($isOccupied)
                        $class .= ' occupied';

                    $disabled = $isOccupied ? 'disabled' : '';

                    echo "
                    <label>
                        <input type='checkbox' name='seats[]' value='$seatID' 
                               data-price='$price' onclick='calcTotal()' $disabled>
                        <span class='$class'>$seatID</span>
                    </label>";
                }
            }
            ?>
        </div>

        <div class="payment-bar">
            <div class="total-text">
                <h4 style="margin:0; color:var(--text-dim); font-size:0.8rem; text-transform:uppercase;">Total Amount
                </h4>
                <p id="total-display" style="margin:5px 0 0 0; font-size:2rem; font-weight:900;">₱0</p>
            </div>
            <button type="submit" class="proceed-btn">PROCEED TO PAYMENT</button>
        </div>
    </form>

    <script>
        function calcTotal() {
            let total = 0;
            document.querySelectorAll('input[type="checkbox"]:checked').forEach(cb => {
                total += parseInt(cb.getAttribute('data-price'));
            });
            document.getElementById('total-display').innerText = '₱' + total.toLocaleString();
        }
    </script>

</body>

</html>