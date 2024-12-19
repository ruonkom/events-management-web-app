<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Include the database connection file
include('../db/config.php');

// Fetch all tickets from the database
$sql = "SELECT id, event_name, event_date_time, venue, price, status, ticket_holder FROM events_tickets";
$result = $conn->query($sql);

$tickets = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tickets[] = $row;
    }
}

// Return the tickets as a JSON response
echo json_encode($tickets);

$conn->close();
?>

