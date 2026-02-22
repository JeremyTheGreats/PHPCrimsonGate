<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $method = $_POST['method'];
    $event_id = $_POST['event_id'];
    $total_amount = $_POST['total_amount'];
    $seats = isset($_POST['final_seats']) ? $_POST['final_seats'] : [];

    // --- STEP 1: FETCH DYNAMIC USER ID BASED ON LOGGED-IN EMAIL ---
    if (!isset($_SESSION['email'])) {
        die("Error: You must be logged in to book tickets.");
    }

    $email = $_SESSION['email'];
    $user_query = $conn->prepare("SELECT id FROM user WHERE email = ?");
    $user_query->bind_param("s", $email);
    $user_query->execute();
    $user_result = $user_query->get_result();
    $user_data = $user_result->fetch_assoc();

    if (!$user_data) {
        die("Error: User account not found in database.");
    }

    $user_id = $user_data['id']; // Now correctly identifies Kimberly (3) or Jeremy (1)

    if (empty($seats)) {
        die("Error: No seats selected.");
    }

    // --- STEP 2: START TRANSACTION FOR BOOKING & POINTS ---
    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, event_id, seat_number, price, status, payment_method) VALUES (?, ?, ?, ?, 'pending', ?)");

        $total_points_to_add = 0;

        foreach ($seats as $seat) {
            // Determine price based on your row logic
            $row_letter = substr($seat, 0, 1);
            $seat_price = in_array($row_letter, ['A', 'B', 'C', 'D', 'E']) ? 5000 : 2000;

            // LOGIC: Earn 10% of seat price as Reward Points
            $points_earned = $seat_price * 0.10;
            $total_points_to_add += $points_earned;

            $stmt->bind_param("iisis", $user_id, $event_id, $seat, $seat_price, $method);
            $stmt->execute();
        }

        // --- STEP 3: UPDATE THE 'POINTS' COLUMN IN THE 'USER' TABLE ---
        $update_points = $conn->prepare("UPDATE user SET points = points + ? WHERE id = ?");
        $update_points->bind_param("di", $total_points_to_add, $user_id);
        $update_points->execute();

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        die("Database Error: " . $e->getMessage());
    }

    // --- STEP 4: REDIRECT TO SUCCESS PAGE ---
    if ($method === 'gcash' || $method === 'maya') {
        echo "<div style='background:#050505; color:white; height:100vh; display:flex; flex-direction:column; align-items:center; justify-content:center; font-family:sans-serif;'>
                <img src='https://upload.wikimedia.org/wikipedia/commons/thumb/5/59/GCash_logo.svg/512px-GCash_logo.svg.png' width='150' style='margin-bottom:20px;'>
                <h2>Redirecting to " . strtoupper($method) . "...</h2>
                <p>Transaction ID: " . uniqid('TXN_') . "</p>
                <p>You earned <b>" . $total_points_to_add . "</b> Reward Points!</p>
                <script>
                    setTimeout(function() {
                        window.location.href = 'payment_success.php?status=success&method=$method&points=$total_points_to_add';
                    }, 3000);
                </script>
              </div>";
    } else {
        header("Location: payment_success.php?status=success&method=card&points=$total_points_to_add");
    }
} else {
    header("Location: seatno.php");
}
?>