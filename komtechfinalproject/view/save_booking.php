<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Include the database connection file
include('../db/config.php');

// Get the raw POST data from the request
$data = json_decode(file_get_contents("php://input"), true);

// Check if the necessary data is received
if (isset($data['event_name']) && isset($data['booking_date']) && isset($data['event_location']) && isset($data['client_name']) && isset($data['client_contact']) && isset($data['payment_status'])) {
    
    // Sanitize inputs to prevent SQL injection
    $event_name = mysqli_real_escape_string($conn, $data['event_name']);
    $booking_date = mysqli_real_escape_string($conn, $data['booking_date']);
    $event_location = mysqli_real_escape_string($conn, $data['event_location']);
    $client_name = mysqli_real_escape_string($conn, $data['client_name']);
    $client_contact = mysqli_real_escape_string($conn, $data['client_contact']);
    $payment_status = mysqli_real_escape_string($conn, $data['payment_status']);

    // If we have an 'id' in the data, it means we are updating an existing booking
    if (isset($data['id']) && !empty($data['id'])) {
        $booking_id = mysqli_real_escape_string($conn, $data['id']);
        
        // Update query
        $query = "UPDATE event_booking 
                  SET event_name = '$event_name', booking_date = '$booking_date', event_location = '$event_location', client_name = '$client_name', client_contact = '$client_contact', payment_status = '$payment_status'
                  WHERE id = $booking_id";
        
        if (mysqli_query($conn, $query)) {
            echo json_encode(["status" => "success", "message" => "Booking updated successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error updating booking"]);
        }
    } else {
        // Insert query (when 'id' is not provided, so it's a new booking)
        $query = "INSERT INTO event_booking (event_name, booking_date, event_location, client_name, client_contact, payment_status)
                  VALUES ('$event_name', '$booking_date', '$event_location', '$client_name', '$client_contact', '$payment_status')";

        if (mysqli_query($conn, $query)) {
            echo json_encode(["status" => "success", "message" => "Booking added successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error adding booking"]);
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
}
?>
