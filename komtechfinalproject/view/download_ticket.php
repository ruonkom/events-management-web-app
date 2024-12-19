<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Include database connection
include('../db/config.php');

// Ensure proper headers are set to force a file download
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="ticket.txt"');

// Get booking ID from POST
$booking_id = $_POST['booking_id'];

// Fetch booking details from the database
$sql = "SELECT * FROM bookings WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $event = $row['event_name'];
    $date = $row['event_date'];
    $location = $row['location'];
    $tickets = $row['ticket_count'];
    $totalPrice = $row['total_price'];

    // Define the ticket content
    $ticketContent = "Your Ticket\n";
    $ticketContent .= "Event: $event\n";
    $ticketContent .= "Date: $date\n";
    $ticketContent .= "Location: $location\n";
    $ticketContent .= "Tickets: $tickets\n";
    $ticketContent .= "Total Price: $$totalPrice\n";

    // Output the content
    echo $ticketContent;
} else {
    echo "Error: Booking not found.";
}

$stmt->close();
$conn->close();
exit;
?>
