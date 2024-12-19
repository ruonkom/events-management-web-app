<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once '../db/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventName = $conn->real_escape_string($_POST['event_name']);
    $eventDateTime = $conn->real_escape_string($_POST['event_date_time']);
    $venue = $conn->real_escape_string($_POST['venue']);
    $price = (float)$_POST['price'];
    $status = $conn->real_escape_string($_POST['status']);
    $ticketHolder = $conn->real_escape_string($_POST['ticket_holder'] ?? '');

    $sql = "INSERT INTO events_tickets (event_name, event_date_time, venue, price, status, ticket_holder) 
            VALUES ('$eventName', '$eventDateTime', '$venue', $price, '$status', '$ticketHolder')";

    if ($conn->query($sql)) {
        echo "Ticket added successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
