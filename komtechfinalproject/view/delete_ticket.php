<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include '../db/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticketId = (int)$_POST['ticket_id'];

    $sql = "DELETE FROM events_tickets WHERE id = $ticketId";

    if ($conn->query($sql)) {
        echo "Ticket deleted successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
