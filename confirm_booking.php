<?php
session_start();
include 'db.php'; 

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized: You must be an admin to do this.");
}

if (isset($_GET['id'])) {
    // 1. Ensure the ID is a clean integer
    $booking_id = intval($_GET['id']); 

    // 2. Use a prepared statement to ensure the string 'confirmed' is sent correctly
    $stmt = $conn->prepare("UPDATE bookings SET status = 'confirmed' WHERE id = ?");
    $stmt->bind_param("i", $booking_id);

    if ($stmt->execute()) {
        // Check if a row was actually changed
        if ($stmt->affected_rows > 0) {
            header("Location: bookings.php?update=success");
        } else {
            // No rows changed? Maybe the ID doesn't exist or it's already confirmed
            header("Location: bookings.php?update=no_change");
        }
        exit();
    } else {
        echo "Database Error: " . $stmt->error; 
    }
} else {
    echo "Error: No ID provided.";
}
?>