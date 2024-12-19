<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../db/config.php');

function handleAjaxRequest($conn) {
    header('Content-Type: application/json');
    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'fetch':
            fetchBookings($conn);
            break;
        case 'save':
            saveBooking($conn);
            break;
        case 'get':
            getBooking($conn);
            break;
        case 'delete':
            deleteBooking($conn);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}

function fetchBookings($conn) {
    $sql = "SELECT * FROM event_bookings ORDER BY created_at DESC";
    $result = $conn->query($sql);
    $bookings = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
    }

    echo json_encode($bookings);
}

function saveBooking($conn) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $event_name = $conn->real_escape_string($_POST['event_name']);
    $booking_date = $conn->real_escape_string($_POST['booking_date']);
    $client_name = $conn->real_escape_string($_POST['client_name']);
    $client_contact = $conn->real_escape_string($_POST['client_contact']);
    $client_email = filter_var($_POST['client_email'], FILTER_VALIDATE_EMAIL);
    $payment_status = $conn->real_escape_string($_POST['payment_status']);

    $errors = [];
    if (empty($event_name)) $errors[] = "Event name is required";
    if (empty($booking_date)) $errors[] = "Booking date is required";
    if (empty($client_name)) $errors[] = "Client name is required";
    if (empty($client_contact)) $errors[] = "Client contact is required";
    if (!$client_email) $errors[] = "Invalid email address";

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
        return;
    }

    if ($id) {
        $stmt = $conn->prepare("UPDATE event_bookings SET event_name = ?, booking_date = ?, client_name = ?, client_contact = ?, client_email = ?, payment_status = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $event_name, $booking_date, $client_name, $client_contact, $client_email, $payment_status, $id);
        $message = "Booking updated successfully";
    } else {
        $stmt = $conn->prepare("INSERT INTO event_bookings (event_name, booking_date, client_name, client_contact, client_email, payment_status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $event_name, $booking_date, $client_name, $client_contact, $client_email, $payment_status);
        $message = "Booking created successfully";
    }

    if ($stmt->execute()) {
        $insert_id = $id ?: $stmt->insert_id;
        echo json_encode(['success' => true, 'message' => $message, 'id' => $insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => "Error: " . $stmt->error]);
    }
    
    $stmt->close();
}

function getBooking($conn) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM event_bookings WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['success' => false, 'message' => 'Booking not found']);
    }
    
    $stmt->close();
}

function deleteBooking($conn) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM event_bookings WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Booking deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => "Error deleting booking: " . $stmt->error]);
    }
    
    $stmt->close();
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
    }
    handleAjaxRequest($conn);
    $conn->close();
} else {
    // Render HTML for non-AJAX requests
    // (HTML content omitted for brevity)
}
?>
