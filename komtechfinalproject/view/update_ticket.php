<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include '../db/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticketId = (int)$_POST['ticket_id'];
    $eventName = $conn->real_escape_string($_POST['event_name']);
    $eventDateTime = $conn->real_escape_string($_POST['event_date_time']);
    $venue = $conn->real_escape_string($_POST['venue']);
    $price = (float)$_POST['price'];
    $status = $conn->real_escape_string($_POST['status']);
    $ticketHolder = $conn->real_escape_string($_POST['ticket_holder'] ?? '');

    $sql = "UPDATE events_tickets 
            SET event_name = '$eventName', 
                event_date_time = '$eventDateTime', 
                venue = '$venue', 
                price = $price, 
                status = '$status', 
                ticket_holder = '$ticketHolder' 
            WHERE id = $ticketId";

    if ($conn->query($sql)) {
        echo "Ticket updated successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
