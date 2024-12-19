<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include('../db/config.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM bookings WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: buy_ticket.php"); // Redirect back to the main page
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>
